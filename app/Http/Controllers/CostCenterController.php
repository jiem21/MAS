<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\CostCenter;
use App\ProcessName;

use Validator;
use Auth;
use Carbon;

class CostCenterController extends Controller
{
   function __construct()
    {
        $this->cost = new CostCenter;
        $this->procname = new ProcessName;
    }
    public function index()
    {
    	$data['costcode'] = $this->procname->get();
    	$data['title'] = 'Cost Center Maintenance';
    	$data['menu_name'] = 'cost_center';
        $data['routes'] = route('RegView');
    	return view('cost-center',$data);
    }
    public function validateCode(Request $req)
    {
        if(empty(trim($req->get('code')))) {
            return response()->json(['error' => true,'message'=>['Please Input the Cost Center Code']]);
        }
        else{
            $cost_center = $this->cost->where('new','=',$req->get('code'))->count();
            $cost_center2 = $this->procname->where('costcode','=',$req->get('code'))->where('status','=',0)->count();
            if ($cost_center >= 1 OR $cost_center2 >= 1) {
                return response()->json(['error' => true,'message'=>['Cost Center Code Is already Used']]);
            }
            else{
                return response()->json(['error' => false,'message'=>['This Cost Center Code is available']]);
            }
        }
    }
    public function validateName(Request $req)
    {
        if(empty(trim($req->get('name')))) {
            return response()->json(['error' => true,'message'=>['Please Input the Process Name']]);
        }
        else{
            $proc_name = $this->procname->where('process_name','=',$req->get('name'))->where('status','=',1)->count();
            if ($proc_name >= 1) {
                return response()->json(['error' => true,'message'=>['Process Name is Exist']]);
            }
            else{
                return response()->json(['error' => false,'message'=>['This Process name is available']]);
            }
        }
    }
    public function saveCode(Request $req)
    {
    	$rules = [
            'costcode'=>'required',
            'process_name'=>'required',
        ];
        $validator = Validator::make($req->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }else{
                $this->cost->insert([
                'old'=>$req->get('costcode'),
                'new'=>$req->get('costcode')
            	]);
                $this->procname->insert([
                'process_name'=>$req->get('process_name'),
                'costcode'=>$req->get('costcode'),
                'status'=>1
                ]);
                activity_logs("Added a new cost center code(".$req->get('costcode').")",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Successfully Saved']]);
            }
        }
    public function getCode(Request $req)
    {
    	if ($req->get('code')) {
             $data = $this->procname->where('costcode','=',$req->get('code'))->first();
             return response()->json(['valid'=>true,'code'=>[$data]]);
        }
    }
    public function updateCode(Request $req)
    {
    	$rules = [
            'costcode_new'=>'required',
            'processname'=>'required',
        ];
        $validator = Validator::make($req->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
        	$this->procname->where('id','=',$req->get('costcode_id'))->update([
                'process_name'=>$req->get('processname'),
                'costcode'=>$req->get('costcode_new'),
                'status'=>$req->get('code_status')
            ]);
            $this->cost->where('new','=',$req->get('costcode_old'))->update(['new'=>$req->get('costcode_new')]);
            activity_logs("updated a cost center code",Auth::user()->username,Carbon\Carbon::now());
        	return response()->json(['error' => false,'message'=>['Successfull updated']]);
        }
    }
}
