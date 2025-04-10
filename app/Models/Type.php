<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    protected $table = 'type';
    protected $primaryKey = 'id';
    protected $connection = 'mysql_bconf';

    public function __construct(...$args){
        $this->setConnection(env('DB_CONNECTION_BCONF','mysql_bconf'));
        parent::__construct(...$args);
    }

    public function deviceConfig(): HasMany
    {
        return $this->hasMany(DevicesConfig::class,'type','id');
    }
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value;
        if(!$value){
            $this->attributes['description'] = $this->attributes['type'];
        }
    }
}
