<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TelusurController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = \App\Models\User::query()
            ->where('disabled', 0)
            ->where('hide_me', 0)
            ->where('id', '!=', $userId);

        // Filter Gender
        if ($request->filled('gender') && $request->gender != 'all') {
            $query->where('gender', (int) $request->gender);
        }

        // Filter Status (var1)
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('var1', $request->status);
        }

        // Filter Umur
        $currentYear = (int) date('Y');
        if ($request->filled('umur_min') && $request->umur_min > 0) {
            $maxBirthYear = $currentYear - (int) $request->umur_min;
            $query->where('birthyear', '<=', $maxBirthYear);
        }
        if ($request->filled('umur_max') && $request->umur_max > 0) {
            $minBirthYear = $currentYear - (int) $request->umur_max;
            $query->where('birthyear', '>=', $minBirthYear);
        }

        // Filter Lokasi
        if ($request->filled('lokasi') && $request->lokasi != 'all') {
            $query->where('location', $request->lokasi);
        }

        // Sort
        $sort = $request->input('sort', 'terbaru');
        if ($sort === 'terakhir_aktif') {
            $query->orderBy('lastlogin', 'desc');
        } elseif ($sort === 'paling_populer') {
            $query->orderBy('followers', 'desc');
        } else {
            $query->orderBy('created', 'desc');
        }

        $members = $query->select('id', 'username', 'fullname', 'avatar', 'gender', 'birthyear', 'location', 'var1')
            ->paginate(12)
            ->withQueryString();

        // Ambil lokasi unik dari DB + gabungkan dengan daftar provinsi lengkap
        $dbLocations = \App\Models\User::query()
            ->where('disabled', 0)
            ->where('hide_me', 0)
            ->where('id', '!=', $userId)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->pluck('location')
            ->toArray();

        $allProvinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi',
            'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung',
            'Kepulauan Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah',
            'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali',
            'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
            'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan',
            'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan',
            'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat',
            'Maluku', 'Maluku Utara', 'Papua Barat', 'Papua',
        ];

        $locations = collect($allProvinces)
            ->merge($dbLocations)
            ->unique()
            ->sort()
            ->values();

        $filters = $request->only(['gender', 'status', 'umur_min', 'umur_max', 'lokasi', 'sort']);

        return view('telusur.index', compact('members', 'locations', 'filters'));
    }
}
