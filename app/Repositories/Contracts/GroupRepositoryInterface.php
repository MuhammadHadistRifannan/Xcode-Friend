<?php

namespace App\Repositories\Contracts;

interface GroupRepositoryInterface
{
    public function findById(int $id): ?object;

    public function create(array $data): object;

    public function update(object $group, array $data): bool;

    public function delete(object $group): bool;

    public function paginate(int $perPage = 15);

    public function findByUri(string $uri): ?object;
}
