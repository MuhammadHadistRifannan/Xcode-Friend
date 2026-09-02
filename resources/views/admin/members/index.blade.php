@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        
        <!-- Header & Search -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2">HOME > ADMIN CP > MEMBERS</div>
                <h1 class="text-3xl font-black text-gray-900">MEMBERS MANAGEMENT</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola role, blokir, dan pantau aktivitas anggota komunitas.</p>
            </div>
            
            <div class="w-full sm:w-72 mt-4 sm:mt-0">
                <form action="{{ route('admin.members') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari username atau email..." class="w-full bg-white border border-gray-300 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block pl-10 pr-4 py-2.5 shadow-sm transition-colors">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.members') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 x-transition.duration.500ms
                 class="fixed bottom-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition.duration.500ms
                 class="fixed bottom-4 right-4 z-50 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex flex-col gap-1">
                <div class="flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <span>Ada kesalahan:</span>
                </div>
                <ul class="list-disc pl-8 font-normal text-xs mt-1">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- Table Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">User</th>
                            <th scope="col" class="px-6 py-4 font-bold">Role</th>
                            <th scope="col" class="px-6 py-4 font-bold">Terakhir Login</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($members as $member)
                            <tr class="hover:bg-gray-50 transition-colors {{ $member->disabled ? 'opacity-60 bg-red-50/50' : '' }}" x-data="{ editRoleModal: false }">
                                <!-- User Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $member->avatar ? asset('storage/'.$member->avatar) : asset('img/avatar.png') }}" alt="{{ $member->fullname ?: $member->username }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 bg-gray-100">
                                        <div>
                                            <div class="font-bold text-gray-900 text-base flex items-center gap-2">
                                                {{ $member->fullname ?: $member->username }}
                                                @if($member->disabled)
                                                    <span class="bg-red-100 text-red-700 text-[10px] px-2 py-0.5 rounded uppercase tracking-wider font-bold">Banned</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Role -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ (strtolower($member->roles) === 'admin' || strtolower($member->roles) === 'administrator' || $member->level == 1) ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $member->roles ?: ($member->level == 1 ? 'Administrator' : 'Member') }}
                                    </span>
                                </td>

                                <!-- Last Login -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    @if($member->lastlogin > 0)
                                        {{ date('d M Y, H:i', $member->lastlogin) }}
                                    @else
                                        <span class="text-gray-400 italic">Belum pernah login</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Button Edit Role -->
                                        <button @click="editRoleModal = true" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit Role">
                                            <i data-lucide="shield" class="w-4 h-4"></i>
                                        </button>
                                        
                                        <!-- Button Ban/Suspend -->
                                        <form action="{{ route('admin.members.ban', $member->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin {{ $member->disabled ? 'MENGAKTIFKAN' : 'MEMBLOKIR' }} pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="{{ $member->disabled ? 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100' : 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100' }} p-2 rounded-lg transition-colors" title="{{ $member->disabled ? 'Aktifkan Kembali' : 'Blokir Pengguna' }}">
                                                <i data-lucide="{{ $member->disabled ? 'user-check' : 'user-x' }}" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Edit Role (Alpine.js) -->
                                    <div x-show="editRoleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <!-- Background overlay -->
                                            <div x-show="editRoleModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="editRoleModal = false" aria-hidden="true"></div>

                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                            <!-- Modal panel -->
                                            <div x-show="editRoleModal" 
                                                 x-transition:enter="ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                 x-transition:leave="ease-in duration-200"
                                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                 class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full relative z-10 text-gray-800">
                                                
                                                <form action="{{ route('admin.members.role', $member->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 text-blue-600 sm:mx-0 sm:h-10 sm:w-10">
                                                                <i data-lucide="shield" class="w-5 h-5"></i>
                                                            </div>
                                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                                                    Ubah Role Pengguna
                                                                </h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-gray-500 mb-4">Ubah hak akses untuk pengguna <strong>{{ $member->fullname ?: $member->username }}</strong>.</p>
                                                                    
                                                                    <div>
                                                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Role Saat Ini</label>
                                                                        <select name="roles" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                                                            <option value="Member" {{ (strtolower($member->roles) !== 'admin' && strtolower($member->roles) !== 'administrator' && $member->level != 1) ? 'selected' : '' }}>Member Biasa</option>
                                                                            <option value="Administrator" {{ (strtolower($member->roles) === 'admin' || strtolower($member->roles) === 'administrator' || $member->level == 1) ? 'selected' : '' }}>Administrator</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                            Simpan
                                                        </button>
                                                        <button type="button" @click="editRoleModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                    
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    <i data-lucide="search-x" class="w-8 h-8 mx-auto text-gray-400 mb-2"></i>
                                    Tidak ada anggota yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($members->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $members->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        
    </div>
</div>
@endsection
