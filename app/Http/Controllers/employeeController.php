<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Employee;
use App\CostCenter;
use App\Allowance;
use App\AllowanceUsage;
use App\ProcessName;

use Carbon;
use Auth;
use Validator;
use DB;
use Excel;

class employeeController extends Controller
{
    function __construct()
    {
    	$this->user = new User;
    	$this->emp = new Employee;
        $this->cost = new CostCenter;
        $this->allowance = new Allowance;
        $this->usage = new AllowanceUsage;
        $this->procname = new ProcessName;
    }
    public function index()
    {
        $data['emp'] = $this->emp->where('EmpStatus','A')->get();
        $data['processName'] = $this->procname->where('status','=',1)->orderBy('process_name','ASC')->get();
        $data['emp2'] = $this->emp->select('Employee.*','ncc.new')->where('EmpStatus','A')->leftjoin('NewCostCenter as ncc','ncc.old','=','Employee.CostCenterCode')->orderBy('EmpNo','ASC')->get();
        $data['title'] = 'Employee Masterlist';
        $data['menu_name'] = 'employee';
        $data['emp_list'] = 'active_emp';
        $data['routes'] = route('emplist');
        return view('employee-master',$data);
    }
    public function inactive_list()
    {
        $data['resign'] = $this->emp->select('Employee.*','ncc.new')->where('EmpStatus','I')->leftjoin('NewCostCenter as ncc','ncc.old','=','Employee.CostCenterCode')->orderBy('EmpNo','ASC')->get();
        $data['title'] = 'Employee Inactive List';
        $data['menu_name'] = 'employee';
        $data['emp_list'] = 'ina_emp';
        $data['routes'] = route('inactivelist');
        return view('employee-inactive',$data);
    }
    public function resigned()
    {
        $data['resign'] = $this->emp->select('Employee.*','ncc.new')->where('EmpStatus','R')->leftjoin('NewCostCenter as ncc','ncc.old','=','Employee.CostCenterCode')->orderBy('EmpNo','ASC')->get();
        $data['title'] = 'Employee Resigned List';
        $data['menu_name'] = 'employee';
        $data['emp_list'] = 'res_emp';
        $data['routes'] = route('resigned');
        return view('employee-resign',$data);
    }
    // Mass Upload of new Employee view
    public function mass_view()
    {
        $data['title'] = 'Mass Upload of New Employee';
        $data['menu_name'] = 'employee';
        $data['emp_list'] = 'mass_emp';
        $data['routes'] = route('mass_view');
        return view('employee-mass',$data);
    }
    public function mass_temp()
    {
        Excel::create('Mass Template', function($excel){
            $excel->sheet('Employee',function ($sheet)
            {
                $sheet->row(1,array('Employee ID','Surname','Firstname','Middlename','Emp Class','Process','Date Hired','Date Cutoff'));
            });
        })->export('xlsx');
    }
    public function mass_upload(Request $request)
    {
        $rules = [
            'allowance' => 'required',
            'upload' => 'required|mimes:xls,xlsx'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>$validator->messages()]);
        }
        else{
            $path = $request->file('upload')->getRealPath();
            config(['excel.import.startRow' => 1 ]);
            $data = Excel::load($path)->get();

            if ($data->count() > 0) {
                foreach ($data->toArray() as $key => $value) {
                    $empid = con_emp_id($value['employee_id']);
                    $validate = $this->emp->where('EmpNo','=',$empid)->count();
                    if ($validate >= 1) {

                    }
                    else{
                        $total_days = $this->getWorkingDaysPerEmp($value['date_hired'],$value['date_cutoff']);
                        $total_allowance = $request->get('allowance') * $total_days;
                        $cost_center = $this->procname->select('b.old as old')->where('status','=',1)->where('process_name','=',$value['process'])->leftjoin('NewCostCenter as b','b.new','=','ProcessName.costcode')->first();
                        $insert_data_1[] = array(
                            'EmpNo' => $empid,
                            'EmpFirstName' => $value['firstname'],
                            'EmpLastName' => $value['surname'],
                            'EmpMiddleName' => $value['middlename'],
                            'EmpType' => 'R',
                            'EmpDateHired' => date('Y-m-d h:i:s',strtotime($value['date_hired'])),
                            'CostCenterCode' => $cost_center->old,
                            'EmpSubProcessCode' => '00',
                            'EmpClass' => $value['emp_class'],
                            'EmpStatus' => 'A',
                            'DateCreated' => Carbon\Carbon::now(),
                            'CreatedBy' =>Auth::user()->username
                        );
                        $insert_data_2[] = array(
                            'EmpNo'=> $empid,
                            'CostCenterCode'=>$cost_center->old,
                            'AllowanceAmtRegPrev'=>'0.00',
                            'AllowanceAmtReg'=> $total_allowance,
                            'AllowanceAmtRegUsage'=>'0.00',
                            'AllowanceAmtOvtPrev'=>'0.00',
                            'AllowanceAmtOvt'=>'0.00',
                            'AllowanceAmtOvtUsage'=>'0.00',
                            'AllowanceAmtOthPrev'=>'0.00',
                            'AllowanceAmtOth'=>'0.00',
                            'AllowanceAmtOthUsage'=>'0.00',
                            'AllowanceFlag'=>'0',
                            'DateCreated'=>Carbon\Carbon::now(),
                            'CreatedBy'=>Auth::user()->username
                        );
                    }
                }
            }
            if (!empty($insert_data_1) AND !empty($insert_data_2)) {
                $insert_data_1 = collect($insert_data_1);
                $insert_data_2 = collect($insert_data_2);
                $slice_1 = $insert_data_1->chunk(1);
                $slice_2 = $insert_data_2->chunk(1);
                ini_set('max_execution_time', 120 ) ;
                foreach ($slice_1 as $chop_1) {
                    $this->emp->insert($chop_1->toArray());
                }
                foreach ($slice_2 as $chop_2) {
                    $this->allowance->insert($chop_2->toArray());
                }

                activity_logs("Mass uploading of Employee",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Mass uploading of new Employee is Sucess']]);
            }
            else{
                return response()->json(['error' => True,'message'=>['Failed to Upload']]);
            }
        }

    }
    // End of mass upload
    public function save_employee(Request $request)
    {
        $rules = [
            'EmpNo'=>'unique:Employee|required|max:9',
            'last_name'=>'required',
            'first_name'=>'required',
            'middle_name'=>'required',
            'date_hired'=>'required',
            'type'=>'required',
            'cost_center_code'=>'required',
            'classification'=>'required',
            'date_end'=>'required',
            'total_days'=>'required',
            'allowance'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
            $empid = con_emp_id($request->get('EmpNo')); 
            $check = $this->emp->where('EmpNo','=',$empid)->count();
            if ($check >= 1) {
                return response()->json(['error' => true,'message'=>['Employee ID is existing']]);
            }
            else{
                try {
                    $get_cost_code = $this->cost->where('new','=',$request->get('cost_center_code'))->first();
                    $push_emp_info = $this->emp->insert([
                        'EmpNo'=>$empid,
                        'EmpFirstName'=>$request->get('first_name'),
                        'EmpLastName'=>$request->get('last_name'),
                        'EmpMiddleName'=>$request->get('middle_name'),
                        'EmpType'=>$request->get('type'),
                        'EmpDateHired'=>date("Y-m-d H:i:s",strtotime($request->get('date_hired'))),
                        'CostCenterCode'=>$get_cost_code->old,
                        'EmpSubProcessCode'=>'00',
                        'EmpClass'=>$request->get('classification'),
                        'EmpStatus'=>'A',
                        'DateCreated'=>Carbon\Carbon::now(),
                        'CreatedBy'=>Auth::user()->username
                    ]);
                    if ($push_emp_info) {
                        $total_allowance = $request->get('total_days') * $request->get('allowance');
                        $this->allowance->insert([
                            'EmpNo'=>$empid,
                            'CostCenterCode'=>$get_cost_code->old,
                            'AllowanceAmtRegPrev'=>'0.00',
                            'AllowanceAmtReg'=> $total_allowance,
                            'AllowanceAmtRegUsage'=>'0.00',
                            'AllowanceAmtOvtPrev'=>'0.00',
                            'AllowanceAmtOvt'=>'0.00',
                            'AllowanceAmtOvtUsage'=>'0.00',
                            'AllowanceAmtOthPrev'=>'0.00',
                            'AllowanceAmtOth'=>'0.00',
                            'AllowanceAmtOthUsage'=>'0.00',
                            'AllowanceFlag'=>'0',
                            'DateCreated'=>Carbon\Carbon::now(),
                            'CreatedBy'=>Auth::user()->username
                        ]);
                        activity_logs("Added a new employee",Auth::user()->username,Carbon\Carbon::now());
                        return response()->json(['error' => false,'message'=>['Employee is successfully added']]);
                    }
                } catch (Exception $e) {
                    return response()->json(['error' => true,'message'=>['Something Went Wrong Please Contact the Sys Dev']]);
                }
            }
        }
    }
    public function update_emp(Request $request)
    {
        $rules = [
            'type'=>'required',
            'classification'=>'required',
            'status'=>'required',
            'cost_center_code'=>'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
            $get_cost_code = $this->cost->where('new','=',$request->get('cost_center_code'))->first();
            if (empty($request->get('date_end'))) {
                $this->emp->where('EmpNo','=',$request->get('EmpNo'))->update([
                    'EmpType' =>$request->get('type'),
                    'EmpClass' =>$request->get('classification'),
                    'EmpStatus' =>$request->get('status'),
                    'EmpEndDate' => NULL,
                    'CostCenterCode' =>$get_cost_code->old
                ]);
                $this->allowance->where('EmpNo','=',$request->get('EmpNo'))->update([
                    'CostCenterCode' =>$get_cost_code->old
                ]);
                activity_logs("updated an employee (".$request->get('EmpNo').")",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Successfully Saved']]);
                // return response()->json(['error' => true,'message'=>[$get_cost_code->old]]);
            }
            else{
               $this->emp->where('EmpNo','=',$request->get('EmpNo'))->update([
                    'EmpType' =>$request->get('type'),
                    'EmpClass' =>$request->get('classification'),
                    'EmpStatus' =>$request->get('status'),
                    'EmpEndDate' =>$request->get('date_end'),
                    'CostCenterCode' =>$get_cost_code->old
                ]);
               activity_logs("updated an employee(".$request->get('EmpNo').")",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Successfully Saved']]); 
            }
        }
    }
    public function update_allowance(Request $request)
    {
        $rules = [
            'reg_all'=>'required',
            'ot_all'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
            $old_data = $this->allowance->where('EmpNo','=',$request->get('empNo'))->first();
            $this->allowance->where('EmpNo','=',$request->get('empNo'))->update([
                'AllowanceAmtRegPrev' => $old_data->AllowanceAmtReg,
                'AllowanceAmtReg' => ($old_data->AllowanceAmtReg - $old_data->AllowanceAmtRegUsage) + $request->get('reg_all'),
                'AllowanceAmtRegUsage' =>0,
                'AllowanceAmtOvtPrev' => $old_data->AllowanceAmtOvt,
                'AllowanceAmtOvt' =>($old_data->AllowanceAmtOvt - $old_data->AllowanceAmtOvtUsage) + $request->get('ot_all'),
                'AllowanceAmtOvtUsage' =>0
            ]);
            $this->usage->insert([
                
            ]);
            activity_logs("updated of allowance of this employee no. ".$request->get('empNo'),Auth::user()->username,Carbon\Carbon::now());
            return response()->json(['error' => false,'message'=>['Allowance is successfully Updated']]);
        }
    }
    public function cost_validation(Request $req)
    {
        $cost_center = $this->cost->where('new','=',$req->get('code'))->count();
        if ($cost_center > 0) {
            $get = $this->cost->where('new','=',$req->get('code'))->first();
            return response()->json(['error' => false,'message'=>['Cost Center Valid'],'cost_center'=>[$get->old]]);
        }
        else{
            return response()->json(['error' => true,'message'=>['Invalid Cost Center Code']]);
        }
    }
    public function view_emp($id)
    {
        $now = Carbon\Carbon::today();
        $until = $now->settime(23,59,59);
        $get_bal = $this->allowance->where('EmpNo','=',$id)->first();
        $data['format_date'] = date('m-d-y');
        $data['empNo'] = $id;
        $data['processName'] = $this->procname->orderBy('process_name','ASC')->get();
        $data['processNameUsed'] = $this->procname->select('processname.process_name','processname.costcode','processname.status')->leftjoin('NewCostCenter as a','a.new','=','processname.costcode')->where('a.old','=',$get_bal->CostCenterCode)->first();
        $data['processNameValidate'] = $this->procname->select('processname.process_name','processname.costcode')->leftjoin('NewCostCenter as a','a.new','=','processname.costcode')->where('a.old','=',$get_bal->CostCenterCode)->count();
        $data['details'] = $this->emp->select('Employee.*','cc.new','cc.old')->where('EmpNo','=',$id)->leftjoin('NewCostCenter as cc','Employee.CostCenterCode','=','cc.old')->first();
        $data['infos'] = $this->emp->select('Employee.*','cc.new','cc.old')->where('EmpNo','=',$id)->leftjoin('NewCostCenter as cc','Employee.CostCenterCode','=','cc.old')->get();
        $data['regallowance'] = ($get_bal->AllowanceAmtReg - $get_bal->AllowanceAmtRegUsage);
        $data['otallowance'] = ($get_bal->AllowanceAmtOvt - $get_bal->AllowanceAmtOvtUsage);
        $data['currentTrans'] = $this->usage->where('EmpNo','=',$id)->where('UsageType','=','T')->whereBetween('TransactionDate',[$now,$until])->orderBy('TransactionDate','DESC');
        $data['title'] = 'Employee Information';
        $data['menu_name'] = 'employee';
        $data['emp_list'] = 'active_emp';
        $data['routes'] = url('/view-emp').'/'.$id;
        return view('employee-view',$data);
    }
    public function date_conversion_generation(Request $req)
    {
        $data['date1'] = date("Y-m-d",strtotime($req->get('date1')));
        $data['date2'] = date("Y-m-d",strtotime($req->get('date2')));

        return response()->json(['converted' => true,'dates'=>[$data]]);
    }
    public function generateTransEmp($id,$date_start,$date_end)
    {
        $date_start_q = date("Y-m-d 00:00:00",strtotime($date_start)); 
        $date_end_q = date("Y-m-d 23:59:59",strtotime($date_end));
        $info = $this->emp->where('EmpNo','=',$id)->first();
        $usage = $this->usage->select('AllowanceUsage.*','b.canteenname')->leftjoin('canteen as b',DB::raw("SUBSTRING(ltrim(AllowanceUsage.CreatedBy), 1, 2)"),'=','b.canteencode')->where('AllowanceUsage.EmpNo','=',$id)->where('AllowanceUsage.UsageType','=','T')->whereBetween('AllowanceUsage.TransactionDate',[$date_start_q,$date_end_q])->get();
        Excel::create('Employee #'.$id, function($excel) use($usage,$info,$date_start,$date_end){
            $excel->sheet('Allowance Usage',function ($sheet) use($usage,$info,$date_start,$date_end)
            {
                $sheet->cells('A1:F1', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });
                $sheet->cells('A2:F2', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontSize(10);
                });
                $sheet->mergeCells('A1:F1')->setCellValue('A1','Allowance Usage of '.$info->EmpFirstName.' '.$info->EmpLastName);
                $sheet->mergeCells('A2:F2')->setCellValue('A2',date('m/d/y',strtotime($date_start)).'-'.date('m/d/y',strtotime($date_end)));
                $sheet->row(3,array('Employee ID','Receipt No.','Order Description','Canteen Name','Amount','Transaction Date'));

                foreach ($usage as $key => $use) {
                    $sheet->row(4+$key,array($use->EmpNo,$use->Receipt,$use->OrderedItemsDescription,$use->canteenname,gen_currency($use->AllowanceUsageAmount),$use->TransactionDate));
                }

            });
        })->export('xlsx');
    }
}
