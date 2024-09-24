<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\TransactionModel;

use App\Common\Services\MobileAppNotification;
use App\Common\Services\NotificationService;
use App\Common\Services\EmailService;
use App\Common\Services\SMSService;

use Session;
use TCPDF;
use PDF;

class WalletController extends Controller
{
    public function __construct(
									UserModel             $user_model,
									AdminModel            $admin_model,
									TransactionModel      $transaction_model,
									NotificationService   $notification_service,
									MobileAppNotification $mobileappnotification_service,
									EmailService          $email_service,
									SMSService            $sms_service
								)
	{
		$this->arr_view_data         = [];
		$this->module_title          = 'My Wallet';
		$this->module_view_folder    = 'front.wallet';

		$this->UserModel             = $user_model;
		$this->AdminModel            = $admin_model;
		$this->TransactionModel      = $transaction_model;
		$this->NotificationService   = $notification_service;
		$this->MobileAppNotification = $mobileappnotification_service;
		$this->EmailService          = $email_service;
		$this->SMSService            = $sms_service;

		$this->TCPDF                 = new TCPDF();

		$this->auth                  = auth()->guard('users');
		$user                        = $this->auth->user();
		if($user) {
		$this->user_id               = $user->id;
		$this->user_first_name       = $user->first_name;
		$this->user_last_name        = $user->last_name;
      	}  
	}

	/*
    | Function  : Show wallet amount.
    | Author    : Deepak Arvind Salunke
    | Date      : 24/04/2018
    | Output    : Success or Error
    */

	public function index()
	{
		$arr_user = [];

		$obj_user = $this->UserModel->where('id', $this->user_id)->first();
		if($obj_user) {
			$arr_user = $obj_user->toArray();
		}

		$this->arr_view_data['arr_user']   = $arr_user;
		$this->arr_view_data['page_title'] = $this->module_title;

		return view($this->module_view_folder.'.my_wallet',$this->arr_view_data);
	} // end index


	/*
    | Function  : Store payment details after successful payment
    | Author    : Deepak Arvind Salunke
    | Date      : 24/04/2018
    | Output    : Success or Error
    */

