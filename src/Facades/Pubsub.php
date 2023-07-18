<?php

namespace VergilLai\LaravelGoeasy\Facades;

use Illuminate\Support\Facades\Facade;

class Pubsub extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'goeasy-pubsub';
    }
}