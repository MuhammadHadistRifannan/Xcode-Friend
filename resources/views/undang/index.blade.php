@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8" x-data="{ tab: 'undang' }">
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-9">
            <!-- Header Section -->
            <div class="mb-6 flex items-center gap-3">
                <div class="w-1 h-6 bg-red-600 rounded"></div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-wider">INVITATION</h1>
            </div>
            <p class="text-sm text-gray-600 mb-8">Manage network invitations and track referral history.</p>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6 flex gap-6">
                <button 
                    @click="tab = 'undang'" 
                    :class="tab === 'undang' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm font-bold transition-colors">
                    Undang
                </button>
                <button 
                    @click="tab = 'sejarah'" 
                    :class="tab === 'sejarah' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm font-bold transition-colors">
                    Sejarah
                </button>
            </div>

            <!-- TAB: UNDANG -->
            <div x-show="tab === 'undang'">
                
                <!-- Share Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Bagikan lagi</h3>
                    <div class="flex items-center gap-3">
                        <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors text-gray-700">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </button>
                        <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors text-gray-700">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Invite Form Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-6">Kirimkan permohonan</h3>
                    
                    <div class="space-y-6 max-w-2xl">
                        <!-- Email Input -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">KE (EMAIL ADDRESS)</label>
                            <input 
                                type="email" 
                                placeholder="user@domain.com" 
                                class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500"
                            >
                            <p class="text-xs text-gray-400 mt-1 italic">Banyak email harus dipisahkan dengan koma (,)</p>
                        </div>

                        <!-- Message Textarea -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">PESAN (OPTIONAL)</label>
                            <textarea 
                                rows="4" 
                                placeholder="Type your invitation message..." 
                                class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 resize-none"
                            ></textarea>
                        </div>

                        <button class="bg-[#b91c1c] hover:bg-red-800 text-white px-6 py-2.5 rounded-md font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                            KIRIMKAN PERSETUJUAN
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB: SEJARAH -->
            <div x-show="tab === 'sejarah'" style="display: none;">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4">EMAIL ADDRESS</th>
                                <th class="px-6 py-4">TIME</th>
                                <th class="px-6 py-4">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">user.alpha@example.com</td>
                                <td class="px-6 py-4 text-gray-500">2023-10-24 14:32 UTC</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-full text-[10px] font-bold border border-yellow-200">
                                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500"></div> Pending
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">beta.tester@domain.net</td>
                                <td class="px-6 py-4 text-gray-500">2023-10-23 09:15 UTC</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-[10px] font-bold border border-green-200">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Accepted
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">gamma.ray@system.org</td>
                                <td class="px-6 py-4 text-gray-500">2023-10-20 18:45 UTC</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-bold border border-red-200">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Rejected
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Sidebar (col-span-3) -->
        <div class="col-span-12 lg:col-span-3">
            @php
                $sidebarData = [
                    'reviews' => ['rating' => 4.9, 'count' => 532],
                    'networkLinks' => [
                        ['id' => 1, 'label' => 'LinkedIn', 'url' => '#'],
                        ['id' => 2, 'label' => 'phpBB Group', 'url' => '#'],
                        ['id' => 3, 'label' => 'Facebook', 'url' => '#']
                    ]
                ];
            @endphp
            <x-sidebar-right />
        </div>

    </div>
</div>
@endsection
