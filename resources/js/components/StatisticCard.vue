<template>
  <div 
    ref="cardRef"
    class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden"
  >
    <!-- Background Decor Element -->
    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full opacity-60 pointer-events-none"></div>

    <div class="relative z-10 flex items-start justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          {{ label }}
        </p>
        <div class="flex items-baseline gap-1.5">
          <span class="text-3xl sm:text-4xl font-extrabold text-emerald-900 tracking-tight font-mono">
            {{ displayValue }}
          </span>
          <span v-if="unit" class="text-xs font-medium text-slate-500">
            {{ unit }}
          </span>
        </div>
        <p v-if="subtext" class="text-[11px] text-slate-500 mt-2">
          {{ subtext }}
        </p>
      </div>

      <div class="w-12 h-12 rounded-xl bg-emerald-100/70 text-emerald-800 flex items-center justify-center flex-shrink-0">
        <slot name="icon">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  unit: {
    type: String,
    default: ''
  },
  subtext: {
    type: String,
    default: ''
  }
});

const cardRef = ref(null);
const displayValue = ref('0');

const animateCount = () => {
  const target = parseFloat(props.value);
  if (isNaN(target)) {
    displayValue.value = String(props.value);
    return;
  }

  const isDecimal = String(props.value).includes('.');
  const duration = 1600;
  const startTime = performance.now();

  const update = (now) => {
    const elapsed = now - startTime;
    const progress = Math.min(elapsed / duration, 1);
    // Ease out quart
    const ease = 1 - Math.pow(1 - progress, 4);
    const current = target * ease;

    if (isDecimal) {
      displayValue.value = current.toFixed(2);
    } else {
      displayValue.value = Math.floor(current).toLocaleString('id-ID');
    }

    if (progress < 1) {
      requestAnimationFrame(update);
    } else {
      displayValue.value = isDecimal ? target.toFixed(2) : target.toLocaleString('id-ID');
    }
  };

  requestAnimationFrame(update);
};

onMounted(() => {
  if (!window.IntersectionObserver) {
    displayValue.value = String(props.value);
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      animateCount();
      observer.disconnect();
    }
  }, { threshold: 0.2 });

  if (cardRef.value) {
    observer.observe(cardRef.value);
  }
});

watch(() => props.value, () => {
  animateCount();
});
</script>
