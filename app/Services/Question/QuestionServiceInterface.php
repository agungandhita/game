<?php

namespace App\Services\Question;

use App\Models\Question;
use App\DTOs\Question\CreateQuestionDTO;
use App\DTOs\Question\UpdateQuestionDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuestionServiceInterface
{
    public function getPaginated(string $search = null, string $levelId = null): LengthAwarePaginator;
    public function create(CreateQuestionDTO $dto): Question;
    public function update(Question $question, UpdateQuestionDTO $dto): Question;
    public function delete(Question $question): bool;
}
