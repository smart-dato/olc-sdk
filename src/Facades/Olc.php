<?php

namespace SmartDato\Olc\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SmartDato\Olc\Olc
 */
class Olc extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \SmartDato\Olc\Olc::class;
    }
}
