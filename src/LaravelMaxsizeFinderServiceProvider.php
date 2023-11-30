<?php

namespace GoCPA\LaravelMaxsizeFinder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use GoCPA\LaravelMaxsizeFinder\Commands\LaravelMaxsizeFinderCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-maxsize-finder_table')
            ->hasCommand(LaravelMaxsizeFinderCommand::class);
    }
}
