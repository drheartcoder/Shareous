<!DOCTYPE html>
<html>
  <head>
      <style type="text/css">
      .listed-btn a {border: 1px solid #ff4747; color: #ffffff; display: block; font-size: 18px; letter-spacing: 0.5px; background-color: #ff4747;
          margin: 0 auto; max-width: 200px; padding: 11px 6px; height: initial; text-align: center; text-transform: capitalize; text-decoration: none; width: 100%;
          border-radius: 5px;}
          .listed-btn a:hover{background-color: transparent; order: 1px solid #f50001; color: #f50001;}
          .logo-bg{margin-top: 40px;}

          .btn-cancel {
              background-color: #fff;
              border: 1px solid #ff4747;
              font-size: 12px;
              text-align: center;
              padding: 6px 10px 4px;
              min-width: 110px;
              text-transform: uppercase;
              color: #ff4747;
              border-radius: 3px;
              line-height: 16px;
          }
      </style>
  </head>

  <body style="background:#f1f1f1; margin:5px; padding:5px; font-size:15px; font-family:'roboto', sans-serif; line-height:25px; color:#666; text-align:justify;">
    <div style="max-width:610px;width:100%;margin:0 auto;">
        <div style="padding:0px 17px;">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td bgcolor="#FFFFFF" style="border:2px solid #e5e5e5;">
                        <table style="margin-bottom: 0; margin-top: 33px;" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr >
                                <td style=" color: #333;font-size: 17px; text-align: center;">
                                    <table style="margin-bottom: 0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td style="text-align:center;"><a href="{{url('/')}}"><img src="{{url('/front/images/logo-inner.png')}}" alt="{{config('app.project.name')}}"/></a></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>

                            <tr><td style="color: rgb(51, 51, 51); text-align: center; font-size: 21px; line-height: 26px; padding-top: 5px;">Welcome To {{ config('app.project.name') }}</tr>
                            <tr><td style="color: #333333;font-size: 15px;padding-top: 3px;text-align: center;">Reset your account password</td></tr>
                            <tr>
                              <td height="40"></td>
                            </tr>
                            <tr>
                              <td style="color: #333333; font-size: 16px; padding: 0 30px;">
                                Hello <span style="color:#f50001;font-family: 'Latomedium',sans-serif;">Support,</span>
                              </td>
                            </tr>
                            <tr>
                              <td style="color: #545454;font-size: 15px;padding: 12px 30px;">
                                You recently requested a password reset,Please click below to reset your account password,
                              </td>
                            </tr>

                            <tr>
                              <td height="20"></td>
                            </tr>

                            <tr><td class="listed-btn"><a target='_blank' href="{{ URL::to('support/password_reset',$token,false) }}" class='link2' style='color:#ffffff;'>Reset Your Password</a></td></tr>
                            <tr>
                              <td height="40"></td>
                            </tr>
                            <tr>
                              <td style="color: #333333; font-size: 16px; padding: 0 30px;">
                                Thanks &amp; Regards,
                              </td>
                            </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="margin-bottom: 0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="font-size:15px;background:#333; text-align: center; color: rgb(255, 255, 255); padding: 15px;">
                                                    Copyright &copy; {{date('Y')}} <a href="{{url('/')}}" style="color:#fff;">{{config('app.project.name')}}</a>. All Right Reserved.  <a href="{{url('/terms-conditions')}}" style="color:#fff;">Terms &amp; Conditions</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>             
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>      
        </div>       
  </body>
</html>
