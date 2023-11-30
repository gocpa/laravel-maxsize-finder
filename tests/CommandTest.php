<?php

use GoCPA\LaravelMaxsizeFinder\Commands\LaravelMaxsizeFinderCommand;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

beforeEach(function () {
    Storage::fake('s3');

    Storage::disk('s3')->put('public/small_file.txt', str_repeat('a', 1024)); // 1 KB
    Storage::disk('s3')->put('public/large_file.txt', str_repeat('a', 10485760)); // 10 MB
});

it('displays file counts and total size', function () {
    artisan(LaravelMaxsizeFinderCommand::class, ['--disk' => 's3'])
        ->expectsOutput('Количество файлов в хранилище: 2')
        ->expectsOutput('Общий размер файлов: 10.00 MiB')
        ->assertExitCode(0);
});

it('displays total size', function () {
    $class = (new LaravelMaxsizeFinderCommand)->getFileSizeList('', 's3')->toArray();
    expect($class)->toBe([
        'public/large_file.txt' => 10485760,
        'public/small_file.txt' => 1024,
    ]);
});
