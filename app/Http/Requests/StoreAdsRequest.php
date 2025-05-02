<?php

namespace App\Http\Requests;

use App\Enums\ContentTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdsRequest extends FormRequest
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
            'title' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'totalprice' => 'required|numeric|min:0',
            'ads_type_id' => 'required|exists:ads_types,id',
            'active_from' => 'required|date|date_format:Y-m-d',
            'end_on' => 'required|date|date_format:Y-m-d|after:active_from',
            'content_type_id' => [
                'required',
                Rule::in([
                    ContentTypes::IMAGE->value,
                    ContentTypes::VIDEO->value,
                ]),
            ],
            'discount' => 'required|numeric|min:0',
            'content_path' => 'required|max:2048',
            'page_id' => 'required|exists:pages,id',
            'category_id' => 'required|exists:categories,id',
        ];

    }

    /**
     * Customize validation messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The ads title is required.',
            'totalprice.numeric' => 'The total price must be a valid number.',
            'end_on.after' => 'The end date must be after the start date.',
            'content_path.required' => 'A file must be uploaded.',
        ];
    }
}
