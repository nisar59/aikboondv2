<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobs extends Model
{
    use HasFactory;

    protected $table='cron_jobs';
    protected $fillable=['cron_job_for','cron_job_description','status','message'];

    public static function COMPENSATION()
    {
       return self::where('cron_job_for', 0)->first();
    }

    public static function INIT_COMPENSATION_JOB()
    {
        $job=self::COMPENSATION();
        if($job==null){
           return self::create([
                    'cron_job_for'=>0,
                    'cron_job_description'=>'Cron job for calculating Compensation',
                    'status'=>1,
                    'message'=>'Cron job is initiated'
           ]);
        }else{
            return $job->update([
                    'status'=>1,
                    'message'=>'Cron job is initiated'
            ]);
        }
    }

    public static function COMPLETE_COMPENSATION_JOB()
    {
        $job=self::COMPENSATION();
        if($job!=null)
            return $job->update([
                    'status'=>2,
                    'message'=>'Cron job is successfully compeleted'
            ]);
        
    }




    public static function RECORD_EXCEPTION_IN_COMPENSATION_JOB($error)
    {
        $job=self::COMPENSATION();
        if($job!=null)
            return $job->update([
                    'status'=>3,
                    'message'=>$error
            ]);
        
    }



}
