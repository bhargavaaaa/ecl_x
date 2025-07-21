<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'max:255', "unique:users,email,". auth()->user()->id . ",id"],
            'phone'         => ['required', 'string', 'max:15', "unique:users,phone,". auth()->user()->id . ",id"],
            'country_id'    => ['nullable', 'integer'],
            'state_id'      => ['nullable', 'integer'],
            'city_id'       => ['nullable', 'integer'],
            'address'       => ['nullable', 'string', 'max:255'],
            'avatar'        => ['nullable', 'image', 'max:1024'],
        ];
    }
}
