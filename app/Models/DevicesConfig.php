<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\{Connect,Group,Model,Type};

class DevicesConfig extends EModel
{
    protected $table = 'devices_config';
    protected $primaryKey = 'id';
    protected $connection = 'mysql_bconf';
    protected $guarded = [];

    public function __construct(...$args){
        $this->setConnection(env('DB_CONNECTION_BCONF','mysql_bconf'));
        parent::__construct(...$args);
    }

    public function connect(): BelongsTo
    {
        return $this->belongsTo(Connect::class);
    }
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }
    public function setConfigEnableCommandAttribute($value)
    {
        $this->attributes['config_enable_command'] = $value;
        if(!$value){
            $this->attributes['config_enable_command'] = '';
        }
    }
    public function setConfigEnablePassAttribute($value)
    {
        $this->attributes['config_enable_pass'] = $value;
        if(!$value){
            $this->attributes['config_enable_pass'] = '';
        }
    }
    public function setConfigEnablePassStrAttribute($value)
    {
        $this->attributes['config_enable_pass_str'] = $value;
        if(!$value){
            $this->attributes['config_enable_pass_str'] = '';
        }
    }
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value;
        if(!$value){
            $this->attributes['description'] = $this->attributes['name'];
        }
    }
    public function setDateAttribute($value)
    {
        dd($value);
        if(!$value){
            $this->attributes['description'] = $this->attributes['name'];
        }
    }
}
