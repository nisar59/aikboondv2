<?php

namespace Modules\Updates\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Updates\App\Models\Updates;
use Yajra\DataTables\Facades\DataTables;
use Auth;
class UpdatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $updates = Updates::orderBy('id', 'ASC')->get();
            return DataTables::of($updates)
                ->addColumn('action', function ($row) {
                    $action = '';
                        if (Auth::user()->can('updates.edit')) {
                            $action .= '<a class="btn btn-primary btn-sm" href="' . url('/updates/edit/' . $row->id) . '"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        if (Auth::user()->can('updates.delete')) {
                            $action .= '<a class="btn btn-danger btn-sm" href="' . url('/updates/destroy/' . $row->id) . '"><i class="fas fa-trash-alt"></i></a>';
                        }
                    return $action;
                })
                ->addColumn('status', function ($row) {
                    $status = '';
                    if ($row->status==1) {
                        $status= '<a class="btn btn-primary btn-sm" href="' . url('/updates/status/' . $row->id) . '">Active</a>';
                    }
                   else{
                       $status= '<a class="btn btn-danger btn-sm" href="' . url('/updates/status/' . $row->id) . '">Deactive</a>';
                    }
                    return $status;
                })
                ->addColumn('image', function ($row) {

                    $img= '<img style="height:50px;" src="'.url('img/updates/', $row->image).'" class="img-fluid w-25"/>';
                    return $img;
                })
                ->removeColumn('id')
                ->rawColumns(['action', 'image', 'status'])
                ->make(true);
        }

        return view('updates::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('updates::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req): RedirectResponse
    {
        $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required'],
            'time'=>['required'],
        ]);

        $path=public_path('img/updates');

        $user=Updates::create([
            'name' => $req->name,
            'image'=>FileUpload($req->file('image'), $path),
            'time'=>$req->time,
        ]);
            return redirect('updates')->with('success', 'Update successfully created');

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('updates::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $update=Updates::find($id);
        return view('updates::edit')->withUpdate($update);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, $id): RedirectResponse
    {
        $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'time' => ['required'],
        ]);

        $path=public_path('img/updates');

        $update=Updates::find($id);
        $update->name=$req->name;
        $update->time=$req->time;

        if($req->file('image')!=null){
            $update->image=FileUpload($req->file('image'), $path);
        }
        $update->save();
            return redirect('updates')->with('success', 'Update successfully Updated');


    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        Updates::find($id)->delete();
        return redirect('updates')->with('success','Update successfully deleted');

    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function status($id)
    {
        $user=Updates::find($id);
        if($user->status==0){
            $user->status=1;
        }else{
            $user->status=0;
        }
        $user->save();
        return redirect('updates')->with('success','Update status successfully updated');

    }
}
