@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <div class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-widest">
        PAGES &rsaquo; EDIT
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-1">EDIT PAGE</h1>
    <p class="text-sm text-gray-600 mt-1 mb-8">Perbarui informasi halaman <strong>{{ $page->name }}</strong>.</p>

    <div class="grid grid-cols-12 gap-8">

        <!-- Form -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
                <form action="{{ route('pages.update', $page->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Logo Upload Section -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">LOGO HALAMAN</label>
                        <div class="flex items-center gap-4">
                            @if($page->logo_url)
                                <img src="{{ $page->logo_url }}" alt="Logo" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                    <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input
                                    type="file"
                                    name="logo"
                                    accept="image/jpeg,image/png,image/jpg"
                                    class="text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-gray-100 file:text-gray-700 file:text-xs file:font-medium hover:file:bg-gray-200 cursor-pointer border border-gray-200 rounded-md py-1.5 px-3 w-full"
                                >
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks 5MB.</p>
                                @error('logo')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">NAMA HALAMAN</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $page->name) }}"
                            class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }} rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-500"
                        >
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                            DESKRIPSI <span class="font-normal text-gray-400">(OPSIONAL)</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full border {{ $errors->has('description') ? 'border-red-400' : 'border-gray-300' }} rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 resize-none"
                        >{{ old('description', $page->description) }}</textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 bg-[#b91c1c] hover:bg-red-800 text-white font-bold py-3 px-4 rounded-md uppercase tracking-wider text-sm transition-colors shadow-sm flex justify-center items-center gap-2">
                            SIMPAN PERUBAHAN <i data-lucide="save" class="w-4 h-4"></i>
                        </button>
                        <a href="{{ route('pages.show', $page->id) }}"
                            class="px-6 py-3 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white border border-red-200 rounded-lg p-6 shadow-sm mt-6">
                <h3 class="text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Danger Zone
                </h3>
                <p class="text-xs text-gray-600 mb-4">Menghapus halaman bersifat permanen dan tidak bisa dibatalkan.</p>
                <form action="{{ route('pages.destroy', $page->id) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus halaman ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Hapus Halaman Ini
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-3 space-y-4">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Info Page</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ID</span>
                        <span class="font-mono text-gray-700">#{{ $page->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Views</span>
                        <span class="text-gray-700">{{ number_format($page->views) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Likes</span>
                        <span class="text-gray-700">{{ number_format($page->users) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Last Updated</span>
                        <span class="text-gray-700">{{ $page->updated ? date('d M Y', $page->updated) : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
