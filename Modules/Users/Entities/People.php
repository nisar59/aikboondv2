<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class People extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'otp', 'status'];
    protected $table="people";
    
    protected static function newFactory()
    {
        return \Modules\Doctor\Database\factories\DoctorFactory::new();
    }
}
