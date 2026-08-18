<?php

namespace App\Repositories\Contracts;

interface ForumRepositoryInterface
{
    public function findById(int $id): ?object;

    public function threadsFor(int $forumId, int $perPage = 15);

    public function paginate(int $perPage = 15);

    public function tree();
}
