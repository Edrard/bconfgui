<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql_bconf';

    public function __construct(...$args){
        $this->setConnection(env('DB_CONNECTION_BCONF','mysql_bconf'));
        parent::__construct(...$args);
    }
}
