<?php
   $logo = $base_url = $guest_fname = $guest_lname = $guest_address = $guest_country_code = $guest_mobile = $guest_email = $host_fname = $host_lname = $host_address = $host_country_code = $host_mobile = $host_email = $sac = $property_name = '';

   $amount = $gst_amount = $selected_of_room = $room_amount = $selected_of_desk = $desk_amount = $selected_of_cubicles = $cubicles_amount = $selected_no_of_slots = $property_amount = $no_of_days = $property_amount = $no_of_days = $no_of_guest = $sub_amount = $service_fee = $admin_commission = $final_amount = $total_room_amount = $total_desk_amount = $total_cubicles_amount = '0';

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

      $service_fee = isset($data['service_fee']) && !empty($data['service_fee']) ? number_format($data['service_fee'], 2, '.', '') : '';
      $admin_commission = isset($data['admin_commission']) && !empty($data['admin_commission']) ? number_format($data['admin_commission'], 2, '.', '') : '';

      // Paid amount without service fee
      $final_amount = isset($data['final_amount']) && !empty($data['final_amount']) ? number_format($data['final_amount'], 2, '.', '') : '';
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
      // Paid amount with service fee
      $amount = isset($transaction_data['amount']) && !empty($transaction_data['amount']) ? number_format($transaction_data['amount'], 2, '.', '') : '';

      $gst_amount = isset($transaction_data['booking_details']['gst_amount']) && !empty($transaction_data['booking_details']['gst_amount']) ? number_format($transaction_data['booking_details']['gst_amount'], 2, '.', '') : '';

      $coupon_code_id = isset($transaction_data['booking_details']['coupon_code_id']) && !empty($transaction_data['booking_details']['coupon_code_id']) ? $transaction_data['booking_details']['coupon_code_id'] : 0;

      $coupen_code_amount = isset($transaction_data['booking_details']['coupen_code_amount']) && !empty($transaction_data['booking_details']['coupen_code_amount']) ? number_format($transaction_data['booking_details']['coupen_code_amount'], 2, '.', '') : 0;


      $property_name = isset($transaction_data['booking_details']['property_details']['property_name']) && !empty($transaction_data['booking_details']['property_details']['property_name']) ? $transaction_data['booking_details']['property_details']['property_name'] : '';
      $property_state = isset($transaction_data['booking_details']['property_details']['state']) && !empty($transaction_data['booking_details']['property_details']['state']) ? $transaction_data['booking_details']['property_details']['state'] : '';

      $property_type_slug = isset($transaction_data['booking_details']['property_type_slug']) && !empty($transaction_data['booking_details']['property_type_slug']) ? $transaction_data['booking_details']['property_type_slug'] : 'shareous';

      $sac = get_sac( $property_type_slug );

      if($property_type_slug == 'warehouse') {
         $no_of_slots = isset($transaction_data['booking_details']['property_details']['no_of_slots']) && !empty($transaction_data['booking_details']['property_details']['no_of_slots']) ? $transaction_data['booking_details']['property_details']['no_of_slots'] : '0';
         $property_area = isset($transaction_data['booking_details']['property_details']['property_area']) && !empty($transaction_data['booking_details']['property_details']['property_area']) ? $transaction_data['booking_details']['property_details']['property_area'] : '0';

         $selected_no_of_slots = isset($transaction_data['booking_details']['selected_no_of_slots']) && !empty($transaction_data['booking_details']['selected_no_of_slots']) ? $transaction_data['booking_details']['selected_no_of_slots'] : '0';
         $property_amount = isset($transaction_data['booking_details']['property_amount']) && !empty($transaction_data['booking_details']['property_amount']) ? $transaction_data['booking_details']['property_amount'] : '0';
         $no_of_days = isset($transaction_data['booking_details']['no_of_days']) && !empty($transaction_data['booking_details']['no_of_days']) ? $transaction_data['booking_details']['no_of_days'] : '0';

         $sub_amount = ( $no_of_slots / $property_area ) * ($no_of_days * $property_amount * $selected_no_of_slots);

         $gst_percentage = get_gst_data(0, 'warehouse');

      } else if($property_type_slug == 'office-space') {
         $selected_of_room = isset($transaction_data['booking_details']['selected_of_room']) && !empty($transaction_data['booking_details']['selected_of_room']) ? $transaction_data['booking_details']['selected_of_room'] : '0';
         $room_amount = isset($transaction_data['booking_details']['room_amount']) && !empty($transaction_data['booking_details']['room_amount']) ? $transaction_data['booking_details']['room_amount'] : '0';
         $total_room_amount = number_format( ( $selected_of_room * $room_amount ) , 2, '.', '');

         $selected_of_desk = isset($transaction_data['booking_details']['selected_of_desk']) && !empty($transaction_data['booking_details']['selected_of_desk']) ? $transaction_data['booking_details']['selected_of_desk'] : '0';
         $desk_amount = isset($transaction_data['booking_details']['desk_amount']) && !empty($transaction_data['booking_details']['desk_amount']) ? $transaction_data['booking_details']['desk_amount'] : '0';
         $total_desk_amount = number_format( ( $selected_of_desk * $desk_amount ) , 2, '.', '');

         $selected_of_cubicles = isset($transaction_data['booking_details']['selected_of_cubicles']) && !empty($transaction_data['booking_details']['selected_of_cubicles']) ? $transaction_data['booking_details']['selected_of_cubicles'] : '0';
         $cubicles_amount = isset($transaction_data['booking_details']['cubicles_amount']) && !empty($transaction_data['booking_details']['cubicles_amount']) ? $transaction_data['booking_details']['cubicles_amount'] : '0';
         $total_cubicles_amount = number_format( ( $selected_of_cubicles * $cubicles_amount ) , 2, '.', '');

         $no_of_days = isset($transaction_data['booking_details']['no_of_days']) && !empty($transaction_data['booking_details']['no_of_days']) ? $transaction_data['booking_details']['no_of_days'] : '0';

         $sub_amount = $no_of_days * ( $total_room_amount + $total_desk_amount + $total_cubicles_amount );

         $gst_percentage = get_gst_data(0, 'office-space');

      } else {
         $property_amount = isset($transaction_data['booking_details']['property_amount']) && !empty($transaction_data['booking_details']['property_amount']) ? $transaction_data['booking_details']['property_amount'] : '0';

         $no_of_days = isset($transaction_data['booking_details']['no_of_days']) && !empty($transaction_data['booking_details']['no_of_days']) ? $transaction_data['booking_details']['no_of_days'] : '0';

         $no_of_guest = isset($transaction_data['booking_details']['no_of_guest']) && !empty($transaction_data['booking_details']['no_of_guest']) ? $transaction_data['booking_details']['no_of_guest'] : '0';

         $property_amount = isset($transaction_data['booking_details']['property_details']['price_per_night']) && !empty($transaction_data['booking_details']['property_details']['price_per_night']) ? $transaction_data['booking_details']['property_details']['price_per_night'] : '';

         $sub_amount = $property_amount * $no_of_days * $no_of_guest;

         $gst_percentage = get_gst_data($property_amount, 'other');

      }
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


   // Host Data Starts
   if(isset($host_data) && !empty($host_data)) {
      $host_fname        = isset($host_data['first_name']) && !empty($host_data['first_name']) ? ucfirst($host_data['first_name']) : '';
      $host_lname        = isset($host_data['last_name']) && !empty($host_data['last_name']) ? ucfirst($host_data['last_name']) : '';
      $host_address      = isset($host_data['address']) && !empty($host_data['address']) ? $host_data['address'] : '';
      $host_country_code = isset($host_data['country_code']) && !empty($host_data['country_code']) ? $host_data['country_code'] : '';
      $host_mobile       = isset($host_data['mobile_number']) && !empty($host_data['mobile_number']) ? $host_data['mobile_number'] : '';
      $host_email        = isset($host_data['email']) && !empty($host_data['email']) ? $host_data['email'] : '';
      $host_gstin        = isset($host_data['gstin']) && !empty($host_data['gstin']) ? $host_data['gstin'] : '';
      $apply_gst         = isset($host_data['apply_gst']) && !empty($host_data['apply_gst']) ? $host_data['apply_gst'] : 'no';
   }
   // Host Data Ends

   $even_style = "background-color: #ffffff; font-size: 8px; color: #333; text-align: left;";
   $odd_style = "background-color: #efefef; font-size: 8px; color: #333; text-align: left;";

