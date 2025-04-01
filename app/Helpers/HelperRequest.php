<?php

namespace App\Helpers;

class HelperRequest
{
    static $unset = ['_token','_component_name','_method'];

    public static function cleans(array $data, $unset = []): array {
        foreach(array_merge(static::$unset,$unset) as $val){
            if(isset($data[$val])){
                unset($data[$val]);
            }
        }
        return $data;
    }

}
