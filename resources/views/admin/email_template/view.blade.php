<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
    .listed-btn a {border: 1px solid #ff4747; color: #ffffff; display: block; font-size: 18px; letter-spacing: 0.5px; background-color: #ff4747;
      margin: 0 auto; max-width: 200px; padding: 11px 6px; height: initial; text-align: center; text-transform: capitalize; text-decoration: none; width: 100%;
      border-radius: 5px;}
      .listed-btn a:hover{background-color: transparent; order: 1px solid #f50001; color: #f50001;}
      .logo-bg{margin-top: 40px;}
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
              <table style="margin-bottom: 0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr >
                  <td style=" color: #333;font-size: 17px; text-align: center;">
                    <table style="margin-bottom: 0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="text-align:center;"><a href="{{url('/')}}"><img src="{{url('/front/images/logo-inner.png')}}" class="logo-bg"  alt="logo"/></a></td>

                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td height="20"></td>
                </tr>

                <tr><td style="color: rgb(51, 51, 51); text-align: center; font-size: 21px; line-height: 26px; padding-top: 5px;">Welcome To Vacation Home Rental</td></tr>
                <tr>
                <td>
                {!!$object['template_html']!!}
                {{-- <p style="color: #333333;font-size: 17px;padding-top: 5px;text-align: center;">##SUBJECT##</p>

                    <div style="height: 10px"></div>

                    <p style="color: #333333; font-size: 18px; padding: 0 40px;">
                      Hello <span style="color:#f50001;font-family: 'Latomedium',sans-serif;">##USER_NAME##,</span>
                    </p>

                    <p style="color: #545454;font-size: 17px;padding: 15px 40px;">
                      You are recently requested a password reset,Please click on below link to reset your account password,
                    </p>


                    <p class="listed-btn"><a href="##SITE_URL##">Reset Password</a></p>
                    <div style="height: 30px"></div>
                   --}}</td>
                </tr>
                {{-- <tr>
                  <td style="color: #333333; font-size: 16px; padding: 0 50px;">
                    Thanks &amp; Regards,
                  </td>
                </tr>

                <tr>
                  <td style="color: #f50001;  font-size: 15px; padding: 0 50px;">
                    Vacation Home Rental
                  </td>
                </tr> --}}
                <tr>
                  <td>&nbsp;</td>
                </tr>                                    
                <tr>
                  <td>
                    <table style="margin-bottom: 0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="font-size:15px;background:#333; text-align: center; color: rgb(255, 255, 255); padding: 15px;">
                          Copyright &copy; {{date('Y')}} <a href="{{url('/')}}" style="color:#fff;">Vacation Home Rental</a>. All Right Reserved.  <a href="{{url('/terms-and-conditions')}}" style="color:#fff;">Terms &amp; Conditions</a>
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

