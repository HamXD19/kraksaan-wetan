<template>
  <div class="min-h-screen flex flex-col bg-slate-50 text-slate-800 antialiased font-sans">
    <!-- Main Sticky Navbar -->
    <Navbar />

    <!-- Page Content Container -->
    <main class="flex-1">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Main Footer -->
    <Footer />

    <!-- Floating Scroll-To-Top Button -->
    <button 
      v-show="showScrollTop"
      @click="scrollToTop"
      class="fixed bottom-6 right-6 z-40 p-3 rounded-full bg-emerald-700 text-white shadow-xl hover:bg-emerald-800 transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-emerald-500"
      aria-label="Kembali ke atas"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
    </button>
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
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
