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
      <i class="fa fa-plus-square-o"></i>
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
     <i class="fa fa-plus-square-o"></i>
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
      <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="store" enctype="multipart/form-data"  files ="true">
        {{csrf_field()}}

        <div class="form-group col-lg-11">
           <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> First Name <i class="red">*</i></label>
           <div class="col-sm-8 col-lg-5 controls">
            <input type="text" name="first_name" class="form-control" value="{{old('first_name')}}" data-rule-lettersonly="true" data-rule-required="true" data-rule-maxlength="255"  placeholder="First Name">
            <span class='error help-block'>{{ $errors->first('first_name') }}</span>
          </div>          
        </div> 
        <div class="form-group col-lg-11">
           <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> Last Name <i class="red">*</i></label>
           <div class="col-sm-8 col-lg-5 controls">
            <input type="text" name="last_name" class="form-control" value="{{old('last_name')}}" data-rule-lettersonly="true"  data-rule-required="true" data-rule-maxlength="255"  placeholder="Last Name">
            <span class='error help-block'>{{ $errors->first('last_name') }}</span>
          </div>          
        </div>
        <div class="form-group col-lg-11">
           <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> Email Id <i class="red">*</i></label>
           <div class="col-sm-8 col-lg-5 controls">
            <input type="text" name="email" class="form-control" value="{{old('email')}}" data-rule-email="true" data-rule-required="true" data-rule-maxlength="255"  placeholder="Email address">
            <span class='error help-block'>{{ $errors->first('email') }}</span>
          </div>          
        </div>
        <div class="form-group col-lg-11">
           <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;"> Support level <i class="red">*</i></label>
           <div class="col-sm-8 col-lg-5 controls">
              <select class="form-control" tabindex="1" name="support_level" data-rule-required="true" onchange="getData();">
                <option value="">Select Support Level   </option>
                <option value="L1">Highest Level (L1) </option>
                <option value="L2">Middle Level (L2) </option>
                <option value="L3">Lowest Level (L3) </option>
              </select>
              <span class='error help-block'>{{ $errors->first('support_level') }}</span>
          </div>          
        </div>
        <div class="form-group col-lg-11">
           <div class="col-sm-6 col-sm-offset-4 col-lg-6 col-lg-offset-3">
              <input type="submit" value="Save" class="btn btn btn-primary btn-custom">
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
