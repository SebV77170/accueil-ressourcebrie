<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'categorie' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
