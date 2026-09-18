<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileStorageHelper
{
    /**
     * Ekstrak relative path berkas yang berada di storage disk public.
     * Menerima format:
     * - "uploads/abc.jpg"
     * - "/storage/uploads/abc.jpg"
     * - "http://localhost:8000/storage/uploads/abc.jpg"
     * - "https://domain.com/storage/uploads/abc.jpg"
     *
     * Mengembalikan null jika file merupakan URL eksternal atau tidak berada di storage lokal.
     */
    public static function getRelativePublicPath(?string $pathOrUrl): ?string
    {
        if (empty($pathOrUrl)) {
            return null;
        }

        $trimmed = trim($pathOrUrl);

        // Jika diawali http/https, parse URL path
        if (filter_var($trimmed, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($trimmed, PHP_URL_PATH);
            if (! $parsed) {
                return null;
            }
            $trimmed = $parsed;
        }

        // Hapus leading slash
        $trimmed = ltrim($trimmed, '/');

        // Jika formatnya storage/uploads/...
        if (str_starts_with($trimmed, 'storage/')) {
            $trimmed = substr($trimmed, 8); // potong 'storage/'
        }

        // Pastikan berada dalam folder uploads/
        if (str_starts_with($trimmed, 'uploads/')) {
            return $trimmed;
        }

        // Cek langsung apakah exists di storage disk public
        if (Storage::disk('public')->exists($trimmed)) {
            return $trimmed;
        }

        // Coba prefix 'uploads/' jika hanya nama file
        if (Storage::disk('public')->exists('uploads/'.$trimmed)) {
            return 'uploads/'.$trimmed;
        }

        return null;
    }

    /**
     * Hapus berkas fisik dari storage lokal disk public jika ada.
     */
    public static function deleteFileIfLocal(?string $pathOrUrl): bool
    {
        $relativePath = self::getRelativePublicPath($pathOrUrl);

        if (! $relativePath) {
            return false;
        }

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->delete($relativePath);
        }

        return false;
    }

    /**
     * Hapus banyak berkas fisik sekaligus jika bertipe lokal.
     *
     * @param  array<int, string|null>  $pathsOrUrls
     */
    public static function deleteFilesIfLocal(array $pathsOrUrls): void
    {
        foreach ($pathsOrUrls as $path) {
            self::deleteFileIfLocal($path);
        }
    }
}
