<?php

namespace Modules\Updates\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Updates\Database\factories\UpdatesFactory;

class Updates extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected  $table='updates';
    protected $fillable = ['name', 'image', 'time', 'status'];

    protected static function newFactory(): UpdatesFactory
    {
        //return UpdatesFactory::new();
    }
}
