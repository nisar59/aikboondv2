<?php
use Modules\Settings\Entities\Settings;
use App\Models\Countries;
use App\Models\States;
use Modules\Cities\Entities\Cities;
use Modules\UnionCouncils\App\Models\UnionCouncils;

function AllPermissions()
{
	$role=[];
	$role['users']=['view','add','edit','delete'];
	$role['permissions']=['view','add','edit','delete'];
	$role['country']=['view','add','edit','delete'];
	$role['states']=['view','add','edit','delete'];
	$role['cities']=['view','add','edit','delete'];
 	$role['donors']=['view','add','edit','delete', 'view-all', 'view-by-union-council', 'view-by-city', 'view-by-state'];
	$role['requests']=['view','edit','delete'];
	$role['compensation']=['view','add','edit','delete'];
    $role['updates']=['view','add','edit','delete'];
    $role['settings']=['view','add','edit','delete'];



return $role;

}

function FileUpload($file, $path){
	if($file==null){return null;}
	 $imgname=$file->getClientOriginalName();
	  if($file->move($path,$imgname)){
	  	return $imgname;
	  }
	  else{
	  	return null;
	  }
}


function Settings()
{
	return Settings::first();
}

function Country($id)
{
	$country=Countries::find($id);
	if ($country!=null) {
		return $country->name;
	}
}

function PaymentMethods($id)
{
	$p_method=PaymentMethods::find($id);
	if ($p_method!=null) {
		return $p_method->bank_name;
	}
}


function City($id)
{
	$cities=Cities::find($id);
	if ($cities!=null) {
		return $cities->name;
	}
}



function UnionCouncil($id)
{
	$uniCoucil=UnionCouncils::find($id);
	if ($uniCoucil!=null) {
		return $uniCoucil->name;
	}
}



function State($id)
{
	$states=States::find($id);
	if ($states!=null) {
		return $states->name;
	}
}

function AllCoutries()
{
    $Countries=Countries::where('id',167)->get();
    return $Countries;
}

function AllStates($country_id=null)
{	if($country_id==null){
	    $states=States::all();
	}else{
		$states=States::where('country_id',$country_id)->get();
	}
    return $states;
}

function AllCities($state_id=null)
{
    $cities=Cities::where('state_id', $state_id)->get();
    return $cities;
}


function BloodGroups()
{
	$groups=[
			'A+'=>'A+',
			'A-'=>'A-',
			'B+'=>'B+',
			'B-'=>'B-',
			'O+'=>'O+',
			'O-'=>'O-',
			'AB+'=>'AB+',
			'AB-'=>'AB-',
	];

	return $groups;
}


function GenerateVerificationCode(){
	//return substr(number_format(time() * rand(),0,'',''),0,4);
    return 1234;
}


function sendMsg($phn, $msg)
{
    $response=['success'=>true, 'message'=>null];

    $api=Settings()->sms_api;
	$key=Settings()->sms_api_secret;

	if($api==null || $api=="" || $key==null || $key==""){
    	$response=['success'=>false, 'message'=>"sms notifications are disabled"];
    	return (object) $response;
	}
    $response=['success'=>true, 'message'=>'Sucessfully sent'];
//	$sender='8583';
//
//	$url = $api."?hash=".$key."&receivernum=".$phn."&sendernum=".$sender."&textmessage=".$msg;
//
//    $res= Http::timeout(60)->withOptions(['verify' => false])->get($url);
//    $body=json_decode($res->body());
//
//    if($res->successful()){
//
//        if($body->STATUS=="ERROR"){
//        $response=['success'=>false, 'message'=>$body->ERROR_DESCRIPTION];
//        }
//        else{
//            $response=['success'=>true, 'message'=>$body->ERROR_DESCRIPTION];
//        }
//    }
//    else{
//        $response=['success'=>false, 'message'=>"Something went wrong"];
//    }

    return (object) $response;

}



// function sendMsg($phn, $msg)
// {

// $APIKey = '5af6a68df519842f9d0bb0244a1eddb9';

// $receiver = '923025869931';
// $sender = '';
// $textmessage = 'ghjgjhgjh';

// $url ="https://api.veevotech.com/v3/sendsms?hash=".$APIKey."&receivernum=".$receiver."&sendernum=".$sender."&textmessage=".$textmessage;;

// #----CURL Request Start
// $ch = curl_init();
// $timeout = 30;
// curl_setopt ($ch,CURLOPT_URL, $url) ;
// curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
// curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
// $response = curl_exec($ch) ;
// curl_close($ch) ;
// #----CURL Request End, Output Response
// echo $response ;



// }
