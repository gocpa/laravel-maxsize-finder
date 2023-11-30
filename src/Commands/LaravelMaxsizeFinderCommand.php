<?php

namespace GoCPA\LaravelMaxsizeFinder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileAttributes;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'gocpa:laravel-maxsize-finder',
    description: 'Command description',
)]
class LaravelMaxsizeFinderCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fileSizeList = $this->getFileSizeList();
        $totalSize = self::formatBytes($fileSizeList->sum());

        $this->info('Количество файлов в хранилище: '.$fileSizeList->count());
        $this->info('Общий размер файлов: '.$totalSize);

        $fileSizeList
            ->sortByDesc(fn (int $size) => $size)
            ->filter(fn ($size) => $size > 10485760)
            ->map(fn ($size) => self::formatBytes($size))
            ->tap(fn () => $this->components->twoColumnDetail('<fg=yellow>Путь к файлу</>', '<fg=yellow>Размер</>'))
            ->each(fn (string $humanSize, string $file) => $this->components->twoColumnDetail($file, $humanSize));

        return self::SUCCESS;
    }

    /** Получает список файлов */
    public function getFileSizeList(): Collection
    {
        $disk = Storage::disk('s3');
        $fileSizeList = collect($disk->listContents('', true))
            ->filter(fn ($file) => is_a($file, FileAttributes::class))
            ->mapWithKeys(fn (FileAttributes $fileAttributes) => [$fileAttributes->path() => $fileAttributes->fileSize()]);

        return $fileSizeList;
    }

    /**
     * Format bytes to kb, mb, gb, tb
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return number_format(round($bytes, $precision), $precision, '.', ' ').' '.$units[$i];
    }
}
