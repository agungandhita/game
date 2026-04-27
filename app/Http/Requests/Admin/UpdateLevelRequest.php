<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['nullable', 'string', 'max:100'],
            'time_per_question' => ['required', 'integer', 'min:10', 'max:120'],
            'is_active'         => ['nullable', 'boolean'],
        ];
    }
}
