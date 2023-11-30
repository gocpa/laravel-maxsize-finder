<?php

namespace GoCPA\LaravelMaxsizeFinder;

use GoCPA\LaravelMaxsizeFinder\Commands\LaravelMaxsizeFinderCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelMaxsizeFinderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-maxsize-finder')
            ->hasCommand(LaravelMaxsizeFinderCommand::class);
    }
}
