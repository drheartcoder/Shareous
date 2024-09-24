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
      <i class="fa {{$module_icon}}"></i>
      <a href="{{ url($previous_page_url) }}">{{ $previous_page_title or ''}}</a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$page_icon}}"></i>
    </span>
    <li class="active">{{ $page_title or ''}}</li>
  </ul>
</div>
<!-- END Breadcrumb -->

<!-- BEGIN Main Content -->
<div class="row">
  <div class="col-md-12">
    <div class="box {{support_navbar_color()}}">
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
        @include('support.layout._flash_errors') 
        <div class="row">
          <div class="col-sm-12">
            <!-- <form name="validation-form" id="validation_form" method="POST" class="form-horizontal" action="{{ $module_url_path }}/send/{{ $id }}" enctype="multipart/form-data"  files ="true"> -->
              <form name="validation-form" id="validation_form" method="POST" class="form-horizontal" enctype="multipart/form-data"  files="true">
              {{ csrf_field() }}
              
              <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">Email<i class="red">*</i></label>
                <div class="col-sm-7 col-lg-7 controls">
                  <input type="text" name="email_id" id="email_id" readonly="" class="form-control" data-rule-email="true" data-rule-required="true" data-rule-maxlength="255" placeholder="Email" value="{{ isset($user_data['user_details']['email']) ? $user_data['user_details']['email'] : 'NA' }}">
                  <span class='help-block'>{{ $errors->first('email_id') }}
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">Message<i class="red">*</i></label>
                <div class="col-sm-7 col-lg-7 controls">
                  <textarea name="message" id="message" rows="7" placeholder="Reply Message" class="form-control" data-rule-required="true" >{{ old('message') }}</textarea>
                  <span class='help-block'>{{ $errors->first('message') }}</span>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                  <button class="btn btn-primary" id="btn_send">Send</button>
                  <button class="btn btn-primary btn-custom" id="btn_reject">Reject & Send</button>
                  <a href="{{ url($module_url_path) }}/view/{{ $id }}" class="btn btn-cancel">Cancel</a>
                </div>
              </div>

            </form>      
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() 
    { 
      $("#btn_send").click(function(){
        $('#validation_form').attr('action', '{{ $module_url_path }}/send/{{ $id }}');
      });

      $("#btn_reject").click(function(){
        $('#validation_form').attr('action', '{{ $module_url_path }}/reject/{{ $id }}');
      });

      jQuery('#validation_form').validate({
        ignore: [],
        errorPlacement: function(error, element) 
        {
          if(element.attr("name") == "category_image")
          { 
            error.appendTo("#category_image");
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
