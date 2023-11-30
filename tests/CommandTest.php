<?php

use GoCPA\LaravelMaxsizeFinder\Commands\LaravelMaxsizeFinderCommand;
use Illuminate\Support\Facades\Storage;

it('Команда получает корректный список файлов', function () {
    Storage::fake('s3');

    // Создание тестовых файлов
    Storage::disk('s3')->put('example.txt', 'contents');
    Storage::disk('s3')->put('another.txt', 'more contents');

    $command = new LaravelMaxsizeFinderCommand();
    $fileSizeList = $command->getFileSizeList();

    expect($fileSizeList)->toHaveCount(2);
});

it('Команда выполняется и выводит корректную информацию', function () {
    Storage::fake('s3');

    // Здесь мокаем файлы на диске s3, например:
    Storage::disk('s3')->put('example.txt', 'contents1');
    Storage::disk('s3')->put('example2.txt', 'contents2');

    $this->artisan('gocpa:laravel-maxsize-finder --no-interaction')
        ->expectsOutput('Количество файлов в хранилище: 2')
        ->expectsOutput('Общий размер файлов: 18.00 B') // Соответствующий вывод размера
        ->assertExitCode(0);
});

it('Байты выводятся по-человечески', function () {
    expect(LaravelMaxsizeFinderCommand::formatBytes(1024))->toEqual('1 024.00 B');
    expect(LaravelMaxsizeFinderCommand::formatBytes(1048576))->toEqual('1 024.00 KiB');
    // Добавьте здесь дополнительные проверки для других размеров и единиц
});
