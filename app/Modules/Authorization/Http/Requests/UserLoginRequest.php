<?php

namespace App\Modules\Authorization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['login' => "string", 'verification_code' => "string"])] public function rules(): array
    {
        return [
            'login' => 'required|string|regex:/^7\d{3}\d{3}\d{2}\d{2}$/i',
            'verification_code' => 'required|string|min:4|max:4',
        ];
    }
}
