<?php

namespace App\Repositories\Contracts;

interface PostRepositoryInterface
{
    public function findById(int $id): ?object;

    public function create(array $data): object;

    public function update(object $post, array $data): bool;

    public function delete(object $post): bool;

    public function feedForUser(int $userId, int $perPage = 15);

    public function paginate(int $perPage = 15);
}