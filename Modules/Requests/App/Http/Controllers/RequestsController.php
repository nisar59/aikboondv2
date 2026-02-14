<?php

namespace Modules\Requests\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Requests\App\Models\Requests;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\States;
use Carbon\Carbon;
use Throwable;
use Auth;
use DB;
class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       $req=request();
         if ($req->ajax()) {

            $strt=$req->start;
            $length=$req->length;

            $requests=Requests::query();

            $total=$requests->count();

           $requests=$requests->offset($strt)->limit($length)->orderBy('status', 'ASC')->get();

           return DataTables::of($requests)
           ->setOffset($strt)
           ->with([
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total
           ])

            ->addColumn('user_name', function ($row) {
                if($row->user()->exists()){
                    return $row->user->name;
                }
            })


            ->addColumn('phone_no', function ($row) {
                if($row->user()->exists()){
                    return $row->user->phone;
                }
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
             ->editColumn('ucouncil_id',function ($row)
                {
                   return UnionCouncil($row->ucouncil_id);
                })

            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y');
            })

            ->editColumn('status', function ($row) {
                $status='';

                if($row->status==0){
                    $status='<span class="btn btn-sm btn-warning">Pending</span>';
                }elseif($row->status==1){
                    $status='<span class="btn btn-sm btn-success">Completed</span>';
                }else{
                    $status='<span class="btn btn-sm btn-danger">Rejected</span>';
                }

                return $status;

            })

           ->rawColumns(['status'])
           ->make(true);
        }
         $states=States::where('country_id',167)->get();
        return view('requests::index',compact('states'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('requests::create');
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
        return view('requests::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('requests::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
