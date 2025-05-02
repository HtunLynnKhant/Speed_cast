<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'driver_ic_number' => 'required|string|max:255',
            'driver_car_plate' => 'required|string|max:255',
            'drivers_license' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'drivers_car_license' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }
}
