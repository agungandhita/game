<?php

namespace App\DTOs\Question;

readonly class UpdateQuestionDTO
{
    public function __construct(
        public int $level_id,
        public string $content,
        public string $type,
        public ?\Illuminate\Http\UploadedFile $image,
        public ?array $options,
        public ?string $correct_option
    ) {
    }

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            $request->input('level_id'),
            $request->input('content'),
            $request->input('type'),
            $request->file('image'),
            $request->input('options'),
            $request->input('correct_option')
        );
    }
}
