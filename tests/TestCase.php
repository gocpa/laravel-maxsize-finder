<?php

namespace GoCPA\LaravelMaxsizeFinder\Tests;

use GoCPA\LaravelMaxsizeFinder\LaravelMaxsizeFinderServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelMaxsizeFinderServiceProvider::class,
        ];
    }
}
