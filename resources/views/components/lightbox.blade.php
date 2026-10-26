<!-- LIGHTBOX MODAL -->
<div id="lightbox-modal" class="fixed inset-0 z-[100] hidden bg-black/95 flex flex-col justify-center items-center backdrop-blur-sm">
    <!-- Close -->
    <button type="button" onclick="closeLightbox()" class="absolute top-5 right-5 text-neutral-400 hover:text-white transition cursor-pointer p-2 bg-neutral-900/50 rounded-full">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    
    <!-- Prev -->
    <button type="button" onclick="prevLightboxImage()" class="absolute left-4 sm:left-10 text-neutral-400 hover:text-white transition p-3 bg-neutral-900/50 rounded-full hover:scale-110">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </button>
    
    <!-- Next -->
    <button type="button" onclick="nextLightboxImage()" class="absolute right-4 sm:right-10 text-neutral-400 hover:text-white transition p-3 bg-neutral-900/50 rounded-full hover:scale-110">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </button>
    
    <!-- Main Image Container -->
    <div class="relative max-w-5xl w-full px-16 flex justify-center items-center h-[85vh]">
        <img id="lightbox-img" src="" class="max-w-full max-h-full object-contain transition-opacity duration-200 shadow-2xl rounded-sm opacity-0">
    </div>
    
    <!-- Counter -->
    <div id="lightbox-counter" class="absolute bottom-6 bg-black/50 px-4 py-1.5 rounded-full text-white text-xs font-semibold tracking-wider"></div>
</div>

<script>
if (typeof window.openLightbox === 'undefined') {
    let lightboxImages = [];
    let lightboxCurrentIndex = 0;

    window.openLightbox = function(images, index) {
        lightboxImages = Array.isArray(images) ? images : [images];
        lightboxCurrentIndex = index || 0;
        updateLightbox();
        const modal = document.getElementById('lightbox-modal');
        if (modal) modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };
    window.closeLightbox = function() {
        const modal = document.getElementById('lightbox-modal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };
    window.prevLightboxImage = function() {
        lightboxCurrentIndex = (lightboxCurrentIndex > 0) ? lightboxCurrentIndex - 1 : lightboxImages.length - 1;
        updateLightbox();
    };
    window.nextLightboxImage = function() {
        lightboxCurrentIndex = (lightboxCurrentIndex < lightboxImages.length - 1) ? lightboxCurrentIndex + 1 : 0;
        updateLightbox();
    };
    window.updateLightbox = function() {
        const img = document.getElementById('lightbox-img');
        if (!img) return;
        img.style.opacity = '0';
        setTimeout(() => {
            img.src = lightboxImages[lightboxCurrentIndex] || '';
            img.style.opacity = '1';
            
            const counter = document.getElementById('lightbox-counter');
            if (counter) {
                if (lightboxImages.length > 1) {
                    counter.textContent = (lightboxCurrentIndex + 1) + ' / ' + lightboxImages.length;
                    counter.classList.remove('hidden');
                } else {
                    counter.classList.add('hidden');
                }
            }
        }, 150);
    };

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightbox-modal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevLightboxImage();
            if (e.key === 'ArrowRight') nextLightboxImage();
        }
    });
}
</script>
