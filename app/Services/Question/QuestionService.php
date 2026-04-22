<?php

namespace App\Services\Question;

use App\Models\Question;
use App\Models\QuestionOption;
use App\DTOs\Question\CreateQuestionDTO;
use App\DTOs\Question\UpdateQuestionDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class QuestionService implements QuestionServiceInterface
{
    public function getPaginated(string $search = null, string $levelId = null): LengthAwarePaginator
    {
        $query = Question::with('level.world');

        if ($search) {
            if (method_exists($query, 'whereLike')) {
                $query->whereLike('content', $search);
            } else {
                $query->where('content', 'like', "%{$search}%");
            }
        }

        if ($levelId) {
            $query->where('level_id', $levelId);
        }

        return $query->latest()->paginate(10);
    }

    public function create(CreateQuestionDTO $dto): Question
    {
        return DB::transaction(function () use ($dto) {
            $imagePath = null;
            if ($dto->image) {
                $imagePath = $dto->image->store('questions', 'public');
            }

            $question = Question::create([
                'level_id' => $dto->level_id,
                'content' => $dto->content,
                'type' => $dto->type,
                'image_path' => $imagePath,
            ]);

            if ($dto->type == 'multiple_choice' && $dto->options) {
                foreach ($dto->options as $key => $optionData) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $optionData['content'] ?? '',
                        'label' => $optionData['label'] ?? null,
                        'is_correct' => $key == $dto->correct_option,
                    ]);
                }
            } elseif ($dto->type == 'matching' && $dto->options) {
                foreach ($dto->options as $key => $optionData) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $optionData['content'] ?? '',
                        'label' => $optionData['label'] ?? null,
                        'is_correct' => true, // All matching pairs are considered correct definition parts
                    ]);
                }
            }

            return $question;
        });
    }

    public function update(Question $question, UpdateQuestionDTO $dto): Question
    {
        return DB::transaction(function () use ($question, $dto) {
            if ($dto->image) {
                if ($question->image_path) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $question->image_path = $dto->image->store('questions', 'public');
            }

            $question->update([
                'level_id' => $dto->level_id,
                'content' => $dto->content,
                'type' => $dto->type,
            ]);

            // Avoid mass deleting options, but for simplicity of the refactor and because 
            // question relation is fine, we recreate if it's the right type.
            $question->options()->delete();

            if ($dto->type == 'multiple_choice' && $dto->options) {
                foreach ($dto->options as $key => $optionData) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $optionData['content'] ?? '',
                        'label' => $optionData['label'] ?? null,
                        'is_correct' => $key == $dto->correct_option,
                    ]);
                }
            } elseif ($dto->type == 'matching' && $dto->options) {
                foreach ($dto->options as $key => $optionData) {
                    if(!empty($optionData['content']) && !empty($optionData['label'])) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'content' => $optionData['content'],
                            'label' => $optionData['label'],
                            'is_correct' => true,
                        ]);
                    }
                }
            }

            return $question;
        });
    }

    public function delete(Question $question): bool
    {
        if ($question->image_path) {
            Storage::disk('public')->delete($question->image_path);
        }
        return $question->delete();
    }
}
