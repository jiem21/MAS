<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\AllowanceGenerated;
use App\Http\Requests;
use Mail;
use DB;
class mailController extends Controller
{
	function __construct()
	{
		$this->user = new User;
		$this->generatedAll = new AllowanceGenerated;
	}
	public function sendNotif($receiver,$gen_id)
	{
		$mailer_info = $this->user->where('id','=',$receiver)->first();
		$data['receiver'] = $this->user->where('id','=',$receiver)->first();
		$data['meal_gen'] = $this->generatedAll->where('id','=',$gen_id)->first();
		Mail::send('email.notification', $data, function($message) use ($mailer_info){
            $message->to($mailer_info->email)->subject('Meal Allowance New Pending Request');
        });
	}

	public function sendDisapproved($receiver,$gen_id)
	{
		$mailer_info = $this->user->where('id','=',$receiver)->first();
		$data['receiver'] = $this->user->where('id','=',$receiver)->first();
		$data['meal_gen'] = $this->generatedAll->where('id','=',$gen_id)->first();
		Mail::send('email.disapproved', $data, function($message) use ($mailer_info){
            $message->to($mailer_info->email)->subject('Meal Allowance Disapproved Request');
        });
	}
}
