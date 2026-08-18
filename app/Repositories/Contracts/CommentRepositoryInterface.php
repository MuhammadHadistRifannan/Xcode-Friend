<?php

namespace App\Repositories\Contracts;

interface CommentRepositoryInterface
{
    public function findById(int $id): ?object;

    public function create(array $data): object;

    public function delete(object $comment): bool;

    public function forStream(int $streamId, int $perPage = 15);

    public function forUser(int $userId, int $perPage = 15);
}
