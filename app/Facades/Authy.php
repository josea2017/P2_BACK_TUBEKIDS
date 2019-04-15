<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Authy extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'authy';
    }


}