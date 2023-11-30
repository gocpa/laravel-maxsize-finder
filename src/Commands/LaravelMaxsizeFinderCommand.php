<?php

namespace GoCPA\LaravelMaxsizeFinder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileAttributes;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'gocpa:laravel-maxsize-finder',
    description: 'Показывает большие файлы в s3 хранилище',
)]
class LaravelMaxsizeFinderCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption(
                'disk',
                null,
                InputOption::VALUE_OPTIONAL,
                'Выбранный диск',
                's3'
            );
        $this
            ->addOption(
                'location',
                null,
                InputOption::VALUE_OPTIONAL,
                'Проверить только в папке',
                ''
            );
        $this
            ->addOption(
                'size',
                null,
                InputOption::VALUE_OPTIONAL,
                'Отображать только файлы больше выбранного размера',
                10485760 // 10 MB in bytes
            );
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $minSize = $this->option('size');
        $disk = $this->option('disk');
        $location = $this->option('location');

        $fileSizeList = $this->getFileSizeList($location, $disk);
        $totalSize = $this->formatBytes($fileSizeList->sum());

        $this->info('Количество файлов в хранилище: '.$fileSizeList->count());
        $this->info('Общий размер файлов: '.$totalSize);

        $this->displayLargeFiles($fileSizeList, $minSize);

        return self::SUCCESS;
    }

    /** Получает список файлов */
    public function getFileSizeList(string $location, string $disk): Collection
    {
        $disk = Storage::disk($disk);

        return collect($disk->listContents($location, true))
            ->filter(fn ($file) => is_a($file, FileAttributes::class))
            ->mapWithKeys(fn (FileAttributes $fileAttributes) => [$fileAttributes->path() => $fileAttributes->fileSize()]);
    }

    /** Отображение файлов большого размера */
    public function displayLargeFiles(Collection $fileSizeList, int $minSize): void
    {
        $fileSizeList
            ->sortByDesc(fn (int $size) => $size)
            ->filter(fn ($size) => $size > $minSize)
            ->map(fn ($size) => $this->formatBytes($size))
            ->tap(fn () => $this->components->twoColumnDetail('<fg=yellow>Путь к файлу</>', '<fg=yellow>Размер</>'))
            ->each(fn (string $humanSize, string $file) => $this->components->twoColumnDetail($file, $humanSize));
    }

    /**
     * Format bytes to kb, mb, gb, tb
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $index = (int) floor(log($bytes, 1024));
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        return number_format($bytes / pow(1024, $index), $precision, '.', ' ').' '.$units[$index];
    }
}
