<template>
  <div class="min-h-screen bg-[#132b23] flex items-center justify-center p-3 sm:p-6 lg:p-10 relative overflow-hidden font-sans">
    <!-- Atmospheric Gunung Bromo Page Backdrop -->
    <div 
      class="absolute inset-0 bg-cover bg-center filter blur-xl brightness-[0.20] scale-110 pointer-events-none"
      style="background-image: url('/images/gunung-bromo.jpg');"
    ></div>
    <div class="absolute inset-0 bg-[#0f241d]/75 pointer-events-none"></div>

    <!-- Decorative Ambient Background Glow & Bokeh Orbs (Like Reference) -->
    <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-emerald-700/20 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-teal-600/15 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/4 right-12 w-48 h-48 rounded-full bg-emerald-500/10 blur-2xl pointer-events-none"></div>
    
    <!-- Subtle bokeh circles -->
    <div class="absolute top-16 left-20 w-16 h-16 rounded-full bg-emerald-600/15 border border-emerald-500/30 pointer-events-none hidden md:block"></div>
    <div class="absolute bottom-20 left-12 w-10 h-10 rounded-full bg-emerald-600/20 border border-emerald-400/30 pointer-events-none hidden md:block"></div>
    <div class="absolute top-32 right-32 w-12 h-12 rounded-full bg-teal-600/15 border border-teal-400/30 pointer-events-none hidden md:block"></div>

    <!-- Main Login Master Container (Reference Layout) -->
    <div class="w-full max-w-5xl rounded-3xl sm:rounded-[2.5rem] shadow-2xl shadow-slate-950/80 overflow-hidden border border-emerald-800/40 flex flex-col md:flex-row relative z-10 bg-[#17382e]">
      
      <!-- ========================================================================= -->
      <!-- LEFT SECTION: GUNUNG BROMO AS BACKGROUND (Clean & Full)                   -->
      <!-- ========================================================================= -->
      <div class="w-full md:w-[56%] relative p-6 sm:p-10 flex flex-col justify-between overflow-hidden z-20 min-h-[300px] sm:min-h-[380px] md:min-h-[580px] group">
        <!-- Full-Bleed Bromo Background Image -->
        <img 
          src="/images/gunung-bromo.jpg" 
          alt="Gunung Bromo Kabupaten Probolinggo" 
          class="absolute inset-0 w-full h-full object-cover object-center transform scale-105 group-hover:scale-110 transition-transform duration-1000"
        />
        
        <!-- Deep Gradient Overlays for High Contrast & Readability -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-black/60 pointer-events-none"></div>
        <div class="absolute inset-0 bg-emerald-950/30 mix-blend-overlay pointer-events-none"></div>

        <!-- Top Left: Logo Kabupaten Probolinggo & Kelurahan Header on Frosted Glass Badge -->
        <div class="relative z-10 flex items-center gap-3.5 bg-white/95 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl shadow-2xl border border-white/60 max-w-fit">
          <div class="w-11 h-13 sm:w-13 sm:h-15 flex-shrink-0">
            <img 
              src="/images/logo-kabupaten-probolinggo.png" 
              alt="Logo Kabupaten Probolinggo" 
              class="w-full h-full object-contain filter drop-shadow-xs"
            />
          </div>
          <div>
            <p class="text-[10px] sm:text-[11px] font-black tracking-wider text-emerald-900 uppercase leading-tight">
              Pemerintah Kabupaten Probolinggo
            </p>
            <h1 class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-wide mt-0.5">
              KELURAHAN KRAKSAAN WETAN
            </h1>
            <p class="text-[10px] text-slate-500 font-medium">
              Kecamatan Kraksaan &bull; Jawa Timur
            </p>
          </div>
        </div>

        <!-- Center: Spacious view of Mount Bromo without obstructing elements -->
        <div class="relative z-10 my-auto py-8"></div>

        <!-- Bottom Left: Copyright & Service Information -->
        <div class="relative z-10 pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px]">
          <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-black/55 backdrop-blur-md border border-white/15 text-white/90 font-medium">
            &copy; 2026 Kelurahan Kraksaan Wetan
          </span>
          <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-emerald-950/70 backdrop-blur-md border border-emerald-400/20 text-emerald-200 font-semibold">
            Sistem Administrasi & CMS Digital
          </span>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- RIGHT SECTION: DARK FOREST GREEN (Login Form & Reference Styling)        -->
      <!-- ========================================================================= -->
      <div class="w-full md:w-[44%] bg-[#17382e] p-6 sm:p-10 lg:p-12 flex flex-col justify-between relative z-10 text-white">
        
        <!-- Smooth Organic S-Curve Divider (Blends Left White Area into Right Dark Green) -->
        <svg 
          class="hidden md:block absolute top-0 -left-16 lg:-left-24 h-full w-16 lg:w-24 text-[#17382e] fill-current z-30 pointer-events-none" 
          viewBox="0 0 100 100" 
          preserveAspectRatio="none"
        >
          <path d="M 0,0 C 20,20 100,30 100,50 C 100,70 35,85 30,100 L 100,100 L 100,0 Z" />
        </svg>

        <!-- Form Header -->
        <div class="space-y-1 mb-6 sm:mb-8 pt-2">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
            Login
          </h2>
          <p class="text-xs text-emerald-200/75 leading-relaxed">
            Masuk ke panel administrator untuk mengelola layanan, berita, dan transparansi kelurahan.
          </p>
        </div>

        <!-- Error Notification -->
        <div v-if="errorMsg" class="mb-5 p-3 rounded-2xl bg-rose-500/20 border border-rose-400/30 text-rose-200 text-xs flex items-center gap-2.5 animate-in fade-in duration-200">
          <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span class="leading-tight">{{ errorMsg }}</span>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Username / Email Input -->
          <div>
            <label class="block text-xs font-semibold text-emerald-100/90 mb-1.5">Username / Email</label>
            <div class="relative">
              <input 
                type="email" 
                v-model="email" 
                required 
                autocomplete="email"
                placeholder="Enter your username or email" 
                class="w-full px-4 py-3 rounded-2xl bg-[#112921]/90 border border-emerald-700/50 text-white placeholder-emerald-400/40 text-xs sm:text-sm focus:outline-hidden focus:border-teal-400 focus:ring-2 focus:ring-teal-500/30 transition shadow-inner"
              />
              <svg class="w-4 h-4 text-emerald-400/50 absolute right-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
          </div>

          <!-- Password Input -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-semibold text-emerald-100/90">Password</label>
              <button 
                type="button" 
                @click="showForgotModal = true"
                class="text-[11px] text-emerald-300/80 hover:text-teal-200 transition underline underline-offset-2"
              >
                Forgot Password?
              </button>
            </div>
            <div class="relative">
              <input 
                :type="showPassword ? 'text' : 'password'" 
                v-model="password" 
                required 
                autocomplete="current-password"
                placeholder="Enter your password" 
                class="w-full px-4 py-3 pr-11 rounded-2xl bg-[#112921]/90 border border-emerald-700/50 text-white placeholder-emerald-400/40 text-xs sm:text-sm focus:outline-hidden focus:border-teal-400 focus:ring-2 focus:ring-teal-500/30 transition shadow-inner"
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="text-emerald-400/60 hover:text-emerald-200 absolute right-3.5 top-3.5 p-0.5 transition"
                title="Tampilkan / Sembunyikan Kata Sandi"
              >
                <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
              </button>
            </div>
          </div>

          <!-- Submit Button (Reference Teal Pill Shape) -->
          <div class="pt-2">
            <button 
              type="submit" 
              :disabled="loading"
              class="w-full py-3.5 px-6 rounded-2xl bg-[#4e9486] hover:bg-[#438376] active:bg-[#387165] disabled:opacity-50 text-white font-bold text-xs sm:text-sm transition-all duration-200 shadow-lg shadow-teal-950/40 flex items-center justify-center gap-2 cursor-pointer"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              <span>{{ loading ? 'Memproses Masuk...' : 'Login ke Dashboard' }}</span>
            </button>
          </div>
        </form>

        <!-- Form Footer: Links & Support -->
        <div class="mt-8 pt-6 border-t border-emerald-800/60 space-y-3 text-center">
          <div class="text-[11px] text-emerald-300/80">
            <span>Belum memiliki akun staf? </span>
            <button 
              type="button" 
              @click="showHelpModal = true"
              class="text-amber-300 hover:text-amber-200 font-bold underline underline-offset-2 transition ml-1 cursor-pointer"
            >
              Hubungi Admin
            </button>
          </div>

          <div class="flex items-center justify-center gap-4 text-[11px] text-emerald-400/60 pt-1">
            <router-link to="/" class="hover:text-emerald-200 transition">
              &larr; Website Publik
            </router-link>
            <span>&bull;</span>
            <button type="button" @click="showTermsModal = true" class="hover:text-emerald-200 transition">
              Terms & Services
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL 1: Forgot Password Dialog -->
    <div v-if="showForgotModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-slate-800 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mx-auto">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div class="text-center space-y-1.5">
          <h3 class="font-bold text-slate-900 text-base">Lupa Kata Sandi?</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Demi keamanan data pemerintahan kelurahan, reset kata sandi hanya dapat diproses secara langsung oleh <strong>Admin Kelurahan</strong> melalui menu Kelola Akun Staf.
          </p>
        </div>
        <div class="pt-2">
          <button 
            type="button" 
            @click="showForgotModal = false"
            class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition"
          >
            Saya Mengerti
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL 2: Hubungi Admin / Bantuan Akun -->
    <div v-if="showHelpModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-slate-800 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div class="text-center space-y-1.5">
          <h3 class="font-bold text-slate-900 text-base">Pendaftaran Akun Staf</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Akun staf dibuat khusus untuk aparatur Kelurahan Kraksaan Wetan sesuai bidang tugas (Konten & Humas, Pelayanan, Administrasi). Silakan menghubungi <strong>Administrator Kelurahan</strong> untuk pembuatan akun baru.
          </p>
        </div>
        <div class="pt-2">
          <button 
            type="button" 
            @click="showHelpModal = false"
            class="w-full py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL 3: Terms & Services -->
    <div v-if="showTermsModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 text-slate-800 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
          <h3 class="font-bold text-slate-900 text-base">Ketentuan Akses & Keamanan</h3>
          <button @click="showTermsModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-lg">&times;</button>
        </div>
        <div class="text-xs text-slate-600 space-y-2 leading-relaxed">
          <p>1. Panel administrator ini hanya diperuntukkan bagi aparatur dan staf resmi Kelurahan Kraksaan Wetan yang berwenang.</p>
          <p>2. Dilarang membagikan atau memindahtangankan kredensial akun kepada pihak ketiga.</p>
          <p>3. Seluruh aktivitas pengelolaan berita, data warga, pengajuan pelayanan, dan anggaran terekam di sistem keamanan server.</p>
        </div>
        <div class="pt-2">
          <button 
            type="button" 
            @click="showTermsModal = false"
            class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { AdminService } from '../../services/api';

const router = useRouter();
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const loading = ref(false);
const errorMsg = ref('');

const showForgotModal = ref(false);
const showHelpModal = ref(false);
const showTermsModal = ref(false);

const handleLogin = async () => {
  loading.value = true;
  errorMsg.value = '';
  try {
    const res = await AdminService.login({ 
      email: email.value, 
      password: password.value 
    });
    if (res.status === 'success') {
      router.push('/admin');
    } else {
      errorMsg.value = res.message || 'Login gagal. Periksa kembali email dan kata sandi Anda.';
    }
  } catch (err) {
    errorMsg.value = err.response?.data?.message || 'Email atau kata sandi tidak sesuai.';
  } finally {
    loading.value = false;
  }
};
</script>
