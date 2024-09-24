<?php
    $logo = $base_url = $guest_fname = $guest_lname = $guest_address = $guest_country_code = $guest_mobile = $guest_email = $guest_gstin = $guest_state = $shareous_address = $shareous_email = $shareous_phone = $shareous_gstin = '';

    $amount = '0';

    // For INR Currency Only
    $currency_icon = '&#x20B9;';

    // Website Data Starts
    if(isset($data) && !empty($data)) {
      $logo = isset($data['logo']) && !empty($data['logo']) ? $data['logo'] : '';
      $base_url = isset($data['base_url']) && !empty($data['base_url']) ? $data['base_url'] : '';
    }
    // Website Data Ends


   // Transaction Data Starts
   if(isset($transaction_data) && !empty($transaction_data)) { 
      $trasactionid = isset($transaction_data['id']) && !empty($transaction_data['id']) ? $transaction_data['id'] : '';
      $transaction_date = isset($transaction_data['transaction_date']) && !empty($transaction_data['transaction_date']) ? date('d.m.Y',strtotime($transaction_data['transaction_date'])) : '';

      // For INR Currency Only
      $amount = isset($transaction_data['amount']) && !empty($transaction_data['amount']) ? number_format($transaction_data['amount'],2,'.','') : '';
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
                  <div><span><b>Service Category :</b></span> Service</div>
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
                    <div><span><b>GST No :</b></span> <?php echo $guest_gstin; ?></div>
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
                      <td>Amount added to User Wallet</td>
                      <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $amount; ?></td>
                      <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $amount; ?></td>
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
                      <td><span style="font-family:DejaVu Sans;"><?php echo $currency_icon; ?></span><?php echo $amount; ?></td>
                    </tr>

                    <?php 
                      $row_count = $row_count + 1;

                      if( ($row_count % 2) == 1 ) { $row_style = $odd_style; }
                      else { $row_style = $even_style; }
                    ?>

                    <tr style="<?php echo $row_style; ?>">
                      <td><b>Total</b></td>
                      <td></td>
                      <td></td>
                      <td><span style="font-family:DejaVu Sans;"><b><?php echo $currency_icon; ?></span><?php echo $amount; ?></b></td>
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
                </td>
              </tr>

            </table>
         </div>
      </td>
   </tr>
</table>