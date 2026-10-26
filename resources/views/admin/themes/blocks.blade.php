@extends('layouts.admin')

@section('content')
<div class="bg-[#f9f9f9] min-h-[calc(100vh-64px)] py-8 font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="text-[11px] font-medium text-gray-400 mb-1">Home &gt; Admin Panel &gt; Manage Blocks</div>
            <h1 class="text-[28px] font-normal text-gray-800 tracking-tight mb-2">MANAGE BLOCKS</h1>
            <p class="text-[#666666] text-[13px] max-w-2xl leading-relaxed">
                Konfigurasikan tata letak (layout) halaman komunitas Anda.<br>
                Unggah foto/banner promosi, salin URL atau langsung sisipkan kode HTML gambar ke kolom yang diinginkan.
            </p>
        </div>

        <!-- Section 1: Upload Foto & Galeri Blocks -->
        <div class="mb-8 bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden" x-data="{ isDragging: false, selectedFiles: [] }">
            <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-3.5 bg-[#cc0000]"></div>
                    <h2 class="text-[12px] font-bold text-gray-800 tracking-wider uppercase">UPLOAD FOTO & BANNER BLOCKS</h2>
                </div>
                <div class="text-[11px] text-gray-500 flex items-center gap-1.5">
                    <i data-lucide="image" class="w-3.5 h-3.5 text-[#cc0000]"></i>
                    <span>Format: JPG, PNG, GIF, WEBP, SVG (Maks. 5MB)</span>
                </div>
            </div>

            <div class="p-5">
                <!-- Form Upload -->
                <form action="{{ route('admin.themes.blocks.upload') }}" method="POST" enctype="multipart/form-data" id="blockUploadForm" class="mb-6">
                    @csrf
                    <div 
                        @dragover.prevent="isDragging = true" 
                        @dragleave.prevent="isDragging = false" 
                        @drop.prevent="isDragging = false; selectedFiles = Array.from($event.dataTransfer.files); $refs.fileInput.files = $event.dataTransfer.files"
                        :class="isDragging ? 'border-[#cc0000] bg-red-50/20' : 'border-[#d0d0d0] hover:border-[#cc0000] bg-[#fafafa]'"
                        class="border-2 border-dashed rounded-lg p-6 text-center transition-colors cursor-pointer relative"
                        @click="$refs.fileInput.click()">
                        
                        <input 
                            x-ref="fileInput" 
                            type="file" 
                            name="images[]" 
                            multiple 
                            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" 
                            class="hidden" 
                            @change="selectedFiles = Array.from($event.target.files)">

                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-[#cc0000] mb-3">
                                <i data-lucide="cloud-upload" class="w-6 h-6"></i>
                            </div>
                            <p class="text-[13px] font-medium text-gray-700">
                                <span class="text-[#cc0000] font-semibold hover:underline">Pilih file foto</span> atau tarik & lepaskan ke sini
                            </p>
                            <p class="text-[11px] text-gray-400 mt-1">Dapat memilih satu atau beberapa gambar sekaligus</p>
                        </div>

                        <!-- Preview list file yang dipilih sebelum upload -->
                        <template x-if="selectedFiles.length > 0">
                            <div class="mt-4 pt-4 border-t border-gray-200 text-left" @click.stop>
                                <div class="text-[11px] font-semibold text-gray-700 mb-2 flex items-center justify-between">
                                    <span>File terpilih (<span x-text="selectedFiles.length"></span> foto):</span>
                                    <button type="button" @click="selectedFiles = []; $refs.fileInput.value = ''" class="text-red-500 hover:underline text-[10px]">Reset</button>
                                </div>
                                <div class="flex flex-wrap gap-2 max-h-36 overflow-y-auto">
                                    <template x-for="(file, index) in selectedFiles" :key="index">
                                        <div class="bg-white border border-gray-300 rounded px-2.5 py-1 text-[11px] text-gray-700 flex items-center gap-1.5 shadow-xs">
                                            <i data-lucide="file-image" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span class="truncate max-w-[180px]" x-text="file.name"></span>
                                            <span class="text-[10px] text-gray-400" x-text="'(' + (file.size / 1024).toFixed(1) + ' KB)'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-[11px] text-gray-500">
                            Foto akan disimpan di direktori publik: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-[11px] text-gray-700">public/uploads/blocks/</code>
                        </span>
                        <button type="submit" class="bg-[#b90000] hover:bg-[#a00000] text-white text-[11px] font-bold py-2.5 px-5 rounded transition-colors uppercase tracking-wide flex items-center gap-2 shadow-sm">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            UNGGAH FOTO KE BLOCKS
                        </button>
                    </div>
                </form>

                <!-- Galeri Foto yang telah diunggah -->
                <div class="border-t border-[#e5e5e5] pt-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="images" class="w-4 h-4 text-gray-600"></i>
                            <h3 class="text-[12px] font-bold text-gray-800 uppercase tracking-wider">
                                GALERI FOTO BLOCKS (<span class="text-[#cc0000]">{{ count($uploadedImages) }}</span>)
                            </h3>
                        </div>
                        <span class="text-[11px] text-gray-400">Klik "Sisipkan" untuk memasukkan foto langsung ke kolom layout</span>
                    </div>

                    @if(count($uploadedImages) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($uploadedImages as $img)
                                <div class="bg-[#fcfcfc] border border-gray-200 rounded-lg overflow-hidden shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between group">
                                    <!-- Image Preview Thumbnail -->
                                    <div class="relative bg-gray-100 aspect-video flex items-center justify-center overflow-hidden border-b border-gray-100">
                                        <img src="{{ $img['url'] }}" alt="{{ $img['filename'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" loading="lazy">
                                        
                                        <!-- Overlay View Fullscreen -->
                                        <a href="{{ $img['url'] }}" target="_blank" title="Buka foto ukuran asli" class="absolute top-2 right-2 p-1.5 bg-black/60 hover:bg-black/80 text-white rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>

                                    <!-- Details & Filename -->
                                    <div class="p-3 flex-1 flex flex-col justify-between">
                                        <div class="mb-2">
                                            <p class="text-[11px] font-semibold text-gray-800 truncate" title="{{ $img['filename'] }}">
                                                {{ $img['filename'] }}
                                            </p>
                                            <div class="flex items-center justify-between text-[10px] text-gray-400 mt-1">
                                                <span>{{ number_format($img['size'] / 1024, 1) }} KB</span>
                                                <span>{{ date('d M Y, H:i', $img['time']) }}</span>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="space-y-1.5 pt-2 border-t border-gray-100">
                                            <!-- Copy URL Button -->
                                            <button 
                                                type="button" 
                                                onclick="copyToClipboard('{{ $img['url'] }}', 'URL foto berhasil disalin!')" 
                                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-[10.5px] font-medium py-1.5 px-2 rounded flex items-center justify-center gap-1.5 transition-colors">
                                                <i data-lucide="copy" class="w-3 h-3 text-gray-500"></i>
                                                Salin URL
                                            </button>

                                            <!-- Dropdown Sisipkan ke Kolom (Alpine.js) -->
                                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                                <button 
                                                    type="button" 
                                                    @click="open = !open" 
                                                    class="w-full bg-red-50 hover:bg-red-100 text-[#cc0000] border border-red-200 text-[10.5px] font-bold py-1.5 px-2 rounded flex items-center justify-center gap-1 transition-colors">
                                                    <i data-lucide="plus-circle" class="w-3 h-3 text-[#cc0000]"></i>
                                                    Sisipkan ke Kolom
                                                    <i data-lucide="chevron-down" class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div 
                                                    x-show="open" 
                                                    x-transition 
                                                    class="absolute z-30 bottom-full mb-1 left-0 right-0 bg-white border border-gray-200 rounded-md shadow-lg py-1 text-[11px]">
                                                    <div class="px-2.5 py-1 text-[9.5px] font-bold text-gray-400 tracking-wider uppercase border-b border-gray-100">
                                                        Pilih Kolom Tujuan:
                                                    </div>
                                                    <button 
                                                        type="button" 
                                                        @click="insertPhoto('block_left_column', '{{ $img['url'] }}', 'Left Column'); open = false" 
                                                        class="w-full text-left px-2.5 py-1.5 hover:bg-red-50 text-gray-700 hover:text-[#cc0000] flex items-center justify-between">
                                                        <span>Kolom Kiri (Left)</span>
                                                        <i data-lucide="layout-panel-left" class="w-3 h-3 text-gray-400"></i>
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        @click="insertPhoto('block_center_column', '{{ $img['url'] }}', 'Center Column'); open = false" 
                                                        class="w-full text-left px-2.5 py-1.5 hover:bg-red-50 text-gray-700 hover:text-[#cc0000] flex items-center justify-between">
                                                        <span>Kolom Tengah (Center)</span>
                                                        <i data-lucide="layout-panel-top" class="w-3 h-3 text-gray-400"></i>
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        @click="insertPhoto('block_right_column', '{{ $img['url'] }}', 'Right Column'); open = false" 
                                                        class="w-full text-left px-2.5 py-1.5 hover:bg-red-50 text-gray-700 hover:text-[#cc0000] flex items-center justify-between">
                                                        <span>Kolom Kanan (Right)</span>
                                                        <i data-lucide="panel-right-dashed" class="w-3 h-3 text-gray-400"></i>
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        @click="insertPhoto('block_header_code', '{{ $img['url'] }}', 'Header Code'); open = false" 
                                                        class="w-full text-left px-2.5 py-1.5 hover:bg-red-50 text-gray-700 hover:text-[#cc0000] flex items-center justify-between border-t border-gray-100">
                                                        <span>Header Code</span>
                                                        <i data-lucide="code" class="w-3 h-3 text-gray-400"></i>
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        @click="insertPhoto('block_footer_code', '{{ $img['url'] }}', 'Footer Code'); open = false" 
                                                        class="w-full text-left px-2.5 py-1.5 hover:bg-red-50 text-gray-700 hover:text-[#cc0000] flex items-center justify-between">
                                                        <span>Footer Code</span>
                                                        <i data-lucide="code" class="w-3 h-3 text-gray-400"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.themes.blocks.delete-image') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto {{ $img['filename'] }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="filename" value="{{ $img['filename'] }}">
                                                <button type="submit" class="w-full text-gray-400 hover:text-red-600 hover:bg-red-50 text-[10.5px] py-1 px-2 rounded flex items-center justify-center gap-1 transition-colors">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                    Hapus Foto
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-10 bg-[#fafafa] rounded-lg border border-dashed border-gray-200">
                            <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                <i data-lucide="image-off" class="w-6 h-6"></i>
                            </div>
                            <h4 class="text-[13px] font-semibold text-gray-700 mb-1">Belum ada foto yang diunggah</h4>
                            <p class="text-[11px] text-gray-400 max-w-sm mx-auto">
                                Unggah foto atau banner promosi menggunakan form di atas untuk disisipkan ke dalam kolom layout website.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 2: Form Layout Configuration -->
        <form action="{{ route('admin.themes.blocks.update') }}" method="POST" id="blocksConfigForm">
            @csrf
            
            <!-- Sticky Action Bar -->
            <div class="flex items-center justify-between mb-6 bg-white p-4 rounded border border-[#e5e5e5] shadow-sm sticky top-0 z-20">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-3.5 bg-[#cc0000]"></div>
                    <h2 class="text-[11px] font-bold text-[#888888] tracking-widest uppercase">LAYOUT CONFIGURATION</h2>
                </div>
                <button type="submit" class="bg-[#b90000] hover:bg-[#a00000] text-white text-[11px] font-bold py-2.5 px-6 rounded transition-colors uppercase tracking-wide flex items-center gap-2 shadow-sm">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    SAVE BLOCKS
                </button>
            </div>

            <div class="space-y-6">
                <!-- Header Code -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden" id="card_block_header_code">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">HEADER CODE</h3>
                            <p class="text-[11px] text-gray-500 mt-1">Inserted just before the closing &lt;/head&gt; tag. Ideal for CSS, Meta tags, or tracking scripts.</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <textarea id="block_header_code" name="header_code" rows="5" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none transition-all" placeholder="<!-- Your HTML or script here -->">{{ $blocks['header_code'] }}</textarea>
                    </div>
                </div>

                <!-- Footer Code -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden" id="card_block_footer_code">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">FOOTER CODE</h3>
                            <p class="text-[11px] text-gray-500 mt-1">Inserted just before the closing &lt;/body&gt; tag. Ideal for Analytics or JS scripts.</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <textarea id="block_footer_code" name="footer_code" rows="5" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none transition-all" placeholder="<!-- Your HTML or script here -->">{{ $blocks['footer_code'] }}</textarea>
                    </div>
                </div>

                <!-- Left Column -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden" id="card_block_left_column">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">LEFT COLUMN HTML</h3>
                            <p class="text-[11px] text-gray-500 mt-1">Displays on the left sidebar (below the user menu).</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <textarea id="block_left_column" name="left_column" rows="6" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none transition-all" placeholder="<!-- Ad code or widget HTML here -->">{{ $blocks['left_column'] }}</textarea>
                    </div>
                </div>

                <!-- Center Column -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden" id="card_block_center_column">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">CENTER COLUMN HTML</h3>
                            <p class="text-[11px] text-gray-500 mt-1">Displays at the top of the main content feed.</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <textarea id="block_center_column" name="center_column" rows="6" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none transition-all" placeholder="<!-- Ad code or widget HTML here -->">{{ $blocks['center_column'] }}</textarea>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="bg-white rounded border border-[#e5e5e5] shadow-sm overflow-hidden" id="card_block_right_column">
                    <div class="bg-[#f8f9fa] border-b border-[#e5e5e5] px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-[12px] font-bold text-gray-800 tracking-wider">RIGHT COLUMN HTML</h3>
                            <p class="text-[11px] text-gray-500 mt-1">Displays on the right sidebar (below sponsored ads/suggestions).</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <textarea id="block_right_column" name="right_column" rows="6" class="w-full bg-[#fcfcfc] border border-[#e5e5e5] text-[#333333] text-[13px] font-mono rounded focus:border-[#cc0000] focus:ring-1 focus:ring-[#cc0000] p-3 outline-none transition-all" placeholder="<!-- Ad code or widget HTML here -->">{{ $blocks['right_column'] }}</textarea>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<script>
    // Inisialisasi ikon Lucide
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Helper Copy URL ke Clipboard
    function copyToClipboard(text, msg = 'URL berhasil disalin!') {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showToast(msg);
            }).catch(() => fallbackCopy(text, msg));
        } else {
            fallbackCopy(text, msg);
        }
    }

    function fallbackCopy(text, msg) {
        let ta = document.createElement("textarea");
        ta.value = text;
        ta.style.position = "fixed";
        ta.style.left = "-999999px";
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            document.execCommand('copy');
            showToast(msg);
        } catch (e) {
            console.error('Copy failed', e);
        }
        ta.remove();
    }

    function showToast(title) {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: title,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        } else {
            alert(title);
        }
    }

    // Helper untuk menyisipkan tag <img> ke textarea kolom yang dipilih
    function insertPhoto(textareaId, imageUrl, columnName) {
        const textarea = document.getElementById(textareaId);
        if (!textarea) return;

        const htmlSnippet = `<div class="block-banner mb-4 text-center">\n    <a href="#" target="_blank">\n        <img src="${imageUrl}" alt="Banner" class="w-full rounded-xl shadow-sm hover:opacity-95 transition" style="max-width: 100%; height: auto;">\n    </a>\n</div>`;

        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        const val = textarea.value;

        const prefix = val.substring(0, startPos);
        const suffix = val.substring(endPos);
        const spacer = (prefix.length > 0 && !prefix.endsWith('\n')) ? '\n' : '';
        const spacerEnd = (suffix.length > 0 && !suffix.startsWith('\n')) ? '\n' : '';

        textarea.value = prefix + spacer + htmlSnippet + spacerEnd + suffix;
        textarea.focus();

        const newPos = (prefix + spacer + htmlSnippet).length;
        textarea.setSelectionRange(newPos, newPos);

        // Scroll ke kartu textarea tersebut
        const card = document.getElementById('card_' + textareaId) || textarea;
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Efek highlight berkedip lembut
        textarea.classList.add('ring-2', 'ring-[#cc0000]', 'border-[#cc0000]', 'bg-red-50/20');
        setTimeout(() => {
            textarea.classList.remove('ring-2', 'ring-[#cc0000]', 'border-[#cc0000]', 'bg-red-50/20');
        }, 1500);

        showToast(`Kode foto disisipkan ke ${columnName}!`);
    }
</script>
@endsection

