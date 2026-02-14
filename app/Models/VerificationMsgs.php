<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationMsgs extends Model
{
    use HasFactory;

    protected $table='verification_msgs';
    protected $fillable=['phone','code'];
}
