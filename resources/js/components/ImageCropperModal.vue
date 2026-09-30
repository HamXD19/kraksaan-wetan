<template>
  <div 
    v-if="show" 
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/70 backdrop-blur-xs"
  >
    <div class="bg-white rounded-2xl sm:rounded-3xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[95vh]">
      <!-- Header -->
      <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
        <div>
          <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Sesuaikan & Potong Foto (Crop)</span>
          </h3>
          <p class="text-[11px] text-slate-500">Geser atau zoom gambar untuk menentukan bidang foto yang akan diunggah.</p>
        </div>
        <button 
          type="button" 
          @click="cancel" 
          class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- Crop Area Container -->
      <div class="p-4 sm:p-6 bg-slate-900 flex-1 flex flex-col items-center justify-center overflow-hidden relative select-none">
        <div 
          ref="containerRef"
          class="relative overflow-hidden flex items-center justify-center bg-black/40 rounded-xl max-w-full shadow-inner cursor-grab active:cursor-grabbing"
          :style="{ width: containerWidth + 'px', height: containerHeight + 'px' }"
          @mousedown="startDrag"
          @mousemove="onDrag"
          @mouseup="endDrag"
          @mouseleave="endDrag"
          @touchstart="startTouch"
          @touchmove="onTouch"
          @touchend="endDrag"
        >
          <!-- Hidden Offscreen Canvas for Export -->
          <canvas ref="canvasRef" class="max-w-full max-h-full"></canvas>

          <!-- Visual Crop Guide Box -->
          <div 
            class="absolute inset-0 pointer-events-none border-2 border-dashed border-emerald-400/80 shadow-[0_0_0_9999px_rgba(0,0,0,0.55)]"
          >
            <!-- Grid lines -->
            <div class="w-full h-full grid grid-cols-3 grid-rows-3 opacity-30">
              <div class="border-r border-b border-white"></div>
              <div class="border-r border-b border-white"></div>
              <div class="border-b border-white"></div>
              <div class="border-r border-b border-white"></div>
              <div class="border-r border-b border-white"></div>
              <div class="border-b border-white"></div>
              <div class="border-r border-white"></div>
              <div class="border-r border-white"></div>
              <div></div>
            </div>
          </div>
        </div>

        <div class="text-[11px] text-slate-400 mt-2 flex items-center gap-2">
          <span>&bull; Klik & geser untuk mengatur posisi gambar</span>
        </div>
      </div>

      <!-- Control Toolbar -->
      <div class="p-4 bg-slate-50 border-t border-slate-200 shrink-0 space-y-3">
        <!-- Aspect Ratio Selection -->
        <div class="flex flex-wrap items-center justify-between gap-2">
          <span class="text-xs font-bold text-slate-700">Rasio Aspek:</span>
          <div class="flex flex-wrap items-center gap-1.5">
            <button
              v-for="r in aspectRatios"
              :key="r.label"
              type="button"
              @click="setAspectRatio(r.val)"
              :class="currentRatio === r.val ? 'bg-emerald-700 text-white font-bold' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'"
              class="px-2.5 py-1 rounded-lg text-[11px] transition cursor-pointer shadow-2xs"
            >
              {{ r.label }}
            </button>
          </div>
        </div>

        <!-- Zoom & Rotation Slider -->
        <div class="flex items-center gap-3">
          <span class="text-xs font-bold text-slate-700 whitespace-nowrap">Zoom:</span>
          <input 
            type="range" 
            v-model.number="zoom" 
            min="0.5" 
            max="3" 
            step="0.05" 
            class="flex-1 accent-emerald-600 cursor-pointer"
            @input="draw"
          />
          <span class="text-[11px] font-mono font-bold text-slate-600 w-10 text-right">{{ Math.round(zoom * 100) }}%</span>

          <button
            type="button"
            @click="rotateImage"
            class="p-1.5 rounded-lg border border-slate-300 hover:bg-white text-slate-700 text-xs font-bold transition flex items-center gap-1 cursor-pointer"
            title="Putar 90 derajat"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>90°</span>
          </button>
        </div>
      </div>

      <!-- Footer Buttons -->
      <div class="px-5 py-3.5 bg-white border-t border-slate-200 flex items-center justify-between shrink-0">
        <button
          type="button"
          @click="cancel"
          class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs cursor-pointer transition"
        >
          Batal
        </button>

        <button
          type="button"
          @click="cropAndConfirm"
          :disabled="processing"
          class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold text-xs shadow-xs transition flex items-center gap-2 cursor-pointer"
        >
          <svg v-if="processing" class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
          <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          <span>{{ processing ? 'Memproses...' : 'Terapkan & Unggah Foto' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  imageFile: {
    type: [File, Blob, null],
    default: null
  },
  defaultAspectRatio: {
    type: Number,
    default: 16 / 9
  }
});

