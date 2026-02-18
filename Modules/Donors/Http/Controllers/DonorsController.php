<?php

namespace Modules\Donors\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Countries;
use App\Models\States;
use Modules\Cities\Entities\Cities;
use Modules\Areas\Entities\Areas;
use App\Models\VerificationMsgs;
use Modules\AddressesAndTowns\Entities\AddressesAndTowns;
use Modules\Donors\Entities\Donor;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Throwable;
use Auth;
use DB;
use Carbon\Carbon;
class DonorsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       $req=request();
       $user=Auth::user();
         if ($req->ajax()) {

            $strt=$req->start;
            $length=$req->length;

            $donors=Donor::query();

             if ($req->name != null) {
            $donors->where('name','LIKE','%'.$req->name.'%');
            }
             if ($req->state_id != null) {
            $donors->where('state_id', $req->state_id);
            }
             if ($req->city_id != null) {
            $donors->where('city_id', $req->city_id);
            }
            if ($req->address != null) {
            $donors->where('address','LIKE','%'.$req->address.'%');
            }
            if ($req->phone != null) {
            $donors->where('phone','LIKE','%'.$req->phone.'%');
            }
            if ($req->last_donate_date != null) {
            $donors->where('last_donate_date',$req->last_donate_date);
            }
            $total=$donors->count();

           $donors=$donors->offset($strt)->limit($length)->get();

           return DataTables::of($donors)
           ->setOffset($strt)
           ->with([
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total
           ])
           ->addColumn('action',function ($row){
               $action='';
               if(Auth::user()->can('donors.edit')){
               $action.='<a class="btn btn-primary btn-sm m-1" href="'.url('/donors/edit/'.$row->id).'"><i class="fas fa-pencil-alt"></i></a>';
            }
            if(Auth::user()->can('donors.delete')){
               $action.='<a class="btn btn-danger btn-sm m-1 verify-prompt" href="javascript:void(0)" data-prompt-msg="Are you sure you want to delete this donor?" data-href="'.url('/donors/destroy/'.$row->id).'"><i class="fas fa-trash-alt"></i></a>';
           }
               return $action;
           })

            ->editColumn('dob', function ($row) {
                return Carbon::parse($row->dob)->age . ' Years';
            })

            ->editColumn('state_id', function ($row) {
                if($row->state()->exists()){
                    return $row->state->name;
                }
            })

            ->editColumn('city_id', function ($row) {
                if($row->city()->exists()){
                    return $row->city->name;
                }
            })

           ->editColumn('last_donate_date',function($row)
             {
                 return Carbon::parse($row->last_donate_date)->format('d-m-Y');
             })
           ->rawColumns(['action'])
           ->make(true);
        }
         $states=States::where('country_id',167)->get();
         $cities=Cities::where('country_id',167)->get();
        return view('donors::index',compact('states','cities'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $states=States::where('country_id',167)->get();
        return view('donors::create',compact('states'));
    }



    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $req)
    {
        $req->validate([
            'name'=>'required',
            'phone'=>'required|min:11|max:11|unique:donors,phone',
            'verification_code'=>'required | min:6 | max:6',
            'dob'=>'required',
            'blood_group'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'ucouncil_id'=>'required',
            'address'=>'required',
        ]);
            DB::beginTransaction();
         try{


            $phone=$req->phone;
            $phone=preg_replace('/[^0-9]/', "", $phone);

            $start_with_0=str_starts_with($phone, '03');

            if($start_with_0){
                $phone = substr_replace($phone,'92',0,1);
            }


            $check_verification=VerificationMsgs::where(['phone'=>$phone, 'code'=>$req->verification_code])->first();

            if($check_verification==null){
                return redirect()->back()->withInput()->with('error', 'Wrong verification code');
            }
            $min_age=Settings()->mini_age;
            $max_age=Settings()->max_age;

            $age= Carbon::parse($req->dob)->age;

            if($age < $min_age || $age > $max_age){
                return redirect()->back()->withInput()->with('error', 'Donor should not younger than :'.$min_age.' years and elder than:'.$max_age.' years');
            }

            $inputs=$req->except('_token','verification_code', 'image', 'phone');
            $path=public_path('img/donors');
            //$inputs['image']=FileUpload($req->image, $path);
            $inputs['user_id']=Auth::user()->id;
            $inputs['phone']=$phone;

            Donor::create($inputs);
            DB::commit();
            return redirect('donors')->with('success','Donor sccessfully saved');
         }catch(Exception $ex){
            DB::rollback();
         return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }catch(Throwable $ex){
            DB::rollback();
        return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('donors::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $donor=Donor::find($id);
        $states=States::where('country_id',167)->get();
        return view('donors::edit',compact('donor','states'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $req, $id)
    {
        $req->validate([
            'name'=>'required',
            'phone'=>'required|min:11|max:12|unique:donors,phone,'.$id,
            'dob'=>'required',
            'blood_group'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'ucouncil_id'=>'required',
            'address'=>'required',
        ]);
            DB::beginTransaction();
         try{


            $phone=$req->phone;
            $phone=preg_replace('/[^0-9]/', "", $phone);

            $start_with_0=str_starts_with($phone, '03');

            if($start_with_0){
                $phone = substr_replace($phone,'92',0,1);
            }


            $donor=Donor::find($id);

            if($donor==null){
                return redirect()->back()->withInput()->with('error', 'Something went wrong, donor not found');
            }

            if($phone!=$donor->phone){
                if($req->verification_code==null){
                    return redirect()->back()->withInput()->with('error', 'you have changed donor phone no, please verify by providing verification code');
                }
                $check_verification=VerificationMsgs::where(['phone'=>$phone, 'code'=>$req->verification_code])->first();
                if($check_verification==null){
                    return redirect()->back()->withInput()->with('error', 'Wrong verification code');
                }
            }

            $min_age=Settings()->mini_age;
            $max_age=Settings()->max_age;

            $age= Carbon::parse($req->dob)->age;

            if($age < $min_age || $age > $max_age){
                return redirect()->back()->withInput()->with('error', 'Donor is '.$age.' years old, donor should not younger than :'.$min_age.' years and elder than:'.$max_age.' years');
            }

            $inputs=$req->except('_token','verification_code', 'image', 'phone');
            $path=public_path('img/donors');

            if($req->image!=null){
                $inputs['image']=FileUpload($req->image, $path);
            }

            $inputs['phone']=$phone;

            $donor->update($inputs);

            DB::commit();
            return redirect('donors')->with('success','Donor sccessfully saved');
         }catch(Exception $ex){
            DB::rollback();
         return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }catch(Throwable $ex){
            DB::rollback();
        return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function Profileupdate(Request $req, $id)
    {
        $res=['success'=>false, 'errors'=>[], 'message'=>null, 'data'=>null];
        DB::beginTransaction();
        try{
            $val = Validator::make($req->all(), [
                'name'=>'required',
                'dob'=>'required',
                'blood_group'=>'required',
                'country_id'=>'required',
                'state_id'=>'required',
                'city_id'=>'required',
                'address'=>'required',
            ]);
            if ($val->fails()) {
                $res=['success'=>false,'message'=>'Required fields are missing','errors'=>$val->messages()->all(),'data'=>null];
                return response()->json($res);
            }

            $donor=Donor::find($id);

            if($donor==null){
                $res=['success'=>false,'message'=>'Something went wrong, donor not found','errors'=>[],'data'=>null];
                return response()->json($res);
            }

            $inputs=$req->except('image','dob');

            $path=public_path('img/donors');

            if($req->image!=null){
                $inputs['image']=FileUpload($req->image, $path);
            }
            $donor->update($inputs);
            $donor['access_token']=Auth::refresh();
            $data=[
                'user'=>$donor
            ];
            DB::commit();
            $res=['success'=>false,'message'=>'Profile sccessfully updated','errors'=>[],'data'=>$data];
            return response()->json($res);
        }catch(Exception $ex){
            DB::rollback();
            $res=['success'=>false, 'message'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
            return response()->json($res);
        }catch(Throwable $ex){
            DB::rollback();
            $res=['success'=>false, 'message'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
            return response()->json($res);
        }

    }




    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
         DB::beginTransaction();
        try{
        Donor::find($id)->delete();
        DB::commit();
         return redirect('donors')->with('success','Blood Donor successfully deleted');

         } catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error','Something went wrong with this error: '.$e->getMessage());
         }catch(Throwable $e){
            DB::rollback();
            return redirect()->back()->with('error','Something went wrong with this error: '.$e->getMessage());
         }
    }



}
