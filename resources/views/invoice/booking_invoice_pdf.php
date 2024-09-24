<?php
$TrasactionDataID = $TrasactionDataDate = $TrasactionDataFor = $ReceivedDataFirstName = $ReceivedDataLastName = $ReceivedDataMobile = $ReceivedDataEmail = $ReceivedDataAddress = $SenderDataFirstName = $SenderDataLastName = $SenderDataMobile = $SenderDataEmail = $SenderDataAddress = $DataAdminCommission = '';
$TrasactionDataAmount = $DataCommissionAmount = $DataFinalAmount = 0;

if(isset($TrasactionData) && count($TrasactionData) > 0)
{ 
    $TrasactionDataID = isset($TrasactionData['id']) && !empty($TrasactionData['id']) ? $TrasactionData['id'] : '';
    $TrasactionDataTransactionID = isset($TrasactionData['transaction_id']) && !empty($TrasactionData['id']) ? $TrasactionData['transaction_id'] : '';
    $TrasactionDataDate = isset($TrasactionData['transaction_date']) && !empty($TrasactionData['transaction_date']) ? date('d-m-Y',strtotime($TrasactionData['transaction_date'])) : '';
    $TrasactionDataFor = isset($TrasactionData['transaction_for']) && !empty($TrasactionData['transaction_for']) ? $TrasactionData['transaction_for'] : '';
    $TrasactionDataAmount = isset($TrasactionData['amount']) && !empty($TrasactionData['amount']) ? $TrasactionData['amount'] : 0;
}

if(isset($ReceivedData) && count($ReceivedData) > 0) 
{
    $ReceivedDataFirstName = isset($ReceivedData['first_name']) && !empty($ReceivedData['first_name']) ? ucfirst($ReceivedData['first_name']) : '';
    $ReceivedDataLastName = isset($ReceivedData['last_name']) && !empty($ReceivedData['last_name']) ? ucfirst($ReceivedData['last_name']) : '';
    $ReceivedDataMobile = isset($ReceivedData['mobile_number']) && !empty($ReceivedData['mobile_number']) ? $ReceivedData['mobile_number'] : '';
    $ReceivedDataEmail = isset($ReceivedData['email']) && !empty($ReceivedData['email']) ? $ReceivedData['email'] : '';
    $ReceivedDataAddress = isset($ReceivedData['address']) && !empty($ReceivedData['address']) ? $ReceivedData['address'] : '';
}

if(isset($SenderData) && count($SenderData) > 0) 
{
    $SenderDataFirstName = isset($SenderData['first_name']) && !empty($SenderData['first_name']) ? ucfirst($SenderData['first_name']) : '';
    $SenderDataLastName = isset($SenderData['last_name']) && !empty($SenderData['last_name']) ? ucfirst($SenderData['last_name']) : '';
    $SenderDataMobile = isset($SenderData['mobile_number']) && !empty($SenderData['mobile_number']) ? $SenderData['mobile_number'] : '';
    $SenderDataEmail = isset($SenderData['email']) && !empty($SenderData['email']) ? $SenderData['email'] : '';
    $SenderDataAddress = isset($SenderData['address']) && !empty($SenderData['address']) ? $SenderData['address'] : '';
}

if(isset($Data) && count($Data) > 0 )
{
    $DataAdminCommission = isset($Data['admin_commission']) && !empty($Data['admin_commission']) ? $Data['admin_commission'] : '';
    $DataCommissionAmount = isset($Data['commission_amount']) && !empty($Data['commission_amount']) ? $Data['commission_amount'] : 0;
    $DataFinalAmount = isset($Data['final_amount']) && !empty($Data['final_amount']) ? $Data['final_amount'] : 0;
}

?>

<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:0px solid #ddd;font-family:Arial, Helvetica, sans-serif;">

<tr>
    <td><img src="<?php if(isset($Data) && count($Data)>0) { echo $Data['logo']; }?>" width="100px" alt=""/></td>
    <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="font-size:9px;">
            <tr>
                <td width="35%"><b>Invoice No. : </b></td>
                <td width="65%"><?php echo $TrasactionDataID ?></td>
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
                <td width="65%"><?php echo $ReceivedDataFirstName.' '.$ReceivedDataLastName; ?></td>
            </tr>
             <tr>
                <td width="35%"><b>Contact Number : </b></td>
                <td width="65%"><?php echo $ReceivedDataMobile; ?></td>
            </tr>
            <tr>
                <td width="35%"><b>Email ID : </b></td>
                <td width="65%"><?php echo $ReceivedDataEmail; ?></td>
            </tr>
            <tr>
                <td width="35%"><b>Address : </b></td>
                <td width="65%"><?php echo $ReceivedDataAddress; ?></td>
            </tr>
        </table>
    </td>
    <td width="50%" style="font-size:9px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">    
             <tr>
                <td width="30%"><b>From : </b></td>
                <td width="70%"><?php echo $SenderDataFirstName.' '.$SenderDataLastName; ?><br /><?php echo $SenderDataEmail; ?> <br /><?php echo $SenderDataAddress; ?> <br /><?php echo $SenderDataMobile; ?></td>
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
                 <?php
                    if (isset($Data) && $Data['user_type'] == 'host') 
                    {
                        ?>  
                         <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Admin Commission(%)</td>
                         <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Commission Amount</td>
                        <?php
                    }
                ?>
                <td style="background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;">Total</td>
            </tr>
            <tr>
                <td style="font-size:9px;text-align: center;"><?php echo $TrasactionDataTransactionID; ?></td>
                <td style="font-size:9px;text-align: center;"><?php echo $TrasactionDataDate; ?></td>
                <td style="font-size:9px;text-align: center;"><?php echo $TrasactionDataFor; ?></td>
                <?php
                    if (isset($Data) && $Data['user_type'] == 'host') 
                    {
                        ?>  
                          <td style="font-size:9px;text-align: center;"><?php echo $DataAdminCommission; ?></td>

                          <td style="font-size:9px;text-align: center;"><?php echo 'INR '.number_format($DataCommissionAmount, 2, '.', ''); ?></td>
                        <?php
                    }
                ?>
                <td style="font-size:9px;text-align: center; "><?php echo 'INR '.number_format($TrasactionDataAmount, 2, '.', ''); ?></td>
            </tr>
             <?php
            if (isset($Data) && $Data['user_type'] == 'host') 
            {
                ?> 
                <tr>
                    <td  style="text-align: right;background-color: #f1f1f1;font-size:9px;font-weight: bold;text-align: center;" colspan="5">Final Amount</td>
                    <td style="font-size:9px;text-align: center;"><?php echo 'INR '.number_format($DataFinalAmount, 2, '.', ''); ?></td>
                </tr>
             <?php
            }
            ?>
        </table>
    </td>
</tr>
</table>

