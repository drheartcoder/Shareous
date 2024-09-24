<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Models\BookingModel;

use App\Common\Services\EmailService;

class SendReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendReminderEmail:checkintomorrow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder email to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BookingModel $booking_model, EmailService $email_service)
    {
        parent::__construct();

        $this->BookingModel = $booking_model;
        $this->EmailService = $email_service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (php_sapi_name() !='cli') { $this->error('Invalid Request'); die(); }
        
        $tomorrow_bookings_obj = $this->BookingModel->with('property_details', 'user_details', 'booking_by_user_details')
                                                    ->whereRaw('DATE(DATE_ADD(check_in_date, INTERVAL -1 DAY)) = CURRENT_DATE')
                                                    ->where('booking_status', '=', '5')
                                                    ->orderBy('check_in_date', 'ASC')
                                                    ->get();

        $tomorrow_bookings_arr = $tomorrow_bookings_obj->toArray();
        
        if (isset($tomorrow_bookings_arr) && count($tomorrow_bookings_arr) > 0) {
            $bar = $this->output->createProgressBar(count($tomorrow_bookings_arr));

            foreach ($tomorrow_bookings_arr as $tomorrow_bookings) {
                $user_details     = $tomorrow_bookings['booking_by_user_details'];
                $owner_details    = $tomorrow_bookings['user_details'];
                $property_details = $tomorrow_bookings['property_details'];

                $built_content = [
                                    'USER_NAME'        => ucfirst($user_details['display_name']),
                                    'CHECKIN_DATE'     => get_added_on_date($tomorrow_bookings['check_in_date']),
                                    'CHECKOUT_DATE'    => get_added_on_date($tomorrow_bookings['check_out_date']),
                                    'PROPERTY_DETAILS' => '<b>'.$property_details['property_name'].'</b><br/> '.$property_details['address'],
                                    'OWNER_DETAILS'    => '<b>'.ucfirst($owner_details['first_name'].' '.$owner_details['last_name']).'</b><br/>'.$owner_details['email'].'<br>'.$owner_details['mobile_number'],
                                    'PROJECT_NAME'     => config('app.project.name')
                                ];
                $mail_data                      = [];
                $mail_data['email_template_id'] = '21';
                $mail_data['arr_built_content'] = $built_content;
                $mail_data['user']              = [
                                                    'email'      => isset($user_details['email']) ? $user_details['email'] :'NA',
                                                    'first_name' => isset($user_details['display_name']) ? ucfirst($user_details['display_name']) : 'NA'
                                                ];

                $this->EmailService->send_mail($mail_data);
                $bar->advance();
            }
            $bar->finish();
            $this->info(count($tomorrow_bookings_arr).' reminder emails sent today '.date('Y-m-d H:i:s'));
        } else {
            $this->error('No bookings to remind!');
        }
    }
}
