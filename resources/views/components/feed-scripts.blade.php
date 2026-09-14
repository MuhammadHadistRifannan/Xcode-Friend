<style>
@keyframes likeElasticPop {
    0% { transform: scale(1); }
    30% { transform: scale(1.45) rotate(-12deg); }
    60% { transform: scale(0.9) rotate(6deg); }
    100% { transform: scale(1) rotate(0); }
}
.animate-like-pop {
    animation: likeElasticPop 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards !important;
}

@keyframes heartFloatUp {
    0% {
        transform: translate(-50%, -50%) scale(0.2);
        opacity: 0;
    }
    40% {
        transform: translate(-50%, -50%) scale(1.35);
        opacity: 0.95;
    }
    70% {
        transform: translate(-50%, -70%) scale(1.15);
        opacity: 0.9;
    }
    100% {
        transform: translate(-50%, -120px) scale(0.85);
        opacity: 0;
    }
}
.floating-heart-anim {
    position: absolute;
    top: 50%;
    left: 50%;
    pointer-events: none;
    z-index: 40;
    animation: heartFloatUp 0.85s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

@keyframes slideDownFadeIn {
    0% {
        opacity: 0;
        transform: translateY(-24px) scale(0.98);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.feed-entry-animate {
    animation: slideDownFadeIn 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.feed-glow-highlight {
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.45), 0 12px 28px -5px rgba(220, 38, 38, 0.18) !important;
    transition: box-shadow 2.5s ease-out;
}

@keyframes shimmerWave {
    0% { background-position: -250% 0; }
    100% { background-position: 250% 0; }
}
.skeleton-shimmer {
    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
    background-size: 250% 100%;
    animation: shimmerWave 1.8s infinite linear;
}
</style>

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
window.switchTab = switchTab;

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
window.previewPhotoHome = previewPhotoHome;

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
window.clearPhotoHome = clearPhotoHome;

function previewMusic(event) {
    const input = event.target;
    const nameLabel = document.getElementById('music-file-name');
    if (input.files && input.files.length > 0) {
        if (nameLabel) {
            nameLabel.textContent = input.files[0].name;
            nameLabel.classList.remove('text-neutral-700');
            nameLabel.classList.add('text-green-600');
        }
    } else {
        if (nameLabel) {
            nameLabel.textContent = 'Klik untuk memilih file audio';
            nameLabel.classList.remove('text-green-600');
            nameLabel.classList.add('text-neutral-700');
        }
    }
}
// Function to handle Reply
function replyTo(streamId, username) {
    const input = document.querySelector('#comment-form-' + streamId + ' input[name="message"]');
    if (input) {
        input.value = '@' + username + ' ';
        input.focus();
    }
}

// ==========================================
// AUTO-LOADER: AJAX LIKES, COMMENTS, BAGI CEPAT & INFINITE SCROLL
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. AJAX for Likes with Elastic Pop Animation (Event Delegation)
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.form-like');
        if (!form) return;
        
        e.preventDefault();
        const url = form.action;
        const formData = new FormData(form);
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
            const btn = form.querySelector('button');
            if(btn) {
                const icon = btn.querySelector('.like-icon') || btn.querySelector('svg');
                if(data.status === 'liked') {
                    btn.classList.add('text-red-700');
                    if (icon) {
                        icon.classList.remove('animate-like-pop');
                        void icon.offsetWidth; // trigger reflow
                        icon.classList.add('animate-like-pop');
                        icon.setAttribute('fill', 'currentColor');
                    }
                } else {
                    btn.classList.remove('text-red-700');
                    if (icon) {
                        icon.classList.remove('animate-like-pop');
                        icon.setAttribute('fill', 'none');
                    }
                }
            }
        });
    });

    // Double-tap on photo to like with Floating Heart Animation
    let lastTapTime = 0;
    document.addEventListener('click', function(e) {
        const photoWrapper = e.target.closest('.post-photo-wrapper');
        if (!photoWrapper) return;

        const currentTime = new Date().getTime();
        const tapInterval = currentTime - lastTapTime;
        lastTapTime = currentTime;

        // Check if double tap occurred (within 300ms)
        if (tapInterval < 300 && tapInterval > 0) {
            e.preventDefault();
            e.stopPropagation();

            const card = photoWrapper.closest('.stream-card');
            if (!card) return;

            // Buat floating heart di tengah gambar
            const heart = document.createElement('div');
            heart.className = 'floating-heart-anim text-red-600 drop-shadow-2xl';
            heart.innerHTML = `<svg class="w-20 h-20 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`;
            photoWrapper.appendChild(heart);
            setTimeout(() => heart.remove(), 900);

            // Trigger like jika belum disukai
            const likeForm = card.querySelector('.form-like');
            const likeBtn = likeForm ? likeForm.querySelector('button') : null;
            if (likeForm && likeBtn && !likeBtn.classList.contains('text-red-700')) {
                likeForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            }
        }
    });

    // 2. AJAX for Comments (Event Delegation)
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.form-comment');
        if (!form) return;

        e.preventDefault();
        const url = form.action;
        const formData = new FormData(form);
        const streamId = form.dataset.streamId;
        const inputField = form.querySelector('input[name="message"]');

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
                const countEl = document.getElementById('comments-count-' + streamId);
                if(countEl) countEl.textContent = data.comments_count;
                
                // Parse Mentions in JS
                let parsedMessage = data.comment.message.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                parsedMessage = parsedMessage.replace(/@([a-zA-Z0-9_]+)/g, '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>');

                // Jika elemen komentar belum disisipkan oleh WebSocket
                if (!document.getElementById('comment-' + data.comment.id)) {
                    const commentHtml = `
                        <div class="flex gap-2" id="comment-${data.comment.id}">
                            <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0 border border-neutral-200">
                                <img src="${data.comment.user.avatar}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1" x-data="{ openCommentOptions: false }">
                                <div class="flex items-start gap-2 group">
                                    <div class="bg-white px-3 py-2 rounded-2xl border border-neutral-100 shadow-sm text-sm break-words max-w-[85%]">
                                        <span class="font-bold text-neutral-900 mr-1">${data.comment.user.fullname}</span>
                                        <span class="text-neutral-700">${parsedMessage}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mt-1 ml-2">
                                    <p class="text-[10px] text-neutral-400">Baru saja</p>
                                    <button type="button" onclick="replyTo('${streamId}', '${data.comment.user.username}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 transition-colors uppercase tracking-wider">Balas</button>
                                </div>
                            </div>
                        </div>
                    `;
                    const listEl = document.getElementById('comments-list-' + streamId);
                    if(listEl) {
                        listEl.insertAdjacentHTML('beforeend', commentHtml);
                        listEl.scrollTop = listEl.scrollHeight;
                    }
                }
                
                if(inputField) inputField.value = '';
            }
        });
    });

    // 3. AJAX for Bagi Cepat (Unggah Postingan Tanpa Refresh)
    const feedUploadForm = document.getElementById('form-feed-upload');
    if (feedUploadForm) {
        feedUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn ? submitBtn.innerHTML : 'Bagikan';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg> Membagikan...
                `;
            }

            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Sisipkan postingan langsung ke paling atas feed jika belum ada
                    const container = document.getElementById('feed-stream-container');
                    if (container && data.html) {
                        const emptyState = document.getElementById('feed-empty-state');
                        if (emptyState) emptyState.remove();

                        if (!document.getElementById('stream-card-' + data.stream_id)) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data.html;
                            const newCard = tempDiv.firstElementChild;
                            newCard.classList.add('feed-entry-animate', 'feed-glow-highlight');
                            container.insertBefore(newCard, container.firstChild);
                            setTimeout(() => {
                                newCard.classList.remove('feed-glow-highlight');
                            }, 2500);
                        }
                    }

                    // Reset form & preview
                    feedUploadForm.reset();
                    const photoPreview = document.getElementById('photo-preview-container-home');
                    if (photoPreview) photoPreview.innerHTML = '';
                    const videoPreview = document.getElementById('video-preview-home');
                    if (videoPreview) videoPreview.classList.add('hidden');
                    
                    if (window.switchTab) switchTab('status');
                    if (window.lucide) window.lucide.createIcons();
                } else {
                    alert(data.message || 'Gagal membagikan status.');
                }
            })
            .catch(err => {
                console.error(err);
                // Fallback: submit biasa jika fetch gagal
                feedUploadForm.submit();
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                }
            });
        });
    }

    // 4. INFINITE SCROLL AUTO-LOADER (Memuat Halaman Berikutnya Saat Di-scroll)
    const sentinel = document.getElementById('feed-infinite-sentinel');
    let isFetchingMore = false;

    if (sentinel) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !isFetchingMore) {
                    const hasMore = sentinel.getAttribute('data-has-more') === '1';
                    const nextPageUrl = sentinel.getAttribute('data-next-page');

                    if (!hasMore || !nextPageUrl) return;

                    isFetchingMore = true;
                    const spinner = document.getElementById('feed-loading-spinner');
                    if (spinner) spinner.classList.remove('hidden');

                    fetch(nextPageUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const container = document.getElementById('feed-stream-container');
                        if (container && data.html) {
                            container.insertAdjacentHTML('beforeend', data.html);
                        }

                        sentinel.setAttribute('data-has-more', data.hasMorePages ? '1' : '0');
                        sentinel.setAttribute('data-next-page', data.nextPageUrl || '');

                        const noMore = document.getElementById('feed-no-more');
                        if (!data.hasMorePages) {
                            if (noMore) noMore.classList.remove('hidden');
                            observer.unobserve(sentinel);
                        }

                        if (window.lucide) window.lucide.createIcons();
                    })
                    .catch(err => console.error('Gagal auto-load feed:', err))
                    .finally(() => {
                        isFetchingMore = false;
                        if (spinner) spinner.classList.add('hidden');
                    });
                }
            });
        }, { rootMargin: '300px' });

        observer.observe(sentinel);
    }

    // 5. WEBSOCKET REALTIME (Laravel Echo & Reverb)
    if (window.Echo) {
        window.Echo.channel('public-feed')
            .listen('.stream.created', function(e) {
                console.log('Realtime StreamCreated:', e);
                // Abaikan jika postingan ini sudah ada (misal diunggah oleh akun sendiri)
                if (document.getElementById('stream-card-' + e.stream_id)) return;

                const container = document.getElementById('feed-stream-container');
                if (container && e.html) {
                    const wallUid = container.getAttribute('data-wall-uid');
                    if (wallUid && e.user_id != wallUid && e.wall_id != wallUid) {
                        return;
                    }
                    const emptyState = document.getElementById('feed-empty-state');
                    if (emptyState) emptyState.remove();

                    // Tampilkan card postingan baru dengan animasi slideDown dan highlight glow
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = e.html;
                    const newCard = tempDiv.firstElementChild;
                    newCard.classList.add('feed-entry-animate', 'feed-glow-highlight');
                    container.insertBefore(newCard, container.firstChild);

                    setTimeout(() => {
                        newCard.classList.remove('feed-glow-highlight');
                    }, 2500);

                    if (window.lucide) window.lucide.createIcons();
                }
            })
            .listen('.comment.created', function(e) {
                console.log('Realtime CommentCreated:', e);
                const countEl = document.getElementById('comments-count-' + e.stream_id);
                if (countEl) countEl.textContent = e.comments_count;

                // Jangan render ulang jika sudah ada
                if (document.getElementById('comment-' + e.comment.id)) return;

                let parsedMessage = e.comment.message.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                parsedMessage = parsedMessage.replace(/@([a-zA-Z0-9_]+)/g, '<a href="/@$1" class="text-blue-600 hover:underline">@$1</a>');

                const commentHtml = `
                    <div class="flex gap-2" id="comment-${e.comment.id}">
                        <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden flex-shrink-0 border border-neutral-200">
                            <img src="${e.comment.user.avatar}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1" x-data="{ openCommentOptions: false }">
                            <div class="flex items-start gap-2 group">
                                <div class="bg-white px-3 py-2 rounded-2xl border border-neutral-100 shadow-sm text-sm break-words max-w-[85%]">
                                    <span class="font-bold text-neutral-900 mr-1">${e.comment.user.fullname}</span>
                                    <span class="text-neutral-700">${parsedMessage}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 mt-1 ml-2">
                                <p class="text-[10px] text-neutral-400">${e.comment.created_human || 'Baru saja'}</p>
                                <button type="button" onclick="replyTo('${e.stream_id}', '${e.comment.user.username}')" class="text-[10px] text-neutral-500 font-semibold hover:text-red-700 transition-colors uppercase tracking-wider">Balas</button>
                            </div>
                        </div>
                    </div>
                `;
                const listEl = document.getElementById('comments-list-' + e.stream_id);
                if (listEl) {
                    listEl.insertAdjacentHTML('beforeend', commentHtml);
                    listEl.scrollTop = listEl.scrollHeight;
                }
            })
            .listen('.stream.liked', function(e) {
                console.log('Realtime StreamLiked:', e);
                const countEl = document.getElementById('like-count-' + e.stream_id);
                if (countEl) countEl.textContent = e.likes_count;
            });
    }
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
    fetchAlbums('video', 'video-album-select');

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
