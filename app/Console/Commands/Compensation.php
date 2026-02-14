<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Compensation\App\Models\Compensation;
use Modules\Donors\Entities\Donor;
use App\Models\CronJobs;
use App\Models\User;
use Carbon\Carbon;
use Throwable;
use DB;

class GenerateCompensation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reg:compensation {month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for calculating Compensation';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        try {
            $month = $this->argument('month');
            $exact_month=Carbon::parse($month);

            $get_month=$exact_month->month;
            $get_year=$exact_month->year;

            CronJobs::INIT_COMPENSATION_JOB();
            $users=User::all();
            $sett=Settings();
            $compensation_for=$sett->compensation_for;

            foreach ($users as $key => $user) {
               if($user->hasAnyRole($compensation_for)){
                    $donors=Donor::where('user_id', $user->id)->whereMonth('created_at', $get_month)->whereYear('created_at', $get_year)->count();

                    $compen=Compensation::where(['type'=>0, 'user_id'=>$user->id])->whereMonth('created_at', $get_month)->whereYear('created_at', $get_year)->first();

                    if($compen==null){
                        Compensation::create([
                            'type'=>0,
                            'user_id'=>$user->id,
                            'registrations_made'=>$donors,
                            'served_cases'=>0,
                            'price_per_head'=>$sett->mini_compensation ,
                            'total_amount'=> (int) $sett->mini_compensation * $donors,
                            'status'=>0,
                            'paid_attachment'=>null

                        ]);
                    }else{
                        $compen->update([
                            'registrations_made'=>$donors,
                            'price_per_head'=>$sett->mini_compensation ,
                            'total_amount'=> (int) $sett->mini_compensation * $donors,
                            ]);
                    }

               }
            }

            CronJobs::COMPLETE_COMPENSATION_JOB();
        } catch (Exception $e) {
             CronJobs::RECORD_EXCEPTION_IN_COMPENSATION_JOB($e->getMessage());
        } catch(Throwable $e){
             CronJobs::RECORD_EXCEPTION_IN_COMPENSATION_JOB($e->getMessage());
        }


    }
}
