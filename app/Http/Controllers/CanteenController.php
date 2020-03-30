<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Canteen;

use Carbon;
use Auth;
use Validator;
use DB;

class CanteenController extends Controller
{
   function __construct($foo = null)
   {
   	$this->canteen = new Canteen;
   }

   public function index()
   {
   	$data['canteen'] = $this->canteen->get();
   	$data['title'] = 'Canteen Maintenance';
   	$data['menu_name'] = 'canteen';
   	$data['routes'] = route('canteen');
   	return view('Canteen.canteen',$data);
   }
   public function saveCanteen(Request $request)
   {
   	$rules = [
            'code'=>'required',
            'name'=>'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
        	$check_code = $this->canteen->where('canteencode','=',$request->get('code'))->count();
        	if ($check_code >= 1) {
        		return response()->json(['error' => true,'message'=>['Canteen Code Is already Used']]);
        	}
        	else{
        		$this->canteen->insert([
        			'canteencode'=>$request->get('code'),
        			'canteenname'=>$request->get('name'),
        			'status'=>1,
        			'created_by'=>Auth::user()->username,
        			'date_created'=>Carbon\Carbon::now()
        		]);
            activity_logs("Added a new canteen",Auth::user()->username,Carbon\Carbon::now());
        		return response()->json(['error' => false,'message'=>['Canteen Details Is save on the system']]);
        	}
        }
   }
   public function getdetails(Request $req)
   {
   	if ($req->get('id')) {
   		$details = $this->canteen->where('id','=',$req->get('id'))->first();
   		return response()->json(['valid'=>true,'details'=>[$details]]);
   	}
   }
   public function upcanteen(Request $request)
   {
     $rules = [
            'code'=>'required',
            'name'=>'required',
            'status'=>'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
          $check_code = $this->canteen->where('id','!=',$request->get('id'))->where('canteencode','=',$request->get('code'))->count();
          if ($check_code >= 1) {
            return response()->json(['error' => true,'message'=>['Canteen Code Is already Used']]);
          }
          else{
            $this->canteen->where('id','=',$request->get('id'))->update([
              'canteencode'=>$request->get('code'),
              'canteenname'=>$request->get('name'),
              'status'=>$request->get('status'),
              'created_by'=>Auth::user()->username
            ]);
            activity_logs("Updated the canteen details",Auth::user()->username,Carbon\Carbon::now());
            return response()->json(['error' => false,'message'=>['Canteen Details Is save on the system']]);
          }
        }
   }
}
