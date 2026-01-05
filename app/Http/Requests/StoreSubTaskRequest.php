<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'responsables' => ['nullable', 'array'],
            'responsables.*' => ['integer', 'exists:users,id'],
            'commentaire'  => ['nullable', 'string'],
        ];
    }
}
