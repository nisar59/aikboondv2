<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Countries;
use App\Models\States;
use Modules\Cities\Entities\Cities;
use Modules\Areas\Entities\Areas;
use App\Models\VerificationMsgs;
use Modules\Donors\Entities\Donor;
use Modules\UnionCouncils\App\Models\UnionCouncils;
use Throwable;
use Artisan;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user=Auth::user();

        $donors=Donor::query();

        if($user->hasRole('super-admin') || $user->can('donors.view-all')){
       }
       elseif(!$user->hasRole('super-admin') && $user->can('donors.view-by-state')){
            $donors->where('state_id', $user->state_id);
       }
       elseif(!$user->hasRole('super-admin') && $user->can('donors.view-by-city')){
            $donors->where('city_id', $user->city_id);
       }
       else{
            $donors->where('ucouncil_id', $user->ucouncil_id);
       }

       $donors=$donors->count();


        return view('home', compact('donors'));
    }




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function fetchStates(Request $req){
         try {
        $data['states'] =States::where("country_id", $req->country_id)->get(["name", "id"]);
        return response()->json($data);
        }
        catch(Exception $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }catch(Throwable $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }


    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function fetchCity(Request $req){
        try {
        $data['cities'] =Cities::where("state_id", $req->state_id)->get(["name", "id"]);
        return response()->json($data);
        }
        catch(Exception $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }catch(Throwable $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }

    }



}
