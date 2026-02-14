<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Settings\Entities\Settings;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        //dd(sendMsg('923025869931', 'testing'));

        $this->data['settings']=Settings::first();
        $this->data['roles']=Role::whereNot('name', 'super-admin')->get();
        return view('settings::index')->withData($this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('settings::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $req)
    {

        $portal_logo=null;
        $portal_favicon=null;
        $footer_logo=null;
        $path=public_path('img/settings/');

        $sett=Settings::first();

        if($sett!=null){
            $portal_logo=$sett->portal_logo;
            $portal_favicon=$sett->portal_favicon;
            $footer_logo=$sett->footer_logo;
        }

        if($req->file('panel_logo')!=null){
            $portal_logo=FileUpload($req->file('panel_logo'), $path);
        }
        if($req->file('panel_favicon')!=null){
            $portal_favicon=FileUpload($req->file('panel_favicon'), $path);
        }
        if($req->file('footer_logo')!=null){
            $footer_logo=FileUpload($req->file('footer_logo'), $path);
        }
        $settings=Settings::firstOrNew(['id'=>1]);
        $settings->portal_name=$req->panel_name;
        $settings->portal_email=$req->panel_email;
        $settings->portal_contact_no=$req->panel_contact_no;
        $settings->portal_logo=$portal_logo;
        $settings->portal_favicon=$portal_favicon;
        $settings->footer_logo=$footer_logo;


        $settings->platform_service_fee=$req->platform_service_fee;
        $settings->mini_age=$req->mini_age;
        $settings->max_age=$req->max_age;
        $settings->mini_compensation=$req->mini_compensation;
        $settings->mini_service_compensation=$req->mini_service_compensation;
        $settings->compensation_for=json_encode($req->compensation_for);


        $settings->sms_api=$req->sms_api;
        $settings->sms_api_secret=$req->sms_api_secret;
        if($settings->save()){
            return redirect()->back()->with('success', 'Panel settings successfully saved');
        }
    }


    public function theme(Request $req)
    {
        $set=Settings::firstOrNew(['id'=>1]);

        if($req->field=='layout'){
        $set->layout=$req->data; 
        }
        if($req->field=='sidebar'){
        $set->sidebar_color=$req->data; 
        }
        if($req->field=='theme'){
        $set->color_theme=$req->data; 
        }
        if($req->field=='mini'){
        $set->mini_sidebar=$req->data; 
        }
        if($req->field=='sticky'){
        $set->sticky_header=$req->data; 
        }

        $set->save();

        return 'success';
    }
    public function restorydefault(Request $req)
    {
        $layout=1;
        $sidebar=1;
        $theme='white';
        $mini='unchecked';
        $sticky='checked';

        $set=Settings::firstOrNew(['id'=>1]);

        $set->layout=$layout; 
        $set->sidebar_color=$sidebar; 

        $set->color_theme=$theme; 

        $set->mini_sidebar=$mini; 

        $set->sticky_header=$sticky; 


        $set->save();

        return 'success';
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('settings::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('settings::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    
    public function apiindex(){
        $set=Settings::first();
        
        $path=public_path('img');
        $url=url('public/img');
        $footer_logo=$url.'/images.png';
        if(file_exists($path.'/settings/'.$set->footer_logo) AND $set->footer_logo!=null){
        $footer_logo=$url.'/settings/'.$set->footer_logo;
        }
        
        $logo=$url.'/images.png';
        if(file_exists($path.'/settings/'.$set->portal_logo) AND $set->portal_logo!=null){
        $logo=$url.'/settings/'.$set->portal_logo;
        }
        
        $set['footer_logo']=$footer_logo;
        $set['portal_logo']=$logo;
        return response()->json($set);
        
        
        
    }
}
