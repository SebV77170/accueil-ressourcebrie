<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubTaskRequest extends FormRequest
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
            'commentaire'  => ['nullable', 'string'],
        ];
    }
}
