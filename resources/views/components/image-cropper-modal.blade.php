<!-- Modal Crop & Compress Foto Interaktif -->
<div id="image-cropper-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="cropper-modal-title">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeCropperModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 id="cropper-modal-title" class="text-sm font-extrabold text-slate-900">Sesuaikan & Potong Foto</h3>
                    <p class="text-[11px] text-slate-500">Crop rasio 1:1 dan kompresi otomatis untuk avatar talent.</p>
                </div>
                <button type="button" onclick="closeCropperModal()" class="p-1 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition" aria-label="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body (Canvas Crop Viewport) -->
            <div class="p-6 space-y-4">
                <!-- Viewport Container -->
                <div class="relative w-72 h-72 sm:w-80 sm:h-80 mx-auto rounded-2xl overflow-hidden bg-slate-900 shadow-inner select-none cursor-grab active:cursor-grabbing border-2 border-slate-700">
                    <canvas id="cropCanvas" width="320" height="320" class="w-full h-full block touch-none"></canvas>

                    <!-- Circular Mask Overlay Visual -->
                    <div class="pointer-events-none absolute inset-0 rounded-full border-2 border-rose-500/80 shadow-[0_0_0_9999px_rgba(15,23,42,0.65)]"></div>
                </div>

                <!-- Zoom Controls -->
                <div class="space-y-1.5 max-w-xs mx-auto">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span class="text-[11px] font-semibold text-slate-600">Perbesar / Perkecil (Zoom)</span>
                        <span id="zoom-label" class="text-[11px] font-mono text-slate-400">1.0x</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="stepZoom(-0.1)" class="p-1 text-slate-400 hover:text-slate-600" title="Zoom Out">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                            </svg>
                        </button>
                        <input type="range"
                               id="cropZoom"
                               min="0.5"
                               max="3"
                               step="0.05"
                               value="1"
                               class="w-full accent-rose-600 cursor-pointer h-1.5 bg-slate-200 rounded-lg">
                        <button type="button" onclick="stepZoom(0.1)" class="p-1 text-slate-400 hover:text-slate-600" title="Zoom In">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Helper Note -->
                <div class="flex items-center justify-between text-[11px] text-slate-400 px-2 pt-1 border-t border-slate-100">
                    <span>💡 Geser foto untuk menyesuaikan posisi.</span>
                    <button type="button" onclick="resetCropPosition()" class="text-rose-600 hover:underline font-semibold">
                        Reset Posisi
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCropperModal()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition">
                    Batal
                </button>
                <button type="button" onclick="applyCropAndCompress()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/25 transition inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Gunakan Foto (Crop & Kompres)</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        let cropperModal, canvas, ctx, zoomInput, zoomLabel;
        let currentImage = null;
        let targetInput = null;
        let onCroppedSuccessCallback = null;

        let scale = 1;
        let baseScale = 1;
        let posX = 0;
        let posY = 0;
        let isDragging = false;
        let dragStartX = 0;
        let dragStartY = 0;

        window.initCropper = function() {
            cropperModal = document.getElementById('image-cropper-modal');
            canvas = document.getElementById('cropCanvas');
            if (!canvas) return;
            ctx = canvas.getContext('2d');
            zoomInput = document.getElementById('cropZoom');
            zoomLabel = document.getElementById('zoom-label');

            zoomInput.addEventListener('input', function(e) {
                scale = parseFloat(e.target.value);
                if (zoomLabel) zoomLabel.textContent = scale.toFixed(1) + 'x';
                render();
            });

            // Mouse events
            canvas.addEventListener('mousedown', function(e) {
                isDragging = true;
                dragStartX = e.clientX - posX;
                dragStartY = e.clientY - posY;
            });

            window.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                posX = e.clientX - dragStartX;
                posY = e.clientY - dragStartY;
                render();
            });

            window.addEventListener('mouseup', function() {
                isDragging = false;
            });

            // Touch events for Mobile
            canvas.addEventListener('touchstart', function(e) {
                if (e.touches.length === 1) {
                    isDragging = true;
                    dragStartX = e.touches[0].clientX - posX;
                    dragStartY = e.touches[0].clientY - posY;
                }
            }, { passive: false });

            canvas.addEventListener('touchmove', function(e) {
                if (!isDragging || e.touches.length !== 1) return;
                e.preventDefault();
                posX = e.touches[0].clientX - dragStartX;
                posY = e.touches[0].clientY - dragStartY;
                render();
            }, { passive: false });

            canvas.addEventListener('touchend', function() {
                isDragging = false;
            });
        };

        window.openImageCropper = function(file, inputElement, onCropSuccess) {
            if (!file || !file.type.startsWith('image/')) return;
            targetInput = inputElement;
            onCroppedSuccessCallback = onCropSuccess;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    currentImage = img;
                    setupImage();
                    if (cropperModal) cropperModal.classList.remove('hidden');
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };

        window.closeCropperModal = function() {
            if (cropperModal) cropperModal.classList.add('hidden');
            currentImage = null;
        };

        window.stepZoom = function(delta) {
            if (!zoomInput) return;
            let val = parseFloat(zoomInput.value) + delta;
            val = Math.max(parseFloat(zoomInput.min), Math.min(parseFloat(zoomInput.max), val));
            zoomInput.value = val;
            scale = val;
            if (zoomLabel) zoomLabel.textContent = scale.toFixed(1) + 'x';
            render();
        };

        window.resetCropPosition = function() {
            if (!currentImage) return;
            setupImage();
        };

        function setupImage() {
            if (!currentImage || !canvas) return;
            // Scale to fill canvas 320x320
            const canvasW = canvas.width;
            const canvasH = canvas.height;
            const maxDim = Math.max(currentImage.width, currentImage.height);
            const minDim = Math.min(currentImage.width, currentImage.height);

            baseScale = canvasW / minDim;
            scale = baseScale;
            zoomInput.min = (baseScale * 0.7).toFixed(2);
            zoomInput.max = (baseScale * 3.5).toFixed(2);
            zoomInput.value = scale.toFixed(2);
            if (zoomLabel) zoomLabel.textContent = '1.0x';

            posX = (canvasW - currentImage.width * scale) / 2;
            posY = (canvasH - currentImage.height * scale) / 2;
            render();
        }

        function render() {
            if (!currentImage || !ctx || !canvas) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.drawImage(currentImage, posX, posY, currentImage.width * scale, currentImage.height * scale);
            ctx.restore();
        }

        window.applyCropAndCompress = function() {
            if (!currentImage || !canvas) return;

            // Render ke offscreen canvas 500x500 untuk resolusi HD yang tajam namun ringan
            const exportSize = 500;
            const offscreen = document.createElement('canvas');
            offscreen.width = exportSize;
            offscreen.height = exportSize;
            const offCtx = offscreen.getContext('2d');

            const ratio = exportSize / canvas.width;
            offCtx.drawImage(
                currentImage,
                posX * ratio,
                posY * ratio,
                currentImage.width * scale * ratio,
                currentImage.height * scale * ratio
            );

            // Kompresi otomatis ke format JPEG kualitas 0.88 (~70-130 KB)
            offscreen.toBlob(function(blob) {
                if (!blob) return;

                const croppedFile = new File([blob], 'avatar_cropped.jpg', {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                // Pasang file terpotong & terkompresi ke target input file form
                if (targetInput && window.DataTransfer) {
                    const dt = new DataTransfer();
                    dt.items.add(croppedFile);
                    targetInput.files = dt.files;
                }

                const previewUrl = URL.createObjectURL(blob);
                const sizeKb = Math.round(blob.size / 1024);

                if (typeof onCroppedSuccessCallback === 'function') {
                    onCroppedSuccessCallback(previewUrl, croppedFile, sizeKb);
                }

                closeCropperModal();
            }, 'image/jpeg', 0.88);
        };

        document.addEventListener('DOMContentLoaded', initCropper);
    })();
</script>

