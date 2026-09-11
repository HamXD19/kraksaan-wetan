<template>
  <div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
          Good Governance & Akuntabilitas
        </div>
        <h2 class="text-xl font-bold text-slate-900">Manajemen Transparansi Anggaran & Kegiatan</h2>
        <p class="text-xs text-slate-500 mt-0.5">Kelola program pembangunan, rencana vs realisasi serapan anggaran, capaian fisik, serta penerima manfaat.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition self-start sm:self-auto"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Kegiatan Baru</span>
      </button>
    </div>

    <!-- Alert Sukses / Info -->
    <div v-if="successMsg" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ successMsg }}</span>
      </div>
      <button @click="successMsg = ''" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
    </div>

    <!-- Real-time Budget Tracking KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Total Pagu Rencana -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pagu Rencana</p>
          <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[10px] font-bold text-slate-600">Tahun {{ filterTahun }}</span>
        </div>
        <p class="text-xl sm:text-2xl font-black text-slate-900 mt-2">
          {{ formatRupiah(summary.total_rencana) }}
        </p>
        <p class="text-[11px] text-slate-500 mt-1">Total alokasi anggaran kegiatan</p>
      </div>

      <!-- Realisasi Anggaran Terserap -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Realisasi Terserap</p>
          <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-[10px] font-bold text-emerald-800">Tersalurkan</span>
        </div>
        <p class="text-xl sm:text-2xl font-black text-emerald-700 mt-2">
          {{ formatRupiah(summary.total_realisasi) }}
        </p>
        <div class="w-full bg-slate-100 h-1.5 rounded-full mt-2 overflow-hidden">
          <div 
            class="bg-emerald-600 h-full rounded-full transition-all duration-500" 
            :style="{ width: Math.min(summary.persentase_total || 0, 100) + '%' }"
          ></div>
        </div>
        <p class="text-[11px] text-emerald-600 font-semibold mt-1">
          {{ summary.persentase_total }}% dari total pagu
        </p>
      </div>

      <!-- Sisa Anggaran -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Sisa Pagu</p>
          <span class="px-2 py-0.5 rounded-md bg-amber-100 text-[10px] font-bold text-amber-800">Saldo</span>
        </div>
        <p class="text-xl sm:text-2xl font-black text-amber-700 mt-2">
          {{ formatRupiah(summary.total_sisa) }}
        </p>
        <p class="text-[11px] text-slate-500 mt-1">Dana belum terserap / termin lanjutan</p>
      </div>

      <!-- Capaian Kegiatan -->
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Kegiatan</p>
          <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[10px] font-bold text-slate-600">{{ summary.total_kegiatan }} Kegiatan</span>
        </div>
        <div class="flex items-baseline gap-2 mt-2">
          <p class="text-2xl font-black text-slate-900">{{ summary.total_kegiatan }}</p>
          <span class="text-xs text-slate-500">Program Terdata</span>
        </div>
        <p class="text-[11px] text-slate-500 mt-1">Real-time update pelaporan warga</p>
      </div>
    </div>

    <!-- Loading Spinner -->
    <LoadingSpinner v-if="loading" />

    <!-- Data Table Container -->
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <!-- Toolbar Filters -->
      <div class="p-4 border-b border-slate-100 flex flex-col lg:flex-row items-center justify-between gap-3 bg-slate-50/50">
        <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
          <!-- Filter Tahun -->
          <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-slate-200 text-xs shadow-xs">
            <span class="font-bold text-slate-500">Tahun:</span>
            <select 
              v-model="filterTahun" 
              @change="loadData"
              class="font-bold text-slate-800 outline-none bg-transparent cursor-pointer"
            >
              <option value="Semua">Semua Tahun</option>
              <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>

          <!-- Filter Kategori -->
          <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-slate-200 text-xs shadow-xs">
            <span class="font-bold text-slate-500">Kategori:</span>
            <select 
              v-model="filterKategori" 
              @change="loadData"
              class="font-bold text-slate-800 outline-none bg-transparent cursor-pointer"
            >
              <option value="Semua">Semua Kategori</option>
              <option v-for="k in kategoriOptions" :key="k.id" :value="k.nama">{{ k.nama }}</option>
            </select>
          </div>
        </div>

        <!-- Pencarian -->
        <div class="relative w-full lg:w-72">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Cari kegiatan, program, atau lokasi..." 
            class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-600 outline-none bg-white shadow-xs"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <!-- Tabel Kegiatan & Anggaran -->
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
            <tr>
              <th class="py-3.5 px-4">Program & Kegiatan</th>
              <th class="py-3.5 px-4">Kategori & Sumber</th>
              <th class="py-3.5 px-4">Rencana vs Realisasi</th>
              <th class="py-3.5 px-4">Penerima Manfaat</th>
              <th class="py-3.5 px-4 text-center">Fisik & Status</th>
              <th class="py-3.5 px-4 text-center">Publik</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-slate-50/80 transition">
              <!-- Program & Kegiatan -->
              <td class="py-3.5 px-4 max-w-xs sm:max-w-sm">
                <div class="space-y-1">
                  <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-slate-100 font-bold text-[10px] text-slate-600">
                      {{ item.tahun }}
                    </span>
                    <span class="text-[10px] font-semibold text-slate-400 truncate">
                      {{ item.program }}
                    </span>
                  </div>
                  <p class="font-bold text-slate-900 text-xs sm:text-sm leading-snug">
                    {{ item.kegiatan }}
                  </p>
                  <p v-if="item.lokasi" class="text-[11px] text-slate-500 flex items-center gap-1">
                    <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ item.lokasi }}</span>
                  </p>
                </div>
              </td>

              <!-- Kategori & Sumber Dana -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span 
                  class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold mb-1"
                  :class="getKategoriBadge(item.kategori)"
                >
                  {{ item.kategori }}
                </span>
                <p class="text-[11px] font-semibold text-slate-700">{{ item.sumber_dana }}</p>
                <p v-if="item.penanggung_jawab" class="text-[10px] text-slate-400">PJ: {{ item.penanggung_jawab }}</p>
              </td>

              <!-- Rencana vs Realisasi Anggaran -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                <div class="space-y-1">
                  <div class="flex items-center justify-between gap-3">
                    <span class="text-[10px] text-slate-400">Pagu:</span>
                    <span class="font-bold text-slate-800">{{ formatRupiah(item.anggaran_rencana) }}</span>
                  </div>
                  <div class="flex items-center justify-between gap-3">
                    <span class="text-[10px] text-emerald-600 font-semibold">Realisasi:</span>
                    <span class="font-bold text-emerald-700">{{ formatRupiah(item.anggaran_realisasi) }}</span>
                  </div>
                  <!-- Mini Progress Bar -->
                  <div class="w-36 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div 
                      class="bg-emerald-600 h-full rounded-full" 
                      :style="{ width: Math.min(item.persentase_realisasi || 0, 100) + '%' }"
                    ></div>
                  </div>
                  <div class="flex items-center justify-between text-[10px]">
                    <span class="font-semibold text-slate-500">{{ item.persentase_realisasi }}%</span>
                    <span class="text-slate-400">Sisa: {{ formatRupiah(item.sisa_anggaran) }}</span>
                  </div>
                </div>
              </td>

              <!-- Penerima Manfaat -->
              <td class="py-3.5 px-4 max-w-xs">
                <div class="space-y-1">
                  <p v-if="item.penerima_manfaat_target" class="text-[11px] font-semibold text-slate-800">
                    🎯 {{ item.penerima_manfaat_target }}
                  </p>
                  <p v-if="item.penerima_manfaat_realisasi" class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md inline-block">
                    ✓ {{ item.penerima_manfaat_realisasi }}
                  </p>
                  <p v-if="!item.penerima_manfaat_target && !item.penerima_manfaat_realisasi" class="text-[10px] text-slate-400 italic">
                    Belum dispesifikasi
                  </p>
                </div>
              </td>

              <!-- Progres Fisik & Status -->
              <td class="py-3.5 px-4 text-center whitespace-nowrap">
                <div class="flex flex-col items-center gap-1">
                  <span 
                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                    :class="getStatusBadge(item.status)"
                  >
                    {{ item.status }}
                  </span>
                  <span class="text-[11px] font-bold text-slate-700">
                    Fisik: {{ item.progres_fisik }}%
                  </span>
                </div>
              </td>

              <!-- Toggle Aktif Publik -->
              <td class="py-3.5 px-4 text-center whitespace-nowrap">
                <button 
                  @click="toggleAktif(item)" 
                  class="px-2 py-1 rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-xs"
                  :class="item.aktif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                  :title="item.aktif ? 'Tampil di portal transparansi publik. Klik untuk sembunyikan.' : 'Disembunyikan. Klik untuk tampilkan.'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="item.aktif ? 'bg-emerald-600' : 'bg-slate-400'"></span>
                  <span>{{ item.aktif ? 'Live' : 'Draft' }}</span>
                </button>
              </td>

              <!-- Aksi -->
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <button 
                  @click="openModal(item)" 
                  class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5 transition"
                >
                  Edit
                </button>
                <button 
                  @click="deleteItem(item)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold transition"
                >
                  Hapus
                </button>
              </td>
            </tr>

            <tr v-if="!filteredItems.length">
              <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                Tidak ada data kegiatan anggaran yang sesuai dengan filter pencarian.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form Tambah / Edit Kegiatan Anggaran -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
      @click.self="showModal = false"
    >
      <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
          <div>
            <h3 class="text-lg font-bold text-slate-900">
              {{ editId ? 'Sunting Program & Kegiatan Anggaran' : 'Tambah Program & Kegiatan Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Isi data pagu rencana, realisasi anggaran, target penerima manfaat, dan capaian fisik.</p>
          </div>
          <button @click="showModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <!-- Baris 1: Tahun Anggaran & Kategori -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Tahun Anggaran *</label>
              <input 
                type="number" 
                v-model.number="form.tahun" 
                required 
                min="2020" 
                max="2035"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-mono font-bold focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <div class="sm:col-span-2">
              <label class="block font-bold text-slate-700 mb-1">Kategori Program *</label>
              <select 
                v-model="form.kategori" 
                required 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option v-for="k in kategoriOptions" :key="k.id" :value="k.nama">
                  {{ k.nama }}
                </option>
                <option v-if="form.kategori && !kategoriOptions.some(k => k.nama === form.kategori)" :value="form.kategori">
                  {{ form.kategori }}
                </option>
              </select>
            </div>
          </div>

          <!-- Baris 2: Program Induk -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Nama Program Induk *</label>
            <input 
              type="text" 
              v-model="form.program" 
              required 
              placeholder="Contoh: Program Peningkatan Sarana dan Prasarana Lingkungan Perkotaan" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            />
          </div>

          <!-- Baris 3: Nama Kegiatan -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Nama Kegiatan Spesifik *</label>
            <input 
              type="text" 
              v-model="form.kegiatan" 
              required 
              placeholder="Contoh: Pembangunan & Normalisasi Saluran Drainase U-Ditch RW 02" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            />
          </div>

          <!-- Baris 4: Sumber Dana & Lokasi -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Sumber Dana *</label>
              <input 
                type="text" 
                v-model="form.sumber_dana" 
                required 
                placeholder="Alokasi Dana Kelurahan (ADK) / APBD / BKK" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Lokasi Pelaksanaan</label>
              <input 
                type="text" 
                v-model="form.lokasi" 
                placeholder="Contoh: Jl. Pattimura s.d. Gang Patemon RW 02" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <!-- Baris 5: Rencana vs Realisasi Anggaran (Budget Tracking) -->
          <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 space-y-3">
            <div class="flex items-center justify-between">
              <span class="font-bold text-emerald-950 text-xs uppercase tracking-wider">Tracking Anggaran (Rupiah)</span>
              <span class="text-[11px] font-bold text-emerald-800">
                Serapan: {{ modalSerapanPercent }}% | Sisa: {{ formatRupiah(modalSisaAnggaran) }}
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Pagu Anggaran Rencana (Rp) *</label>
                <input 
                  type="number" 
                  v-model.number="form.anggaran_rencana" 
                  required 
                  min="0"
                  placeholder="0"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono font-bold text-slate-900 bg-white focus:ring-2 focus:ring-emerald-600 outline-none"
                />
                <span class="text-[10px] text-slate-500 mt-0.5 block font-mono">{{ formatRupiah(form.anggaran_rencana) }}</span>
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">Realisasi Anggaran Terserap (Rp)</label>
                <input 
                  type="number" 
                  v-model.number="form.anggaran_realisasi" 
                  min="0"
                  placeholder="0"
                  class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono font-bold text-emerald-700 bg-white focus:ring-2 focus:ring-emerald-600 outline-none"
                />
                <span class="text-[10px] text-emerald-600 mt-0.5 block font-mono">{{ formatRupiah(form.anggaran_realisasi) }}</span>
              </div>
            </div>
          </div>

          <!-- Baris 6: Penerima Manfaat -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Target Penerima Manfaat</label>
              <input 
                type="text" 
                v-model="form.penerima_manfaat_target" 
                placeholder="Contoh: 350 KK Prasejahtera / 950 Jiwa" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Realisasi Penerima Manfaat</label>
              <input 
                type="text" 
                v-model="form.penerima_manfaat_realisasi" 
                placeholder="Contoh: 348 KK Terbantu / Terbebas genangan" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <!-- Baris 7: Status & Progres Fisik -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Status Pelaksanaan</label>
              <select 
                v-model="form.status" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option value="Rencana">Rencana</option>
                <option value="Sedang Berjalan">Sedang Berjalan</option>
                <option value="Selesai">Selesai</option>
                <option value="Evaluasi">Evaluasi</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Progres Fisik (%)</label>
              <input 
                type="number" 
                v-model.number="form.progres_fisik" 
                min="0" 
                max="100" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-mono font-bold focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Penanggung Jawab (PJ)</label>
              <input 
                type="text" 
                v-model="form.penanggung_jawab" 
                placeholder="Kasi Ekbang / Kesra" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <!-- Baris 8: Deskripsi -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Deskripsi / Rincian Kegiatan</label>
            <textarea 
              v-model="form.deskripsi" 
              rows="2" 
              placeholder="Catatan teknis, spesifikasi pekerjaan, atau dampak kegiatan..." 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none resize-none leading-relaxed"
            ></textarea>
          </div>

          <!-- Baris 9: Status Publikasi -->
          <div class="flex items-center gap-2 pt-1">
            <input 
              type="checkbox" 
              id="check_transparansi_aktif" 
              v-model="form.aktif" 
              class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
            />
            <label for="check_transparansi_aktif" class="font-bold text-slate-700 cursor-pointer select-none">
              Publikasikan ke Portal Warga (Status Aktif)
            </label>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition"
            >
              Batal
            </button>
            <button 
              type="submit" 
              :disabled="saving" 
              class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold transition shadow-sm flex items-center gap-1.5"
            >
              <span v-if="saving">Menyimpan...</span>
              <span v-else>{{ editId ? 'Simpan Perubahan' : 'Tambahkan Kegiatan' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import { AdminService } from '../../services/api';

const loading = ref(true);
const saving = ref(false);
const successMsg = ref('');
const items = ref([]);
const kategoriOptions = ref([]);
const summary = ref({
  total_rencana: 0,
  total_realisasi: 0,
  total_sisa: 0,
  persentase_total: 0,
  total_kegiatan: 0,
});

const filterTahun = ref(new Date().getFullYear());
const filterKategori = ref('Semua');
const searchQuery = ref('');
const showModal = ref(false);
const editId = ref(null);

const form = reactive({
  tahun: new Date().getFullYear(),
  program: '',
  kegiatan: '',
  kategori: 'Infrastruktur & Sarpras',
  sumber_dana: 'Alokasi Dana Kelurahan (ADK)',
  anggaran_rencana: 0,
  anggaran_realisasi: 0,
  penerima_manfaat_target: '',
  penerima_manfaat_realisasi: '',
  progres_fisik: 0,
  status: 'Sedang Berjalan',
  lokasi: '',
  penanggung_jawab: '',
  deskripsi: '',
  urutan: 0,
  aktif: true
});

const availableYears = computed(() => {
  const current = new Date().getFullYear();
  const years = [current, current - 1];
  items.value.forEach(i => {
    if (i.tahun && !years.includes(i.tahun)) {
      years.push(i.tahun);
    }
  });
  return years.sort((a, b) => b - a);
});

const filteredItems = computed(() => {
  if (!searchQuery.value) return items.value;
  const q = searchQuery.value.toLowerCase();
  return items.value.filter(i => 
    (i.kegiatan && i.kegiatan.toLowerCase().includes(q)) ||
    (i.program && i.program.toLowerCase().includes(q)) ||
    (i.lokasi && i.lokasi.toLowerCase().includes(q)) ||
    (i.penerima_manfaat_target && i.penerima_manfaat_target.toLowerCase().includes(q)) ||
    (i.sumber_dana && i.sumber_dana.toLowerCase().includes(q))
  );
});

const modalSisaAnggaran = computed(() => {
  return Math.max(0, (form.anggaran_rencana || 0) - (form.anggaran_realisasi || 0));
});

const modalSerapanPercent = computed(() => {
  if (!form.anggaran_rencana) return 0;
  return Math.min(100, Math.round(((form.anggaran_realisasi || 0) / form.anggaran_rencana) * 1000) / 10);
});

const formatRupiah = (val) => {
  const num = Number(val) || 0;
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(num);
};

const getKategoriBadge = (kategori) => {
  switch (kategori) {
    case 'Infrastruktur & Sarpras':
      return 'bg-blue-100 text-blue-900 border border-blue-200';
    case 'Pemberdayaan Masyarakat':
      return 'bg-amber-100 text-amber-900 border border-amber-200';
    case 'Bantuan Sosial & Kesehatan':
      return 'bg-rose-100 text-rose-900 border border-rose-200';
    case 'Pemerintahan & Pelayanan Digital':
    default:
      return 'bg-emerald-100 text-emerald-900 border border-emerald-200';
  }
};

const getStatusBadge = (status) => {
  switch (status) {
    case 'Selesai':
      return 'bg-emerald-100 text-emerald-800';
    case 'Sedang Berjalan':
      return 'bg-blue-100 text-blue-800';
    case 'Rencana':
      return 'bg-slate-100 text-slate-700';
    case 'Evaluasi':
    default:
      return 'bg-amber-100 text-amber-800';
  }
};

const loadData = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filterTahun.value !== 'Semua') params.tahun = filterTahun.value;
    if (filterKategori.value !== 'Semua') params.kategori = filterKategori.value;

    const res = await AdminService.getTransparansi(params);
    items.value = res?.items || [];
    summary.value = res?.summary || {
      total_rencana: 0,
      total_realisasi: 0,
      total_sisa: 0,
      persentase_total: 0,
      total_kegiatan: 0,
    };
  } catch (err) {
    console.error('Gagal mengambil data transparansi:', err);
  } finally {
    loading.value = false;
  }
};

const openModal = (item = null) => {
  if (item) {
    editId.value = item.id;
    form.tahun = item.tahun;
    form.program = item.program;
    form.kegiatan = item.kegiatan;
    form.kategori = item.kategori;
    form.sumber_dana = item.sumber_dana;
    form.anggaran_rencana = item.anggaran_rencana;
    form.anggaran_realisasi = item.anggaran_realisasi;
    form.penerima_manfaat_target = item.penerima_manfaat_target || '';
    form.penerima_manfaat_realisasi = item.penerima_manfaat_realisasi || '';
    form.progres_fisik = item.progres_fisik ?? 0;
    form.status = item.status || 'Sedang Berjalan';
    form.lokasi = item.lokasi || '';
    form.penanggung_jawab = item.penanggung_jawab || '';
    form.deskripsi = item.deskripsi || '';
    form.urutan = item.urutan ?? 0;
    form.aktif = !!item.aktif;
  } else {
    editId.value = null;
    form.tahun = filterTahun.value !== 'Semua' ? Number(filterTahun.value) : new Date().getFullYear();
    form.program = '';
    form.kegiatan = '';
    form.kategori = kategoriOptions.value.length > 0 ? kategoriOptions.value[0].nama : 'Infrastruktur & Sarpras';
    form.sumber_dana = 'Alokasi Dana Kelurahan (ADK)';
    form.anggaran_rencana = 0;
    form.anggaran_realisasi = 0;
    form.penerima_manfaat_target = '';
    form.penerima_manfaat_realisasi = '';
    form.progres_fisik = 0;
    form.status = 'Sedang Berjalan';
    form.lokasi = '';
    form.penanggung_jawab = '';
    form.deskripsi = '';
    form.urutan = items.value.length + 1;
    form.aktif = true;
  }
  showModal.value = true;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const payload = { ...form };
    const res = await AdminService.saveTransparansi(payload, editId.value);
    successMsg.value = res?.message || 'Data kegiatan anggaran berhasil disimpan!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan kegiatan anggaran: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const toggleAktif = async (item) => {
  const newState = !item.aktif;
  try {
    await AdminService.toggleAktifTransparansi(item.id, newState);
    item.aktif = newState;
    successMsg.value = `Status kegiatan "${item.kegiatan}" berhasil diubah menjadi ${newState ? 'Live (Tampil)' : 'Draft (Sembunyi)'}.`;
  } catch (err) {
    alert('Gagal mengubah status kegiatan: ' + (err.response?.data?.message || err.message));
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus kegiatan "${item.kegiatan}"? Data yang dihapus tidak dapat dikembalikan.`)) {
    return;
  }
  try {
    await AdminService.deleteTransparansi(item.id);
    successMsg.value = `Kegiatan "${item.kegiatan}" berhasil dihapus.`;
    await loadData();
  } catch (err) {
    alert('Gagal menghapus kegiatan: ' + (err.response?.data?.message || err.message));
  }
};

const loadKategori = async () => {
  try {
    const kats = await AdminService.getMasterKategori('transparansi');
    if (kats && kats.length) {
      kategoriOptions.value = kats;
    }
  } catch (err) {
    console.error('Gagal mengambil master kategori transparansi:', err);
  }
};

onMounted(() => {
  loadKategori();
  loadData();
});
</script>
