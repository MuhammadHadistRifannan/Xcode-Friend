<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?Account
    {
        return Account::find($id);
    }

    public function findByUsername(string $username): ?Account
    {
        return Account::where('username', $username)->first();
    }

    public function findByEmail(string $email): ?Account
    {
        return Account::where('email', $email)->first();
    }

    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function update(Account $account, array $data): bool
    {
        return $account->update($data);
    }

    public function delete(Account $account): bool
    {
        return $account->delete();
    }

    public function paginate(int $perPage = 15)
    {
        return Account::orderBy('id', 'desc')->paginate($perPage);
    }

    public function search(string $query)
    {
        return Account::where(function ($q) use ($query) {
            $q->where('username', 'LIKE', "%{$query}%")
              ->orWhere('fullname', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%");
        })->paginate(15);
    }
}
