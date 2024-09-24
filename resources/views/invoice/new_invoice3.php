<?php
    $logo = $base_url = $guest_fname = $guest_lname = $guest_address = $guest_country_code = $guest_mobile = $guest_email = $host_fname = $host_lname = $host_address = $host_country_code = $host_mobile = $host_email = $sac = $property_name = $shareous_address = $shareous_email = $shareous_phone = $shareous_gstin = '';

    $amount = $gst_amount = $selected_of_room = $room_amount = $selected_of_desk = $desk_amount = $selected_of_cubicles = $cubicles_amount = $selected_no_of_slots = $property_amount = $no_of_days = $property_amount = $no_of_days = $no_of_guest = $sub_amount = $service_fee = $admin_commission = $final_amount = $service_fee_gst_amount = $service_fee_without_gst = '0';

    // For INR Currency Only
    $currency_icon = '&#x20B9;';

    // Website Data Starts
    if(isset($data) && !empty($data)) {
        $logo = isset($data['logo']) && !empty($data['logo']) ? $data['logo'] : '';
        $base_url = isset($data['base_url']) && !empty($data['base_url']) ? $data['base_url'] : '';

        $admin_commission = isset($data['admin_commission']) && !empty($data['admin_commission']) ? number_format($data['admin_commission'], 2, '.', '') : '';

        $service_fee = isset($data['service_fee']) && !empty($data['service_fee']) ? number_format($data['service_fee'],2,'.','') : '';
        $service_fee_gst_amount = isset($data['service_fee_gst_amount']) && !empty($data['service_fee_gst_amount']) ? number_format($data['service_fee_gst_amount'],2,'.','') : '';
        $service_fee_percentage = isset($data['service_fee_percentage']) && !empty($data['service_fee_percentage']) ? $data['service_fee_percentage'] : '';
        $service_fee_gst_percentage = isset($data['service_fee_gst_percentage']) && !empty($data['service_fee_gst_percentage']) ? $data['service_fee_gst_percentage'] : '';

        $total_service_fee = $service_fee + $service_fee_gst_amount;

        // Paid amount without service fee
        $final_amount = isset($data['final_amount']) && !empty($data['final_amount']) ? number_format($data['final_amount'],2,'.','') : '';
    }
    // Website Data Ends


   // Transaction Data Starts
   if(isset($transaction_data) && !empty($transaction_data)) { 
      $trasactionid = isset($transaction_data['id']) && !empty($transaction_data['id']) ? $transaction_data['id'] : '';
      $transaction_date = isset($transaction_data['transaction_date']) && !empty($transaction_data['transaction_date']) ? date('d.m.Y',strtotime($transaction_data['transaction_date'])) : '';

      // For INR Currency Only
      // Paid amount with service fee
      $amount = isset($transaction_data['amount']) && !empty($transaction_data['amount']) ? number_format($transaction_data['amount'],2,'.','') : '';

      $gst_amount = isset($transaction_data['booking_details']['gst_amount']) && !empty($transaction_data['booking_details']['gst_amount']) ? number_format($transaction_data['booking_details']['gst_amount'],2,'.','') : '';

      $property_name = isset($transaction_data['booking_details']['property_details']['property_name']) && !empty($transaction_data['booking_details']['property_details']['property_name']) ? $transaction_data['booking_details']['property_details']['property_name'] : '';
      $property_state = isset($transaction_data['booking_details']['property_details']['state']) && !empty($transaction_data['booking_details']['property_details']['state']) ? $transaction_data['booking_details']['property_details']['state'] : '';

      $property_type_slug = isset($transaction_data['booking_details']['property_type_slug']) && !empty($transaction_data['booking_details']['property_type_slug']) ? $transaction_data['booking_details']['property_type_slug'] : 'shareous';
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
      $guest_state        = isset($guest_data['state']) && !empty($guest_data['state']) ? $guest_data['state'] : '';
      $guest_gstin        = isset($guest_data['gstin']) && !empty($guest_data['gstin']) ? $guest_data['gstin'] : '';
   }
   // Guest Data Ends

   $shareous_fname = 'M/s Orpheusdroid';
   $shareous_lname = 'C/O Shareous';

   $get_site_data = DB::table('site_settings')->first();
   if( $get_site_data ) {
      $shareous_address = $get_site_data->site_address;
      $shareous_email   = $get_site_data->site_email_address;
      $shareous_phone   = $get_site_data->site_contact_number;
      $shareous_gstin   = $get_site_data->gstin;
   }
   $sac = get_sac();

  $even_style = "background-color: #ffffff; font-size: 8px; color: #333; text-align: left;";
  $odd_style = "background-color: #efefef; font-size: 8px; color: #333; text-align: left;";
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td bgcolor="#FFFFFF" style="padding:5px;">
         <div style="padding:15px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr><td colspan="2" style="text-align:center;font-size:10px;color:#333">ORIGINAL TAX INVOICE</td></tr>

              <tr><td colspan="2" style="border-bottom:1px solid #ccc;color:#333;width:100%"></td></tr>

              <tr><td height="10"></td></tr>

              <tr>
                <td style="width:50%;font-size:9px;color:#333;text-align:left;font-weight:normal;">
                  <div><span><?php echo $shareous_fname; ?><br><?php echo $shareous_lname; ?><br><?php echo $shareous_address; ?></span></div>
                  <div><span><b>Email :</b> <?php echo $shareous_email; ?></span></div>
                  <div><span><b>Phone :</b> <?php echo $shareous_phone; ?></span></div>
                  <div><span><b>GST No :</b> <?php echo $shareous_gstin; ?></span></div>
                </td>
                <td style="width:50%;color:#333;font-size:9px;text-align:left;font-weight:normal;">
                  <div><span><b>Service Category :</b></span> Commission Service</div>
                  <div><span><b>SAC Code :</b></span> <?php echo $sac; ?></div>
                  <div><span><b>Invoice No :</b></span> <?php echo $trasactionid; ?></div>
                  <div><span><b>Invoice Date :</b></span> <?php echo $transaction_date; ?></div>
                </td>
              </tr>

              <tr><td colspan="2" style="border-bottom:1px solid #ccc;color:#333;width:100%"></td></tr>

              <tr><td height="10"></td></tr>

              <tr>
                <td style="width:50%;font-size:9px; color:#333;text-align:left;font-weight: normal;">
                  <div><span><b>Guest Name :</b></span> <?php echo $guest_fname.' '.$guest_lname; ?></div>
                  <div><span><b>Address :</b></span> <?php echo $guest_address; ?></div>
                  <?php if( isset( $guest_gstin ) && !empty( $guest_gstin ) ) { ?>
                    <div><span>GST No :</span> <?php echo $guest_gstin; ?></div>
                  <?php } ?>
                </td>
                <td style="width:50%;color:#333;font-size:9px;text-align:left;">
                  <div><span><b>Mobile No :</b></span> <?php echo $guest_country_code.$guest_mobile; ?></div>
                  <div><span><b>Email :</b></span> <?php echo $guest_email; ?></div>
                </td>
              </tr>

              <tr><td height="10"></td></tr>

              <tr><td height="10"></td></tr>

              <tr>
                <td colspan="2">
                  <table width="100%;" cellpadding="5" style="border:1px solid #ddd;">

                    <tr style="font-size:8px;color:#000;text-align:left;">
                      <th style="border:1px solid #ddd;"><b>Sr. No.</b></th>
                      <th style="border:1px solid #ddd;"><b>Description</b></th>
                      <th style="border:1px solid #ddd;"><b>Qty</b></th>
                      <th style="border:1px solid #ddd;"><b>Unit</b></th>
                      <th style="border:1px solid #ddd;"><b>Days</b></th>
                      <th style="border:1px solid #ddd;"><b>Rate</b></th>
                      <th style="border:1px solid #ddd;"><b>Total Amount</b></th>
                    </tr>

                    <?php 
                      $row_count = 0;
                      $row_count = $row_count + 1;

                      if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                      else { $row_style = $even_style; }
                    ?>

                    <tr style="<?php echo $row_style; ?>">
                      <td>1</td>
                      <td>Service Fees</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $service_fee; ?></td>
                      <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $service_fee; ?></td>
                    </tr>

                    <?php
                      $row_count = $row_count + 1;

                      if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                      else { $row_style = $even_style; }
                    ?>

                    <tr style="<?php echo $row_style; ?>">
                      <td>Sub-Total</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $service_fee; ?></td>
                    </tr>

                    <?php if( 'Tamil Nadu' == $guest_state ) { 
                      $gst_per = $service_fee_gst_percentage / 2;
                      $gst_price = number_format( ($service_fee_gst_amount / 2) , 2, '.', '');

                      $row_count = $row_count + 1;

                      if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                      else { $row_style = $even_style; }
                    ?>

                       <tr style="<?php echo $row_style; ?>">
                          <td>CGST</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $gst_per; ?>%</td>
                          <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $gst_price; ?></td>
                       </tr>

                      <?php
                        $row_count = $row_count + 1;

                        if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                        else { $row_style = $even_style; }
                      ?>

                       <tr style="<?php echo $row_style; ?>">
                          <td>SGST</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $gst_per; ?>%</td>
                          <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $gst_price; ?></td>
                       </tr>

                    <?php } else if( 'Tamil Nadu' != $guest_state ) { 
                      $row_count = $row_count + 1;

                      if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                      else { $row_style = $even_style; }
                    ?>

                       <tr style="<?php echo $row_style; ?>">
                          <td>IGST</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $service_fee_gst_percentage; ?>%</td>
                          <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $service_fee_gst_amount; ?></td>
                       </tr>

                    <?php } 
                      $row_count = $row_count + 1;

                      if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                      else { $row_style = $even_style; }
                    ?>

                    <tr style="<?php echo $row_style; ?>">
                      <td><b>Total</b></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td style="font-size:9px;"><span style="font-family:DejaVu Sans;"><b><?php echo $currency_icon; ?></span><?php echo $total_service_fee; ?></b></td>
                    </tr>

                  </table>
                </td>
              </tr>

              <tr><td height="10"></td></tr>

              <tr><td height="10"></td></tr>

              <tr>
                <td colspan="2" style="color: #333; font-size: 9px;text-align: left; font-weight: normal;">
                   <div><span style="font-size:10px;"><b>Please Note:</b> </span></div>
                   <div><span><b>1.</b> </span>This invoice is raised on behalf of Shareous </div>
                   <div><span><b>2.</b> </span>This is an electronic invoice and need not have any signature</div>
                   <div><span><b>3.</b> </span>All statutory liabilities will remain with the Property Owner and The customer mentioned in the above invoice</div>
                </td>
              </tr>

            </table>
         </div>
      </td>
   </tr>
</table>