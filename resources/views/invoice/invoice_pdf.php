<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:0px solid #ddd;font-family:Arial, Helvetica, sans-serif;">

<tr>
    <td><img src="<?php if(isset($Data) && count($Data)>0) { echo $Data['logo']; }?>" width="100px" alt=""/></td>
    <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:9px;">
            <tr>
                <td width="35%"><b>Invoice No. : </b></td>
                <td width="65%"><?php if(isset($TrasactionData) && count($TrasactionData)>0){ echo $TrasactionData['id']; }?></td>
            </tr>
             <tr>
                <td width="35%"><b>Admin Ref. : </b></td>
                <td width="65%"><?php if(isset($SenderData) && count($SenderData)>0) { echo $SenderData['email']; }?></td>
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
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData['first_name']; }?></td>
            </tr>
             <tr>
                <td width="35%"><b>Contact Number : </b></td>
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData['mobile_number']; }?></td>
            </tr>
            <tr>
                <td width="35%"><b>Email ID : </b></td>
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData['email']; }?></td>
            </tr>
            <tr>
                <td width="35%"><b>Address : </b></td>
                <td width="65%"><?php if(isset($ReceivedData) && count($ReceivedData)>0) { echo $ReceivedData['address']; }?></td>
            </tr>
        </table>
    </td>
    <td width="50%" style="font-size:9px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">    
             <tr>
                <td width="30%"><b>From : </b></td>
                <td width="70%"><?php if(isset($SenderData) && count($SenderData)>0) { echo ucfirst($SenderData['first_name'].' '.$SenderData['last_name']); }?><br /><?php if(isset($SenderData) && count($SenderData)>0) { echo $SenderData['email']; }?> <br /><?php if(isset($SenderData) && count($SenderData)>0) { echo $SenderData['address']; }?></td>
            </tr>
        </table>    
    </td>
</tr>
<tr>
    <td width="100%" >
        <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-color:#ddd;">
            <tr>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Transaction Id</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Date</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Description</td>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Total</td>
            </tr>
            <tr>
                <td style="font-size:9px;text-align: center;"><?php if(isset($TrasactionData) && count($TrasactionData)>0) { echo $TrasactionData['transaction_id']; }?> </td>
                <td style="font-size:9px;text-align: center;"><?php if(isset($TrasactionData) && count($TrasactionData)>0) { echo date('d-m-Y',strtotime($TrasactionData['transaction_date'])); }?></td>
                <td style="font-size:9px;text-align: center;"><?php if(isset($TrasactionData) && count($TrasactionData)>0) { echo $TrasactionData['transaction_for']; }?></td>
                <td style="font-size:9px;text-align: center; "><?php if(isset($TrasactionData) && count($TrasactionData)>0) { echo 'INR '.$TrasactionData['amount']; }?></td>
            </tr>
        </table>
    </td>
</tr>
</table>

