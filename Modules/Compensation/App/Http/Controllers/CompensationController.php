<?php

namespace Modules\Compensation\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Compensation\App\Models\Compensation;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use Throwable;
use Artisan;
Use Auth;

class CompensationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $req=request();
    if ($req->ajax()) {
        $users=Compensation::get();

            return DataTables::of($users)
                ->addColumn('role', function ($row) {
                    $roles='';
                    if($row->user()->exists()){
                        foreach ($row->user->roles as $key => $role) {
                          $roles.='<span class="badge badge-primary">'.$role->name.'</span>';
                        }
                    }
                    return $roles;
                })
                ->addColumn('name', function ($row) {
                     if($row->user()->exists()){
                        return $row->user->name;
                    }
                })
                ->addColumn('phone', function ($row) {
                     if($row->user()->exists()){
                    return $row->user->phone;
                    }
                })
                ->addColumn('state_id', function ($row) {
                    if($row->user()->exists() && $row->user->state()->exists()){
                        return $row->user->state->name;
                    }
                })

                ->addColumn('city_id', function ($row) {
                    if($row->user()->exists() && $row->user->city()->exists()){
                        return $row->user->city->name;
                    }
                })

                  ->editColumn('ucouncil_id',function ($row)
                {
                   return UnionCouncil($row->ucouncil_id);
                })
                ->addColumn('compensation', function ($row) {
                    return 'Rs. '.$row->total_amount;
                })

                ->editColumn('status', function ($row) {
                    $name='';
                    if($row->user()->exists()){
                        $name=$row->user->name;
                    }
                   if($row->status==0){
                    return '<a href="javascript:void(0)" data-name="'.$name.'" data-href="'.url('compensation/pay',$row->id).'" class="pay pe-auto badge badge-primary">Unpaid</a>';
                   }
                    if($row->status==1){
                    return '<span class="badge badge-success">Paid</span>';
                   }
                })
                ->removeColumn('id')
                ->rawColumns(['role', 'status'])
                ->make(true);
    }


        return view('compensation::index');    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('compensation::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('compensation::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('compensation::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
       
        try {        
            $month=Carbon::parse($req->month)->format('Y-m-d');
            Artisan::call('reg:compensation '.$month);
            return redirect('compensation')->with('info','Generating Compensation is in process, keep checking the status by refreshing the page.');
        }catch(Exception $ex){
            DB::rollback();
         return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }catch(Throwable $ex){
            DB::rollback();
        return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
