<script>
    // Lightbox Logic
    let lightboxImages = [];
    let lightboxCurrentIndex = 0;

    window.openLightbox = function(images, index) {
        lightboxImages = images;
        lightboxCurrentIndex = index;
        updateLightbox();
        document.getElementById('lightbox-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // cegah scroll background
    }
    window.closeLightbox = function() {
        document.getElementById('lightbox-modal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // kembalikan scroll
    }
    window.prevLightboxImage = function() {
        lightboxCurrentIndex = (lightboxCurrentIndex > 0) ? lightboxCurrentIndex - 1 : lightboxImages.length - 1;
        updateLightbox();
    }
    window.nextLightboxImage = function() {
        lightboxCurrentIndex = (lightboxCurrentIndex < lightboxImages.length - 1) ? lightboxCurrentIndex + 1 : 0;
        updateLightbox();
    }
    window.updateLightbox = function() {
        const img = document.getElementById('lightbox-img');
        img.style.opacity = '0'; // fade out
        setTimeout(() => {
            img.src = lightboxImages[lightboxCurrentIndex];
            img.style.opacity = '1'; // fade in
            
            const counter = document.getElementById('lightbox-counter');
            if (lightboxImages.length > 1) {
                counter.textContent = (lightboxCurrentIndex + 1) + ' / ' + lightboxImages.length;
                counter.classList.remove('hidden');
            } else {
                counter.classList.add('hidden'); // Sembunyikan counter kalau cuma 1 foto
            }
        }, 150);
    }

    // Keyboard support (Escape, Left, Right arrow)
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightbox-modal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevLightboxImage();
            if (e.key === 'ArrowRight') nextLightboxImage();
        }
    });

