    @extends('support.layout.master')                
    @section('main_content')
   
    <!-- BEGIN Page Title -->
    <div class="page-title">
        <div>
        </div>
    </div>
    <!-- END Page Title -->

    <!-- BEGIN Breadcrumb -->
    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{ url($support_panel_slug.'/dashboard') }}">Dashboard</a>
            </li>
            <span class="divider">
                <i class="fa fa-angle-right"></i>
            </span>
            <i class="fa fa-key"></i>
            <li class="active">  {{ $page_title or ''}}</li>
        </ul>
    </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->   
    
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class=" box-title">
                    <h3><i class="fa fa-key"></i>Change Password</h3>
                    <div class="box-tool">
                    </div>
                </div>
                <div class="box-content">
                    @include('support.layout._operation_status')  

                    <form name="validation-form" id="change-password-form" class="form-horizontal" method="POST" action="{{url($support_panel_slug.'/profile/update_password')}}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Current password</label>
                            <div class="col-sm-9 col-lg-4 controls">
                                <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Current Password" data-rule-required="true">
                                <span class='help-block error'>{{ $errors->first('current_password') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">New password</label>
                            <div class="col-sm-9 col-lg-4 controls">
                                <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password" data-rule-required="true" data-rule-minlength="6">
                                <span class='help-block error'>{{ $errors->first('new_password') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Re-type New password</label>
                            <div class="col-sm-9 col-lg-4 controls">
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Re-type New password" data-rule-required="true" data-rule-equalto="#new_password">
                                <span class='help-block error'>{{ $errors->first('confirm_password') }}</span>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                <button type="submit" name="Save" class="btn btn-primary btn-custom">Save</button>
                                <a href="{{url($support_panel_slug.'/dashboard')}}" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- END Main Content -->
        <script type="text/javascript">
            $(document).ready(function() 
            { 
              jQuery('#change-password-form').validate({
                ignore: [],
                rules: {
                  "new_password": {
                    pattern: /(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^])(?=.*\d).*/,
                    minlength:6,
                }
            }, 

            messages: {
                new_password: {
                    pattern: "Your password must contain at least (1) lowercase and (1) uppercase and (1) special character and (1) letter, It should be greater than or equal to 6 character",

                },
            }
        });
          });
      </script>
      <script type="text/javascript">
          var node = document.querySelector('[title="BotDetect CAPTCHA Library for Laravel"]');
          node.remove();
      </script>
      @stop