@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Buat Grup Baru</h2>
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

            <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Grup</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" value="{{ old('name') }}" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Grup / URI</label>
                    <input type="text" name="uri" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" value="{{ old('uri') }}" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Logo Grup (Opsional)</label>
                    <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_private" id="is_private" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" {{ old('is_private') ? 'checked' : '' }}>
                    <label for="is_private" class="text-sm font-medium text-gray-700">Grup Privat (Memerlukan persetujuan admin untuk bergabung)</label>
                </div>

                <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                    <a href="{{ route('groups.browse') }}" class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors">Batal</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">Simpan Grup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
