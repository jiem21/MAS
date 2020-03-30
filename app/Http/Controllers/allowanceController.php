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
use App\Http\Controllers\mailController;

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
        $this->mail = new mailController;
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
        $data['id'] = $id;
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
            $count = $this->generatedAll->where('status_approve','=',0)->where('status_review','=',1)->where('approved_by','=',$userid)->count();
        }elseif($role == 1){
            // Admin Side
            $count = $this->generatedAll->where('status','=',0)->count();
        }else{
            // Reviewer Side
            $count = $this->generatedAll->where('status_review','=',0)->where('status','=',0)->where('reviewed_by','=',$userid)->count();
        }
        return $count;
    }
    public function pendingList()
    {
        $userid = Auth::user()->id;
        $role = Auth::user()->role;
        if ($role == 2) {
            // Approver Side
            $data['lists'] = $this->generatedAll->where('status_review','=',1)->where('status','=',0)->where('approved_by','=',$userid)->orderBy('date_created','DESC')->get();
        }elseif($role == 1){
            // Admin Side
            $data['lists'] = $this->generatedAll->where('status','=',0)->orderBy('date_created','DESC')->get();
        }else{
            // Reviewer Side
            $data['lists'] = $this->generatedAll->where('status_review','=',0)->where('status','=',0)->where('reviewed_by','=',$userid)->orderBy('date_created','DESC')->get();
        }
        $data['menu_name'] = 'meal_gen';
        $data['meal_list'] = 'penlist';
        $data['routes'] = route('PenList');
        return view('Allowance.pendingAll',$data);
    }

// Generation of Regular Meal Allowance
    public function generateRegAll(Request $request)
    {
        // Data validation
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
        // Insertion of data to allowanceGenerated Table and it will get the inserted ID.
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
        // Get the working days
            $workingday = $request->get('days');
        // Get the allowance
            $allowance = $request->get('allowance');
        // Allowance Computation
            $total_allowance = $request->get('days') * $request->get('allowance');
        // Insertion of pending allowance per employee on AllowancePending Table
            DB::statement("INSERT INTO AllowancePending (EmpNo,allowance,total_days,total_allowance,allowanceType,allowanceGenID,date_generated,created_by) SELECT EmpNo,'".$allowance."','".$workingday."','".$total_allowance."','Reg','".$id."','".Carbon\Carbon::now()."','".Auth::user()->username."' FROM Employee WHERE EmpStatus = 'A'");
        // Will count the total employee of generated Allowance.
            $get_total_count = $this->allpen->where('allowanceGenID','=',$id)->count();
        // Sum of all employee allowance.
            $sum_allowance = $this->allpen->where('allowanceGenID','=',$id)->sum('total_allowance');
        // Update AllowanceGenerated table for the total employee and sum of all allowance on that generation request.
            $update = $this->generatedAll->where('id','=',$id)->update([
                'total_emp'=> $get_total_count,
                'total_allowance'=> $sum_allowance
            ]);
            if ($update) {
        // Email Notification for the reviewer and approver
                $this->mail->sendNotif($request->get('reviewed_by'),$id);
                $this->mail->sendNotif($request->get('approved_by'),$id);
        // Activity log save.
                activity_logs("Generate Regular Allowance",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Generation of Regular Allowance is Complete']]);
            }
            else{
                return response()->json(['error' => true,'message'=>['Something Went wrong please contact IT Personnel']]);
            }
        }
    }

