<?php
/**
 * User: Andy
 * Date: 19/07/15
 * Time: 12:15
 */

namespace AV\LaravelForm\Facade;

use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AV\LaravelForm\FormBuilder';
    }
}
