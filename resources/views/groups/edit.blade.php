@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Edit Grup: {{ $group->name }}</h2>
        </div>

        <div class="p-8">
            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('groups.update', $group->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Grup</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" value="{{ old('name', $group->name) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">{{ old('description', $group->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-4">Ubah Logo</label>
                    
                    <div class="flex items-center gap-6 mb-4">
                        <img src="{{ $group->logo ? asset('storage/'.$group->logo) : asset('img/default-group.png') }}" class="w-20 h-20 rounded-full object-cover border border-gray-200 bg-gray-100" alt="Current Logo">
                        <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-4">Ubah Banner (Background)</label>
                    
                    <div class="flex flex-col gap-4 mb-4">
                        @if($group->background)
                            <img src="{{ asset('storage/'.$group->background) }}" class="w-full h-32 rounded-xl object-cover border border-gray-200 bg-gray-100" alt="Current Banner">
                        @else
                            <div class="w-full h-32 rounded-xl border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-400">
                                Belum ada banner
                            </div>
                        @endif
                        <input type="file" name="background" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" accept="image/*">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                    <a href="{{ route('groups.show', $group->id) }}" class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors">Batal</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
