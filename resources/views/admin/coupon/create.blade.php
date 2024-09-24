@extends('admin.layout.master') 
@section('main_content')

<div class="page-title"> <div> </div> </div>

<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
        </li>
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="{{$module_icon or ''}}"></i>
            <a href="{{ url($module_url_path) }}">{{ $module_title or ''}}</a>
        </span> 
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="fa {{$page_icon}}"></i>
        </span>
        <li class="active">{{ $page_title or ''}}</li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-title">
                <h3>
                    <i class="fa {{$page_icon}}"></i>
                    {{ isset($page_title)?$page_title:"" }}
                </h3>
                <div class="box-tool">
                    <a data-action="collapse" href="#"></a>
                    <a data-action="close" href="#"></a>
                </div>
            </div>
            <div class="box-content">
                @include('admin.layout._operation_status') 
                <div class="row">
                    <div class="col-sm-12">
                        <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/store" enctype="multipart/form-data"  files ="true">
                            {{csrf_field()}}

                            <div class="form-group col-lg-11">
                                <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Coupon Code <i class="red">*</i></label>
                                <div class="col-sm-5 col-lg-5 controls">
                                    <input type="text" name="coupon_code" class="form-control" value="{{old('coupon_code')}}" data-rule-required="true" data-rule-minlength="9" data-rule-maxlength="10"  placeholder="Enter Coupon Code">
                                    <span class='error help-block'>{{ $errors->first('coupon_code') }}</span>
                                </div>
                            </div>
                            <div class="form-group col-lg-11">
                                <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Descriptions <i class="red">*</i></label>
                                <div class="col-sm-5 col-lg-5 controls">
                                    <textarea name="descriptions" data-rule-maxlength="500" class="form-control" data-rule-required="true">  {{old('descriptions')}} </textarea>
                                    <span class='error help-block'>{{ $errors->first('descriptions') }}</span>
                                </div>
                            </div>    
                            <div class="form-group col-lg-11">
                                <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> Discount Type <i class="red">*</i></label>
                                <div class="col-sm-8 col-lg-5 controls">
                                    <select class="form-control" tabindex="1" name="discount_type" id="discount_type" data-rule-required="true">
                                        <option value="1"> Fix Amount </option>
                                        <option value="2"> Percentage </option>                
                                    </select>
                                    <span class='error help-block'>{{ $errors->first('discount_type') }}</span>
                                </div>          
                            </div>
                            <div class="form-group col-lg-11" id="fix-amount" >
                                <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Discount <i class="red">*</i></label>
                                <div class="col-sm-5 col-lg-5 controls" >
                                    <input type="text" name="fix-amount" class="form-control" value="{{old('discount')}}" data-rule-required="true" min="1" data-rule-maxlength="10" onkeypress="return isNumberKey(event)" placeholder="Enter fix discount amount">
                                    <span class='error help-block'>{{ $errors->first('discount') }}</span>
                                    <span class="label label-important">NOTE!</span>
                                    <i class="red"> Discount Amount will be only in INR </i>
                                </div>             
                            </div>   
                            <div class="form-group col-lg-11"  id="percentage" style='display:none;'>
                                <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Discount <i class="red">*</i></label>             
                                <div class="col-sm-5 col-lg-5 controls">
                                    <div class="input-group">  
                                        <input type="text" name="percentage" class="form-control" value="{{old('discount')}}" data-rule-required="true"   pattern="^(?!0+$)\d{1,2}(\.\d{0,2})?$" data-native-error="Please enter valid input" onkeypress="return isNumberKey(event)" placeholder="Enter discount in percentage">
                                        <span class="input-group-addon" id="perc-addon"><i class="fa fa-percent"></i></span> 
                                    </div>  
                                    <span class='error help-block'>{{ $errors->first('discount') }}</span>
                                </div>
                            </div>    
                            <div class="form-group col-lg-11">
                                <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Global Expiry <i class="red">*</i></label>
                                <div class="col-sm-5 col-lg-5 controls">
                                    <div class="input-group">  
                                        <input type="text" id="datepicker" name="global_expiry" class="form-control" data-rule-date="true" data-msg-date="The field global expiry must be a date." value="{{old('global_expiry')}}" data-rule-required="true" placeholder="Enter global expiry" readonly="" style="cursor: pointer;">
                                        <span class="input-group-addon" style="cursor: pointer;"><i class="fa fa-calendar"></i></span>
                                    </div>   
                                    <span class="error" style="color:red">{{$errors->first('global_expiry')}}</span>
                                    <div id="err_global_expiry"></div>
                                </div>
                            </div> 

                            <div class="form-group col-lg-11">
                                <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Auto Expiry <i class="red">*</i></label>
                                <div class="col-sm-5 col-lg-5 controls">
                                    <div class='input-group date' id='datetimepicker3'>
                                        <input type="text" name="auto_expiry" class="form-control datepicker-input" value="{{old('auto_expiry')}}" data-rule-required="true" placeholder="Enter auto expiry" readonly="" style="cursor: pointer;">
                                        <span class="input-group-addon" style="cursor: pointer;">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>  
                                    </div>
                                    <span class="error" id="datespicker" style="color:red">{{$errors->first('auto_expiry')}}</span>  
                                    <div id="err_auto_expiry"></div>                
                                </div>
                            </div> 

                            <!-- <div class="form-group col-lg-11">
                                <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Coupon Use <i class="red">*</i></label>
                                <div class="col-sm-5 col-lg-5 controls">
                                    <div class="radio-btns-new">    
                                        <div class="radio-btn">
                                            <input id="min_amount" name="coupon_type" type="radio" data-rule-required="true" checked value="1">
                                            <label for="min_amount">Min. Amount</label>
                                            <div class="check"></div>
                                        </div>
                                        <div class="radio-btn">
                                            <input id="first_time_user" name="coupon_type" type="radio" data-rule-required="true" value="2">
                                            <label for="first_time_user">User First Time</label>
                                            <div class="check"></div>
                                        </div> 
                                        <div class="radio-btn">
                                            <input id="both" name="coupon_type" type="radio" data-rule-required="true" value="3">
                                            <label for="both">Both</label>
                                            <div class="check"><div class="inside"></div></div>
                                        </div>                   
                                    </div>  
                                </div>
                            </div> -->
                            <div class="form-group col-lg-11">
                                <div class="col-sm-6 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                                    <input type="submit" value="Save" class="btn btn btn-primary btn-custom">
                                    <a href="{{url($module_url_path)}}" type="button" class="btn btn-cancel">Cancel</a>
                                </div>
                            </div>
                        </form>      
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>           

        $(function () {
            $("#datepicker").datepicker({
                todayHighlight: true,
                format: 'mm/dd/yyyy',
                minDate: 0,
                autoclose: true
            });

            $('#datetimepicker3').datetimepicker({
                format: 'HH:mm',
                ignoreReadonly: true
            });
        });

        $(document).ready(function(){
            $('#discount_type').on('change', function(){
                if ( this.value == '1') {
                    $("#percentage").hide();
                    $("#fix-amount").show();
                } else  if ( this.value == '2') {
                    $("#fix-amount").hide();
                    $("#percentage").show();
                } else {
                    $("#fix-amount").hide();
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#validate_form').validate({
                errorPlacement: function(error, element) 
                {
                    error.insertAfter(element);
                    var name = $(element).attr("name");
                    if(name === "blog_image") 
                    {
                        error.insertAfter('#err_payment_receipt');
                    } 
                    else if(name === "global_expiry") 
                    {
                        error.insertAfter('#err_global_expiry');
                    } 
                    else if(name === "auto_expiry") 
                    {
                        error.insertAfter('#err_auto_expiry');
                    }                    
                    else 
                    {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>

    @stop
