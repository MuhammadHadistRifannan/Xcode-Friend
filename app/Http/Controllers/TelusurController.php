<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\BlockRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TelusurController extends Controller
{
    public function __construct(
        private AccountRepositoryInterface $accountRepo,
        private BlockRepositoryInterface $blockRepo
    ) {}

    public function index(Request $request): mixed
    {
        try {
            $userId = Auth::id();

            $blockedByMe = $this->blockRepo->getBlockedIds($userId);
            $blockedMe = $this->blockRepo->getBlockerIds($userId);
            $excludedIds = $blockedByMe->merge($blockedMe)->push($userId)->unique()->toArray();

            $sort = $request->input('sort', 'terbaru');
            $filters = array_map('trim', $request->only(['gender', 'status', 'umur_min', 'umur_max', 'lokasi']));
            $members = $this->accountRepo->getTelusurUsers($excludedIds, $filters, $sort);

            $dbLocations = $this->accountRepo->getDistinctLocations($userId);
            $allProvinces = config('provinces.list', []);

            $locations = collect($allProvinces)
                ->merge($dbLocations)
                ->unique()
                ->sort()
                ->values();

            $filters['sort'] = $sort;

            return view('telusur.index', compact('members', 'locations', 'filters'));
        } catch (\Exception $e) {
            Log::error('Telusur error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('beranda')->with('error', 'Gagal memuat halaman telusur.');
        }
    }
}
