<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\Countries;
use App\Models\States;
use Modules\Cities\Entities\Cities;
use Modules\Areas\Entities\Areas;
use Modules\AddressesAndTowns\Entities\AddressesAndTowns;
use Throwable;
use Auth;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //dd(Auth::user()->roles[0]->name);
    if (request()->ajax()) {
        $users=User::with('roles')->orderBy('id','ASC')->get();
            return DataTables::of($users)
                ->addColumn('action', function ($row) {
                    $action='';

                if(Auth::user()->hasRole('super-admin') AND $row->hasRole('super-admin')){
                $action.='<a class="btn btn-primary btn-sm" href="'.url('users/edit/'.$row->id).'"><i class="fas fa-pencil-alt"></i></a>';

                }
                elseif($row->hasRole('super-admin'))
                {
                    return '';
                }
                    else{
                if(Auth::user()->can('users.edit')){
                $action.='<a class="btn btn-primary btn-sm" href="'.url('/users/edit/'.$row->id).'"><i class="fas fa-pencil-alt"></i></a>';
                }
                if(Auth::user()->can('users.delete')){
                $action.='<a class="btn btn-danger btn-sm" href="'.url('/users/destroy/'.$row->id).'"><i class="fas fa-trash-alt"></i></a>';
                    }
                }
                return $action;
                })
                ->addColumn('role', function ($row) {
                    $roles='';
                    foreach ($row->roles as $key => $role) {
                      $roles.='<span class="badge badge-primary">'.$role->name.'</span>';
                    }
                    return $roles;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
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
                  ->removeColumn('id')
                ->rawColumns(['action','role'])
                ->make(true);
    }


        return view('users::index');
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->data['role']=Role::where('name','!=','super-admin')->get();
        $this->data['states']=States::where('country_id',167)->get();
        return view('users::create')->withData($this->data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $req)
    {
        $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string','max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required'],
            'country_id'=>['required'],
            'state_id'=>['required'],
            'city_id'=>['required'],
            'address'=>['required'],

        ]);

        $path=public_path('img/users');

        $user=User::create([
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => Hash::make($req->password),
            'image'=>FileUpload($req->file('image'), $path),
            'country_id'=>$req->country_id,
            'state_id'=>$req->state_id,
            'city_id'=>$req->city_id,
            'address'=>$req->address,

        ]);
        if($user->assignRole($req->role)){
            return redirect('users')->with('success', 'User successfully created');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('users::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->data['role']=Role::where('name','!=','super-admin')->get();
        $this->data['user']=User::with('roles')->find($id);
        $this->data['states']=States::where('country_id',167)->get();
        return view('users::edit')->withData($this->data);
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string','max:255', 'unique:users,email,'.$id],
            'phone' => ['required', 'string','max:255', 'unique:users,phone,'.$id],
            'role' => ['required'],
            'country_id'=>['required'],
            'state_id'=>['required'],
            'city_id'=>['required'],
            'address'=>['required'],
        ]);

        $path=public_path('img/users');

        $user=User::find($id);
        $user->name=$req->name;
        $user->email=$req->email;
        $user->phone=$req->phone;
        $user->country_id=$req->country_id;
        $user->state_id=$req->state_id;
        $user->city_id=$req->city_id;
        $user->address=$req->address;

        if($req->password!=null){
        $user->password=Hash::make($req->password);
        }
        if($req->file('image')!=null){
        $user->image=FileUpload($req->file('image'), $path);
        }
        $user->save();
        $user->roles()->detach();
        if($user->assignRole($req->role)){
            return redirect('users')->with('success', 'User successfully Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
       $user=User::find($id);
       $user->roles()->detach();
        User::find($id)->delete();
        return redirect('users')->with('success','User successfully deleted');

    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function status($id)
    {
       $user=User::find($id);
       if($user->status==0){
        $user->status=1;
       }else{
        $user->status=0;
       }
       $user->save();
       return redirect('users')->with('success','User status successfully updated');

    }




}
