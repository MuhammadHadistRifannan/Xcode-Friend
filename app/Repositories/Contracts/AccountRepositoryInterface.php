<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountRepositoryInterface
{
    public function findById(int $id): ?object;

    public function findByUsername(string $username): ?object;

    public function findLastSeen(int $id): ?int;

    public function updateLastSeen(int $id, int $timestamp): void;

    public function getSuggestions(int $userId, int $limit = 10): Collection;

    public function getTelusurUsers(array $excludedIds, array $filters, string $sort): LengthAwarePaginator;

    public function getDistinctLocations(int $userId): array;
}
