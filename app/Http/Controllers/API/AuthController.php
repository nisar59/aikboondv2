<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VerificationMsgs;
use Illuminate\Http\Request;
use Modules\Donors\Entities\Donor;
use Throwable;
use Validator;
use Auth;
use DB;
class AuthController extends Controller
{
    function __construct()
    {
        auth()->setDefaultDriver('donors');
    }
    public function login(Request $req)
    {
        $res=['success'=>true,'message'=>null,'errors'=>[],'data'=>null];
        DB::beginTransaction();
        try {
            $val = Validator::make($req->all(), [
                'phone'=>'required|min:11|max:11',
                'verification_code'=>'required | min:4 | max:4',
            ]);
            if ($val->fails()) {
                $res=['success'=>false,'message'=>'Required fields are missing','errors'=>$val->messages()->all(),'data'=>null];
                return response()->json($res);
            }
            $phone=$req->phone;
            $phone=preg_replace('/[^0-9]/', "", $phone);
            $start_with_0=str_starts_with($phone, '03');
            if($start_with_0){
                $phone = substr_replace($phone,'92',0,1);
            }
            $check_verification=VerificationMsgs::where(['phone'=>$phone, 'code'=>$req->verification_code])->first();
            if($check_verification==null){
                $res=['success'=>false,'message'=>'Wrong verification code','errors'=>[],'data'=>null];
                return response()->json($res);
            }
            $donor=Donor::where('phone', $phone)->first();
            if(!$donor){
                $donor=Donor::create(['phone'=>$phone]);
            }
            $token = Auth::login($donor);
            $user = Auth::user();
            $user['access_token']=$token;
            $data=[
                'user'=>$user
            ];
            $res=['success'=>true,'message'=>'You have successfully Loggedin','errors'=>[],'data'=>$data];
            DB::commit();
            return response()->json($res);
        } catch (Exception $e) {
            DB::rollback();
            $res=['success'=>false,'message'=>'Something went wrong with this error: '.$e->getMessage(),'errors'=>[],'data'=>null];
            return response()->json($res);

        } catch(Throwable $e){
            DB::rollback();
            $res=['success'=>false,'message'=>'Something went wrong with this error: '.$e->getMessage(),'errors'=>[],'data'=>null];
            return response()->json($res);
        }

    }


    public function refresh()
    {
        $res=['success'=>true,'message'=>null,'errors'=>[],'data'=>null];

        try {
            if(!$token = Auth::refresh())
            {
                $res=['success'=>false,'message'=>'Unauthorized, Login again','errors'=>[],'data'=>null];
            }
            else{
                Auth::setToken($token);
                $user = Auth::user();
                $user['access_token']=$token;
                $data=[
                    'user'=>$user
                ];
                $res=['success'=>true,'message'=>'Authentication Successfull','errors'=>[],'data'=>$data];
            }

            return response()->json($res);

        } catch (Exception $e) {
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException){
                $res=['success'=>false,'message'=>'Something went wrong with this error: '.$e->getMessage(),'errors'=>[],'data'=>null];
                return response()->json($res, 403);
            }

            $res=['success'=>false,'message'=>'Something went wrong with this error: '.$e->getMessage(),'errors'=>[],'data'=>null];
            return response()->json($res);

        } catch (Throwable $e){

            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException){
                $res=['success'=>false,'message'=>'Something went wrong with this error: '.$e->getMessage(),'errors'=>[],'data'=>null];
                return response()->json($res, 403);
            }

            $res=['success'=>false,'message'=>'Something went wrong with this error: '.$e->getMessage(),'errors'=>[],'data'=>null];
            return response()->json($res);


        }

    }


}
