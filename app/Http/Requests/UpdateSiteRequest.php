<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteRequest extends FormRequest
{
    /**
     * Autorisation de la requête
     */
    public function authorize(): bool
    {
        // À affiner plus tard avec une Policy si besoin
        return true;
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ];
    }
}
