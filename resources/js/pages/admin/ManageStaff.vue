<template>
  <div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-[11px] font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-purple-600"></span>
          Super Admin Control Panel
        </div>
        <h2 class="text-xl font-bold text-slate-900">Manajemen Akun Staf & Hak Akses (RBAC)</h2>
        <p class="text-xs text-slate-500 mt-0.5">Kelola akun staf kelurahan dan batasi akses sesuai bidang kerja masing-masing.</p>
      </div>

      <button 
        @click="openModalCreate" 
        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs shadow-sm transition self-start sm:self-auto"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        <span>Tambah Akun Staf Baru</span>
      </button>
    </div>

    <!-- Alert Notifikasi Sukses -->
    <div v-if="successMsg" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ successMsg }}</span>
      </div>
      <button @click="successMsg = ''" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
    </div>

    <!-- Alert Notifikasi Error -->
    <div v-if="errorMsg" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ errorMsg }}</span>
      </div>
      <button @click="errorMsg = ''" class="text-rose-700 hover:text-rose-900 font-bold">&times;</button>
    </div>

    <!-- 4 + 1 Metric Cards: Role Counts -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
      <!-- Total Staf -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Akun</p>
        <p class="text-2xl font-black text-slate-900 mt-1">{{ counts.total || 0 }}</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Seluruh pengguna</p>
      </div>

      <!-- Super Admin -->
      <div class="bg-white p-4 rounded-2xl border border-purple-200 bg-purple-50/30 shadow-xs">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-bold uppercase tracking-wider text-purple-700">Super Admin</p>
          <span class="w-2 h-2 rounded-full bg-purple-600"></span>
        </div>
        <p class="text-2xl font-black text-purple-900 mt-1">{{ counts.super_admin || 0 }}</p>
        <p class="text-[10px] text-purple-600 mt-0.5">Kendali Penuh</p>
      </div>

      <!-- Staff Konten -->
      <div class="bg-white p-4 rounded-2xl border border-emerald-200 bg-emerald-50/30 shadow-xs">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Konten & Humas</p>
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
        </div>
        <p class="text-2xl font-black text-emerald-900 mt-1">{{ counts.staff_konten || 0 }}</p>
        <p class="text-[10px] text-emerald-600 mt-0.5">Berita, Pengumuman, Galeri</p>
      </div>

      <!-- Staff Pelayanan -->
      <div class="bg-white p-4 rounded-2xl border border-blue-200 bg-blue-50/30 shadow-xs">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-bold uppercase tracking-wider text-blue-700">Pelayanan</p>
          <span class="w-2 h-2 rounded-full bg-blue-600"></span>
        </div>
        <p class="text-2xl font-black text-blue-900 mt-1">{{ counts.staff_pelayanan || 0 }}</p>
        <p class="text-[10px] text-blue-600 mt-0.5">Layanan, Pengajuan, Pesan</p>
      </div>

      <!-- Staff Administrasi -->
      <div class="bg-white p-4 rounded-2xl border border-amber-200 bg-amber-50/30 shadow-xs">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Administrasi</p>
          <span class="w-2 h-2 rounded-full bg-amber-600"></span>
        </div>
        <p class="text-2xl font-black text-amber-900 mt-1">{{ counts.staff_administrasi || 0 }}</p>
        <p class="text-[10px] text-amber-600 mt-0.5">LKK & Statistik Wilayah</p>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
      <!-- Role Filter Pills -->
      <div class="flex flex-wrap items-center gap-1.5">
        <button 
          v-for="r in roleFilters" 
          :key="r.value"
          @click="filterRole = r.value; loadStaff()"
          class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
          :class="filterRole === r.value ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900'"
        >
          {{ r.label }}
        </button>
      </div>

      <!-- Search Box -->
      <div class="relative w-full md:w-64">
        <input 
          v-model="search" 
          @input="handleSearch"
          type="text" 
          placeholder="Cari nama atau email staf..." 
          class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-hidden"
        />
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="py-12 flex justify-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-700"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="staffList.length === 0" class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3">
      <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      </div>
      <h3 class="font-bold text-slate-900 text-sm">Tidak ada staf yang sesuai</h3>
      <p class="text-xs text-slate-500">Coba sesuaikan kata kunci pencarian atau filter peran yang dipilih.</p>
    </div>

    <!-- Data Table -->
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
            <tr>
              <th class="px-6 py-3.5">Nama Staf & Email</th>
              <th class="px-6 py-3.5">Peran (Role) & Tanggung Jawab</th>
              <th class="px-6 py-3.5">Hak Akses Menu</th>
              <th class="px-6 py-3.5">Terdaftar Sejak</th>
              <th class="px-6 py-3.5 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
            <tr v-for="staf in staffList" :key="staf.id" class="hover:bg-slate-50/80 transition">
              <!-- Nama & Email -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div 
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white uppercase shadow-xs flex-shrink-0"
                    :class="getAvatarColor(staf.role)"
                  >
                    {{ (staf.name || 'S').charAt(0) }}
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <p class="font-bold text-slate-900">{{ staf.name }}</p>
                      <span v-if="currentAuthUser?.id === staf.id" class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 text-[10px] font-bold">
                        Anda
                      </span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-mono">{{ staf.email }}</p>
                  </div>
                </div>
              </td>

              <!-- Peran & Deskripsi -->
              <td class="px-6 py-4">
                <div class="space-y-1">
                  <span 
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold"
                    :class="getRoleBadgeClass(staf.role)"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="getRoleDotClass(staf.role)"></span>
                    {{ staf.role_label || getRoleLabel(staf.role) }}
                  </span>
                  <p class="text-[10px] text-slate-500 leading-tight">
                    {{ getRoleScopeDescription(staf.role) }}
                  </p>
                </div>
              </td>

              <!-- Hak Akses Menu -->
              <td class="px-6 py-4">
                <div v-if="staf.role === 'super_admin'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-[11px] font-bold">
                  <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                  <span>Semua Menu (Penuh)</span>
                </div>
                <div v-else class="space-y-1">
                  <div class="flex flex-wrap gap-1 max-w-xs">
                    <span 
                      v-for="mKey in (staf.effective_menus || staf.accessible_menus || []).slice(0, 3)" 
                      :key="mKey"
                      class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-medium border border-slate-200"
                    >
                      <span>{{ getMenuIcon(mKey) }}</span>
                      <span>{{ getMenuShortLabel(mKey) }}</span>
                    </span>
                    <span 
                      v-if="(staf.effective_menus || staf.accessible_menus || []).length > 3"
                      class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[10px] font-bold border border-purple-200 cursor-help"
                      :title="formatAllMenusTitle(staf.effective_menus || staf.accessible_menus)"
                    >
                      +{{ (staf.effective_menus || staf.accessible_menus || []).length - 3 }} lainnya
                    </span>
                    <span 
                      v-if="!(staf.effective_menus || staf.accessible_menus)?.length"
                      class="text-[10px] text-amber-600 italic font-semibold"
                    >
                      Belum ada akses menu
                    </span>
                  </div>
                </div>
              </td>

              <!-- Terdaftar Sejak -->
              <td class="px-6 py-4 text-slate-500 text-[11px]">
                {{ formatDate(staf.created_at) }}
              </td>

              <!-- Tombol Aksi -->
              <td class="px-6 py-4 text-right">
                <div class="inline-flex items-center gap-1.5">
                  <!-- View Activity Logs Button -->
                  <router-link 
                    :to="`/admin/activity-logs?user_id=${staf.id}`"
                    class="p-1.5 rounded-lg text-slate-500 hover:text-blue-700 hover:bg-blue-50 transition" 
                    title="Lihat Log Aktifitas Akun"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                  </router-link>

                  <!-- Edit Button -->
                  <button 
                    @click="openModalEdit(staf)"
                    class="p-1.5 rounded-lg text-slate-500 hover:text-purple-700 hover:bg-purple-50 transition" 
                    title="Edit Profil & Peran"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                  </button>

                  <!-- Reset Password Button -->
                  <button 
                    @click="openModalPassword(staf)"
                    class="p-1.5 rounded-lg text-slate-500 hover:text-amber-700 hover:bg-amber-50 transition" 
                    title="Reset Kata Sandi"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                  </button>

                  <!-- Delete Button -->
                  <button 
                    v-if="currentAuthUser?.id !== staf.id"
                    @click="confirmDelete(staf)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" 
                    title="Hapus Akun Staf"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MODAL 1: Tambah / Edit Staf -->
    <div v-if="showModalForm" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
      <div class="bg-white rounded-2xl sm:rounded-3xl max-w-3xl w-full shadow-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden">
        <!-- Modal Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="font-bold text-slate-900 text-base sm:text-lg">
              {{ isEditing ? 'Edit Akun Staf' : 'Tambah Akun Staf Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Tentukan nama, alamat email resmi, serta peran dan hak akses staf.</p>
          </div>
          <button @click="showModalForm = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 font-bold cursor-pointer" title="Tutup">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Form Fields with Sticky Footer -->
        <form @submit.prevent="submitForm" class="flex flex-col flex-1 overflow-hidden">
          <div class="p-5 sm:p-6 overflow-y-auto flex-1 text-xs sm:text-sm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
              <!-- Left Column: Identitas & Keamanan (cols-6) -->
              <div class="md:col-span-6 space-y-4">
                <!-- Nama Lengkap -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Staf *</label>
                  <input 
                    v-model="form.name" 
                    type="text" 
                    required
                    placeholder="Contoh: Rahmat Hidayat, S.Kom." 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-purple-600 focus:outline-hidden"
                  />
                </div>

                <!-- Email Resmi -->
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Resmi *</label>
                  <input 
                    v-model="form.email" 
                    type="email" 
                    required
                    placeholder="nama@kraksaanwetan.go.id" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-purple-600 focus:outline-hidden"
                  />
                </div>

                <!-- Kata Sandi -->
                <div v-if="!isEditing" class="p-3.5 rounded-2xl bg-purple-50/50 border border-purple-100 space-y-1">
                  <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Awal * (Min. 6 karakter)</label>
                  <input 
                    v-model="form.password" 
                    type="password" 
                    required
                    minlength="6"
                    placeholder="Masukkan kata sandi awal staf" 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm focus:ring-2 focus:ring-purple-600 focus:outline-hidden"
                  />
                </div>

                <div v-else class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                  <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru (Opsional)</label>
                  <input 
                    v-model="form.password" 
                    type="password" 
                    minlength="6"
                    placeholder="Kosongkan jika tidak diubah" 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm focus:ring-2 focus:ring-purple-600 focus:outline-hidden"
                  />
                </div>
              </div>

              <!-- Right Column: Pilihan Peran (Role) (cols-6) -->
              <div class="md:col-span-6 space-y-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Peran & Hak Akses (Role) *</label>
                <div class="space-y-2.5">
                  <label 
                    v-for="opt in roleOptions" 
                    :key="opt.value"
                    class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition text-xs"
                    :class="form.role === opt.value ? opt.activeBorderClass : 'border-slate-200 hover:bg-slate-50'"
                  >
                    <input 
                      type="radio" 
                      name="role" 
                      :value="opt.value" 
                      v-model="form.role"
                      @change="onRoleChange(opt.value)"
                      class="mt-0.5 text-purple-600 focus:ring-purple-500 cursor-pointer" 
                    />
                    <div class="space-y-0.5">
                      <p class="font-bold" :class="opt.titleClass">{{ opt.label }}</p>
                      <p class="text-[11px] text-slate-500 leading-snug">{{ opt.desc }}</p>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Bagian Pemilihan Hak Akses Menu Granular -->
            <div class="mt-6 pt-5 border-t border-slate-200">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                <div>
                  <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-900">
                      Hak Akses Menu & Modul Kelurahan *
                    </label>
                    <span 
                      class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                      :class="form.role === 'super_admin' ? 'bg-purple-100 text-purple-800' : (form.accessible_menus.length > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800')"
                    >
                      {{ form.role === 'super_admin' ? 'Semua Menu (Kendali Penuh)' : `${form.accessible_menus.length} Modul Dipilih` }}
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-500 mt-0.5">
                    Tentukan modul mana saja yang dapat dibuka dan dikelola oleh akun staf ini di Panel Admin.
                  </p>
                </div>

                <!-- Tombol Shortcut / Preset Hak Akses -->
                <div v-if="form.role !== 'super_admin'" class="flex items-center gap-1.5 flex-wrap">
                  <button 
                    type="button" 
                    @click="applyRolePreset(form.role)"
                    class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold transition cursor-pointer"
                    title="Kembalikan modul terpilih sesuai rekomendasi peran"
                  >
                    Preset Peran
                  </button>
                  <button 
                    type="button" 
                    @click="selectAllMenus"
                    class="px-2.5 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 text-[11px] font-semibold transition cursor-pointer"
                  >
                    Pilih Semua
                  </button>
                  <button 
                    type="button" 
                    @click="clearMenus"
                    class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-semibold transition cursor-pointer"
                  >
                    Kosongkan
                  </button>
                </div>
              </div>

              <!-- Notifikasi Khusus Super Admin -->
              <div v-if="form.role === 'super_admin'" class="p-3.5 rounded-2xl bg-purple-50 border border-purple-200 text-purple-900 text-xs flex items-center gap-3 shadow-2xs">
                <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="space-y-0.5">
                  <p class="font-bold text-purple-950">Akses Penuh Tanpa Batasan</p>
                  <p class="text-[11px] text-purple-700">Akun dengan peran Super Admin memegang otoritas tertinggi sistem. Seluruh 11 modul CMS, pengelolaan staf, audit log, dan pengaturan sistem aktif otomatis.</p>
                </div>
              </div>

              <!-- Grid Pilihan Menu Checkbox -->
              <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                <label 
                  v-for="menu in menuDefinitions" 
                  :key="menu.key"
                  class="flex items-start gap-3 p-3 rounded-2xl border transition cursor-pointer select-none"
                  :class="form.accessible_menus.includes(menu.key) ? 'border-purple-300 bg-purple-50/40 ring-1 ring-purple-300 shadow-2xs' : 'border-slate-200 hover:bg-slate-50 opacity-80'"
                >
                  <input 
                    type="checkbox" 
                    :value="menu.key" 
                    v-model="form.accessible_menus" 
                    class="mt-0.5 w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-slate-300 cursor-pointer flex-shrink-0"
                  />
                  <div class="space-y-0.5 min-w-0">
                    <div class="flex items-center gap-1.5">
                      <span class="text-sm shrink-0">{{ menu.icon }}</span>
                      <p class="font-bold text-xs text-slate-900 leading-tight truncate">{{ menu.label }}</p>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-snug">{{ menu.desc }}</p>
                  </div>
                </label>
              </div>

              <!-- Peringatan Jika Kosong -->
              <div v-if="form.role !== 'super_admin' && form.accessible_menus.length === 0" class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2.5">
                <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-[11px] leading-tight">
                  <span class="font-bold">Perhatian:</span> Akun ini belum diberi izin ke modul manapun. Pengguna staf hanya dapat mengakses halaman beranda Dashboard tanpa menu kelola.
                </p>
              </div>
            </div>
          </div>

          <!-- Modal Sticky Footer -->
          <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
            <div class="text-xs text-slate-400">
              <span v-if="isEditing" class="text-purple-700 font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                Mode Edit Akun Staf
              </span>
              <span v-else class="text-slate-500">
                Menambahkan akun staf baru
              </span>
            </div>
            <div class="flex items-center gap-2.5">
              <button 
                type="button" 
                @click="showModalForm = false"
                class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs sm:text-sm font-bold transition cursor-pointer"
              >
                Batal
              </button>
              <button 
                type="submit" 
                :disabled="saving"
                class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 disabled:opacity-50 text-white text-xs sm:text-sm font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5"
              >
                {{ saving ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Buat Akun Staf') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 2: Reset Password Cepat -->
    <div v-if="showModalPwd" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
          <div>
            <h3 class="font-bold text-slate-900 text-sm">Reset Kata Sandi</h3>
            <p class="text-xs text-slate-500 truncate max-w-[220px]">{{ targetStaff?.name }}</p>
          </div>
          <button @click="showModalPwd = false" class="text-slate-400 hover:text-slate-700 font-bold text-lg">&times;</button>
        </div>

        <form @submit.prevent="submitResetPassword" class="space-y-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru *</label>
            <input 
              v-model="pwdForm.password" 
              type="password" 
              required
              minlength="6"
              placeholder="Minimal 6 karakter" 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-hidden"
            />
          </div>

          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
            <button 
              type="button" 
              @click="showModalPwd = false"
              class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition"
            >
              Batal
            </button>
            <button 
              type="submit" 
              :disabled="saving"
              class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 disabled:opacity-50 text-white text-xs font-bold transition shadow-xs"
            >
              {{ saving ? 'Menyimpan...' : 'Reset Sandi' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 3: Konfirmasi Hapus Staf -->
    <div v-if="showModalDelete" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200 relative">
        <button 
          type="button" 
          @click="showModalDelete = false" 
          class="absolute top-4 right-4 p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer"
          title="Tutup"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>

        <div class="text-center space-y-1">
          <h3 class="font-bold text-slate-900 text-base">Hapus Akun Staf?</h3>
          <p class="text-xs text-slate-500">
            Akun milik <span class="font-bold text-slate-700">{{ targetStaff?.name }}</span> ({{ targetStaff?.email }}) akan dihapus permanen dari sistem.
          </p>
        </div>

        <div class="flex items-center justify-center gap-2 pt-2">
          <button 
            type="button" 
            @click="showModalDelete = false"
            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition"
          >
            Batal
          </button>
          <button 
            type="button" 
            :disabled="saving"
            @click="executeDelete"
            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white text-xs font-bold transition shadow-xs"
          >
            {{ saving ? 'Menghapus...' : 'Ya, Hapus Akun' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { AdminService } from '../../services/api';
import { useToast } from '../../composables/useToast';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const staffList = ref([]);
const counts = ref({});
const search = ref('');
const filterRole = ref('Semua');
const successMsg = ref('');
const errorMsg = ref('');

const currentAuthUser = ref(null);

const showModalForm = ref(false);
const isEditing = ref(false);
const editId = ref(null);

const showModalPwd = ref(false);
const showModalDelete = ref(false);
const targetStaff = ref(null);

const form = reactive({
  name: '',
  email: '',
  role: 'staff_pelayanan',
  password: '',
  accessible_menus: [],
});

const menuDefinitions = [
  { key: 'berita', label: 'Kelola Berita', icon: '📰', desc: 'Warta berita kelurahan, artikel, liputan kegiatan, dan running text portal warga.' },
  { key: 'pengumuman', label: 'Pengumuman Resmi', icon: '📢', desc: 'Siaran pengumuman penting, info mendesak, dan surat edaran bagi warga.' },
  { key: 'agenda', label: 'Agenda Kegiatan', icon: '📅', desc: 'Jadwal agenda acara, musyawarah desa, gotong royong, dan kegiatan dinas.' },
  { key: 'galeri', label: 'Galeri Foto', icon: '🖼️', desc: 'Dokumentasi album foto dokumenter kegiatan kelurahan dan arsip visual.' },
  { key: 'dokumen', label: 'Dokumen PDF', icon: '📁', desc: 'Publikasi berkas PDF resmi (Musrenbang, UMKM, Renstra & Renja, Regulasi).' },
  { key: 'layanan', label: 'Katalog Layanan', icon: '📑', desc: 'SOP administrasi warga, persyaratan izin, maklumat pelayanan, dan SKM.' },
  { key: 'lembaga', label: 'Lembaga (LKK)', icon: '🏛️', desc: 'Profil lembaga kemasyarakatan (LPM, PKK, Karang Taruna, RT/RW, Posyandu).' },
  { key: 'statistik', label: 'Statistik Kependudukan', icon: '📊', desc: 'Data demografi kependudukan, piramida usia, pekerjaan, dan batas wilayah.' },
  { key: 'transparansi', label: 'Transparansi Anggaran', icon: '💰', desc: 'Publikasi APBD, realisasi anggaran, rincian belanja operasi & pembiayaan.' },
  { key: 'kategori', label: 'Master Kategori', icon: '🏷️', desc: 'Kelola master kategori terpusat berita, dokumen PDF, dan pengumuman.' },
  { key: 'profil', label: 'Profil & Aparatur', icon: '🏢', desc: 'Struktur organisasi pemerintahan dan susunan pamong aparatur kelurahan.' },
];

const roleDefaultMenus = {
  super_admin: ['berita', 'pengumuman', 'agenda', 'galeri', 'dokumen', 'layanan', 'lembaga', 'statistik', 'transparansi', 'kategori', 'profil'],
  staff_konten: ['berita', 'pengumuman', 'agenda', 'galeri', 'dokumen', 'kategori'],
  staff_pelayanan: ['layanan', 'dokumen', 'kategori'],
  staff_administrasi: ['dokumen', 'lembaga', 'statistik', 'transparansi', 'kategori'],
};

const getMenuIcon = (key) => {
  const found = menuDefinitions.find(m => m.key === key);
  return found ? found.icon : '📌';
};

const getMenuShortLabel = (key) => {
  const found = menuDefinitions.find(m => m.key === key);
  return found ? found.label : key;
};

const formatAllMenusTitle = (menus) => {
  if (!Array.isArray(menus)) return '';
  return menus.map(m => getMenuShortLabel(m)).join(', ');
};

const selectAllMenus = () => {
  form.accessible_menus = menuDefinitions.map(m => m.key);
};

const clearMenus = () => {
  form.accessible_menus = [];
};

const applyRolePreset = (role) => {
  form.accessible_menus = [...(roleDefaultMenus[role] || [])];
};

const onRoleChange = (newRole) => {
  if (newRole === 'super_admin') {
    form.accessible_menus = [...roleDefaultMenus['super_admin']];
  } else {
    // Selalu sinkronkan ke preset peran yang baru dipilih bila membuat baru atau jika daftar menu kosong
    if (!isEditing.value || form.accessible_menus.length === 0) {
      form.accessible_menus = [...(roleDefaultMenus[newRole] || [])];
    }
  }
};

const pwdForm = reactive({
  password: '',
});

const roleFilters = [
  { label: 'Semua Peran', value: 'Semua' },
  { label: 'Super Admin', value: 'super_admin' },
  { label: 'Konten & Humas', value: 'staff_konten' },
  { label: 'Pelayanan', value: 'staff_pelayanan' },
  { label: 'Administrasi', value: 'staff_administrasi' },
];

const roleOptions = [
  {
    value: 'super_admin',
    label: 'Super Admin',
    desc: 'Mengelola akun staf dan memegang kendali penuh atas semua modul website kelurahan.',
    activeBorderClass: 'border-purple-500 bg-purple-50/40',
    titleClass: 'text-purple-900',
  },
  {
    value: 'staff_konten',
    label: 'Staff Konten & Humas',
    desc: 'Mengendalikan Kelola Berita, Pengumuman Kelurahan, dan Galeri Foto Kegiatan.',
    activeBorderClass: 'border-emerald-500 bg-emerald-50/40',
    titleClass: 'text-emerald-900',
  },
  {
    value: 'staff_pelayanan',
    label: 'Staff Pelayanan',
    desc: 'Mengendalikan Kelola Layanan Warga, Pengajuan Dokumen Online, dan Pesan Aspirasi.',
    activeBorderClass: 'border-blue-500 bg-blue-50/40',
    titleClass: 'text-blue-900',
  },
  {
    value: 'staff_administrasi',
    label: 'Staff Administrasi',
    desc: 'Mengendalikan Lembaga Kemasyarakatan (LKK) dan Statistik Kependudukan & Wilayah.',
    activeBorderClass: 'border-amber-500 bg-amber-50/40',
    titleClass: 'text-amber-900',
  },
];

const getRoleLabel = (role) => {
  const map = {
    super_admin: 'Super Admin',
    staff_konten: 'Staff Konten & Humas',
    staff_pelayanan: 'Staff Pelayanan',
    staff_administrasi: 'Staff Administrasi',
  };
  return map[role] || role;
};

const getRoleScopeDescription = (role) => {
  const map = {
    super_admin: 'Memegang kendali penuh & mengelola akun staf',
    staff_konten: 'Kelola Berita, Pengumuman, Galeri Foto',
    staff_pelayanan: 'Layanan, Pengajuan Online, Pesan & Aspirasi',
    staff_administrasi: 'Lembaga Kemasyarakatan & Statistik Wilayah',
  };
  return map[role] || '';
};

const getRoleBadgeClass = (role) => {
  switch (role) {
    case 'super_admin':
      return 'bg-purple-100 text-purple-800';
    case 'staff_konten':
      return 'bg-emerald-100 text-emerald-800';
    case 'staff_pelayanan':
      return 'bg-blue-100 text-blue-800';
    case 'staff_administrasi':
      return 'bg-amber-100 text-amber-800';
    default:
      return 'bg-slate-100 text-slate-800';
  }
};

const getRoleDotClass = (role) => {
  switch (role) {
    case 'super_admin':
      return 'bg-purple-600';
    case 'staff_konten':
      return 'bg-emerald-600';
    case 'staff_pelayanan':
      return 'bg-blue-600';
    case 'staff_administrasi':
      return 'bg-amber-600';
    default:
      return 'bg-slate-600';
  }
};

const getAvatarColor = (role) => {
  switch (role) {
    case 'super_admin':
      return 'bg-gradient-to-tr from-purple-700 to-indigo-600';
    case 'staff_konten':
      return 'bg-gradient-to-tr from-emerald-600 to-teal-500';
    case 'staff_pelayanan':
      return 'bg-gradient-to-tr from-blue-600 to-sky-500';
    case 'staff_administrasi':
      return 'bg-gradient-to-tr from-amber-600 to-yellow-500';
    default:
      return 'bg-slate-600';
  }
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
};

const loadStaff = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filterRole.value !== 'Semua') params.role = filterRole.value;
    if (search.value.trim()) params.search = search.value.trim();

    const res = await AdminService.getStaff(params);
    if (res) {
      staffList.value = res.staff || [];
      counts.value = res.counts || {};
    }
  } catch (e) {
    errorMsg.value = e.response?.data?.message || 'Gagal memuat daftar staf.';
  } finally {
    loading.value = false;
  }
};

let searchTimeout = null;
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    loadStaff();
  }, 300);
};

const openModalCreate = () => {
  isEditing.value = false;
  editId.value = null;
  form.name = '';
  form.email = '';
  form.role = 'staff_pelayanan';
  form.password = '';
  form.accessible_menus = [...(roleDefaultMenus['staff_pelayanan'] || [])];
  showModalForm.value = true;
};

const openModalEdit = (staf) => {
  isEditing.value = true;
  editId.value = staf.id;
  form.name = staf.name;
  form.email = staf.email;
  form.role = staf.role;
  form.password = '';
  if (staf.role === 'super_admin') {
    form.accessible_menus = [...roleDefaultMenus['super_admin']];
  } else if (Array.isArray(staf.accessible_menus) && staf.accessible_menus.length > 0) {
    form.accessible_menus = [...staf.accessible_menus];
  } else if (Array.isArray(staf.effective_menus) && staf.effective_menus.length > 0) {
    form.accessible_menus = [...staf.effective_menus];
  } else {
    form.accessible_menus = [...(roleDefaultMenus[staf.role] || [])];
  }
  showModalForm.value = true;
};

const submitForm = async () => {
  saving.value = true;
  successMsg.value = '';
  errorMsg.value = '';
  try {
    const payload = {
      name: form.name,
      email: form.email,
      role: form.role,
      accessible_menus: form.role === 'super_admin' ? null : form.accessible_menus,
    };
    if (form.password) {
      payload.password = form.password;
    }

    if (isEditing.value) {
      const res = await AdminService.updateStaff(editId.value, payload);
      const msg = res.message || 'Data staf berhasil diperbarui.';
      successMsg.value = msg;
      toast.success(msg, 'Akun Staf');
    } else {
      const res = await AdminService.storeStaff(payload);
      const msg = res.message || 'Akun staf berhasil dibuat.';
      successMsg.value = msg;
      toast.success(msg, 'Akun Staf');
    }
    showModalForm.value = false;
    await loadStaff();
  } catch (e) {
    const err = e.response?.data?.message || 'Gagal menyimpan data staf.';
    errorMsg.value = err;
    toast.error(err, 'Gagal Menyimpan');
  } finally {
    saving.value = false;
  }
};

const openModalPassword = (staf) => {
  targetStaff.value = staf;
  pwdForm.password = '';
  showModalPwd.value = true;
};

const submitResetPassword = async () => {
  if (!targetStaff.value) return;
  saving.value = true;
  successMsg.value = '';
  errorMsg.value = '';
  try {
    const res = await AdminService.resetStaffPassword(targetStaff.value.id, {
      password: pwdForm.password,
    });
    const msg = res.message || 'Kata sandi berhasil direset.';
    successMsg.value = msg;
    toast.success(msg, 'Reset Kata Sandi');
    showModalPwd.value = false;
  } catch (e) {
    const err = e.response?.data?.message || 'Gagal mereset kata sandi staf.';
    errorMsg.value = err;
    toast.error(err, 'Gagal Reset Sandi');
  } finally {
    saving.value = false;
  }
};

const confirmDelete = (staf) => {
  targetStaff.value = staf;
  showModalDelete.value = true;
};

const executeDelete = async () => {
  if (!targetStaff.value) return;
  saving.value = true;
  successMsg.value = '';
  errorMsg.value = '';
  try {
    const res = await AdminService.deleteStaff(targetStaff.value.id);
    const msg = res.message || 'Akun staf berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'Akun Dihapus');
    showModalDelete.value = false;
    await loadStaff();
  } catch (e) {
    const err = e.response?.data?.message || 'Gagal menghapus akun staf.';
    errorMsg.value = err;
    toast.error(err, 'Gagal Menghapus');
  } finally {
    saving.value = false;
  }
};

onMounted(() => {
  currentAuthUser.value = AdminService.getAuthUser();
  loadStaff();
});
</script>
