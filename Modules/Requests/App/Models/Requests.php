<?php

namespace Modules\Requests\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Requests\Database\factories\RequestsFactory;
 use Modules\Cities\Entities\Cities;
 use App\Models\States;
use Modules\Donors\Entities\Donor;
class Requests extends Model
{
    use HasFactory;

    protected $table="requests";
    protected $fillable=['user_id','blood_group','state_id','city_id','ucouncil_id', 'payment_screenshot', 'status'];


    public function user()
    {
       return $this->hasOne(Donor::class, 'id', 'user_id');
    }

    public function state()
    {
       return $this->hasOne(States::class, 'id', 'state_id');
    }

    public function city()
    {
       return $this->hasOne(Cities::class, 'id', 'city_id');
    }

    
}
