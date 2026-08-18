<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;

class GroupRepository implements GroupRepositoryInterface
{
    public function findById(int $id): ?Group
    {
        return Group::find($id);
    }

    public function create(array $data): Group
    {
        return Group::create($data);
    }

    public function update(object $group, array $data): bool
    {
        return $group->update($data);
    }

    public function delete(object $group): bool
    {
        return $group->delete();
    }

    public function paginate(int $perPage = 15)
    {
        return Group::orderBy('id', 'desc')->paginate($perPage);
    }

    public function findByUri(string $uri): ?Group
    {
        return Group::where('uri', $uri)->first();
    }
}
