@extends('layouts.admin')
@section('title', 'User Roles Management - Admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-6">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">HOME</a> &gt;
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">ADMIN CP</a> &gt; USER ROLES
            </div>
            <h1 class="text-3xl font-black text-gray-900">USER ROLES</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola jenis peran (roles) untuk mengatur tingkat otorisasi pengguna.</p>
        </div>

        

        

        <div class="grid grid-cols-12 gap-6">
            
            {{-- Form Tambah Role --}}
            <div class="col-span-12 md:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-blue-500"></i>
                        Tambah Role Baru
                    </h2>
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Role <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: Moderator" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2.5">
                            @error('name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-gray-900 text-white font-bold text-sm px-4 py-2.5 rounded-lg hover:bg-gray-800 transition-colors">
                            Simpan Role
                        </button>
                    </form>
                </div>
            </div>

            {{-- List Role --}}
            <div class="col-span-12 md:col-span-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="shield" class="w-5 h-5 text-red-500"></i>
                            Daftar Role
                        </h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 w-16 text-center">ID</th>
                                    <th class="px-6 py-3">Nama Role</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($roles as $role)
                                    <tr class="hover:bg-gray-50 transition-colors group" x-data="{ editModal: false }">
                                        <td class="px-6 py-3 text-center text-gray-500 font-medium">{{ $role->id }}</td>
                                        <td class="px-6 py-3 font-bold text-gray-900">
                                            {{ $role->name }}
                                            @if(in_array(strtolower($role->name), ['admin', 'administrator', 'member']))
                                                <span class="ml-2 inline-block bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Core Role</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            @if(!in_array(strtolower($role->name), ['admin', 'administrator', 'member']))
                                                <div class="flex items-center justify-end gap-2">
                                                    {{-- Tombol Edit --}}
                                                    <button @click="editModal = true" type="button" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit Role">
                                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                                    </button>
                                                    {{-- Tombol Delete --}}
                                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus role {{ $role->name }} secara permanen?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus Role">
                                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                {{-- Edit Role Modal --}}
                                                <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-modal="true">
                                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="editModal = false"></div>
                                                    <div x-show="editModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 z-10 text-left">
                                                        <h3 class="text-base font-bold text-gray-900 mb-4">Edit Nama Role</h3>
                                                        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                                                            @csrf @method('PUT')
                                                            <div class="mb-4">
                                                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Role</label>
                                                                <input type="text" name="name" required value="{{ $role->name }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                                            </div>
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" @click="editModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Batal</button>
                                                                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 font-medium italic" title="Role inti (Core) sistem tidak dapat dihapus">Protected</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada role.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
