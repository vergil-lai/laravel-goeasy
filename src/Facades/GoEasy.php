<?php

namespace VergilLai\LaravelGoeasy\Facades;

use Illuminate\Support\Facades\Facade;

class GoEasy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'goeasy';
    }
}