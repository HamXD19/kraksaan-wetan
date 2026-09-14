<template>
  <div class="min-h-screen flex flex-col bg-slate-50 text-slate-800 antialiased font-sans">
    <!-- Main Sticky Navbar -->
    <Navbar />

    <!-- Page Content Container -->
    <main class="flex-1">
      <router-view v-slot="{ Component }">
        <transition name="page" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Main Footer -->
    <Footer />

    <!-- Floating Scroll-To-Top Button -->
    <transition name="pop">
      <button 
        v-show="showScrollTop"
        @click="scrollToTop"
        class="fixed bottom-6 right-6 z-40 p-3.5 rounded-full bg-emerald-700 text-white shadow-xl hover:bg-emerald-800 transition-all duration-300 transform hover:scale-110 active:scale-90 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer"
        aria-label="Kembali ke atas"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
      </button>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Navbar from '../components/Navbar.vue';
import Footer from '../components/Footer.vue';

const showScrollTop = ref(false);

const handleScroll = () => {
  showScrollTop.value = window.scrollY > 300;
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<style>
/* Smooth Modern Page Transitions */
.page-enter-active,
.page-leave-active {
  transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1), transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

.page-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}

/* Pop Spring Transition for Scroll-to-Top */
.pop-enter-active,
.pop-leave-active {
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.pop-enter-from,
.pop-leave-to {
  opacity: 0;
  transform: scale(0.6) translateY(16px);
}
</style>
