<template>
  <div 
    class="fixed top-5 right-5 z-[99999] max-w-sm sm:max-w-md w-full pointer-events-none flex flex-col gap-3 px-4 sm:px-0"
    aria-live="polite"
  >
    <TransitionGroup name="toast">
      <div 
        v-for="item in toasts" 
        :key="item.id"
        class="pointer-events-auto rounded-3xl shadow-2xl border backdrop-blur-xl overflow-hidden p-4 sm:p-5 flex flex-col gap-3 relative transition-all duration-300 select-none group cursor-pointer bg-slate-900/95"
        :class="getToastCardClass(item.type)"
        @click="remove(item.id)"
      >
        <!-- Subtle Glow Halo Backdrop -->
        <div 
          class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-2xl opacity-30 pointer-events-none transition-transform group-hover:scale-125"
          :class="getGlowClass(item.type)"
        ></div>

        <div class="flex items-start gap-3.5 relative z-10">
          <!-- Themed Icon Container -->
          <div 
            class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 border shadow-xs transition-transform group-hover:scale-105"
            :class="getIconContainerClass(item.type)"
          >
            <!-- Success Animated Checkmark -->
            <svg v-if="item.type === 'success'" class="w-6 h-6 text-emerald-400 animate-in zoom-in duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <!-- Error Pulsing Cross -->
            <svg v-else-if="item.type === 'error'" class="w-6 h-6 text-rose-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <!-- Warning Exclamation -->
            <svg v-else-if="item.type === 'warning'" class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <!-- Info Icon -->
            <svg v-else class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>

          <!-- Message Text -->
          <div class="flex-1 min-w-0 pr-2">
            <div class="flex items-center justify-between gap-2">
              <h4 class="text-xs sm:text-sm font-extrabold text-white tracking-wide leading-tight">
                {{ item.title }}
              </h4>
              <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full" :class="getTypeBadgeClass(item.type)">
                {{ item.type }}
              </span>
            </div>
            <p class="text-xs text-slate-200 mt-1.5 leading-relaxed break-words font-medium">
              {{ item.message }}
            </p>
          </div>

          <!-- Close Cross Button -->
          <button 
            type="button" 
            @click.stop="remove(item.id)"
            class="text-white/40 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer shrink-0 -mt-1 -mr-1"
            title="Tutup Pop-up"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Progress Countdown Bar -->
        <div class="w-full bg-white/10 h-1 rounded-full overflow-hidden mt-0.5 relative z-10">
          <div 
            class="h-full rounded-full transition-all duration-75"
            :class="getProgressBarClass(item.type)"
            :style="{ width: `${Math.max(0, item.progress)}%` }"
          ></div>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToast } from '../composables/useToast';

const { toasts, remove } = useToast();

const getToastCardClass = (type) => {
  switch (type) {
    case 'success':
      return 'border-emerald-500/40 shadow-emerald-950/60 ring-1 ring-emerald-500/20';
    case 'error':
      return 'border-rose-500/40 shadow-rose-950/60 ring-1 ring-rose-500/20';
    case 'warning':
      return 'border-amber-500/40 shadow-amber-950/60 ring-1 ring-amber-500/20';
    default:
      return 'border-sky-500/40 shadow-sky-950/60 ring-1 ring-sky-500/20';
  }
};

const getGlowClass = (type) => {
  switch (type) {
    case 'success': return 'bg-emerald-500';
    case 'error': return 'bg-rose-500';
    case 'warning': return 'bg-amber-500';
    default: return 'bg-sky-500';
  }
};

const getIconContainerClass = (type) => {
  switch (type) {
    case 'success': return 'bg-emerald-500/20 border-emerald-500/40';
    case 'error': return 'bg-rose-500/20 border-rose-500/40';
    case 'warning': return 'bg-amber-500/20 border-amber-500/40';
    default: return 'bg-sky-500/20 border-sky-500/40';
  }
};

const getTypeBadgeClass = (type) => {
  switch (type) {
    case 'success': return 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
    case 'error': return 'bg-rose-500/20 text-rose-300 border border-rose-500/30';
    case 'warning': return 'bg-amber-500/20 text-amber-300 border border-amber-500/30';
    default: return 'bg-sky-500/20 text-sky-300 border border-sky-500/30';
  }
};

const getProgressBarClass = (type) => {
  switch (type) {
    case 'success': return 'bg-gradient-to-r from-emerald-400 to-teal-300';
    case 'error': return 'bg-gradient-to-r from-rose-500 to-red-400';
    case 'warning': return 'bg-gradient-to-r from-amber-400 to-yellow-300';
    default: return 'bg-gradient-to-r from-sky-400 to-blue-300';
  }
};
</script>

<style scoped>
.toast-enter-active {
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-leave-active {
  transition: all 0.25s cubic-bezier(0.4, 0, 1, 1);
}
.toast-enter-from {
  opacity: 0;
  transform: translateY(-24px) scale(0.92);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(48px) scale(0.95);
}
</style>
