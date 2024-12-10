<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
            'image' => ['nullable','image','max:3000'],
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'phone' => ['required','string'],
            // 'bank_account_number' => ['required','numeric','max:50'],//max here dont work with number : 100 > 50
            // 'bank_account_number' => ['required','numeric'],
            'bank_account_number' => ['required','numeric','max_digits:50'],//so use this validations with numbers
            'about' => ['nullable','string','max:500'],
        ];
    }
}
