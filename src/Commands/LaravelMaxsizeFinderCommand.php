<?php

namespace GoCPA\LaravelMaxsizeFinder\Commands;

use Illuminate\Console\Command;

class LaravelMaxsizeFinderCommand extends Command
{
    public $signature = 'laravel-maxsize-finder';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
