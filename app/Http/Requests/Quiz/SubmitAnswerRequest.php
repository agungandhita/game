<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'uuid', 'exists:questions,id'],
            'selected_option_id' => ['nullable', 'uuid', 'exists:options,id'],
            'time_spent' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
