<?php
use App\User;
use App\Logs;
use App\CostCenter;
use App\ProcessName;


if (! function_exists('activity_logs')) {
  function activity_logs($activity_name,$activity_user,$activity_date) {
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
      $ip = $_SERVER['REMOTE_ADDR'];
    }

   $logs = new Logs;
   $logs->activity_name = $activity_name;
   $logs->activity_user = $activity_user;
   $logs->activity_date = $activity_date;
   $logs->ip_address = $ip;
   $logs->save();
   
 }
}

if (! function_exists('cost_status')) {
  function cost_status($status) {
   switch ($status) {
     case 1:
     return 'Active Code';
     break;

     case 0:
     return 'Obsolete Code';
     break;
     
     default:
       return 'Error Code';
       break;
   }
 }
}

if (! function_exists('cost_center_status')) {
  function cost_center_status($status) {
   switch ($status) {
     case 0:
     return '(Obsolete Code)';
     break;

     case 1:
     return '';
     break;
     
     default:
       return 'Error Code';
       break;
   }
 }
}

if (! function_exists('get_proc_name')) {
  function get_proc_name($costcenter) {
   $cost = new ProcessName;
   $name = $cost->where('costcode','=',$costcenter)->first();
   return $name->process_name;
 }
}


if (! function_exists('get_name')) {
  function get_name($id) {
   $user = new User;
   $name = $user->where('id','=',$id)->first();
   return $name->name;
 }
}

if (! function_exists('get_costcode')) {
  function get_costcode($id) {
   $code = new CostCenter;
   $new = $code->where('old','=',$id)->first();
   return $new->new;
 }
}

if (! function_exists('allowance_type')) {
  function allowance_type($id) {
   switch ($id) {
    case 'Reg':
    return 'Regular';
    break;
    case 'OT':
    return 'Overtime';
    break;

    default:
    return 'Invalid Type';
    break;
  }
}
}

if (! function_exists('role')) {
  function role($id) {
   switch ($id) {
    case '1':
    return 'Admin';
    break;
    case '2':
    return 'Approver';
    break;
    case '3':
    return 'Reviewer';
    break;
    case '4':
    return 'General User';
    break;

    default:
    return 'Invalid Role';
    break;
  }
}
}
if (! function_exists('classification')) {
  function classification($id) {
   switch ($id) {
    case 'M':
    return 'Monthly';
    break;
    case 'D':
    return 'Daily';
    break;

    default:
    return 'Invalid Classification';
    break;
  }
}
}

if (! function_exists('date_con')) {
  function date_con($date) {
   $new_date = date("F d, Y",strtotime($date));
   return $new_date;
 }
}
if (! function_exists('datetime_con')) {
  function datetime_con($date) {
   $new_date = date("F d, Y H:i:s",strtotime($date));
   return $new_date;
 }
}

if (! function_exists('script_con')) {
  function script_con($date) {
   $new_date = date("m-d-y",strtotime($date));
   return $new_date;
 }
}

if (! function_exists('emp_type')) {
  function emp_type($type) {
   switch ($type) {
     case 'R':
     return "Regular";
     break;
     case 'C':
     return "Contractual";
     break;

     default:
     return "Invalid";
     break;
   }
 }
}

if (! function_exists('con_emp_id')) {
  function con_emp_id($id) {
   $new_id = str_pad($id, 9, 0, STR_PAD_LEFT);
   return $new_id;
 }
}

if (! function_exists('currency')) {
  function currency($money) {
   $money = number_format($money,2);
   return $money;
 }
}

if (! function_exists('gen_currency')) {
  function gen_currency($money) {
   $money = number_format($money,2);
   $moeny = '₱'.$money;
   return $money;
 }
}

if (! function_exists('allowance_type')) {
  function allowance_type($type) {
   switch ($type) {
     case 'Reg':
     return "Regular Allowance";
     break;
     case 'OT':
     return "OT Allowance";
     break;

     default:
     return "Invalid";
     break;
   }
 }
}

if (! function_exists('allowance_status')) {
  function allowance_status($status) {
   switch ($status) {
     case '0':
     return "Pending";
     break;
     case '1':
     return "Approved";
     break;
     case '2':
     return "Disapproved";
     break;

     default:
     return "Invalid";
     break;
   }
 }
}

// Main Status
if (! function_exists('main_status')) {
  function main_status($status) {
   switch ($status) {
     case '0':
     return "Pending";
     break;
     case '1':
     return "Approved";
     break;
     case '2':
     return "Disapproved";
     break;

     default:
     return "Invalid";
     break;
   }
 }
}

if (! function_exists('canteen_status')) {
  function canteen_status($status) {
   switch ($status) {
     case '1':
     return "Active";
     break;
     case '2':
     return "Inactive";
     break;

     default:
     return "Invalid";
     break;
   }
 }
}