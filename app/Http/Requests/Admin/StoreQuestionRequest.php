<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'level_id' => ['required', 'uuid', 'exists:levels,id'],
            'question_text' => ['required', 'string'],
            'options' => ['required', 'array', 'size:4'],
            'options.*' => ['required', 'string'],
            'correct_option' => ['required', 'integer', 'min:0', 'max:3'],
        ];
    }
}
