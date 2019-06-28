<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CommonUtils extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'CommonService';
    }

}