// Generation of OT Meal Allowance.
        // generate of employee list of active and inactive employee export to excel
    public function GenerateEmpListForOT()
    {
        $employee = $this->emp->select('Employee.*','b.new',DB::raw('(c.AllowanceAmtOvt - c.AllowanceAmtOvtUsage) as RegAll'))->leftjoin('NewCostCenter as b','Employee.CostCenterCode','=','b.old')->leftjoin('Allowance as c','Employee.EmpNo','=','c.EmpNo')->where('EmpStatus','!=','R')->get();
        Excel::create('Employee List', function($excel) use($employee){
            $excel->sheet('Employee',function ($sheet) use($employee)
            {
                $sheet->cells('A1:C1', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });
                $sheet->cells('A2:C2', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontSize(10);
                });
                $sheet->mergeCells('A1:C1')->setCellValue('A1','Employee List');
                $sheet->mergeCells('A2:C2')->setCellValue('A2',date('m/d/y h:i:s A',strtotime(Carbon\Carbon::now())));
                $sheet->row(3,array('Employee ID','Employee Name','Number of Days'));

                foreach ($employee as $key => $emp) {
                    $sheet->row(4+$key,array($emp->EmpNo,$emp->EmpLastName.', '.$emp->EmpFirstName.' '.$emp->EmpMiddleName,0));
                }

            });
        })->export('xlsx');
    }
    // Uploading of excel and import data to table.
    public function Upload_OTAllowance(Request $request)
    {
        // Data validation of the system.
        $rules = [
            'date_from' => 'required',
            'date_to' => 'required',
            'allowance' => 'required',
            'upload' => 'required|mimes:xls,xlsx',
            'reviewed_by' => 'required',
            'approved_by' => 'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>$validator->messages()]);
        }
        else{
            // Insertion of data to allowanceGenerated Table and it will get the inserted ID.
            $id = $this->generatedAll->insertGetId([
                'date_from'=>$request->get('date_from'),
                'date_to'=>$request->get('date_to'),
                'allowance'=>$request->get('allowance'),
                'allowance_type'=>'OT',
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
            // Get the excel file path
            $path = $request->file('upload')->getRealPath();
            // This code will set reading of the to row 3
            config(['excel.import.startRow' => 3 ]);
            // Reading of all data
            $data = Excel::load($path)->get();
            // Transferring of data to array form
            if ($data->count() > 0) {
                foreach ($data->toArray() as $key => $value) {
                    // $value['ColumnNameOnExcel'] change the uppercase to lowercase then all white space convert it to underscore
                    $insert_data[] = array(
                        'EmpNo' => $value['employee_id'],
                        'allowance' => $request->get('allowance'),
                        'total_days' => $value['number_of_days'],
                        'total_allowance' => $value['number_of_days'] * $request->get('allowance'),
                        'allowanceType' => 'OT',
                        'allowanceGenID' => $id,
                        'date_generated' => Carbon\Carbon::now(),
                        'created_by' => Auth::user()->username
                    );
                }
            }
            if (!empty($insert_data)) {
                // Getting of all data
                $insert_data = collect($insert_data);
                // Slice the data into 1
                $slice = $insert_data->chunk(1);
                // Change the execution for 360 seconds
                ini_set('max_execution_time',360);
                // insertion of data per employee into AllowancePending Table
                foreach ($slice as $chop) {
                   $this->allpen->insert($chop->toArray());
                }
                // Will count the total employee of generated Allowance.
                $get_total_count = $this->allpen->where('allowanceGenID','=',$id)->count();
                // Sum of all employee allowance.
                $sum_allowance = $this->allpen->where('allowanceGenID','=',$id)->sum('total_allowance');
                // Update AllowanceGenerated table for the total employee and sum of all allowance on that generation request.
                $update = $this->generatedAll->where('id','=',$id)->update([
                    'total_emp'=> $get_total_count,
                    'total_allowance'=> $sum_allowance
                ]);
                // Email Notification for the reviewer and approver
                $this->mail->sendNotif($request->get('reviewed_by'),$id);
                $this->mail->sendNotif($request->get('approved_by'),$id);
                // Activity log save.
                activity_logs("Generate OT Allowance",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Generation of OT Meal Allowance is Complete']]);
            }
            else{
                return response()->json(['error' => True,'message'=>['Failed to Generate OT Allowance']]);
            }
        }
    }
    // Update Approver Status
    public function Update_status(Request $req)
    {
        $information = $this->generatedAll->where('id','=',$req->get('id'))->first();
        if ($req->get('type') == 2) {
            // If Approve for Approver
            if ($req->get('status') == 1) {
                DB::beginTransaction();
                try {
                    // Check if Regular or OT Allowance
                    // Regular Allowance
                    if ($req->get('alltype') == 'Reg') {

                        DB::statement("INSERT INTO AllowanceUsage (EmpNo,CostCenterCode,Receipt,Cashier,AllowanceUsageAmount,AllowanceAmtRegUsage,AllowanceAmtOvtUsage,AllowanceAmtOthUsage,TransactionDate,OrderedItemsDescription,UsageType,DateCreated,CreatedBy) SELECT a.EmpNo,b.CostCenterCode,'ALLOWANCE UPDATE HISTORY - RG','ALLOWANCE UPDATE HISTORY - RG',a.total_allowance,((b.AllowanceAmtReg - b.AllowanceAmtRegUsage) + a.total_allowance),(b.AllowanceAmtOvt - b.AllowanceAmtOvtUsage),(b.AllowanceAmtOth - b.AllowanceAmtOthUsage),'".Carbon\Carbon::now()."','ALLOWANCE UPDATE HISTORY - RG','H','".Carbon\Carbon::now()."','SYSTEM' from AllowancePending as a LEFT JOIN Allowance as b on a.EmpNo = b.EmpNo WHERE a.allowanceGenID = ".$req->get('id'));

                       $this->allowance
                       ->select('Allowance.total_allowance as total_allowance','AllowancePending.AllowanceAmtRegPrev as AllowanceAmtRegPrev','AllowancePending.AllowanceAmtReg as AllowanceAmtReg','AllowancePending.AllowanceAmtRegUsage as AllowanceAmtRegUsage')
                       ->where('a.allowanceGenID','=',$req->get('id'))
                       ->rightjoin('AllowancePending as a' ,'Allowance.EmpNo','=','a.EmpNo')
                       ->update([
                        'AllowanceAmtRegPrev' => DB::raw('AllowanceAmtReg'),
                        'AllowanceAmtReg' => DB::raw('(AllowanceAmtReg - AllowanceAmtRegUsage) + total_allowance'),
                        'AllowanceAmtRegUsage' => 0.00
                       ]);

                       $this->generatedAll->where('id','=',$req->get('id'))->update([
                            'status_approve' => $req->get('status'),
                            'approved_date' => Carbon\Carbon::now(),
                            'status' => 1
                        ]);

                       DB::commit();
                       activity_logs("Approved and Uploaded the allowance request for regular",Auth::user()->username,Carbon\Carbon::now());
                       return response()->json(['error' => false,'approved' =>true,'message'=>['Regular Meal Allowance Approved']]);
                    }else{
                        // OT Meall Allowance
                        DB::statement("INSERT INTO AllowanceUsage (EmpNo,CostCenterCode,Receipt,Cashier,AllowanceUsageAmount,AllowanceAmtRegUsage,AllowanceAmtOvtUsage,AllowanceAmtOthUsage,TransactionDate,OrderedItemsDescription,UsageType,DateCreated,CreatedBy) SELECT a.EmpNo,b.CostCenterCode,'ALLOWANCE UPDATE HISTORY - OT','ALLOWANCE UPDATE HISTORY - OT',a.total_allowance,(b.AllowanceAmtReg - b.AllowanceAmtRegUsage),((b.AllowanceAmtOvt - b.AllowanceAmtOvtUsage) + a.total_allowance),(b.AllowanceAmtOth - b.AllowanceAmtOthUsage),'".Carbon\Carbon::now()."','ALLOWANCE UPDATE HISTORY - OT','H','".Carbon\Carbon::now()."','SYSTEM' from AllowancePending as a LEFT JOIN Allowance as b on a.EmpNo = b.EmpNo WHERE a.allowanceGenID = ".$req->get('id'));

                        $this->allowance
                       ->select('Allowance.total_allowance as total_allowance','AllowancePending.AllowanceAmtOvtPrev as AllowanceAmtOvtPrev','AllowancePending.AllowanceAmtOvt as AllowanceAmtOvt','AllowancePending.AllowanceAmtOvtUsage as AllowanceAmtOvtUsage')
                       ->where('a.allowanceGenID','=',$req->get('id'))
                       ->rightjoin('AllowancePending as a' ,'Allowance.EmpNo','=','a.EmpNo')
                       ->update([
                        'AllowanceAmtOvtPrev' => DB::raw('AllowanceAmtOvt'),
                        'AllowanceAmtOvt' => DB::raw('(AllowanceAmtOvt - AllowanceAmtOvtUsage) + total_allowance'),
                        'AllowanceAmtOvtUsage' => 0.00
                       ]);

                       $this->generatedAll->where('id','=',$req->get('id'))->update([
                            'status_approve' => $req->get('status'),
                            'approved_date' => Carbon\Carbon::now(),
                            'status' => 1
                        ]);
                        DB::commit();
                        activity_logs("Approved and Uploaded the allowance request for OT",Auth::user()->username,Carbon\Carbon::now());
                       return response()->json(['error' => false,'approved' =>true,'message'=>['Overtime Meal Allowance Approved']]);
                    }
                } catch (Exception $e) {
                    return response()->json(['error' => true,'approved' =>false,'message'=>[$e->getMessage()]]);
                   DB::rollback();
               }
            }
            else{
                // Disapprove by Approver
                $rules = [
                    'reasons'=>'required'
                ];
                $validator = Validator::make($req->all(),$rules);
                if ($validator->fails()) {
                   return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
                }
               else{
                $this->generatedAll->where('id','=',$req->get('id'))->update([
                    'status_approve' => $req->get('status'),
                    'approver_reason' => $req->get('reasons'),
                    'approved_date' => Carbon\Carbon::now(),
                    'status' => 2,
                ]);
                $prepared_by_id = $information->prepared_by;
                $this->mail->sendDisapproved($prepared_by_id,$req->get('id'));
                activity_logs("Disapproved the request",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'approved' =>false,'message'=>['Disapproved Request']]);
                }  
            }
        }
        else{
            // Approve by Reviewer
            if ($req->get('status') == 1) {
                $this->generatedAll->where('id','=',$req->get('id'))->update([
                    'status_review' => $req->get('status'),
                    'reviewed_date' => Carbon\Carbon::now()
                ]);
                activity_logs("Approved the request",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'approved' =>true,'message'=>['Approved Request']]);
            }
            // Disapprove by Reviwer
            else{
                $rules = [
                    'reasons'=>'required'
                ];
                $validator = Validator::make($req->all(),$rules);
                if ($validator->fails()) {
                   return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
                }
               else{
                $this->generatedAll->where('id','=',$req->get('id'))->update([
                    'status_review' => $req->get('status'),
                    'reviewer_reason' => $req->get('reasons'),
                    'reviewed_date' => Carbon\Carbon::now(),
                    'status' => 2,
                ]);
                $prepared_by_id = $information->prepared_by;
                $this->mail->sendDisapproved($prepared_by_id,$req->get('id'));
                activity_logs("Disapproved the request",Auth::user()->username,Carbon\Carbon::now());
                return response()->json(['error' => false,'approved' =>false,'message'=>['Disapproved Request']]);
                }
            }
        }
    }

    // Generate Allowance Employee List
    public function generate_allowance_details(Request $req)
    {
        $allowanceGen = $this->generatedAll->where('id','=',$req->get('id'))->first();
        $allowancelist = $this->allpen->select('AllowancePending.*','b.EmpFirstName as fn','b.EmpLastName as ln')->where('allowanceGenID','=',$req->get('id'))->leftjoin('Employee as b','AllowancePending.EmpNo','=','b.EmpNo')->get();
        $file_name = $allowanceGen->allowance_type.' - '.date('F d,Y',strtotime($allowanceGen->date_from)).' - '.date('F d,Y',strtotime($allowanceGen->date_to));
        Excel::create($file_name, function($excel) use($allowanceGen,$allowancelist){
            $excel->sheet('Employee List',function ($sheet) use($allowanceGen,$allowancelist){
                // Columns
                $sheet->row(1,array('Employee No.','Fullname','Allowance'));
                foreach ($allowancelist as $key => $value) {
                    $sheet->row(2+$key,array($value->EmpNo,$value->ln.' '.$value->fn,$value->total_allowance));
                }
            });
        })->export('xlsx');
    }
}