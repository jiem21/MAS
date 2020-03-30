<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Employee;

use Validator;
use DB;

class ClientController extends Controller
{
    public function __construct()
    {
    	$this->user = new User;
        $this->emp = new Employee;
    }
    public function index()
    {
    	$data['title'] = "Home";
    	return view('welcome',$data);
    }
    public function showlogin()
    {
    	$data['title'] = "Login";
    	return view('auth/login_2',$data);
    }
}
