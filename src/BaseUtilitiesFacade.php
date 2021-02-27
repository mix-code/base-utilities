<?php

namespace MixCode\BaseUtilities;

use Illuminate\Support\Facades\Facade;

class BaseUtilitiesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'base-utilities';
    }
}