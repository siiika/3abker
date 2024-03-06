<?php

namespace App\Http\Requests;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = JWTAuth::user()->id; // Assuming you have access to the user model through $this->user()

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'age' => 'required|integer|min:0',
            'father_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:6',
        ];
    }
}
