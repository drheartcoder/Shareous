@extends('admin.layout.master') 
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
      <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
    </li>
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$module_icon}}"></i>
      <a href="{{ url($module_url_path) }}">{{ $module_title or ''}}</a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa fa-pencil-square-o"></i>
    </span>
    <li class="active">{{ str_singular($page_title)}}</li>
  </ul>
</div>
<!-- END Breadcrumb -->

<!-- BEGIN Main Content -->
<div class="row">
   <div class="col-md-12">
    <div class="box">
     <div class="box-title">
        <h3>
         <i class="fa fa-pencil-square-o"></i>
         {{ isset($page_title)?str_singular($page_title):"" }}
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
            <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="{{$module_url_path}}/update/{{$id}}" enctype="multipart/form-data" files ="true">
              {{csrf_field()}}

                
                <div class="form-group col-lg-11">
                   <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> First Name <i class="red">*</i></label>
                   <div class="col-sm-8 col-lg-5 controls">
                    <input type="text" name="first_name" class="form-control" value="{{isset($support_team['first_name'])? $support_team['first_name'] :'' }}" data-rule-required="true" data-rule-lettersonly="true" data-rule-maxlength="255"  placeholder="First Name">
                    <span class='error help-block'>{{ $errors->first('first_name') }}</span>
                  </div>          
                </div> 
                <div class="form-group col-lg-11">
                   <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> Last Name <i class="red">*</i></label>
                   <div class="col-sm-8 col-lg-5 controls">
                    <input type="text" name="last_name" class="form-control" value="{{isset($support_team['last_name'])? $support_team['last_name'] :'' }}" data-rule-required="true" data-rule-lettersonly="true" data-rule-maxlength="255"  placeholder="Last Name">
                    <span class='error help-block'>{{ $errors->first('last_name') }}</span>
                  </div>          
                </div>
                <div class="form-group col-lg-11">
                   <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> Email Id <i class="red">*</i></label>
                   <div class="col-sm-8 col-lg-5 controls">
                    <input type="text" name="email" class="form-control" readonly value="{{isset($support_team['email'])? $support_team['email'] :'' }}">
                    <span class='error help-block'>{{ $errors->first('email') }}</span>
                  </div>          
                </div>  

                <div class="form-group col-lg-11">
                   <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> Support level <i class="red">*</i> </label>
                   <div class="col-sm-8 col-lg-5 controls">
                      <select style="margin-top: 0;" class="form-control" tabindex="1" data-rule-required="true" name="support_level" onchange="getData();">
                        <option value="">Select</option>
                        <option value="L1" <?php if($support_team['support_level'] == 'L1'){ ?> selected="selected" <?php } ?> >Highest Level (L1) </option>
                        <option value="L2" <?php if($support_team['support_level'] == 'L2'){ ?> selected="selected" <?php } ?> >Middle Level (L2) </option>
                        <option value="L3" <?php if($support_team['support_level'] == 'L3'){ ?> selected="selected" <?php } ?> >Lowest Level (L3) </option>
                      </select>
                      <span class='error help-block'>{{ $errors->first('support_level') }}</span>
                  </div>          
                </div>    
                <div class="form-group col-lg-11">
                    <div class="col-sm-6 col-sm-offset-3 col-lg-6 col-lg-offset-3">
                      <input type="submit" value="Update" class="btn btn btn-primary btn-custom">
                      <a href="{{url($admin_panel_slug.'/support_team')}}" type="button" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>

            </form>      
          </div>
        </div>
     </div>
    </div>
   </div>
<script type="text/javascript">
  
$(document).ready(function()
{
    $('#validate_form').validate();
});

</script>
@stop
