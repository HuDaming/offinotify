<?php

namespace Tantupix\Offinotify;

use Illuminate\Support\Facades\Facade;

class OffinotifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'offinotify';
    }
}