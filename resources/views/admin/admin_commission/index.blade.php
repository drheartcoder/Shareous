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
      <i class="fa fa-home">
      </i>
      <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard
      </a>
    </li>
    <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa {{$module_icon}}">
      </i>
    </span> 
    <li class="active">  {{ isset($page_title)?$page_title:"" }}
    </li>
  </ul>
</div>
<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3>
          <i class="fa {{$module_icon}}">
          </i>{{ isset($page_title) ? $page_title:"" }} 
        </h3>
        <div class="box-tool">
        </div>
      </div>
      <div class="box-content">
        @include('admin.layout._operation_status')

        <form name="validation-form" id="admin-commission" method="POST" class="form-horizontal" action="{{$module_url_path}}/update" enctype="multipart/form-data">
          {{ csrf_field() }}         

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Admin Commission<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <div class="input-group">
                  <input type="text" name="admin_commission" id="admin_commission" class="form-control" data-rule-required="true" pattern="^(?!0+$)\d{1,2}(\.\d{0,2})?$" data-native-error="Please enter valid input" placeholder="Admin Commission" onkeypress="return isNumberKey(event)" value="{{$arr_admin_commission['admin_commission'] or ''}}">
                  <span class="input-group-addon" id="perc-addon"><i class="fa fa-percent"></i></span>
              </div>  
                  <span class='help-block'>{{ $errors->first('admin_commission') }}</span>
            </div>
          </div>

          <!-- <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Tax (GST)<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <div class="input-group">  
                  <input type="text" name="gst" id="gst" class="form-control" data-rule-required="true" pattern="^(?!0+$)\d{1,2}(\.\d{0,2})?$" data-native-error="Please enter valid input" placeholder="Enter GST" onkeypress="return isNumberKey(event)" value="{{$arr_admin_commission['gst'] or ''}}">
                  <span class="input-group-addon" id="perc-addon"><i class="fa fa-percent"></i></span>
              </div>
              <span class='help-block'>{{ $errors->first('gst') }}</span>
            </div>
          </div> -->

          <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
              <button class="btn btn btn-primary btn-custom">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script type="text/javascript">
    $(document).ready(function() 
    {
      jQuery('#admin-commission').validate({
        ignore: [],
        errorPlacement: function(error, element) 
        {  error.insertAfter(element);
        } 
      });
    });
  </script>
@endsection