	public function payment_store(Request $request)
	{
		$url              = url()->previous();
		$notification_url = str_replace(url('/'), "", $url);
		
		$old_amount       = '';
		$transaction_id   = $request->input('transaction_id');
		$payment_amount   = $request->input('payment_amount');

		$data['transaction_id']   = $transaction_id;
		$data['payment_type']     = 'wallet';
		$data['user_id']          = $this->user_id;
		$data['user_type']        = '1';
		$data['amount']           = $payment_amount;
		$data['transaction_for']  = $payment_amount." INR amount added in wallet";
		$data['transaction_date'] = date('Y-m-d H:i:s');
		$obj_transaction = $this->TransactionModel->create($data);
		
		if($obj_transaction)
		{
			$obj_user_data = $this->UserModel->where('id', $this->user_id)->first();
			$invoice       = $this->generateInvoice($obj_transaction->id);
			$this->TransactionModel->where('id',$obj_transaction->id)->update(['invoice' => $invoice]);
			if($obj_user_data) {
				$arr_user   = $obj_user_data->toArray();
				$old_amount = $arr_user['wallet_amount'];
			}

			$arr_built_content = array(
										'USER_NAME' => isset($this->user_first_name) ? $this->user_first_name : 'NA',
										'SUBJECT'   => $payment_amount." INR amount added in wallet successfully"
									);
			
	        $arr_notify_data['arr_built_content']  = $arr_built_content;
	        $arr_notify_data['notify_template_id'] = '8';
	        $arr_notify_data['sender_id']          = '1';
	        $arr_notify_data['sender_type']        = '2';
	        $arr_notify_data['receiver_type']      = '1';
	        $arr_notify_data['receiver_id']        = $this->user_id;
	        $arr_notify_data['url']                = $notification_url;
	        $notification_status = $this->NotificationService->send_notification($arr_notify_data);

			$user_data['wallet_amount'] = $payment_amount + $old_amount;
			$obj_user     = $this->UserModel->where('id', $this->user_id)->update($user_data);
			if($obj_user) {
				$type = get_notification_type_of_user($this->user_id);

				if(isset($type) && !empty($type)) {
				    // for mail
				    if($type['notification_by_email'] == 'on') {
				        $arr_built_content = [
											'USER_NAME'            => isset($arr_user['display_name'])?ucfirst($arr_user['display_name']):'NA',
											'Email'                => isset($arr_user['email'])?ucfirst($arr_user['email']):'NA' ,
											'MESSAGE'              => $payment_amount." INR amount added to wallet successfully",
											'PROJECT_NAME'         => config('app.project.name'),
											'NOTIFICATION_SUBJECT' => 'Notification'
				                        	];

				        $arr_mail_data                      = [];
				        $arr_mail_data['email_template_id'] = '13';
				        $arr_mail_data['arr_built_content'] = $arr_built_content;
				        $arr_mail_data['user']              = ['email' => isset($arr_user['email']) ? ucfirst($arr_user['email']) : 'NA', 'first_name' => isset($arr_user['display_name']) ? ucfirst($arr_user['display_name']) : 'NA'];
				        $arr_mail_data['attachment']        = public_path('uploads/invoice/'.$invoice);
				        $status = $this->EmailService->send_invoice_mail($arr_mail_data);
				    }

				    // for sms 
				    if($type['notification_by_sms'] == 'on') {
				        $country_code  = isset($arr_user['country_code']) ? $arr_user['country_code'] : '';
				        $mobile_number = isset($arr_user['mobile_number']) ? $arr_user['mobile_number'] : '';

				        $arr_sms_data                  = [];
				        $arr_sms_data['msg']           = $payment_amount." INR amount added to wallet successfully";
				        $arr_sms_data['mobile_number'] = $country_code.$mobile_number;
				        $status = $this->SMSService->send_SMS($arr_sms_data);
				    }

					// for push notification
				    if($type['notification_by_push'] == 'on') {
						$headings = $payment_amount.' INR amount added to wallet successfully';
						$content  = $payment_amount.' INR amount added to wallet successfully.';
						$user_id  = $this->user_id;
						$status = $this->MobileAppNotification->send_app_notification($headings, $content, $user_id);
	                }
				}

				$arr_json['status']  = 'success';
				Session::flash('success', $payment_amount.' INR amount added to wallet successfully');
			} else {
				$arr_json['status']  = 'error';
				Session::flash('error','Something went wrong. Please try again');
			}
		} else {
			$arr_json['status']  = 'error';
			Session::flash('error','Something went wrong. Please try again');
		}

		return response()->json($arr_json);
	} // end payment


	/*
    | Function  : Generate invoice after amount added to wallet successful
    | Author    : Deepak Arvind Salunke
    | Date      : 05/05/2018
    | Output    : Success or Error
    */

	public function generateInvoice($transaction_id = false)
	{
		$data = $guest_data = $arr_transaction = [];

		if(isset($transaction_id) && $transaction_id != false)
		{
			$obj_transaction = $this->TransactionModel->where('id',$transaction_id)->first();
			if($obj_transaction)
			{
				$arr_transaction = $obj_transaction->toArray();
			}

			$obj_user_data = $this->UserModel->where('id', $this->user_id)->first();
			if($obj_user_data)
			{
				$guest_data = $obj_user_data->toArray();
			}

			$data['logo']  	  = url('/front/images/logo-inner.png');
          	$data['base_url'] = url('/');

          	PDF::SetTitle(config('app.project.name'));
            PDF::SetAuthor(config('app.project.name'));
            PDF::SetCreator(PDF_CREATOR);
            PDF::SetSubject('Wallet Invoice');

            // set margins
            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

            // set auto page breaks
            PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set JPEG quality
            PDF::setJPEGQuality(100);


			$view1 = view('invoice.wallet_invoice')->with(['guest_data'=>$guest_data, 'transaction_data'=>$arr_transaction, 'data'=>$data]);
			$html1 = $view1->render();

			$view2 = view('invoice.wallet_invoice2')->with(['guest_data'=>$guest_data, 'transaction_data'=>$arr_transaction, 'data'=>$data]);
			$html2 = $view2->render();


			// First Page 
            PDF::AddPage();
            $html1 = $view1->render();
            PDF::writeHTML($html1, true, false, true, false, 'L');

            // Second Page 
            PDF::AddPage();
            $html2 = $view2->render();
            PDF::writeHTML($html2, true, false, true, false, 'L');


            $FileName = 'Invoice'.$transaction_id.'.pdf';
            PDF::output(public_path('uploads/invoice/'.$FileName),'F');
            PDF::reset();
      	}
      	return $FileName;
	}
}
