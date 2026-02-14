<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = ['id'];
    protected $table='settings';
    protected static function newFactory()
    {
        return \Modules\Settings\Database\factories\SettingsFactory::new();
    }


    public function getCompensationForAttribute($value)
    {
        return (array) json_decode($value);
    }
}
