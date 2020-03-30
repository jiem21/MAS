<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Employee;
use Carbon;

use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->emp = new Employee;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Date
        $now = Carbon\Carbon::today();
        $until5 = Carbon\Carbon::today()->addDays(5)->settime(23,59,59);
        $data['now'] = $now;
        $data['until5'] = $until5;
        $data['title'] = 'Dashboard';
        $data['menu_name'] = 'dashboard';
        $data['routes'] = route('dashboard');
        // $data['list_emp'] = $this->emp->whereBetween('EmpEndDate',[$now,$until5])->get();
        $data['list_emp'] = $this->emp->select('Employee.*','ncc.new')->whereBetween('EmpEndDate',[$now,$until5])->leftjoin('NewCostCenter as ncc','ncc.old','=','Employee.CostCenterCode')->orderBy('EmpNo','ASC')->get();;
        return view('home',$data);
    }
}
