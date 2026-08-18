<?php

namespace App\Repositories\Contracts;

use App\Models\Account;

interface UserRepositoryInterface
{
    public function findById(int $id): ?Account;

    public function findByUsername(string $username): ?Account;

    public function findByEmail(string $email): ?Account;

    public function create(array $data): Account;

    public function update(Account $account, array $data): bool;

    public function delete(Account $account): bool;

    public function paginate(int $perPage = 15);

    public function search(string $query);
}