const emit = defineEmits(['update:show', 'cropped', 'cancelled']);

const containerRef = ref(null);
const canvasRef = ref(null);
const processing = ref(false);

const aspectRatios = [
  { label: '16:9 (Banner)', val: 16 / 9 },
  { label: '4:3 (Artikel)', val: 4 / 3 },
  { label: '1:1 (Persegi)', val: 1 },
  { label: '3:4 (Poster)', val: 3 / 4 },
  { label: 'Bebas', val: 0 }
];

const currentRatio = ref(props.defaultAspectRatio || 16 / 9);
const containerWidth = ref(480);
const containerHeight = ref(270);

let loadedImage = null;
const zoom = ref(1);
const rotation = ref(0);
const offsetX = ref(0);
const offsetY = ref(0);

let isDragging = false;
let startX = 0;
let startY = 0;

const updateContainerDimensions = () => {
  const maxW = Math.min(window.innerWidth - 64, 520);
  const ratio = currentRatio.value === 0 ? 16 / 9 : currentRatio.value;
  
  if (ratio >= 1) {
    containerWidth.value = maxW;
    containerHeight.value = Math.round(maxW / ratio);
  } else {
    const maxH = 340;
    containerHeight.value = maxH;
    containerWidth.value = Math.round(maxH * ratio);
  }
};

const setAspectRatio = (val) => {
  currentRatio.value = val;
  updateContainerDimensions();
  nextTick(() => {
    draw();
  });
};

const rotateImage = () => {
  rotation.value = (rotation.value + 90) % 360;
  draw();
};

const loadImage = () => {
  if (!props.imageFile) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const img = new Image();
    img.onload = () => {
      loadedImage = img;
      zoom.value = 1;
      rotation.value = 0;
      offsetX.value = 0;
      offsetY.value = 0;
      updateContainerDimensions();
      nextTick(() => {
        draw();
      });
    };
    img.src = e.target.result;
  };
  reader.readAsDataURL(props.imageFile);
};

const draw = () => {
  if (!loadedImage || !canvasRef.value) return;

  const canvas = canvasRef.value;
  const ctx = canvas.getContext('2d');

  canvas.width = containerWidth.value;
  canvas.height = containerHeight.value;

  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.save();

  // Translate to center for rotation & zoom
  ctx.translate(canvas.width / 2 + offsetX.value, canvas.height / 2 + offsetY.value);
  ctx.rotate((rotation.value * Math.PI) / 180);
  ctx.scale(zoom.value, zoom.value);

  // Compute fitted base size
  let drawW = loadedImage.width;
  let drawH = loadedImage.height;

  // Fit image to cover container
  const scale = Math.max(canvas.width / drawW, canvas.height / drawH);
  drawW = drawW * scale;
  drawH = drawH * scale;

  ctx.drawImage(loadedImage, -drawW / 2, -drawH / 2, drawW, drawH);
  ctx.restore();
};

// Drag & Pan Handlers
const startDrag = (e) => {
  isDragging = true;
  startX = e.clientX - offsetX.value;
  startY = e.clientY - offsetY.value;
};

const onDrag = (e) => {
  if (!isDragging) return;
  offsetX.value = e.clientX - startX;
  offsetY.value = e.clientY - startY;
  draw();
};

const startTouch = (e) => {
  if (e.touches.length === 1) {
    isDragging = true;
    startX = e.touches[0].clientX - offsetX.value;
    startY = e.touches[0].clientY - offsetY.value;
  }
};

const onTouch = (e) => {
  if (!isDragging || e.touches.length !== 1) return;
  offsetX.value = e.touches[0].clientX - startX;
  offsetY.value = e.touches[0].clientY - startY;
  draw();
};

const endDrag = () => {
  isDragging = false;
};

const cancel = () => {
  emit('update:show', false);
  emit('cancelled');
};

const cropAndConfirm = async () => {
  if (!canvasRef.value) return;
  processing.value = true;

  try {
    const canvas = canvasRef.value;
    canvas.toBlob((blob) => {
      if (!blob) {
        processing.value = false;
        return;
      }
      const fileName = props.imageFile?.name ? `cropped-${props.imageFile.name}` : `foto-${Date.now()}.jpg`;
      const croppedFile = new File([blob], fileName, { type: 'image/jpeg' });
      emit('cropped', croppedFile);
      emit('update:show', false);
      processing.value = false;
    }, 'image/jpeg', 0.92);
  } catch (err) {
    console.error('Crop error:', err);
    processing.value = false;
  }
};

watch(() => props.show, (newVal) => {
  if (newVal) {
    currentRatio.value = props.defaultAspectRatio || 16 / 9;
    nextTick(loadImage);
  } else {
    loadedImage = null;
  }
});
</script>
