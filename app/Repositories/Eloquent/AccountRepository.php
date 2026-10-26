<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountRepository implements AccountRepositoryInterface
{
    public function findById(int $id): ?object
    {
        return DB::table('jcow_accounts')->where('id', $id)->first();
    }

    public function findByUsername(string $username): ?object
    {
        return DB::table('jcow_accounts')->where('username', $username)->first();
    }

    public function findLastSeen(int $id): ?int
    {
        return DB::table('jcow_accounts')
            ->where('id', $id)
            ->value('last_seen');
    }

    public function updateLastSeen(int $id, int $timestamp): void
    {
        DB::table('jcow_accounts')
            ->where('id', $id)
            ->update(['last_seen' => $timestamp]);
    }

    public function getSuggestions(int $userId, ?int $limit = null): Collection
    {
        $limit = $limit ?? config('pagination.suggestions');

        $friendIds = DB::table('jcow_friends')
            ->where('uid', $userId)
            ->pluck('fid')
            ->toArray();

        $pendingIds = DB::table('jcow_friend_reqs')
            ->where('uid', $userId)
            ->orWhere('fid', $userId)
            ->pluck('uid')
            ->merge(DB::table('jcow_friend_reqs')
                ->where('uid', $userId)
                ->orWhere('fid', $userId)
                ->pluck('fid'))
            ->toArray();

        $blockedIds = DB::table('jcow_blacks')
            ->where('uid', $userId)
            ->pluck('bid')
            ->merge(DB::table('jcow_blacks')->where('bid', $userId)->pluck('uid'))
            ->toArray();

        $excludeIds = array_merge($friendIds, $pendingIds, $blockedIds, [$userId]);

        return DB::table('jcow_accounts')
            ->whereNotIn('id', $excludeIds)
            ->where('disabled', 0)
            ->select('id', 'fullname', 'username', 'avatar')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getTelusurUsers(array $excludedIds, array $filters, string $sort): LengthAwarePaginator
    {
        $query = \App\Models\User::query()
            ->where('disabled', 0)
            ->where('hide_me', 0)
            ->whereNotIn('id', $excludedIds);

        if (!empty($filters['gender']) && $filters['gender'] !== 'all') {
            $query->where('gender', (int) $filters['gender']);
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('var1', trim($filters['status']));
        }

        $currentYear = (int) date('Y');

        if (!empty($filters['umur_min']) && (int) $filters['umur_min'] > 0) {
            $query->where('birthyear', '<=', $currentYear - (int) $filters['umur_min']);
        }

        if (!empty($filters['umur_max']) && (int) $filters['umur_max'] > 0) {
            $query->where('birthyear', '>=', $currentYear - (int) $filters['umur_max']);
        }

        if (!empty($filters['lokasi']) && $filters['lokasi'] !== 'all') {
            $loc = trim($filters['lokasi']);
            $locAliases = [
                'DI Yogyakarta' => ['DI Yogyakarta', 'Daerah Istimewa Yogyakarta'],
                'Daerah Istimewa Yogyakarta' => ['DI Yogyakarta', 'Daerah Istimewa Yogyakarta'],
                'DKI Jakarta' => ['DKI Jakarta', 'Daerah Khusus Ibukota Jakarta'],
                'Daerah Khusus Ibukota Jakarta' => ['DKI Jakarta', 'Daerah Khusus Ibukota Jakarta'],
                'Aceh' => ['Aceh', 'Nanggroe Aceh Darussalam'],
                'Nanggroe Aceh Darussalam' => ['Aceh', 'Nanggroe Aceh Darussalam'],
                'Jawa Barat' => ['Jawa Barat', 'Jawa Selatan'],
            ];

            if (isset($locAliases[$loc])) {
                $query->whereIn('location', $locAliases[$loc]);
            } else {
                $query->where('location', $loc);
            }
        }

        switch ($sort) {
            case 'last_seen':
                $query->orderBy('last_seen', 'desc');
                break;
            case 'followers':
                $query->orderBy('followers', 'desc');
                break;
            default:
                $query->orderBy('created', 'desc');
                break;
        }

        return $query->select('id', 'username', 'fullname', 'avatar', 'gender', 'birthyear', 'location', 'var1', 'about_me')
            ->paginate(config('pagination.telusur'))
            ->withQueryString();
    }

    public function getDistinctLocations(int $userId): array
    {
        return DB::table('jcow_accounts')
            ->where('disabled', 0)
            ->where('hide_me', 0)
            ->where('id', '!=', $userId)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->where('location', '!=', 'Jawa Selatan')
            ->distinct()
            ->pluck('location')
            ->toArray();
    }
}
