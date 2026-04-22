<?php

namespace App\Services\Grade;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Collection;

interface GradeServiceInterface
{
    public function getPaginated(string $search = null, string $levelId = null): LengthAwarePaginator;
    public function getById(int $id): UserProgress;
    public function getAllForExport(): Collection;
}
