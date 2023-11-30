<?php

namespace GoCPA\LaravelMaxsizeFinder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GoCPA\LaravelMaxsizeFinder\LaravelMaxsizeFinder
 */
class LaravelMaxsizeFinder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \GoCPA\LaravelMaxsizeFinder\LaravelMaxsizeFinder::class;
    }
}
