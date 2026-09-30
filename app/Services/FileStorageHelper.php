<?php

namespace App\Services;

use App\Models\AgendaKegiatan;
use App\Models\AnggaranRealisasi;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\HalamanKustom;
use App\Models\Lembaga;
use App\Models\MaklumatPelayanan;
use App\Models\Pengumuman;
use App\Models\PerangkatKelurahan;
use App\Models\ProfilKelurahan;
use App\Models\SurveiSkm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $trimmed = trim((string) $pathOrUrl);
        $trimmed = str_replace('\\', '/', $trimmed);

        // Jika diawali http/https, parse URL path
        if (filter_var($trimmed, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($trimmed, PHP_URL_PATH);
            if (! $parsed) {
                return null;
            }
            $trimmed = $parsed;
        }

        // Hapus query string jika ada
        if (str_contains($trimmed, '?')) {
            $trimmed = explode('?', $trimmed)[0];
        }

        // Decode URL encoding
        $trimmed = rawurldecode($trimmed);

        // Hapus leading slash
        $trimmed = ltrim($trimmed, '/');

        // Jika formatnya storage/uploads/... atau storage/...
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

        $deleted = false;
        if (Storage::disk('public')->exists($relativePath)) {
            $deleted = Storage::disk('public')->delete($relativePath);
        }

        // Cek dan bersihkan juga jika terdapat duplikat berkas di folder root atau uploads/
        if (str_starts_with($relativePath, 'uploads/')) {
            $baseName = basename($relativePath);
            if (Storage::disk('public')->exists($baseName)) {
                Storage::disk('public')->delete($baseName);
                $deleted = true;
            }
        } elseif (Storage::disk('public')->exists('uploads/'.$relativePath)) {
            Storage::disk('public')->delete('uploads/'.$relativePath);
            $deleted = true;
        }

        return $deleted;
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

    /**
     * Mengambil seluruh daftar berkas lokal aktif yang sedang tercatat di database.
     *
     * @return array<int, string>
     */
    public static function getAllActiveDatabaseFiles(): array
    {
        $dbFiles = [];

        // Dokumen Publik
        foreach (Dokumen::pluck('file') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Berita
        foreach (Berita::pluck('gambar') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Galeri
        foreach (Galeri::pluck('gambar') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Pengumuman
        foreach (Pengumuman::pluck('file') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }
        foreach (Pengumuman::pluck('banner') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }
        foreach (Pengumuman::pluck('thumbnail') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Perangkat Kelurahan
        foreach (PerangkatKelurahan::pluck('foto') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Lembaga
        foreach (Lembaga::pluck('logo') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Agenda Kegiatan
        foreach (AgendaKegiatan::pluck('foto') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Halaman Kustom
        foreach (HalamanKustom::pluck('gambar') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Maklumat Pelayanan
        foreach (MaklumatPelayanan::pluck('gambar') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Survei Kepuasan Masyarakat (SKM)
        foreach (SurveiSkm::pluck('file_laporan') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Anggaran / APBDes (Transparansi)
        foreach (AnggaranRealisasi::pluck('gambar') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }
        foreach (AnggaranRealisasi::pluck('file_lampiran') as $f) {
            $rel = self::getRelativePublicPath($f);
            if ($rel) {
                $dbFiles[] = $rel;
            }
        }

        // Profil Kelurahan (logo, hero, lurah)
        $profil = ProfilKelurahan::first();
        if ($profil) {
            foreach ([$profil->logo, $profil->hero_image, $profil->lurah_foto] as $f) {
                $rel = self::getRelativePublicPath($f);
                if ($rel) {
                    $dbFiles[] = $rel;
                }
            }
        }

        return array_values(array_unique(array_filter($dbFiles)));
    }

    /**
     * Memindai seluruh berkas di storage public yang tidak terdaftar di database (berkas sampah/orphaned).
     *
     * @return array<int, array{path: string, size: int, size_formatted: string, last_modified: string}>
     */
    public static function getOrphanedFiles(): array
    {
        $activeFiles = self::getAllActiveDatabaseFiles();
        $allDiskFiles = Storage::disk('public')->allFiles();

        $orphans = [];
        foreach ($allDiskFiles as $file) {
            // Abaikan file sistem
            if (str_ends_with($file, '.gitignore') || str_ends_with($file, '.gitkeep')) {
                continue;
            }

            if (! in_array($file, $activeFiles, true)) {
                $size = Storage::disk('public')->exists($file) ? Storage::disk('public')->size($file) : 0;
                $lastModified = Storage::disk('public')->exists($file) ? Storage::disk('public')->lastModified($file) : 0;

                $orphans[] = [
                    'path' => $file,
                    'size' => $size,
                    'size_formatted' => self::formatBytes($size),
                    'last_modified' => $lastModified ? date('d-m-Y H:i:s', $lastModified) : '-',
                ];
            }
        }

        return $orphans;
    }

    /**
     * Menghapus seluruh berkas sampah/orphaned dari storage folder public.
     *
     * @return array{deleted_count: int, bytes_freed: int, bytes_freed_formatted: string, deleted_files: array<int, string>}
     */
    public static function cleanOrphanedFiles(): array
    {
        $orphans = self::getOrphanedFiles();
        $deleted = [];
        $bytesFreed = 0;

        foreach ($orphans as $orphan) {
            $path = $orphan['path'];
            if (Storage::disk('public')->exists($path)) {
                $bytesFreed += $orphan['size'];
                Storage::disk('public')->delete($path);
                $deleted[] = $path;
            }
        }

        return [
            'deleted_count' => count($deleted),
            'bytes_freed' => $bytesFreed,
            'bytes_freed_formatted' => self::formatBytes($bytesFreed),
            'deleted_files' => $deleted,
        ];
    }

    /**
     * Format byte ke string yang mudah dibaca (KB, MB, GB).
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    /**
     * Menghasilkan binary berkas PDF standar A4 yang valid secara dinamis (pure PHP)
     */
    public static function generatePdfContent(string $title, string $category = 'Umum', ?string $docNum = '', ?string $desc = ''): string
    {
        $cleanTitle = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);
        $cleanCategory = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $category ?: 'Umum');
        $cleanDocNum = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $docNum ?: '');
        $cleanDesc = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', strip_tags($desc ?: ''));

        $content = "%PDF-1.4\n";
        $offsets = [];

        // 1 0 obj - Catalog
        $offsets[1] = strlen($content);
        $content .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

        // 2 0 obj - Pages
        $offsets[2] = strlen($content);
        $content .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";

        // Lines of text and graphics in page
        $lines = [];
        $lines[] = 'BT /F1 15 Tf 50 780 Td (PEMERINTAH KABUPATEN PROBOLINGGO) Tj ET';
        $lines[] = 'BT /F1 13 Tf 50 760 Td (KECAMATAN KRAKSAAN - KELURAHAN KRAKSAAN WETAN) Tj ET';
        $lines[] = 'BT /F2 9 Tf 50 742 Td (Jl. Mayjend Sutoyo No. 01, Kraksaan Wetan, Probolinggo 67282 | Telp: 0811-3000-0000) Tj ET';
        $lines[] = '0.5 w 50 732 m 545 732 l S';
        $lines[] = '1.5 w 50 730 m 545 730 l S';

        $lines[] = 'BT /F1 12 Tf 50 690 Td (DOKUMEN RESMI DIGITAL PELAYANAN PUBLIK) Tj ET';

        $chunk1 = substr($cleanTitle, 0, 70);
        $chunk2 = substr($cleanTitle, 70, 70);
        $lines[] = 'BT /F1 11 Tf 50 660 Td ('.addcslashes($chunk1, '()\\').') Tj ET';
        if (! empty($chunk2)) {
            $lines[] = 'BT /F1 11 Tf 50 644 Td ('.addcslashes($chunk2, '()\\').') Tj ET';
        }

        $y = empty($chunk2) ? 625 : 607;
        if (! empty($cleanDocNum)) {
            $lines[] = "BT /F2 10 Tf 50 $y Td (Nomor: ".addcslashes($cleanDocNum, '()\\').') Tj ET';
            $y -= 18;
        }
        if (! empty($cleanCategory)) {
            $lines[] = "BT /F2 10 Tf 50 $y Td (Kategori / Klasifikasi: ".addcslashes($cleanCategory, '()\\').') Tj ET';
            $y -= 22;
        }

        $lines[] = '0.5 w 50 '.($y + 8).' m 545 '.($y + 8).' l S';
        $lines[] = "BT /F1 10 Tf 50 $y Td (Uraian / Ringkasan Dokumen:) Tj ET";
        $y -= 18;

        if (! empty($cleanDesc)) {
            $words = explode(' ', $cleanDesc);
            $curLine = '';
            foreach ($words as $w) {
                if (strlen($curLine.' '.$w) > 80) {
                    $lines[] = "BT /F2 9.5 Tf 50 $y Td (".addcslashes(trim($curLine), '()\\').') Tj ET';
                    $y -= 15;
                    $curLine = $w;
                    if ($y < 150) {
                        break;
                    }
                } else {
                    $curLine .= ' '.$w;
                }
            }
            if (! empty($curLine) && $y >= 150) {
                $lines[] = "BT /F2 9.5 Tf 50 $y Td (".addcslashes(trim($curLine), '()\\').') Tj ET';
                $y -= 20;
            }
        }

        // Box legal
        $lines[] = '0.75 w 0.94 0.94 0.94 rg 50 75 495 55 re f';
        $lines[] = '0.75 w 0.2 0.5 0.3 RG 50 75 495 55 re S';
        $lines[] = 'BT /F1 8 Tf 60 115 Td (CATATAN LEGALITAS BERKAS RESMI) Tj ET';
        $lines[] = 'BT /F2 8 Tf 60 102 Td (Dokumen ini merupakan arsip digital resmi dari Portal Resmi Kelurahan Kraksaan Wetan.) Tj ET';
        $lines[] = 'BT /F2 8 Tf 60 90 Td (Dapat diunduh dan digunakan untuk kepentingan permohonan, transparansi, serta validasi warga.) Tj ET';

        $streamText = implode("\n", $lines)."\n";
        $streamLen = strlen($streamText);

        // 4 0 obj - Stream content
        $offsets[4] = strlen($content);
        $content .= "4 0 obj\n<< /Length $streamLen >>\nstream\n".$streamText."endstream\nendobj\n";

        // 3 0 obj - Page
        $offsets[3] = strlen($content);
        $content .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> >>\nendobj\n";

        // 5 0 obj - Font Bold
        $offsets[5] = strlen($content);
        $content .= "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n";

        // 6 0 obj - Font Regular
        $offsets[6] = strlen($content);
        $content .= "6 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        // Cross-Reference Table
        $xrefOffset = strlen($content);
        $content .= "xref\n0 7\n0000000000 65535 f \n";
        for ($i = 1; $i <= 6; $i++) {
            $content .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $content .= "trailer\n<< /Size 7 /Root 1 0 R >>\nstartxref\n$xrefOffset\n%%EOF\n";

        return $content;
    }

    /**
     * Memastikan berkas PDF fisik tersedia di disk public.
     * Jika berkas fisik tidak ada di disk atau kosong, secara otomatis dibuatkan berkas PDF resmi yang valid.
     */
    public static function ensureValidPdfFile(?string $pathOrUrl, string $title, string $category = 'Umum', ?string $docNum = '', ?string $desc = ''): string
    {
        $relativePath = self::getRelativePublicPath($pathOrUrl);

        if (! empty($relativePath) && Storage::disk('public')->exists($relativePath) && Storage::disk('public')->size($relativePath) > 100) {
            return $relativePath;
        }

        // Tentukan target penyimpanan
        $targetPath = ! empty($relativePath) ? $relativePath : ('uploads/'.Str::random(24).'.pdf');
        if (! str_ends_with(strtolower($targetPath), '.pdf')) {
            $targetPath .= '.pdf';
        }

        $pdfContent = self::generatePdfContent($title, $category, $docNum, $desc);
        Storage::disk('public')->put($targetPath, $pdfContent);

        return $targetPath;
    }
}
