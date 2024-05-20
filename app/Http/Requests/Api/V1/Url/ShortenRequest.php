<?php

namespace App\Http\Requests\Api\V1\Url;

use Illuminate\Foundation\Http\FormRequest;

class ShortenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url',
            'subfolder' => 'nullable|string|max:255'
        ];
    }
}
