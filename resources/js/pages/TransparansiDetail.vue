<template>
  <div class="pb-20">
    <!-- Breadcrumb Nav -->
    <Breadcrumb 
      :items="[
        { label: 'Informasi Publik', to: '/informasi-publik' },
        { label: 'Transparansi Anggaran', to: '/transparansi' },
        { label: header.judul ? `Tahun ${header.tahun}` : 'Detail Anggaran' }
      ]" 
    />

    <LoadingSpinner v-if="loading" />

    <div v-else-if="error" class="max-w-4xl mx-auto px-4 sm:px-6 mt-12">
      <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-slate-200 shadow-xs space-y-4">
        <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-2xl font-bold">
          !
        </div>
        <h2 class="text-xl font-bold text-slate-900">{{ error }}</h2>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
          Dokumen anggaran yang Anda cari mungkin belum dipublikasikan atau URL tautan telah diperbarui.
        </p>
        <router-link 
          to="/transparansi" 
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          <span>Kembali ke Arsip Transparansi</span>
        </router-link>
      </div>
    </div>

    <!-- Main Content Container -->
    <div v-else class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-10">
      <!-- 1. Header Detail APBD -->
      <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xs space-y-6">
        <div class="space-y-3">
          <div class="flex flex-wrap items-center gap-2.5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-[#2073B7] border border-blue-200 text-xs font-bold uppercase tracking-wider">
              <span class="w-2 h-2 rounded-full bg-[#2073B7]"></span>
              Tahun Anggaran {{ header.tahun }}
            </span>
            <span 
              v-if="header.status" 
              class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
              :class="header.status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
            >
              {{ header.status === 'published' ? 'Publikasi Resmi' : 'Draft Dokumen' }}
            </span>
            <div class="flex items-center gap-1 text-xs text-slate-500 font-medium">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              <span>Rilis: {{ formatTanggal(header.tanggal_publikasi) }}</span>
            </div>
          </div>

          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
            {{ header.judul }}
          </h1>
        </div>

        <!-- Narasi Penjelasan Umum APBD -->
        <div v-if="header.deskripsi" class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
          {{ header.deskripsi }}
        </div>

        <!-- 4 Quick KPI Highlight Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
          <!-- Total Pendapatan -->
          <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 space-y-1">
            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">Total Pendapatan</span>
            <p class="text-lg font-black text-blue-900">{{ formatRupiah(sections.pendapatan?.total_realisasi || header.total_pendapatan_realisasi) }}</p>
            <p class="text-[10px] text-blue-600 font-medium">
              Target: {{ formatRupiah(sections.pendapatan?.total_anggaran || header.total_pendapatan_rencana) }}
            </p>
          </div>

          <!-- Total Belanja -->
          <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 space-y-1">
            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Total Belanja</span>
            <p class="text-lg font-black text-rose-900">{{ formatRupiah(sections.belanja?.total_realisasi || header.total_belanja_realisasi) }}</p>
            <p class="text-[10px] text-rose-600 font-medium">
              Alokasi: {{ formatRupiah(sections.belanja?.total_anggaran || header.total_belanja_rencana) }}
            </p>
          </div>

          <!-- Surplus / Defisit -->
          <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100 space-y-1">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Surplus / (Defisit)</span>
            <p 
              class="text-lg font-black"
              :class="(sections.belanja?.surplus_defisit_realisasi ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
            >
              {{ formatRupiah(sections.belanja?.surplus_defisit_realisasi ?? (header.total_pendapatan_realisasi - header.total_belanja_realisasi)) }}
            </p>
            <p class="text-[10px] text-slate-500 font-medium">Pendapatan - Belanja</p>
          </div>

          <!-- Pembiayaan Netto -->
          <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 space-y-1">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Pembiayaan Netto</span>
            <p class="text-lg font-black text-emerald-900">{{ formatRupiah(sections.pembiayaan?.netto?.realisasi || header.pembiayaan_netto_realisasi) }}</p>
            <p class="text-[10px] text-emerald-600 font-medium">Penerimaan - Pengeluaran</p>
          </div>
        </div>
      </div>

      <!-- 2. Tabel Rincian Anggaran (3 Section: Solid Blue Header #2073B7) -->

      <!-- TABEL 1: PENDAPATAN -->
      <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 bg-[#2073B7] text-white flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center font-bold text-xs">1</span>
            <h2 class="text-base sm:text-lg font-bold tracking-tight">PENDAPATAN KELURAHAN</h2>
          </div>
          <span class="text-xs font-semibold text-white/80 hidden sm:inline-block">Tahun Anggaran {{ header.tahun }}</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-slate-100/90 text-slate-700 font-bold border-b border-slate-200">
                <th class="py-3 px-4 sm:px-6 w-2/5">Uraian / Akun</th>
                <th class="py-3 px-4 text-right">Rencana / Anggaran (Rp)</th>
                <th class="py-3 px-4 text-right">Realisasi (Rp)</th>
                <th class="py-3 px-4 text-right">Lebih / (Kurang) (Rp)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <template v-for="grp in (sections.pendapatan?.kelompok || [])" :key="grp.kategori">
                <!-- Sub-Kategori Header Row -->
                <tr class="bg-slate-50 font-bold text-slate-800">
                  <td colspan="4" class="py-2.5 px-4 sm:px-6 tracking-wide text-[11px] text-blue-900 uppercase">
                    {{ grp.kategori }}
                  </td>
                </tr>

                <!-- Item Rows -->
                <tr v-for="item in grp.items" :key="item.id" class="hover:bg-slate-50/70 transition">
                  <td class="py-2.5 px-4 sm:px-6 pl-6 sm:pl-8 text-slate-700">
                    {{ item.uraian }}
                  </td>
                  <td class="py-2.5 px-4 text-right text-slate-700 font-medium">
                    {{ formatRupiah(item.anggaran) }}
                  </td>
                  <td class="py-2.5 px-4 text-right text-slate-900 font-semibold">
                    {{ formatRupiah(item.realisasi) }}
                  </td>
                  <td 
                    class="py-2.5 px-4 text-right font-semibold"
                    :class="item.selisih >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                  >
                    {{ formatSelisih(item.selisih) }}
                  </td>
                </tr>

                <!-- Sub-Total Sub-Kategori -->
                <tr class="bg-slate-50/50 font-semibold text-slate-700 border-t border-slate-200/60">
                  <td class="py-2 px-4 sm:px-6 text-[11px] italic text-slate-600">
                    Jumlah {{ grp.kategori }}
                  </td>
                  <td class="py-2 px-4 text-right text-[11px]">
                    {{ formatRupiah(grp.subtotal_anggaran) }}
                  </td>
                  <td class="py-2 px-4 text-right text-[11px] text-slate-900 font-bold">
                    {{ formatRupiah(grp.subtotal_realisasi) }}
                  </td>
                  <td 
                    class="py-2 px-4 text-right text-[11px] font-bold"
                    :class="grp.subtotal_selisih >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                  >
                    {{ formatSelisih(grp.subtotal_selisih) }}
                  </td>
                </tr>
              </template>

              <!-- Grand Total Pendapatan -->
              <tr class="bg-blue-50 font-black text-slate-900 border-t-2 border-blue-200">
                <td class="py-3.5 px-4 sm:px-6 text-xs sm:text-sm uppercase tracking-wide text-blue-950">
                  TOTAL PENDAPATAN
                </td>
                <td class="py-3.5 px-4 text-right text-xs sm:text-sm text-slate-800">
                  {{ formatRupiah(sections.pendapatan?.total_anggaran) }}
                </td>
                <td class="py-3.5 px-4 text-right text-xs sm:text-sm text-blue-950">
                  {{ formatRupiah(sections.pendapatan?.total_realisasi) }}
                </td>
                <td 
                  class="py-3.5 px-4 text-right text-xs sm:text-sm"
                  :class="(sections.pendapatan?.total_selisih ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(sections.pendapatan?.total_selisih) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TABEL 2: BELANJA -->
      <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 bg-[#2073B7] text-white flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center font-bold text-xs">2</span>
            <h2 class="text-base sm:text-lg font-bold tracking-tight">BELANJA KELURAHAN</h2>
          </div>
          <span class="text-xs font-semibold text-white/80 hidden sm:inline-block">Tahun Anggaran {{ header.tahun }}</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-slate-100/90 text-slate-700 font-bold border-b border-slate-200">
                <th class="py-3 px-4 sm:px-6 w-2/5">Uraian / Akun</th>
                <th class="py-3 px-4 text-right">Rencana / Anggaran (Rp)</th>
                <th class="py-3 px-4 text-right">Realisasi (Rp)</th>
                <th class="py-3 px-4 text-right">Lebih / (Kurang) (Rp)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <template v-for="grp in (sections.belanja?.kelompok || [])" :key="grp.kategori">
                <!-- Sub-Kategori Header Row -->
                <tr class="bg-slate-50 font-bold text-slate-800">
                  <td colspan="4" class="py-2.5 px-4 sm:px-6 tracking-wide text-[11px] text-blue-900 uppercase">
                    {{ grp.kategori }}
                  </td>
                </tr>

                <!-- Item Rows -->
                <tr v-for="item in grp.items" :key="item.id" class="hover:bg-slate-50/70 transition">
                  <td class="py-2.5 px-4 sm:px-6 pl-6 sm:pl-8 text-slate-700">
                    {{ item.uraian }}
                  </td>
                  <td class="py-2.5 px-4 text-right text-slate-700 font-medium">
                    {{ formatRupiah(item.anggaran) }}
                  </td>
                  <td class="py-2.5 px-4 text-right text-slate-900 font-semibold">
                    {{ formatRupiah(item.realisasi) }}
                  </td>
                  <td 
                    class="py-2.5 px-4 text-right font-semibold"
                    :class="item.selisih >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                  >
                    {{ formatSelisih(item.selisih) }}
                  </td>
                </tr>

                <!-- Sub-Total Sub-Kategori -->
                <tr class="bg-slate-50/50 font-semibold text-slate-700 border-t border-slate-200/60">
                  <td class="py-2 px-4 sm:px-6 text-[11px] italic text-slate-600">
                    Jumlah {{ grp.kategori }}
                  </td>
                  <td class="py-2 px-4 text-right text-[11px]">
                    {{ formatRupiah(grp.subtotal_anggaran) }}
                  </td>
                  <td class="py-2 px-4 text-right text-[11px] text-slate-900 font-bold">
                    {{ formatRupiah(grp.subtotal_realisasi) }}
                  </td>
                  <td 
                    class="py-2 px-4 text-right text-[11px] font-bold"
                    :class="grp.subtotal_selisih >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                  >
                    {{ formatSelisih(grp.subtotal_selisih) }}
                  </td>
                </tr>
              </template>

              <!-- Grand Total Belanja -->
              <tr class="bg-rose-50 font-black text-slate-900 border-t-2 border-rose-200">
                <td class="py-3.5 px-4 sm:px-6 text-xs sm:text-sm uppercase tracking-wide text-rose-950">
                  TOTAL BELANJA
                </td>
                <td class="py-3.5 px-4 text-right text-xs sm:text-sm text-slate-800">
                  {{ formatRupiah(sections.belanja?.total_anggaran) }}
                </td>
                <td class="py-3.5 px-4 text-right text-xs sm:text-sm text-rose-950">
                  {{ formatRupiah(sections.belanja?.total_realisasi) }}
                </td>
                <td 
                  class="py-3.5 px-4 text-right text-xs sm:text-sm"
                  :class="(sections.belanja?.total_selisih ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(sections.belanja?.total_selisih) }}
                </td>
              </tr>

              <!-- SURPLUS / DEFISIT ROW -->
              <tr class="bg-amber-50 font-black text-slate-900 border-t-2 border-amber-300">
                <td class="py-3.5 px-4 sm:px-6 text-xs sm:text-sm uppercase tracking-wide text-amber-950">
                  SURPLUS / (DEFISIT)
                </td>
                <td 
                  class="py-3.5 px-4 text-right text-xs sm:text-sm"
                  :class="(sections.belanja?.surplus_defisit_anggaran ?? 0) >= 0 ? 'text-emerald-800' : 'text-rose-800'"
                >
                  {{ formatRupiah(sections.belanja?.surplus_defisit_anggaran) }}
                </td>
                <td 
                  class="py-3.5 px-4 text-right text-xs sm:text-sm"
                  :class="(sections.belanja?.surplus_defisit_realisasi ?? 0) >= 0 ? 'text-emerald-800' : 'text-rose-800'"
                >
                  {{ formatRupiah(sections.belanja?.surplus_defisit_realisasi) }}
                </td>
                <td 
                  class="py-3.5 px-4 text-right text-xs sm:text-sm"
                  :class="(sections.belanja?.surplus_defisit_selisih ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(sections.belanja?.surplus_defisit_selisih) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TABEL 3: PEMBIAYAAN -->
      <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 bg-[#2073B7] text-white flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center font-bold text-xs">3</span>
            <h2 class="text-base sm:text-lg font-bold tracking-tight">PEMBIAYAAN KELURAHAN</h2>
          </div>
          <span class="text-xs font-semibold text-white/80 hidden sm:inline-block">Tahun Anggaran {{ header.tahun }}</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-slate-100/90 text-slate-700 font-bold border-b border-slate-200">
                <th class="py-3 px-4 sm:px-6 w-2/5">Uraian / Akun</th>
                <th class="py-3 px-4 text-right">Rencana / Anggaran (Rp)</th>
                <th class="py-3 px-4 text-right">Realisasi (Rp)</th>
                <th class="py-3 px-4 text-right">Lebih / (Kurang) (Rp)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <!-- 3.1 Penerimaan Pembiayaan -->
              <tr class="bg-slate-50 font-bold text-slate-800">
                <td colspan="4" class="py-2.5 px-4 sm:px-6 tracking-wide text-[11px] text-blue-900 uppercase">
                  Penerimaan Pembiayaan
                </td>
              </tr>
              <tr v-for="item in (sections.pembiayaan?.penerimaan?.items || [])" :key="item.id" class="hover:bg-slate-50/70 transition">
                <td class="py-2.5 px-4 sm:px-6 pl-6 sm:pl-8 text-slate-700">
                  {{ item.uraian }}
                </td>
                <td class="py-2.5 px-4 text-right text-slate-700 font-medium">
                  {{ formatRupiah(item.anggaran) }}
                </td>
                <td class="py-2.5 px-4 text-right text-slate-900 font-semibold">
                  {{ formatRupiah(item.realisasi) }}
                </td>
                <td 
                  class="py-2.5 px-4 text-right font-semibold"
                  :class="item.selisih >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(item.selisih) }}
                </td>
              </tr>
              <tr v-if="!sections.pembiayaan?.penerimaan?.items?.length">
                <td colspan="4" class="py-2.5 px-4 sm:px-6 pl-8 text-slate-400 italic text-[11px]">
                  Tidak ada pos penerimaan pembiayaan
                </td>
              </tr>
              <tr class="bg-slate-50/50 font-semibold text-slate-700">
                <td class="py-2 px-4 sm:px-6 text-[11px] italic text-slate-600">
                  Jumlah Penerimaan Pembiayaan
                </td>
                <td class="py-2 px-4 text-right text-[11px]">
                  {{ formatRupiah(sections.pembiayaan?.penerimaan?.total_anggaran) }}
                </td>
                <td class="py-2 px-4 text-right text-[11px] text-slate-900 font-bold">
                  {{ formatRupiah(sections.pembiayaan?.penerimaan?.total_realisasi) }}
                </td>
                <td 
                  class="py-2 px-4 text-right text-[11px] font-bold"
                  :class="(sections.pembiayaan?.penerimaan?.total_selisih ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(sections.pembiayaan?.penerimaan?.total_selisih) }}
                </td>
              </tr>

              <!-- 3.2 Pengeluaran Pembiayaan -->
              <tr class="bg-slate-50 font-bold text-slate-800">
                <td colspan="4" class="py-2.5 px-4 sm:px-6 tracking-wide text-[11px] text-blue-900 uppercase">
                  Pengeluaran Pembiayaan
                </td>
              </tr>
              <tr v-for="item in (sections.pembiayaan?.pengeluaran?.items || [])" :key="item.id" class="hover:bg-slate-50/70 transition">
                <td class="py-2.5 px-4 sm:px-6 pl-6 sm:pl-8 text-slate-700">
                  {{ item.uraian }}
                </td>
                <td class="py-2.5 px-4 text-right text-slate-700 font-medium">
                  {{ formatRupiah(item.anggaran) }}
                </td>
                <td class="py-2.5 px-4 text-right text-slate-900 font-semibold">
                  {{ formatRupiah(item.realisasi) }}
                </td>
                <td 
                  class="py-2.5 px-4 text-right font-semibold"
                  :class="item.selisih >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(item.selisih) }}
                </td>
              </tr>
              <tr v-if="!sections.pembiayaan?.pengeluaran?.items?.length">
                <td colspan="4" class="py-2.5 px-4 sm:px-6 pl-8 text-slate-400 italic text-[11px]">
                  Tidak ada pos pengeluaran pembiayaan
                </td>
              </tr>
              <tr class="bg-slate-50/50 font-semibold text-slate-700">
                <td class="py-2 px-4 sm:px-6 text-[11px] italic text-slate-600">
                  Jumlah Pengeluaran Pembiayaan
                </td>
                <td class="py-2 px-4 text-right text-[11px]">
                  {{ formatRupiah(sections.pembiayaan?.pengeluaran?.total_anggaran) }}
                </td>
                <td class="py-2 px-4 text-right text-[11px] text-slate-900 font-bold">
                  {{ formatRupiah(sections.pembiayaan?.pengeluaran?.total_realisasi) }}
                </td>
                <td 
                  class="py-2 px-4 text-right text-[11px] font-bold"
                  :class="(sections.pembiayaan?.pengeluaran?.total_selisih ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(sections.pembiayaan?.pengeluaran?.total_selisih) }}
                </td>
              </tr>

              <!-- PEMBIAYAAN NETTO -->
              <tr class="bg-emerald-50 font-black text-slate-900 border-t-2 border-emerald-300">
                <td class="py-3 px-4 sm:px-6 text-xs uppercase tracking-wide text-emerald-950">
                  PEMBIAYAAN NETTO
                </td>
                <td class="py-3 px-4 text-right text-xs">
                  {{ formatRupiah(sections.pembiayaan?.netto?.anggaran) }}
                </td>
                <td class="py-3 px-4 text-right text-xs text-emerald-950">
                  {{ formatRupiah(sections.pembiayaan?.netto?.realisasi) }}
                </td>
                <td 
                  class="py-3 px-4 text-right text-xs font-bold"
                  :class="(sections.pembiayaan?.netto?.selisih ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'"
                >
                  {{ formatSelisih(sections.pembiayaan?.netto?.selisih) }}
                </td>
              </tr>

              <!-- SILPA TAHUN BERKENAAN -->
              <tr class="bg-slate-900 text-white font-black">
                <td class="py-3.5 px-4 sm:px-6 text-xs sm:text-sm uppercase tracking-wider text-amber-300">
                  SISA LEBIH PEMBIAYAAN ANGGARAN (SILPA) TAHUN BERKENAAN
                </td>
                <td class="py-3.5 px-4 text-right text-xs sm:text-sm text-slate-200">
                  {{ formatRupiah(sections.pembiayaan?.silpa?.anggaran) }}
                </td>
                <td class="py-3.5 px-4 text-right text-xs sm:text-sm text-amber-300">
                  {{ formatRupiah(sections.pembiayaan?.silpa?.realisasi) }}
                </td>
                <td class="py-3.5 px-4 text-right text-xs sm:text-sm text-slate-200">
                  {{ formatSelisih(sections.pembiayaan?.silpa?.selisih) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 3. Download File Section Resmi -->
      <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-700">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl bg-rose-600/90 text-white flex items-center justify-center shrink-0 shadow-md">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
          </div>
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 font-bold text-[10px] uppercase tracking-wider">PDF Resmi</span>
              <span class="text-xs text-slate-400">Arsip Monografi APBD</span>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-white leading-snug">
              Dokumen Monografi & Lampiran APBD Tahun {{ header.tahun }}
            </h3>
            <p class="text-xs text-slate-300">
              Unduh salinan berkas resmi format PDF berstempel untuk verifikasi, penelitian, atau arsip warga.
            </p>
          </div>
        </div>

        <a 
          :href="downloadUrl" 
          target="_blank" 
          download
          class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs shadow-lg transition whitespace-nowrap self-stretch sm:self-auto cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          <span>Download Monografi / Dokumen APBD</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const route = useRoute();
const loading = ref(true);
const error = ref('');

const header = ref({});
const sections = ref({
  pendapatan: { kelompok: [], total_anggaran: 0, total_realisasi: 0, total_selisih: 0 },
  belanja: { kelompok: [], total_anggaran: 0, total_realisasi: 0, total_selisih: 0, surplus_defisit_anggaran: 0, surplus_defisit_realisasi: 0, surplus_defisit_selisih: 0 },
  pembiayaan: {
    penerimaan: { items: [], total_anggaran: 0, total_realisasi: 0, total_selisih: 0 },
    pengeluaran: { items: [], total_anggaran: 0, total_realisasi: 0, total_selisih: 0 },
    netto: { anggaran: 0, realisasi: 0, selisih: 0 },
    silpa: { anggaran: 0, realisasi: 0, selisih: 0 }
  }
});
const downloadUrl = ref('');

const formatRupiah = (val) => {
  const num = Number(val) || 0;
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(num);
};

const formatSelisih = (val) => {
  const num = Number(val) || 0;
  if (num === 0) return 'Rp 0';
  const prefix = num > 0 ? '+ ' : '- ';
  return prefix + formatRupiah(Math.abs(num));
};

const formatTanggal = (dateStr) => {
  if (!dateStr) return '-';
  try {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    }).format(d);
  } catch {
    return dateStr;
  }
};

const loadDetail = async () => {
  loading.value = true;
  error.value = '';
  try {
    const slug = route.params.slug;
    const res = await KelurahanService.getTransparansiDetail(slug);
    if (!res || !res.header) {
      error.value = 'Dokumen anggaran tidak ditemukan.';
      return;
    }
    header.value = res.header || {};
    sections.value = res.sections || sections.value;
    downloadUrl.value = res.unduh_url || KelurahanService.getTransparansiDownloadUrl(slug);
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal memuat rincian anggaran APBD.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadDetail();
});
</script>
