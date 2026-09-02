@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    <!-- Breadcrumb -->
    <div class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-widest">
        PAGES &rsaquo; CREATE
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-1">CREATE A PAGE</h1>
    <p class="text-sm text-gray-600 mt-1 mb-8">Establish your presence on the X-CODE network.</p>

    <div class="grid grid-cols-12 gap-8">

        <!-- =============================== -->
        <!-- Left Column: Form (col-span-9)  -->
        <!-- =============================== -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
                <form action="{{ route('pages.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- PAGE ADDRESS (URI / Slug) -->
                    <div>
                        <label for="uri" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                            PAGE ADDRESS
                        </label>
                        <div class="flex rounded-md border {{ $errors->has('uri') ? 'border-red-400' : 'border-gray-300' }} overflow-hidden bg-gray-50">
                            <span class="px-4 py-2 text-sm text-gray-500 border-r border-gray-300 whitespace-nowrap">
                                friends.xcode.co.id/pages/
                            </span>
                            <input
                                type="text"
                                id="uri"
                                name="uri"
                                value="{{ old('uri') }}"
                                placeholder="yourpagename"
                                class="flex-1 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white"
                            >
                        </div>
                        @error('uri')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                            </p>
                        @else
                            <p class="text-xs text-gray-500 mt-2 italic">
                                Hanya angka (0-9) dan huruf kecil (a-z). Min. 6 karakter. Contoh: <strong>mycoolpage</strong>
                            </p>
                        @enderror
                    </div>

                    <!-- PAGE NAME -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                            PAGE NAME
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama tampilan halaman kamu"
                            class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }} rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-red-500"
                        >
                        @error('name')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- PAGE DESCRIPTION (Optional) -->
                    <div>
                        <label for="description" class="block text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">
                            PAGE DESCRIPTION <span class="font-normal text-gray-400">(OPSIONAL)</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Deskripsikan halaman kamu secara singkat..."
                            class="w-full border {{ $errors->has('description') ? 'border-red-400' : 'border-gray-300' }} rounded-md px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 resize-none"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-[#b91c1c] hover:bg-red-800 text-white font-bold py-3 px-4 rounded-md uppercase tracking-wider text-sm transition-colors shadow-sm flex justify-center items-center gap-2"
                        >
                            BUAT HALAMAN <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- ================================= -->
        <!-- Right Column: Sidebar (col-span-3)-->
        <!-- ================================= -->
        <div class="col-span-12 lg:col-span-3 space-y-6">

            <x-sidebar-right />

        </div>
    </div>
</div>
@endsection
