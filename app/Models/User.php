<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'accessible_menus'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_STAFF_KONTEN = 'staff_konten';

    public const ROLE_STAFF_PELAYANAN = 'staff_pelayanan';

    public const ROLE_STAFF_ADMINISTRASI = 'staff_administrasi';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_STAFF_KONTEN => 'Staff Konten & Humas',
        self::ROLE_STAFF_PELAYANAN => 'Staff Pelayanan',
        self::ROLE_STAFF_ADMINISTRASI => 'Staff Administrasi',
    ];

    public const MENUS = [
        'berita' => 'Berita & Artikel',
        'pengumuman' => 'Pengumuman Resmi',
        'agenda' => 'Agenda Kegiatan',
        'galeri' => 'Galeri Foto',
        'dokumen' => 'Dokumen PDF Kelurahan',
        'layanan' => 'Katalog SOP & Maklumat Pelayanan',
        'lembaga' => 'Lembaga Kemasyarakatan (LKK)',
        'statistik' => 'Statistik Kependudukan',
        'transparansi' => 'Transparansi Anggaran (APBD)',
        'kategori' => 'Master Kategori',
        'profil' => 'Profil & Aparatur Kelurahan',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['role_label', 'effective_menus'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'accessible_menus' => 'array',
        ];
    }

    /**
     * Get human-readable role label in Indonesian
     */
    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? 'Pengguna';
    }

    /**
     * Get effective list of accessible menus for this user
     *
     * @return array<string>
     */
    public function getEffectiveMenusAttribute(): array
    {
        if ($this->isSuperAdmin()) {
            return array_keys(self::MENUS);
        }

        if (is_array($this->accessible_menus) && count($this->accessible_menus) > 0) {
            return $this->accessible_menus;
        }

        return self::getDefaultMenusForRole($this->role);
    }

    /**
     * Get default menu array for a role
     *
     * @return array<string>
     */
    public static function getDefaultMenusForRole(string $role): array
    {
        return match ($role) {
            self::ROLE_SUPER_ADMIN => array_keys(self::MENUS),
            self::ROLE_STAFF_KONTEN => ['berita', 'pengumuman', 'agenda', 'galeri', 'dokumen', 'kategori'],
            self::ROLE_STAFF_PELAYANAN => ['layanan', 'dokumen', 'kategori'],
            self::ROLE_STAFF_ADMINISTRASI => ['dokumen', 'lembaga', 'statistik', 'transparansi', 'kategori'],
            default => [],
        };
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user has any of the given roles
     *
     * @param  string|array<string>  $roles
     */
    public function hasRole(string|array $roles): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->role, $roles, true);
    }

    /**
     * Check if user has permission to access a specific menu
     */
    public function canAccessMenu(string $menu): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($menu, $this->effective_menus, true);
    }

    /**
     * Check if user has permission to access any of the given menus
     *
     * @param  array<string>  $menus
     */
    public function canAccessAnyMenu(array $menus): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($menus as $m) {
            if ($this->canAccessMenu($m)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Relasi ke riwayat log aktifitas user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
