<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Interfaces\Services\BannerServiceInterface;
use App\Interfaces\Services\CategoryServiceInterface;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function __construct(
        private readonly BannerServiceInterface $service,
        private readonly CategoryServiceInterface $categoryService
    ) {
    }

    /**
     * New banner view.
     *
     * @return View
     */
    public function new(Request $request): View
    {
        $categories = $this->categoryService->getAll($request);

        return view('admin.banner.new', compact('categories'));
    }

    /**
     * Create new banner.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:204800',
            'video_link' => 'nullable|url',
            'status' => 'nullable|in:0,1',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->hasFile('image') && $request->input('video_link')) {
                $validator->errors()->add('image', 'You cannot fill both image and video link. Please choose one.');
                $validator->errors()->add('video_link', 'You cannot fill both image and video link. Please choose one.');
            }

            if (!$request->hasFile('image') && !$request->input('video_link')) {
                $validator->errors()->add('image', 'You must fill at least one of image or video link.');
                $validator->errors()->add('video_link', 'You must fill at least one of image or video link.');
            }

            // Custom validation for status

        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $banner = $this->service->create($request);

            if (!$banner) {
                return redirect()->back()->withErrors(['msg' => 'Failed to create banner.']);
            }

            return redirect()->route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.banner.list' :
                (auth()->user()->hasRole('admin') ? 'admin.banner.list' : 'client.banner.list')
            )->with(
                'success',
                __('response.admin.banner.create.success')
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['status' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Banner list view
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $banners = $this->service->getAll($request); // Assuming $this->bannerService is your service

        // Otherwise, return the full page with the banners
        return view('admin.banner.list', compact('banners'));
    }

    /**
     * Banner edit view.
     *
     * @param integer $id
     * @return View
     */
    public function edit(string $id): View
    {
        $banner = $this->service->findById($id);
        $categories = $this->categoryService->getAllActive();

        return view('admin.banner.edit', compact('banner', 'categories'));
    }

    /**
     * Update banner.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:204800',
            'video_link' => 'nullable|url',
        ]);

        $validator->after(function ($validator) use ($request, $id) {
            $banner = Banner::findOrFail($id);
            $hasImage = $request->hasFile('image') || $banner->contents->where('type', \App\Enums\ContentTypes::IMAGE->value)->isNotEmpty();
            $hasVideoLink = $request->filled('video_link') || $banner->video_link;

            if ($request->hasFile('image') && $request->input('video_link')) {
                $validator->errors()->add('image', 'You cannot fill both image and video link. Please choose one.');
                $validator->errors()->add('video_link', 'You cannot fill both image and video link. Please choose one.');
            }

            if (!$hasImage && !$hasVideoLink) {
                $validator->errors()->add('image', 'You must fill at least one of image or video link.');
                $validator->errors()->add('video_link', 'You must fill at least one of image or video link.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $isUpdated = $this->service->update($request, $id);

            if ($isUpdated) {
                return redirect()->route(
                    auth()->user()->hasRole('superadmin') ? 'superadmin.banner.list' :
                    (auth()->user()->hasRole('admin') ? 'admin.banner.list' : 'client.banner.list')
                )->with('success', __('response.admin.banner.update.success'));
            }

            return redirect()->back()->withErrors(['msg' => 'No changes were made to the banner.']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['status' => $e->getMessage()]);
        }
    }

    /**
     * Delete banner.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route(
            auth()->user()->hasRole('superadmin') ? 'superadmin.banner.list' :
            (auth()->user()->hasRole('admin') ? 'admin.banner.list' : 'client.banner.list')
        )->with(
            'success',
            __('response.admin.banner.delete.success')
        );
    }
}
