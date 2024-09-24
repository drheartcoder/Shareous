@extends('admin.layout.master')
<style>
    .control-label{font-weight: 600;}
</style>
@section('main_content')

<!-- BEGIN Page Title -->
<div class="page-title">
    <div>
        <h1>
            <i class="fa fa-eye"></i>
            {{ isset($page_title)?$page_title:"" }}
        </h1>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/admin/css/app.css">
<!-- END Page Title -->

<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
        </li>
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="fa {{$module_icon}}"></i>
            <a href="{{ $module_url_path}}">{{ $module_title or ''}}</a>
        </span> 
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="fa fa-eye"></i>
        </span>
        <li class="active">{{ $page_title or ''}}</li>
    </ul>
</div>
<!-- END Breadcrumb -->

<!-- BEGIN Main Content -->
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-title">
                <h3>
                    <i class="fa {{$module_icon}}"></i>
                    {{ isset($page_title)?$page_title:"" }}
                </h3>
            </div>
            <div class="box-content">
                @include('admin.layout._operation_status')
                @if(isset($arr_user) && sizeof($arr_user)>0)
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading font-bold">Personal Details</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <div class="thumbnail">

                                             @if(isset($arr_user['profile_image']) && $arr_user['profile_image'] != '')
                                                @if(strpos($arr_user['profile_image'], 'http') !== false)
                                                    <?php $profile_img_src = $arr_user['profile_image']; ?>
                                                @else
                                                    @if(file_exists($profile_image_base_path.$arr_user['profile_image'] ))
                                                        <?php $profile_img_src = $profile_image_public_path.$arr_user['profile_image']; ?>
                                                    @else
                                                        <?php $profile_img_src = url('/uploads').'/default-profile.png'; ?> 
                                                    @endif
                                                @endif
                                            @else
                                                <?php $profile_img_src = url('/uploads').'/default-profile.png'; ?> 
                                            @endif
                                            
                                             <img src="{{$profile_img_src }}" class="img-responsive">

                                           <!--  @if(isset($arr_user['profile_image']) && $arr_user['profile_image']!="" )
                                            <img src="{{$profile_image_public_path.$arr_user['profile_image'] }}" class="img-responsive">
                                            @else
                                            <img src="{{url('/uploads/profile_image').'/default-image.jpeg' }}" class="img-responsive">
                                            @endif -->
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Username:</label>
                                            <div class="col-lg-8">
                                                {{isset($arr_user['user_name']) && $arr_user['user_name']!=""?$arr_user['user_name']:'NA'}}
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Display Name:</label>
                                            <div class="col-lg-8">
                                                {{isset($arr_user['display_name']) && $arr_user['display_name']!=""?$arr_user['display_name']:'NA'}}
                                            </div>
                                        </div>
                                        

                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">First Name:</label>
                                            <div class="col-lg-8">
                                                {{isset($arr_user['first_name']) && $arr_user['first_name']!=""?$arr_user['first_name']:'NA'}}
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Last Name:</label>
                                            <div class="col-lg-8">
                                                {{isset($arr_user['last_name']) && $arr_user['last_name']!=""?$arr_user['last_name']:'NA'}}
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Email:</label>
                                            <div class="col-lg-8">
                                                {{isset($arr_user['email']) && $arr_user['email']!="" ? $arr_user['email'] : 'NA'}}
                                                <i class="fa fa-check-circle" @if($arr_user['is_email_verified']) style="color: green;" @endif></i>
                                            </div>
                                        </div>

                                        <?php
                                        $country_code      = isset($arr_user['country_code']) && $arr_user['country_code'] != "" ? $arr_user['country_code'] : '';
                                        $mobile_number     = isset($arr_user['mobile_number']) && $arr_user['mobile_number'] != "" ? $arr_user['mobile_number'] : 'NA';
                                        $new_mobile_number = isset($arr_user['new_mobile_number']) && $arr_user['new_mobile_number'] != "" ? $arr_user['new_mobile_number'] : '';
                                        ?>
                                        
                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Mobile:</label>
                                            <div class="col-lg-8">
                                                {{ $country_code.$mobile_number }}
                                                <i class="fa fa-check-circle" @if($arr_user['is_mobile_verified']) style="color: green;" @endif></i>
                                            </div>
                                        </div>

                                        @if( !empty($new_mobile_number) && $new_mobile_number != '' )
                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">New Mobile:</label>
                                            <div class="col-lg-8">
                                                {{ $country_code.$new_mobile_number }}
                                                <i class="fa fa-check-circle"></i>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Birthdate:</label>
                                            <div class="col-lg-8">
                                                {{isset($arr_user['birth_date']) && $arr_user['birth_date']!="" && $arr_user['birth_date']!="0000-00-00"? date('d-M-Y',strtotime($arr_user['birth_date'])):'NA'}}
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Gender:</label>
                                            <div class="col-lg-8">
                                                <?php
                                                    $gender = isset($arr_user['gender']) && !empty($arr_user['gender']) ? $arr_user['gender'] : '-';
                                                    if($gender == '0')
                                                    {
                                                        $gender_val = "Female";
                                                    }
                                                    elseif($gender == '1')
                                                    {
                                                        $gender_val = "Male";
                                                    }
                                                    else
                                                    {
                                                        $gender_val = "-";
                                                    }
                                                ?>
                                                {{ $gender_val }}
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-lg-4 control-label">Wallet Amount:</label>
                                            <div class="col-lg-8"> &#8377;
                                                {{ isset($arr_user['wallet_amount']) && $arr_user['wallet_amount']!="" ? $arr_user['wallet_amount'] : '0.00' }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading font-bold">Address Details</div>
                            <div class="panel-body">
                                <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Address:</label>
                                    <div class="col-lg-8">
                                        {{isset($arr_user['address']) && $arr_user['address']!=""?$arr_user['address']:'NA'}}
                                    </div>
                                </div>                           

                                <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">City:</label>
                                    <div class="col-lg-8">
                                        {{isset($arr_user['city']) && $arr_user['city']!=""?$arr_user['city']:'NA'}}
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading font-bold">Account Verification Details</div>
                            <div class="panel-body">
                                <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Account Status:</label>
                                    <div class="col-lg-8">
                                        {{(isset($arr_user['status']) && $arr_user['status']=="1") ? 'Active' : 'Inactive'}}
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Email Verification:</label>
                                    <div class="col-lg-8">
                                        @if($arr_user['is_email_verified'] == '0')
                                            @if ($arr_user['otp'] != "0" && $arr_user['otp'] != "")
                                                {{ $arr_user['otp'] }}
                                            @else
                                                Verification link is emailed
                                            @endif
                                        @else
                                            Verified
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Mobile OTP:</label>
                                    <div class="col-lg-8">
                                        @if(isset($arr_user['mobile_number']) && $arr_user['mobile_number']!="")
                                           {{(isset($arr_user['mobile_otp']) && $arr_user['mobile_otp']!="0" && $arr_user['mobile_otp']!="") ? $arr_user['mobile_otp'] : 'Verified'}}
                                        @else
                                            {{'N/A'}}
                                        @endif
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
        

            <div class="row">
                @if(isset($arr_bank_details) && sizeof($arr_bank_details)>0)
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading font-bold">Bank Details</div>
                        <div class="panel-body">                                 
                            <div class="form-group col-sm-12">
                                <label class="col-lg-4 control-label">Bank Name:</label>
                                <div class="col-lg-8">
                                    {{isset($arr_bank_details['0']['bank_name']) && $arr_bank_details['0']['bank_name']!=""?$arr_bank_details['0']['bank_name']:'NA'}}
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-lg-4 control-label">Bank Account No:</label>
                                <div class="col-lg-8">
                                    {{isset($arr_bank_details['0']['account_number']) && $arr_bank_details['0']['account_number']!=""?$arr_bank_details['0']['account_number']:'NA'}}
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-lg-4 control-label">Bank IFSC No.:</label>
                                <div class="col-lg-8">
                                    {{isset($arr_bank_details['0']['ifsc_code']) && $arr_bank_details['0']['ifsc_code']!=""?$arr_bank_details['0']['ifsc_code']:'NA'}}
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-lg-4 control-label">Account Type:</label>
                                <div class="col-lg-8">
                                    @if($arr_bank_details['0']['account_type'] == 1) Saving Account @elseif($arr_bank_details['0']['account_type'] == 2) Current Account @elseif($arr_bank_details['0']['account_type'] == 3) Recurring Account @elseif($arr_bank_details['0']['account_type'] == 4) Demat Account @else NRI Account @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                @endif

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-0 col-lg-10 col-lg-offset-0">
                        <button type="button" onclick="location.href='{{ $module_url_path }}'" class="btn">Back</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            @endif
                </div>
        </div>
    </div>
</div>


@stop
