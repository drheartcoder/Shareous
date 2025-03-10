<?php
  $logo = $base_url = '';
  $amount = $gst_amount = '0';
  $guest_fname = $guest_lname = $guest_address = $guest_country_code = $guest_mobile = $guest_email = '';
  $host_fname = $host_lname = $host_address = $host_country_code = $host_mobile = $host_email = '';

  // For Currency Conversion
  /*if( Session::get('get_currency') == 'USD' ) {
    $currency_icon = '&#36;';
  } else if( Session::get('get_currency') == 'EUR' ) {
    $currency_icon = '&#8364;';
  } else {
    $currency_icon = '&#x20B9;';
  }*/

  // For INR Currency Only
  $currency_icon = '&#x20B9;';

  // Website Data Starts
  if(isset($data) && !empty($data)) {
    $logo = isset($data['logo']) && !empty($data['logo']) ? $data['logo'] : '';
    $base_url = isset($data['base_url']) && !empty($data['base_url']) ? $data['base_url'] : '';

    $gst_amount = isset($data['gst_amount']) && !empty($data['gst_amount']) ? number_format($data['gst_amount'], 2, '.', '') : '';
    $service_fee = isset($data['service_fee']) && !empty($data['service_fee']) ? number_format($data['service_fee'], 2, '.', '') : '';
    $service_fee_gst_amount = isset($data['service_fee_gst_amount']) && !empty($data['service_fee_gst_amount']) ? number_format($data['service_fee_gst_amount'], 2, '.', '') : '';

    $total_gst_amount = $gst_amount + $service_fee + $service_fee_gst_amount;

    //dd($total_gst_amount, $gst_amount, $service_fee, $service_fee_gst_amount);
  }
  // Website Data Ends


  // Transaction Data Starts
  if(isset($transaction_data) && !empty($transaction_data)) { 
    $trasactionid = isset($transaction_data['id']) && !empty($transaction_data['id']) ? $transaction_data['id'] : '';
    $transaction_date = isset($transaction_data['transaction_date']) && !empty($transaction_data['transaction_date']) ? date('d.m.Y',strtotime($transaction_data['transaction_date'])) : '';


    // For Currency Conversion
    /*$pre_amount = isset($transaction_data['amount']) && !empty($transaction_data['amount']) ? $transaction_data['amount'] : '';
    $amount = number_format(currencyConverterAPI('INR', Session::get('get_currency'), $pre_amount), 2, '.', '');

    $pre_gst_amount = isset($transaction_data['booking_details']['gst_amount']) && !empty($transaction_data['booking_details']['gst_amount']) ? $transaction_data['booking_details']['gst_amount'] : '';
    $gst_amount = number_format(currencyConverterAPI('INR', Session::get('get_currency'), $pre_gst_amount), 2, '.', '');*/

    // For INR Currency Only
    $amount = isset($transaction_data['amount']) && !empty($transaction_data['amount']) ? number_format($transaction_data['amount'], 2, '.', '') : '';
  }
  // Transaction Data Ends


  // Guest Data Starts
  if(isset($guest_data) && !empty($guest_data)) {
    $guest_fname        = isset($guest_data['first_name']) && !empty($guest_data['first_name']) ? ucfirst($guest_data['first_name']) : '';
    $guest_lname        = isset($guest_data['last_name']) && !empty($guest_data['last_name']) ? ucfirst($guest_data['last_name']) : '';
    $guest_address      = isset($guest_data['address']) && !empty($guest_data['address']) ? $guest_data['address'] : '';
    $guest_country_code = isset($guest_data['country_code']) && !empty($guest_data['country_code']) ? $guest_data['country_code'] : '';
    $guest_mobile       = isset($guest_data['mobile_number']) && !empty($guest_data['mobile_number']) ? $guest_data['mobile_number'] : '';
    $guest_email        = isset($guest_data['email']) && !empty($guest_data['email']) ? $guest_data['email'] : '';
  }
  // Guest Data Ends


  // Host Data Starts
  if(isset($host_data) && !empty($host_data)) {
    $host_fname        = isset($host_data['first_name']) && !empty($host_data['first_name']) ? ucfirst($host_data['first_name']) : '';
    $host_lname        = isset($host_data['last_name']) && !empty($host_data['last_name']) ? ucfirst($host_data['last_name']) : '';
    $host_address      = isset($host_data['address']) && !empty($host_data['address']) ? $host_data['address'] : '';
    $host_country_code = isset($host_data['country_code']) && !empty($host_data['country_code']) ? $host_data['country_code'] : '';
    $host_mobile       = isset($host_data['mobile_number']) && !empty($host_data['mobile_number']) ? $host_data['mobile_number'] : '';
    $host_email        = isset($host_data['email']) && !empty($host_data['email']) ? $host_data['email'] : '';
  }
  // Host Data Ends
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td width="45%" align="left"><img src="<?php echo $logo; ?>" alt="logo" width="120px" /></td>
                  <td width="95%" align="center" style="font-size:10px;font-weight:bold;color:#333;text-align:right;padding-left:50px;"><?php echo $transaction_date; ?></td>
               </tr>
               <tr>
                  <td height="10"></td>
               </tr>
               <tr>
                  <td colspan="2" style="padding-top:10px;text-align:center;font-size:14px;font-weight:bold;color:#333;height:25px;width:100%"><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $amount; ?></td>
               </tr>
               <tr>
                  <td colspan="2" style="padding-bottom:5px;text-align: center;font-size:10px;color:#333;height:20px;">---------- Payment Receipt No : <?php echo $trasactionid; ?> ----------</td>
               </tr>
               <tr>
                  <td colspan="2" style="padding-bottom:20px;text-align:center;font-size:10px;color:#333;margin-bottom:20px;">Thanks for using our services, <?php echo $guest_fname; ?></td>
               </tr>
               <tr>
                  <td style="padding:20px 5px 0;border-bottom:1px solid #ccc;width:100%;"></td>
               </tr>
               <tr>
                  <td height="10" style=""></td>
               </tr>
               <tr>
                  <td style="font-size:15px;color:#333;">Totall Bill <span style="font-family: DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $amount; ?></td>
               </tr>
               
               <tr>
                  <td style="font-size:11px;color:#a6a5a5;font-weight:600;">Includes <span style="font-family: DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $total_gst_amount; ?> Taxes</td>
               </tr>
               <tr>
                  <td height="2" colspan="2"></td>
               </tr>
               
               <tr>
                  
                  <td style="width:50%;vertical-align:top;text-align:left;color:#333;font-size:9px;">
                    <div style="font-weight:normal;margin:0 0px 12px 0;"><span><b>Host Name : </b></span><?php echo $host_fname.' '.$host_lname; ?></div>
                    <div style="font-weight:normal;margin:0 0px 12px 0;"><span><b>Mobile No : </b></span><?php echo $host_country_code.$host_mobile; ?></div>
                    <div style="font-weight:normal;margin:0 0px 12px 0;"><span><b>Email : </b></span><?php echo $host_email; ?></div>
                    <div style="font-weight:normal;margin:0 0px 12px 0;"><span><b>Address : </b></span><?php echo $host_address; ?></div>
                  </td>

                  <td style="width:50%;vertical-align:top;text-align:left;color:#333;font-size:9px;">
                    <div style="font-weight:normal;margin:0 0px 12px 0"><span><b>Guest Name : </b></span><?php echo $guest_fname.' '.$guest_lname; ?></div>
                    <div style="font-weight:normal;margin:0 0px 12px 0"><span><b>Mobile No : </b></span><?php echo $guest_country_code.$guest_mobile; ?></div>
                    <div style="font-weight:normal;margin:0 0px 12px 0"><span><b>Email : </b></span><?php echo $guest_email; ?></div>
                    <div style="font-weight:normal;margin:0 0px 12px 0"><span><b>Address : </b></span><?php echo $guest_address; ?></div>
                  </td>

               </tr>
               <tr>
                  <td height="2" colspan="2" style="border-bottom:1px solid #ccc"></td>
               </tr>
               <tr>
                  <td height="2" colspan="2"></td>
               </tr>
               <tr>
                  <td colspan="2" style="color:#333; font-size:9px;text-align:left;">
                    <div style="color:#333;font-size:12px;text-align:center;">
                      <span style="color:#333;font-weight: 600;text-align: left;">Complete Booking Details and T&amp;C :</span>
                    </div>
                    
                    <div style="font-weight: normal;"><span><b>1.</b> &nbsp;</span>Please note that cancellation charges will be applicable at the time of.</div>
                    <div style="font-weight: normal;"><span><b>2.</b> &nbsp;</span>Please note that cancellation charges will be applicable at the time of cancellation as per the company policy.</div>
                    <div style="font-weight: normal;"><span><b>3.</b> &nbsp;</span>Please note that cancellation charges will be applicable.</div>
                    
                    <div style="text-align: left; font-size:8px;">Please note that cancellation charges will be applicable at the time of cancellation as per the company policy. This may change with or without intimation from time to time</div>
                    <div style="text-align: left; font-size:8px;">All rights reserved. Shareous. All Host and Guest hereby agree and accept to all the Terms and Conditions and Policies of the Company unconditionally. </div>

                  </td>
               </tr>
            </table>
      </td>
   </tr>
</table>