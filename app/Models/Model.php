<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EModel;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Model extends EModel
{
    protected $table = 'model';
    protected $primaryKey = 'id';
    protected $connection = 'mysql_bconf';

    public function __construct(...$args){
        $this->setConnection(env('DB_CONNECTION_BCONF','mysql_bconf'));
        parent::__construct(...$args);
    }

    public function deviceConfig(): HasMany
    {
        return $this->hasMany(DevicesConfig::class,'model','id');
    }
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value;
        if(!$value){
            $this->attributes['description'] = $this->attributes['model'];
        }
    }
}
