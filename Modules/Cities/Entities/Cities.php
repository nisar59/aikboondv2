<?php

namespace Modules\Cities\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cities extends Model
{
    use HasFactory;

    protected $fillable = ['state_id','country_id','name'];
    protected $table='cities';
    
    protected static function newFactory()
    {
        return \Modules\Cities\Database\factories\CitiesFactory::new();
    }
}
