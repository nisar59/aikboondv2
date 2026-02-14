<?php

namespace Modules\Donors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
 use Modules\Cities\Entities\Cities;
 use App\Models\States;

class Donor extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table='donors';

    protected $fillable = ['name','phone','pin','dob','blood_group','last_donate_date','image','country_id','state_id','city_id','address','status'];
    protected $with=['state', 'city'];

    protected static function newFactory()
    {
        return \Modules\Donors\Database\factories\DonorFactory::new();
    }


    public function state()
    {
       return $this->hasOne(States::class, 'id', 'state_id');
    }

    public function city()
    {
       return $this->hasOne(Cities::class, 'id', 'city_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



}
