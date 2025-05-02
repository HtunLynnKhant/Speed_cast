<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Subbanner;

class UpdateSubbannerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust this based on your authorization logic
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:204800',
            'video_link' => 'nullable|url',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'image' => $this->file('image'),
            'video_link' => $this->input('video_link'),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $subbanner = Subbanner::findOrFail($this->route('id')); // Get the current subbanner

            $hasImage = $this->hasFile('image') || $subbanner->image_path; // Check if new or existing image is present
            $hasVideoLink = $this->filled('video_link') || $subbanner->video_link; // Check if new or existing video link is present

            if ($this->hasFile('image') && $this->input('video_link')) {
                $validator->errors()->add('image', 'You cannot fill both image and video link. Please choose one.');
                $validator->errors()->add('video_link', 'You cannot fill both image and video link. Please choose one.');
            }

            if (!$hasImage && !$hasVideoLink) {
                $validator->errors()->add('image', 'You must fill at least one of image or video link.');
                $validator->errors()->add('video_link', 'You must fill at least one of image or video link.');
            }

            // Custom validation for status
            if ($this->input('status') == 1) {
                $activeCount = Subbanner::where('status', 1)
                    ->where('id', '!=', $subbanner->id) // Exclude the current subbanner
                    ->count();
                if ($activeCount >= 4) {
                    $validator->errors()->add('status', 'Only 4 active sub-banners are allowed.');
                }
            }
        });
    }
}
