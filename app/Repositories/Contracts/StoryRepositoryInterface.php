<?php

namespace App\Repositories\Contracts;

interface StoryRepositoryInterface
{
    public function findById(int $id): ?object;

    public function create(array $data): object;

    public function update(object $story, array $data): bool;

    public function delete(object $story): bool;

    public function paginate(int $perPage = 15);

    public function published(int $perPage = 15);
}
