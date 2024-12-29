<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'prefixname' => ['nullable','string', 'max:255'],
            'firstname' => ['required','string', 'max:255'],
            'middlename' => ['nullable','string', 'max:255'],
            'lastname' => ['required','string', 'max:255'],
            'suffixname' => ['nullable','string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'password' => [
                'nullable',
                'string',
                Rule::requiredIf(function () {
                    if( ! $this->route('user') ) {
                        return true;
                    }
                    return false;
                })
            ],
            'photo' => ['nullable','file'],
        ];
    }
}
