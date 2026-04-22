<?php

namespace App\Services\Grade;

use App\Models\UserProgress;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class GradeService implements GradeServiceInterface
{
    public function getPaginated(string $search = null, string $levelId = null): LengthAwarePaginator
    {
        $query = UserProgress::with(['user', 'level.world']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    if (method_exists($q2, 'whereLike')) {
                        $q2->whereLike('nickname', $search);
                    } else {
                        $q2->where('nickname', 'like', "%{$search}%");
                    }
                })->orWhereHas('level', function ($q2) use ($search) {
                    if (method_exists($q2, 'whereLike')) {
                        $q2->whereLike('title', $search);
                    } else {
                        $q2->where('title', 'like', "%{$search}%");
                    }
                });
            });
        }

        if ($levelId) {
            $query->where('level_id', $levelId);
        }

        return $query->latest()->paginate(10);
    }

    public function getById(int $id): UserProgress
    {
        return UserProgress::with(['user', 'level.world', 'level.questions'])->findOrFail($id);
    }

    public function getAllForExport(): Collection
    {
        return UserProgress::with(['user', 'level'])->get();
    }
}
