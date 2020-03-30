<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Employee;
use App\CostCenter;
use App\Allowance;
use App\AllowanceUsage;
use App\AllowanceGenerated;
use App\AllowancePending;

use View;
use Carbon;
use Auth;
use Validator;
use DB;
use Excel;

class allowanceController extends Controller
{
    function __construct()
    {
        $this->user = new User;
        $this->emp = new Employee;
        $this->cost = new CostCenter;
        $this->allowance = new Allowance;
        $this->usage = new AllowanceUsage;
        $this->generatedAll = new AllowanceGenerated;
        $this->allpen = new AllowancePending;
    }

    public function RegView()
    {
        $data['reviewer'] = $this->user->where('role','=','3')->get();
        $data['approver'] = $this->user->where('role','=','2')->get();
        $data['menu_name'] = 'meal_gen';
        $data['meal_list'] = 'regall';
        $data['routes'] = route('RegView');
        return view('Allowance.regularAll',$data);
    }

    public function OTView()
    {
        $data['reviewer'] = $this->user->where('role','=','3')->get();
        $data['approver'] = $this->user->where('role','=','2')->get();
        $data['menu_name'] = 'meal_gen';
        $data['meal_list'] = 'otall';
        $data['routes'] = route('OTView');
        return view('Allowance.OTAll',$data);
    }
    // Get Working Days from ITMS Calendar
        // General Counting
    public function getWorkingDays(Request $date)
    {
        $working = 0;
        $date_from_con = strtotime($date->get('date_from'));
        $date_to_con = strtotime($date->get('date_to'));

        for ($i = $date_from_con; $i <= $date_to_con ; $i = $i + (60*60*24)) { 
            if (date("N",$i) <= 6) {
                $date = date("Y-m-d",$i);
                $getdate = DB::connection('calendar')->table('tblHoliday')->select('*')->where('holiDate','=',$date)->count();
                if ($getdate == 0) {
                    $working++;
                }
            }
            
        }
        return response()->json(['workingday' => [$working]]);
    }
        // Pro Rated
    public function getWorkingDaysPerEmp($date_hired,$date_to)
    {
        $working = 0;
        $date_from_con = strtotime($date_hired);
        $date_to_con = strtotime($date_to);


        for ($i = $date_from_con; $i <= $date_to_con ; $i = $i + (60*60*24)) { 
            if (date("N",$i) <= 6) {
                $date = date("Y-m-d",$i);
                $getdate = DB::connection('calendar')->table('tblHoliday')->select('*')->where('holiDate','=',$date)->count();
                if ($getdate == 0) {
                    $working++;
                }
            }
            
        }
        return $working;
    }

    public function getGenlist()
    {
        $data['lists'] = $this->generatedAll->orderBy('date_created','DESC')->get();
        $data['menu_name'] = 'meal_gen';
        $data['meal_list'] = 'genlist';
        $data['routes'] = route('GenList');
        return view('Allowance.ListGenAll',$data);
    }
    public function viewGen($id)
    {
        $data['lists'] = $this->generatedAll->where('id','=',$id)->get();
        $data['emplist'] =  $this->allpen->select('AllowancePending.*',
                                                    'emp.EmpFirstName',
                                                    'emp.EmpLastName',
                                                    'emp.CostCenterCode')
                                          ->where('AllowancePending.allowanceGenID','=',$id)
                                          ->leftjoin('Employee as emp','emp.EmpNo','=','AllowancePending.EmpNo')
                                          ->get();
        $data['menu_name'] = 'meal_gen';
        $data['routes'] = route('GenList');
        return view('Allowance.ViewGenerated',$data);
    }
    public function TotalNotif()
    {
        $userid = Auth::user()->id;
        $role = Auth::user()->role;
        if ($role == 2) {
            // Approver Side
            $count = $this->generatedAll->where('status_review','=',1)->where('approved_by','=',$userid)->count();
        }elseif($role == 1){
            // Admin Side
            $count = $this->generatedAll->where('status','=',0)->count();
        }else{
            // Reviewer Side
            $count = $this->generatedAll->where('status','=',0)->where('reviewed_by','=',$userid)->count();
        }
        return $count;
    }
    public function pendingList()
    {
        $userid = Auth::user()->id;
        $role = Auth::user()->role;
        if ($role == 2) {
            // Approver Side
            $data['lists'] = $this->generatedAll->where('status_review','=',1)->where('approved_by','=',$userid)->orderBy('date_created','DESC')->get();
        }elseif($role == 1){
            // Admin Side
            $data['lists'] = $this->generatedAll->where('status','=',0)->orderBy('date_created','DESC')->get();
        }else{
            // Reviewer Side
            $data['lists'] = $this->generatedAll->where('status','=',0)->where('reviewed_by','=',$userid)->orderBy('date_created','DESC')->get();
        }
        $data['menu_name'] = 'meal_gen';
        $data['meal_list'] = 'penlist';
        $data['routes'] = route('PenList');
        return view('Allowance.pendingAll',$data);
    }

