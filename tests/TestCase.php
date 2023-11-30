<?php

namespace GoCPA\LaravelMaxsizeFinder\Tests;

use GoCPA\LaravelMaxsizeFinder\LaravelMaxsizeFinderServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'GoCPA\\LaravelMaxsizeFinder\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelMaxsizeFinderServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-maxsize-finder_table.php.stub';
        $migration->up();
        */
    }
}