function switchTab(tabName) {
    const tabs = ['status', 'unggah', 'video'];
    
    tabs.forEach(t => {
        // Sembunyikan konten tab
        const content = document.getElementById('tab-content-' + t);
        if(content) content.classList.add('hidden');
        
        // Nonaktifkan input agar tidak menimpa name yang sama saat disubmit
        if(content) {
            const inputs = content.querySelectorAll('input, textarea, select');
            inputs.forEach(input => input.disabled = true);
        }

        // Kembalikan gaya tombol tab ke default (abu-abu)
        const btn = document.getElementById('tab-btn-' + t);
        if(btn) {
            btn.classList.remove('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
            btn.classList.add('text-neutral-500', 'font-medium');
        }
    });

    // Tampilkan konten tab yang aktif
    const activeContent = document.getElementById('tab-content-' + tabName);
    if(activeContent) activeContent.classList.remove('hidden');
    
    // Aktifkan kembali input di tab yang aktif
    if(activeContent) {
        const activeInputs = activeContent.querySelectorAll('input, textarea, select');
        activeInputs.forEach(input => input.disabled = false);
    }

    // Ubah gaya tombol tab yang aktif (merah dan bold)
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    if(activeBtn) {
        activeBtn.classList.remove('text-neutral-500', 'font-medium');
        activeBtn.classList.add('text-red-700', 'font-bold', 'border-red-700', 'border-b-2');
    }
}

// Inisialisasi tab saat halaman pertama dimuat
document.addEventListener('DOMContentLoaded', function() {
    switchTab('status');
});

function previewPhotoHome(event) {
    const input = event.target;
    const container = document.getElementById('photo-preview-container-home');
    const clearBtn = document.getElementById('btn-clear-photo');
    if(container) container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        if(container) container.classList.remove('hidden');
        if(clearBtn) clearBtn.classList.remove('hidden');
        
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-24 object-cover rounded-xl border border-neutral-200 shadow-sm';
                if(container) container.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
}
function clearPhotoHome() {
    const input = document.getElementById('photo-input-home');
    if(input) input.value = '';
    const container = document.getElementById('photo-preview-container-home');
    if(container) {
        container.innerHTML = '';
        container.classList.add('hidden');
    }
    const clearBtn = document.getElementById('btn-clear-photo');
    if(clearBtn) clearBtn.classList.add('hidden');
}



// Function to handle Reply
function replyTo(streamId, username) {
    const input = document.querySelector('#comment-form-' + streamId + ' input[name="message"]');
    if (input) {
        input.value = '@' + username + ' ';
        input.focus();
    }
}

// AJAX Handling
document.addEventListener('DOMContentLoaded', function() {
    
    // AJAX for Likes
    document.querySelectorAll('.form-like').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = this.action;
            const formData = new FormData(this);
            const streamId = url.split('/').pop();

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const countEl = document.getElementById('like-count-' + streamId);
                if(countEl) countEl.textContent = data.likes;
                const btn = this.querySelector('button');
                if(btn) {
                    if(data.status === 'liked') {
                        btn.classList.add('text-red-700');
                    } else {
                        btn.classList.remove('text-red-700');
                    }
                }
            });
        });
    });

    // AJAX for Comments
    document.querySelectorAll('.form-comment').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = this.action;
            const formData = new FormData(this);
            const streamId = this.dataset.streamId;
            const inputField = this.querySelector('input[name="message"]');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update comment count
                    const countEl = document.getElementById('comments-count-' + streamId);
                    if(countEl) countEl.textContent = data.comments_count;
                    
                    // Parse Mentions in JS
                    let parsedMessage = data.comment.message;
                    // Escape HTML basic
                    parsedMessage = parsedMessage.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                    // Regex for Mention
                    parsedMessage = parsedMessage.replace(/@([a-zA-Z0-9_]+)/g, '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>');

                    // Append new comment HTML
                    const commentHtml = `
                        <div class="flex items-start space-x-2 mb-3">
                            <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0">
                                <img src="${data.comment.user.avatar}" class="w-full h-full">
                            </div>
                            <div class="bg-neutral-50 p-2.5 rounded-lg flex-1 text-sm">
                                <span class="font-bold text-neutral-900">${data.comment.user.fullname}</span>
                                <p class="text-neutral-700 mt-1">${parsedMessage}</p>
                                <button type="button" onclick="replyTo('${streamId}', '${data.comment.user.username}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 mt-1 uppercase tracking-wider">Reply</button>
                            </div>
                        </div>
                    `;
                    const listEl = document.getElementById('comments-list-' + streamId);
                    if(listEl) listEl.insertAdjacentHTML('beforeend', commentHtml);
                    
                    // Clear input
                    if(inputField) inputField.value = '';
                }
            });
        });
    });
    // ==========================================
    // ALBUM MANAGEMENT (Fetch, Toggle Mode, Create)
    // ==========================================

    function fetchAlbums(type, selectId) {
        fetch(`/api/albums?type=${type}`)
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById(selectId);
                if(!select) return;
                select.innerHTML = '<option value="0">-- Pilih Album --</option>';
                data.forEach(album => {
                    const option = document.createElement('option');
                    option.value = album.id;
                    option.textContent = album.name;
                    select.appendChild(option);
                });
            });
    }

    // Load album saat pertama kali
    fetchAlbums('photos', 'photo-album-select');
    fetchAlbums('videos', 'video-album-select');

    // Fungsi toggle mode (Pilih <-> Buat Baru)
    window.toggleAlbumMode = function(type, mode) {
        const prefix = type === 'photos' ? 'photo' : 'video';
        const selectMode = document.getElementById(`${prefix}-album-select-mode`);
        const createMode = document.getElementById(`${prefix}-album-create-mode`);
        if(!selectMode || !createMode) return;
        
        if (mode === 'create') {
            selectMode.classList.add('hidden');
            createMode.classList.remove('hidden');
            document.getElementById(`${prefix}-album-new-name`).focus();
        } else {
            createMode.classList.add('hidden');
            selectMode.classList.remove('hidden');
            document.getElementById(`${prefix}-album-new-name`).value = '';
            document.getElementById(`${prefix}-album-new-desc`).value = '';
            document.getElementById(`${prefix}-album-new-privacy`).value = 'public';
        }
    }

    // Fungsi simpan album baru via AJAX
    window.saveNewAlbum = function(type) {
        const prefix = type === 'photos' ? 'photo' : 'video';
        const inputName = document.getElementById(`${prefix}-album-new-name`);
        const inputDesc = document.getElementById(`${prefix}-album-new-desc`);
        const inputPrivacy = document.getElementById(`${prefix}-album-new-privacy`);
        const msgEl = document.getElementById(`${prefix}-album-msg`);
        
        if(!inputName || !msgEl) return;

        const valName = inputName.value.trim();
        if(!valName) {
            msgEl.textContent = "Nama album tidak boleh kosong!";
            msgEl.classList.remove('hidden', 'text-green-600');
            msgEl.classList.add('text-red-600');
            return;
        }

        const formData = new FormData();
        formData.append('name', valName);
        formData.append('description', inputDesc.value.trim());
        formData.append('privacy', inputPrivacy.value);
        formData.append('type', type);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("album.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Tampilkan sukses
                msgEl.textContent = `Album '${data.album.name}' berhasil dibuat!`;
                msgEl.classList.remove('hidden', 'text-red-600');
                msgEl.classList.add('text-green-600');
                
                // Fetch ulang dropdown & kembali ke mode select
                fetchAlbums(type, `${prefix}-album-select`);
                
                setTimeout(() => {
                    toggleAlbumMode(type, 'select');
                    msgEl.classList.add('hidden');
                    // Pilih album yang baru dibuat (opsional, karena fetchAlbums asinkron)
                    setTimeout(() => {
                        const select = document.getElementById(`${prefix}-album-select`);
                        if(select) select.value = data.album.id;
                    }, 500);
                }, 1500);
            } else {
                msgEl.textContent = "Gagal membuat album.";
                msgEl.classList.remove('hidden', 'text-green-600');
                msgEl.classList.add('text-red-600');
            }
        });
    }

});
</script>
