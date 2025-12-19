<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReigisterRequest extends BaseRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:10|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date',
            'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'id_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'user_type' => 'required|in:admin,tenant,owner',
        ];
    }
}
