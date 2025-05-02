<?php

namespace App\Http\Requests;

use App\Models\Subbanner;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubbannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
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
            if ($this->hasFile('image') && $this->input('video_link')) {
                $validator->errors()->add('image', 'You cannot fill both image and video link. Please choose one.');
                $validator->errors()->add('video_link', 'You cannot fill both image and video link. Please choose one.');
            }

            if (!$this->hasFile('image') && !$this->input('video_link')) {
                $validator->errors()->add('image', 'You must fill at least one of image or video link.');
                $validator->errors()->add('video_link', 'You must fill at least one of image or video link.');
            }

            // Custom validation for status
            if ($this->input('status') == 1) {
                $activeCount = Subbanner::where('status', 1)->count();
                if ($activeCount >= 4) {
                    $validator->errors()->add('status', 'Only 4 active sub-banners are allowed.');
                }
            }
        });
    }
}
