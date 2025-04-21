<?php

namespace App\Helpers;

class DeviceConfigCheckerHelper
{
    static private $example_device = ['ip','port','login','password','group','type','connect','model','config_enable','config_search'];

    static public function check($config){
        foreach(static::$example_device as $key){
            if(!isset($config[$key])){
                return $key;
            }
        }
        return FALSE;
    }
}
