<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;

use Carbon;
use Auth;

use Validator;

class UserController extends Controller
{
	function __construct()
    {
    	$this->user = new User;
    }

    public function index()
    {
    	$data['users'] = $this->user->where('role','!=',1)->get();
        $data['title'] = 'User Maintenance';
        $data['menu_name'] = 'user';
        $data['routes'] = route('userlist');
        return view('user-maintenance',$data);
    }
    public function save(Request $request)
    {
        $rules = [
            'username'=>'unique:users|required',
            'fullname'=>'required',
            'email'=>'required',
            'password'=>'required',
            'role'=>'required'
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>$validator->messages()]);
        }
        else{
            $check = $this->user->where('username','=',$request->get('username'))->count();
            if ($check == 1) {
               return response()->json(['error' => true,'message'=>['Username is already Used']]);
            }
            else{
                $this->user->insert([
                'name'=>$request->get('fullname'),
                'email'=>$request->get('email'),
                'username'=>$request->get('username'),
                'password'=>strtoupper(md5($request->get('password'))),
                'role'=>$request->get('role'),
                'created_at'=>Carbon\Carbon::now(),
                'created_by'=>Auth::user()->name
            ]);
                activity_logs("Added a new account",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Successfully Saved']]);
            }
        }
    }
    public function get_detials(Request $request)
    {
        if ($request->get('id')) {
             $data = $this->user->where('id','=',$request->get('id'))->first();
             return response()->json(['valid'=>true,'info'=>[$data]]);
        } 
    }
    public function update(Request $request)
    {
        $rules = [
            'fullname'=>'required',
            'email'=>'required',
            'role'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>$validator->messages()]);
        }
        else{
            $this->user->where('id','=',$request->get('id'))->update([
                'name'=>$request->get('fullname'),
                'email'=>$request->get('email'),
                'role'=>$request->get('role'),
                'updated_at'=>Carbon\Carbon::now()
            ]);
            activity_logs("updated an Account",Auth::user()->username,Carbon\Carbon::now());
            return response()->json(['error' => false,'message'=>['Updated Successfully']]);
        }
    }
    public function delete(Request $request)
    {
        if ($request->get('id')) {
            activity_logs("Deleted an Account",Auth::user()->username,Carbon\Carbon::now());
             $data = $this->user->where('id','=',$request->get('id'))->delete();
             return response()->json(['error' => false,'message'=>['Deleted Successfully']]);
        } 
    }
    public function change_password(Request $request)
    {
        $rules = [
            'curr_pass'=>'required',
            'new_pass'=>'required',
            'con_pass'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>$validator->messages()]);
        }
        else{
            $validate_pass = $this->user->where('id','=',$request->get('user_id'))->where('password','=',md5($request->get('curr_pass')))->count();
            if ($validate_pass == 0) {
                return response()->json(['error_pass' => true,'message'=>['Current password is incorrect']]);
            }
            elseif($request->get('new_pass') != $request->get('con_pass')){
                return response()->json(['error_new' => true,'message'=>['New password and Confirm password does not match']]);
            }
            else{
                $this->user->where('id','=',$request->get('user_id'))->update(['password'=> strtoupper(md5($request->get('new_pass')))]);
                return response()->json(['error' => false,'message'=>['Successfully Changed']]);
            }
        }
    }
}