    public function generateRegAll(Request $request)
    {
        $rules = [
            'date_from'=>'required',
            'date_to'=>'required',
            'days'=>'required',
            'allowance'=>'required',
            'prepared_by'=>'required',
            'reviewed_by'=>'required',
            'approved_by'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
            $id = $this->generatedAll->insertGetId([
                'date_from'=>$request->get('date_from'),
                'date_to'=>$request->get('date_to'),
                'total_days'=>$request->get('days'),
                'allowance'=>$request->get('allowance'),
                'allowance_type'=>'Reg',
                'status'=>0,
                'prepared_by'=>$request->get('prepared_by'),
                'prepared_date'=>Carbon\Carbon::now(),
                'reviewed_by'=>$request->get('reviewed_by'),
                'status_review'=>0,
                'approved_by'=>$request->get('approved_by'),
                'status_approve'=>0,
                'date_created'=>Carbon\Carbon::now(),
                'created_by'=>Auth::user()->username
            ]);
            $getEmp = $this->emp->where('EmpStatus','=','A')->get();

            foreach ($getEmp as $EmpInfo) {
                $date_from = strtotime($request->get('date_from'));
                $date_hired = strtotime($EmpInfo->EmpDateHired);
                if ($date_from > $date_hired) {
                    $workingday = $request->get('days');
                    $total_allowance = $workingday * $request->get('allowance');
                }
                else{
                    $workingday = $this->getWorkingDaysPerEmp($EmpInfo->EmpDateHired,$request->get('date_to'));
                    $total_allowance = $workingday * $request->get('allowance');
                }
                $this->allpen->insert([
                    'EmpNo'=>$EmpInfo->EmpNo,
                    'allowance'=>$request->get('allowance'),
                    'total_days'=>$workingday,
                    'total_allowance'=>$total_allowance,
                    'allowanceType'=>'Reg',
                    'allowanceGenID'=>$id,
                    'date_generated'=>Carbon\Carbon::now(),
                    'created_by'=>Auth::user()->username
                ]);
            }

            $get_total_count = $this->allpen->where('allowanceGenID','=',$id)->count();
            $sum_allowance = $this->allpen->where('allowanceGenID','=',$id)->sum('total_allowance');

            $update = $this->generatedAll->where('id','=',$id)->update([
                'total_emp'=> $get_total_count,
                'total_allowance'=> $sum_allowance
            ]);
            if ($update) {
                return response()->json(['error' => false,'message'=>['Generation of Regular Allowance is Complete']]);
            }
            else{
                return response()->json(['error' => true,'message'=>['Something Went wrong please contact IT Personnel']]);
            }
        }
    }
    public function GenerateEmpListForOT()
    {
        $employee = $this->emp->select('Employee.*','b.new',DB::raw('(c.AllowanceAmtOvt - c.AllowanceAmtOvtUsage) as RegAll'))->leftjoin('NewCostCenter as b','Employee.CostCenterCode','=','b.old')->leftjoin('Allowance as c','Employee.EmpNo','=','c.EmpNo')->where('EmpStatus','=','A')->get();
        Excel::create('Employee List', function($excel) use($employee){
            $excel->sheet('Employee',function ($sheet) use($employee)
            {
                $sheet->cells('A1:G1', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });
                $sheet->cells('A2:G2', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontSize(10);
                });
                $sheet->mergeCells('A1:G1')->setCellValue('A1','Employee List');
                $sheet->mergeCells('A2:G2')->setCellValue('A2',date('m/d/y h:i:s A',strtotime(Carbon\Carbon::now())));
                $sheet->row(3,array('Employee ID','Employee Name','Classification','Date Hired','Cost Center Code','Remaining OT Allowance','Uploaded Allowance'));

                foreach ($employee as $key => $emp) {
                    $sheet->row(4+$key,array($emp->EmpNo,$emp->EmpLastName.', '.$emp->EmpFirstName.' '.$emp->EmpMiddleName,classification($emp->EmpClass),$emp->EmpDateHired,$emp->new,$emp->RegAll,0));
                }

            });
        })->export('xlsx');
    }
    // Update Approver Status
    public function Update_status(Request $req)
    {
        # code...
    }
}
