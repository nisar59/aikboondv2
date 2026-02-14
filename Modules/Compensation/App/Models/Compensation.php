<?php

namespace Modules\Compensation\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Compensation\Database\factories\CompensationFactory;
use App\Models\User;
class Compensation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table='compensation';
    protected $fillable = ['type','user_id','registrations_made','served_cases','price_per_head','total_amount','status','paid_attachment'];
    protected $with=['user'];
    
    protected static function newFactory(): CompensationFactory
    {
        //return CompensationFactory::new();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