?>

<style type="text/css">
   .temp{ border: 2px solid #000000; background-color: #C0C0C0; }
</style>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td bgcolor="#FFFFFF" style="padding:5px;">
         <div style="padding:15px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:15px;">
               <tr>
                  <td colspan="2" style="padding-bottom:20px;text-align: center;font-size:10px;color:#333">ORIGINAL TAX INVOICE</td>
               </tr>

               <tr><td colspan="2" style="border-bottom:1px solid #ccc;color:#333;width:100%"></td></tr>

               <tr><td height="10"></td></tr>

               <tr>
                  
                  <td style="width: 50%; clear: both; color: #333; font-size: 9px; text-align: left; vertical-align: top; font-weight: normal;">
                     <div><span style="font-size:10px;color:#333;"><b><?php echo $host_fname.' '.$host_lname; ?></b></span></div>
                     <div><span><?php echo $host_address; ?></span></div>
                      <div><span><b>Email :</b> <?php echo $host_email; ?></span></div>
                      <div><span><b>Mobile No :</b> <?php echo $host_country_code.$host_mobile; ?></span></div>
                     <?php if( isset( $host_gstin ) && !empty( $host_gstin ) ) { ?>
                        <div><span><b>GST No :</b> <?php echo $host_gstin; ?></span></div>
                     <?php } ?>
                  </td>
                  
                  <td style="width: 50%; clear: both; color: #333; font-size: 9px; text-align: left; vertical-align: top; font-weight: normal;">
                     <div><span><b>Service Category :</b></span> Rent / Leasing of Property</div>
                     <div><span><b>SAC Code :</b></span> <?php echo $sac; ?></div>
                     <div><span><b>Invoice No :</b></span> <?php echo $trasactionid; ?></div>
                     <div><span><b>Invoice Date :</b></span> <?php echo $transaction_date; ?></div>
                  </td>

               </tr>

               <tr><td height="10" style="border-bottom:1px solid #ccc;color:#333;width:100%;"></td></tr>

               <tr><td height="10"></td></tr>
               
               <tr>
                  
                  <td style="width:50%;vertical-align:top;padding-top:18px;font-size:9px;color:#333;text-align:left;">
                     <div style="font-weight:normal;margin:0 10px 15px 0"><span><b>Guest Name :</b></span> <?php echo $guest_fname.' '.$guest_lname; ?></div>
                     <div style="font-weight:normal;margin:0 10px 15px 0"><span><b>Address :</b></span> <?php echo $guest_address; ?></div>

                     <?php if( isset( $guest_gstin ) && !empty( $guest_gstin ) ) { ?>
                        <div style="font-weight: normal;margin:0 10px 15px 0"><span><b>GST No :</b></span> <?php echo $guest_gstin; ?></div>
                     <?php } ?>
                  </td>
                  
                  <td style="width:50%;vertical-align: top; padding-bottom:15px;padding-top: 18px;clear:both; color:#333;font-size:9px;text-align: left;">
                     <div style="font-weight: normal;margin:0 10px 15px 0"><span style="font-weight: 600;"><b>Mobile No :</b></span> <?php echo $guest_country_code.$guest_mobile; ?></div>
                     <div style="font-weight: normal;margin:0 10px 15px 0"><span style="font-weight: 600;"><b>Email :</b></span> <?php echo $guest_email; ?></div>
                  </td>
               </tr>

               <tr><td height="10"></td></tr>

               <tr><td height="10"></td></tr>

               <tr>
                  <td colspan="2">
                     <table width="100%" cellpadding="5" style="border:1px solid #ddd;">
                        
                        <tr style="font-size:8px;color:#000;text-align:left;">
                           <th style="border:1px solid #ddd;"><b>Sr. No.</b></th>
                           <th style="border:1px solid #ddd;"><b>Description</b></th>
                           <th style="border:1px solid #ddd;"><b>Qty</b></th>
                           <th style="border:1px solid #ddd;"><b>Unit</b></th>
                           <th style="border:1px solid #ddd;"><b>Days</b></th>
                           <th style="border:1px solid #ddd;"><b>Rate</b></th>
                           <th style="border:1px solid #ddd;"><b>Total Amount</b></th>
                        </tr>

                        <?php if($property_type_slug == 'warehouse') {
                           $row_count = 0;
                           $row_count = $row_count + 1;
                           
                           if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                           else { $row_style = $even_style; }
                        ?>

                           <tr style="<?php echo $row_style; ?>">
                              <td>1</td>
                              <td><?php echo $property_name; ?></td>
                              <td><?php echo $selected_no_of_slots; ?></td>
                              <td>Slots</td>
                              <td><?php echo $no_of_days; ?></td>
                              <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $property_amount; ?></td>
                              <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $property_amount; ?></td>
                           </tr>

                        <?php } else if($property_type_slug == 'office-space') { $cnt = 0; 

                           if($selected_of_room != '0' || $selected_of_room != '0.0') {
                              $row_count = 0;
                              $row_count = $row_count + 1;
                              $cnt = 1 + $cnt;

                              if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                              else { $row_style = $even_style; }
                        ?>

                              <tr style="<?php echo $row_style; ?>">
                                 <td><?php echo $cnt; ?></td>
                                 <td><?php echo $property_name; ?></td>
                                 <td><?php echo $selected_of_room; ?></td>
                                 <td>Private Room</td>
                                 <td><?php echo $no_of_days; ?></td>
                                 <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $room_amount; ?></td>
                                 <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $total_room_amount; ?></td>
                              </tr>

                           <?php } 

                           if($selected_of_desk != '0' || $selected_of_desk != '0.0') {
                              $row_count = $row_count + 1;
                              $cnt = 1 + $cnt;

                              if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                              else { $row_style = $even_style; }
                           ?>

                              <tr style="<?php echo $row_style; ?>">
                                 <td><?php echo $cnt; ?></td>
                                 <td><?php echo $property_name; ?></td>
                                 <td><?php echo $selected_of_desk; ?></td>
                                 <td>Dedicated Desk</td>
                                 <td><?php echo $no_of_days; ?></td>
                                 <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $desk_amount; ?></td>
                                 <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $total_desk_amount; ?></td>
                              </tr>

                           <?php } 

                           if($selected_of_cubicles != '0' || $selected_of_cubicles != '0.0') { 
                              $row_count = $row_count + 1;
                              $cnt = 1 + $cnt;

                              if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                              else { $row_style = $even_style; }
                           ?>

                              <tr style="<?php echo $row_style; ?>">
                                 <td><?php echo $cnt; ?></td>
                                 <td><?php echo $property_name; ?></td>
                                 <td><?php echo $selected_of_cubicles; ?></td>
                                 <td>Cubicles</td>
                                 <td><?php echo $no_of_days; ?></td>
                                 <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $cubicles_amount; ?></td>
                                 <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $total_cubicles_amount; ?></td>
                              </tr>

                           <?php } 
                        } else { 
                           $row_count = 0;
                           $row_count = $row_count + 1;

                           if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                           else { $row_style = $even_style; }
                        ?>

                           <tr style="<?php echo $row_style; ?>">
                              <td>1</td>
                              <td><?php echo $property_name; ?></td>
                              <td><?php echo $no_of_guest; ?></td>
                              <td>Guest</td>
                              <td><?php echo $no_of_days; ?></td>
                              <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $property_amount; ?></td>
                              <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $property_amount; ?></td>
                           </tr>

                        <?php }
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
                           <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo number_format($sub_amount, 2, '.', ''); ?></td>
                        </tr>

                        <?php
                           if( $coupon_code_id != 0 ) {
                           $row_count = $row_count + 1;
                           
                           if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                           else { $row_style = $even_style; }
                        ?>

                        <tr style="<?php echo $row_style; ?>">
                           <td>Discount</td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo "- ".$currency_icon; ?></span><?php echo number_format($coupen_code_amount, 2, '.', ''); ?></td>
                        </tr>

                        <?php
                           }

                           if( $apply_gst == 'yes' && $property_state == $guest_state ) { 
                           $gst_per = $gst_percentage / 2;
                           $gst_price = number_format( ($gst_amount / 2) , 2, '.', '');
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
                              <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $gst_price; ?></td>
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
                              <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $gst_price; ?></td>
                           </tr>

                        <?php } else if( $apply_gst == 'yes' && $property_state != $guest_state ) {
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
                              <td><?php echo $gst_percentage; ?>%</td>
                              <td><span style="font-family: DejaVu Sans; sans-serif;"><?php echo $currency_icon; ?></span><?php echo $gst_amount; ?></td>
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
                           <td style="font-size:9px;"><span style="font-family: DejaVu Sans; sans-serif;"><b><?php echo $currency_icon; ?></span><?php echo $final_amount; ?></b></td>
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