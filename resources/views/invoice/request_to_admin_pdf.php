<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:0px solid #ddd;font-family:Arial, Helvetica, sans-serif;">

<tr>
    <td><img src="<?php if(isset($Data) && count($Data)>0) { echo $Data['logo']; }?>" width="100px" alt=""/></td>
    <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:9px;">
            <tr>
                <td width="35%"><b>Report No. : </b></td>
                <td width="65%"><?php echo $report_id;?></td>
            </tr>
           
             <tr>
                <td width="35%"><b>Date : </b></td>
                <td width="65%"><?php echo date('d-m-Y'); ?></td>
            </tr>
          
        </table>
    </td>
</tr>

<tr>
     <td width="50%">
        <table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:9px;">
          
             <tr>
                <td width="35%"><b>To : </b></td>
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData->site_name; }?></td>
            </tr>
             <tr>
                <td width="35%"><b>Contact Number : </b></td>
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData->site_contact_number; }?></td>
            </tr>
            <tr>
                <td width="35%"><b>Email ID : </b></td>
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData->site_email_address; }?></td>
            </tr>
            <tr>
                <td width="35%"><b>Address : </b></td>
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData->site_address; }?></td>
            </tr>
        </table>
    </td>
    <td width="50%" style="font-size:9px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">    
             <tr>
                <td width="30%"><b>From : </b></td>
                <td width="70%"><?php if(isset($SenderData) && count($SenderData)>0) { echo ucfirst($SenderData->fname.' '.$SenderData->lname); }?><br /><?php if(isset($SenderData) && count($SenderData)>0) { echo $SenderData->email; }?> <br /><?php if(isset($SenderData) && count($SenderData)>0) { echo $SenderData->address; }?></td>
            </tr>
        </table>    
    </td>
</tr>
<tr>
    <td width="100%" >
        <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-color:#ddd;">
            <tr>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Transaction Id</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Booking Id</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Property Name</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Date</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Commission</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Property Amount</td>
            </tr>
             
            <?php foreach($obj_data as $Data)
            {
            ?>
            <tr>
                <td style="font-size:9px;text-align: center;"><?php echo $Data->transaction_id;?> </td>
                <td style="font-size:9px;text-align: center;"><?php echo $Data->booking_id; ?></td>
                <td style="font-size:9px;text-align: center;"><?php echo $Data->property_name; ?></td>
                <td style="font-size:9px;text-align: center;"><?php echo get_added_on_date($Data->transaction_date); ?></td>
                <td style="font-size:9px;text-align: center;"><?php echo $Data->admin_commission;?>%</td>
                <td style="font-size:9px;text-align: center;"><?php echo number_format($Data->total_amount,2).' '.$Data->currency_code;?></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="5" style="text-align: right; font-size:9px;font-weight: bold;">Total Amount : </td>
                <td style="text-align: center; font-size:9px;font-weight: bold;"><?php  echo number_format($total_amount,2); ?>&nbsp;INR</td>
            </tr>
        </table>
    </td>
</tr>
</table>



