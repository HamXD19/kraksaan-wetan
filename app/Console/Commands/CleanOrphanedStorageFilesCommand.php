<?php

namespace App\Console\Commands;

use App\Services\FileStorageHelper;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('storage:clean-orphans {--dry-run : Tampilkan daftar berkas sampah tanpa menghapusnya}')]
#[Description('Pindai dan bersihkan berkas-berkas foto/dokumen pada storage yang tidak tercatat di database')]
class CleanOrphanedStorageFilesCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memindai berkas storage lokal...');

        $orphans = FileStorageHelper::getOrphanedFiles();

        if (empty($orphans)) {
            $this->info('Penyimpanan bersih! Tidak ada berkas sampah/orphaned yang ditemukan.');

            return self::SUCCESS;
        }

        $this->warn(sprintf('Ditemukan %d berkas sampah/orphaned:', count($orphans)));

        $tableData = array_map(function ($item) {
            return [
                $item['path'],
                $item['size_formatted'],
                $item['last_modified'],
            ];
        }, $orphans);

        $this->table(['Path Berkas', 'Ukuran', 'Terakhir Diubah'], $tableData);

        if ($this->option('dry-run')) {
            $totalSize = array_sum(array_column($orphans, 'size'));
            $this->comment(sprintf(
                'Mode DRY-RUN aktif: %d berkas (%s) tidak dihapus. Jalankan tanpa --dry-run untuk menghapusnya.',
                count($orphans),
                FileStorageHelper::formatBytes($totalSize)
            ));

            return self::SUCCESS;
        }

        if (! $this->confirm('Apakah Anda yakin ingin menghapus berkas-berkas di atas dari storage fisik?', true)) {
            $this->info('Pembersihan dibatalkan.');

            return self::SUCCESS;
        }

        $result = FileStorageHelper::cleanOrphanedFiles();

        $this->info(sprintf(
            'Sukses! Berhasil menghapus %d berkas sampah. Ruang disk yang dibebaskan: %s.',
            $result['deleted_count'],
            $result['bytes_freed_formatted']
        ));

        return self::SUCCESS;
    }
}
