<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('auth/login_2');
});
Route::get('/login2', 'ClientController@showlogin')->name('login');
Route::post('/validate', 'LoginController@authenticate')->name('validate');

Route::auth();
Route::group(['middleware' => ['auth']],function(){
// Dashboard
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

// Employee
Route::get('/master-list', 'employeeController@index')->name('emplist');
Route::get('/inactive-list', 'employeeController@inactive_list')->name('inactivelist');
Route::get('/Resigned-List', 'employeeController@resigned')->name('resigned');
	// Mass Upload List
Route::get('/Mass-Upload', 'employeeController@mass_view')->name('mass_view');
Route::get('/Mass-Template', 'employeeController@mass_temp')->name('mass_temp');
Route::post('/Mass-Upload-File', 'employeeController@mass_upload')->name('mass_upload');
	// End
Route::get('/check-code', 'employeeController@cost_validation')->name('check-code');
Route::post('/save-emp', 'employeeController@save_employee')->name('save-emp');
Route::post('/update-emp', 'employeeController@update_emp')->name('update-emp');
Route::post('/update-all', 'employeeController@update_allowance')->name('update-all');
Route::get('/master-list/view-emp/{id}', 'employeeController@view_emp')->name('view-emp');
Route::get('/convert-date', 'employeeController@date_conversion_generation')->name('dateconversion');
Route::get('/Generate-Per-Emp/{id}/{date_start}/{date_end}', 'employeeController@generateTransEmp')->name('gentransemp');

// Allowance
	// Navigation
Route::get('/Regular-Allowance', 'allowanceController@RegView')->name('RegView');
Route::get('/OverTime-Allowance', 'allowanceController@OTView')->name('OTView');
Route::get('/List-Of-Generated-Allowance', 'allowanceController@getGenlist')->name('GenList');
Route::get('/Pending-For-Approval', 'allowanceController@pendingList')->name('PenList');
	// View
Route::get('/Generated-Allowance/View/{id}', 'allowanceController@viewGen')->name('GenView');
	// Get working Days
Route::get('/getWorking', 'allowanceController@getWorkingDays')->name('getWorkingDays');
	// Generation of reg Meal Allowance
Route::post('/generateReg', 'allowanceController@generateRegAll')->name('generateReg');
	// Generate Employee list for ot
Route::get('/GenerateEmpListForOT', 'allowanceController@GenerateEmpListForOT')->name('GenerateEmpListForOT');
Route::post('/Import-Generated-OT', 'allowanceController@Upload_OTAllowance')->name('Import-Generated-OT');
Route::post('/Generate-List', 'allowanceController@generate_allowance_details')->name('Generate-List');
	// Get Count of pending per user
Route::get('/countnotif', 'allowanceController@TotalNotif')->name('notif');
	// Approval Function
Route::any('/Approval-Section', 'allowanceController@Update_status')->name('Approval-Section');


// User Maintenance
Route::get('/user-maintenance', 'userController@index')->name('userlist');
Route::post('/user-save', 'userController@save')->name('usersave');
Route::get('/user-details', 'userController@get_detials')->name('user-details');
Route::post('/user-update', 'userController@update')->name('userupdate');
Route::post('/user-delete', 'userController@delete')->name('userdelete');
Route::post('/change-pass', 'userController@change_password')->name('change-pass');

// Cost Center Maintenance
Route::get('/Cost-Center-Code', 'CostCenterController@index')->name('costcenter');
Route::get('/code-details', 'CostCenterController@getCode')->name('code-details');
Route::get('/validate-code', 'CostCenterController@validateCode')->name('validate-code');
Route::get('/validate-name', 'CostCenterController@validateName')->name('validate-name');
Route::post('/saveCode', 'CostCenterController@saveCode')->name('saveCode');
Route::post('/updateCode', 'CostCenterController@updateCode')->name('updateCode');

// Report Generation
Route::get('/Cost-Center-Report-Generation', 'ReportController@costcenter_rep')->name('costcenter_rep');
Route::get('/Historical-Report-Generation', 'ReportController@historical_rep')->name('historical_rep');
Route::get('/Audit-Trail-Report', 'ReportController@audit_rep')->name('audit_rep');
	// Generate Report
	Route::post('/Generate-Cost', 'ReportController@gen_costcenter')->name('gen_costcenter');
	Route::post('/Generate-Historical', 'ReportController@gen_historical')->name('gen_historical');
	Route::post('/Generate-Audit', 'ReportController@gen_audit_trail')->name('gen_audit_trail');

// Canteen
Route::get('/Canteen-Maintenance', 'CanteenController@index')->name('canteen');
Route::post('/Canteen-Maintenance/save', 'CanteenController@saveCanteen')->name('saveCanteen');
Route::get('/Canteen-Maintenance/getInfo', 'CanteenController@getdetails')->name('getdetailsCan');
Route::post('/Canteen-Maintenance/update', 'CanteenController@upcanteen')->name('upcanteen');
});
