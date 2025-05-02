<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust for role-based authorization if needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'amount' => 'required|numeric|min:0',
            'payment_type' => ['required', 'integer', Rule::in([
                \App\Enums\PaymentTypes::FULL->value,
                \App\Enums\PaymentTypes::PARTIAL->value,
            ])],
            'currency_id' => 'required|exists:currencies,id',
            'date' => 'required|date',
            'is_fully_paid' => 'nullable|boolean',
        ];

        // If updating, make fields optional except required ones
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $key => $rule) {
                $rules[$key] = str_replace('required', 'sometimes|required', $rule);
            }
        }

        return $rules;
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'The payment amount is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'payment_type.required' => 'Please select a payment type.',
            'currency_id.exists' => 'The selected currency is invalid.',
            'date.required' => 'The payment date is required.',
        ];
    }
}
