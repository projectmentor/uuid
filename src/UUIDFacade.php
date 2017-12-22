<?php

namespace projectmentor\UUID;

use Illuminate\Support\Facades\Facade;

class UUIDFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'uuid';
    }
}
