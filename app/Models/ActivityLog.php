<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper method untuk mencatat log aktifitas secara aman.
     *
     * @param  array<string, mixed>|null  $properties
     */
    public static function record(
        string $action,
        string $module,
        string $description,
        ?array $properties = null,
        ?User $user = null
    ): ?self {
        try {
            $currentUser = $user ?? request()->user();

            return self::create([
                'user_id' => $currentUser?->id,
                'user_name' => $currentUser?->name ?? 'Sistem',
                'user_email' => $currentUser?->email,
                'user_role' => $currentUser?->role,
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => $properties,
            ]);
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }
}
