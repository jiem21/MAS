<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;

use Validator;
use Redirect;
use Auth;
use DB;



class LoginController extends Controller
{
	function __construct()
	{
		$this->user = new User;
	}

	public function showlogin()
	{
		$data['title'] = "Login";
		return view('auth/login_2',$data);
	}
	public function authenticate(Request $request)
	{
		$rules = [
			'user_id'=>'required',
			'password'=>'required',
		];
		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			return response()->json(['error'=>true,'message'=>$validator->messages()]);
		}
		else{
			$password = trim(strtoupper(md5($request->get('password'))));
			$user = $this->user->where('username','=',$request->get('user_id'))->first();
			if ($user && trim($user->password) == $password) {
				// Auth::login($user);
				Auth::guard()->login($user);
				if (Auth::check()) {
					return response()->json(['error'=>false,'message'=>['Successfully Login'],'redirect' => Redirect::intended('/dashboard')]);
				}
			}
			else{
				return response()->json(['error'=>true,'message'=>['Incorrect Password']]);
			}
		}
	}
}