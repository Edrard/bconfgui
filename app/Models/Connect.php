<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DevicesConfig;

class Connect extends Model
{
    protected $table = 'connect';
    protected $primaryKey = 'id';
    protected $connection = 'mysql_bconf';

    public function __construct(...$args){
        $this->setConnection(env('DB_CONNECTION_BCONF','mysql_bconf'));
        parent::__construct(...$args);
    }

    public function deviceConfigs(): HasMany
    {
        return $this->hasMany(DevicesConfig::class);
    }
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value;
        if(!$value){
            $this->attributes['description'] = $this->attributes['connect'];
        }
    }
}
