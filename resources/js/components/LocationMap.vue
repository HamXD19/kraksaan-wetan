<template>
  <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-lg">
    <div class="grid grid-cols-1 lg:grid-cols-12">
      <!-- Info Box Left -->
      <div class="lg:col-span-5 p-8 lg:p-10 flex flex-col justify-between bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950 text-white">
        <div class="space-y-6">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 border border-emerald-600/50 text-emerald-200 text-xs font-semibold">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            PUSAT KOTA KRAKSAAN
          </div>

          <div>
            <h3 class="text-2xl font-bold tracking-tight text-white mb-2">Kantor Kelurahan Kraksaan Wetan</h3>
            <p class="text-xs sm:text-sm text-emerald-200/90 leading-relaxed">
              Berlokasi strategis di kawasan perkantoran dan pusat niaga Kraksaan, mudah dijangkau dengan kendaraan pribadi maupun angkutan umum.
            </p>
          </div>

          <div class="space-y-4 pt-2 text-xs">
            <div class="flex items-start gap-3">
              <div class="p-2 rounded-lg bg-emerald-800/80 text-amber-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <div>
                <p class="font-bold text-white text-sm">Alamat Kantor</p>
                <p class="text-emerald-100/80 leading-relaxed">Jl. Dr. Soetomo No. 12, Kraksaan Wetan, Kec. Kraksaan, Kab. Probolinggo, Jawa Timur 67282</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="p-2 rounded-lg bg-emerald-800/80 text-amber-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <p class="font-bold text-white text-sm">Jam Pelayanan Tatap Muka</p>
                <p class="text-emerald-100/80">Senin - Kamis: 08.00 - 15.30 WIB<br>Jumat: 08.00 - 14.30 WIB</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="p-2 rounded-lg bg-emerald-800/80 text-amber-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              </div>
              <div>
                <p class="font-bold text-white text-sm">Layanan Telepon</p>
                <p class="text-emerald-100/80">(0335) 841234</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="p-2 rounded-lg bg-emerald-800/80 text-amber-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
              </div>
              <div>
                <p class="font-bold text-white text-sm">Titik Koordinat GPS</p>
                <p class="text-emerald-100/90 text-[11px] font-medium tracking-wide select-all">-7.757241, 113.426526</p>
              </div>
            </div>
          </div>
        </div>

        <div class="pt-6 flex flex-col sm:flex-row gap-2.5">
          <a 
            href="https://www.google.com/maps/dir/?api=1&destination=-7.757240954829376,113.42652618748562" 
            target="_blank" 
            rel="noopener noreferrer"
            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs sm:text-sm shadow-md transition transform hover:-translate-y-0.5"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Petunjuk Arah Google Maps
          </a>
          <a 
            href="https://maps.google.com/?q=-7.757240954829376,113.42652618748562" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center gap-1.5 px-4 py-3.5 rounded-xl border border-emerald-600/70 hover:bg-emerald-800/60 text-emerald-100 font-bold text-xs sm:text-sm transition"
            title="Buka di Aplikasi Google Maps"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            <span>Buka Peta</span>
          </a>
        </div>
      </div>

      <!-- Map Embed Frame Right -->
      <div class="lg:col-span-7 min-h-[380px] sm:min-h-[460px] relative bg-slate-100 group">
        <iframe 
          title="Peta Lokasi Kantor Kelurahan Kraksaan Wetan"
          src="https://maps.google.com/maps?q=-7.757240954829376,113.42652618748562&hl=id&z=17&output=embed" 
          class="w-full h-full border-0 absolute inset-0" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>

        <!-- Floating Marker Overlay Badge -->
        <div class="absolute top-4 left-4 z-10 bg-white/95 backdrop-blur-xs p-3 rounded-2xl border border-slate-200 shadow-md flex items-center gap-3 pointer-events-none">
          <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center shadow-xs flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div>
            <div class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
              <p class="text-xs font-bold text-slate-900 leading-none">Kantor Kelurahan Kraksaan Wetan</p>
            </div>
            <p class="text-[10px] text-slate-600 font-medium mt-1">Koordinat: -7.757241, 113.426526</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
