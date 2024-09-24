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
      <i class="fa {{$page_icon}}"></i>
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
            <form name="validation-form" id="validation_form" method="POST" class="form-horizontal" action="{{$module_url_path}}/send/{{$id}}" enctype="multipart/form-data"  files ="true">
              {{csrf_field()}}
              <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">Email<i class="red">*</i></label>
                <div class="col-sm-7 col-lg-7 controls">
                  <input type="text" name="email_id" id="email_id" class="form-control" data-rule-email="true" data-rule-required="true" data-rule-maxlength="255" placeholder="Email" value="{{$arr_contact_enquiry['email_id']}}">
                  <span class='help-block'>{{ $errors->first('email_id') }}
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">Message<i class="red">*</i></label>
                <div class="col-sm-7 col-lg-7 controls">
                  <textarea name="message" rows="7" class="form-control desc"  data-rule-textInMce="true" data-rule-required="true" placeholder="Content">
                    @if(!empty($arr_contact_enquiry['message']))
                    <p>***Your Reply Message Here***</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>{{ "On ".$arr_contact_enquiry['created_at'].', '.$arr_contact_enquiry['name'].' wrote:' }}</p>
                    <div style="background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;">{{$arr_contact_enquiry['message']}}</div>
                    @endif
                  </textarea>
                  <span class='help-block'>{{ $errors->first('message')}}
                  </span>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                  <button class="btn btn-primary btn-custom">Send</button>
                  <a href="{{url($admin_panel_slug.'/contact')}}" class="btn btn-cancel">Cancel</a>
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
    <script type="text/javascript">
   function saveTinyMceContent()
    {
      tinyMCE.triggerSave();
    }

    $(document).ready(function()
    {
      tinymce.init({
        selector: 'textarea',
        height:350,
        plugins: [
        'advlist autolink lists link charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime table contextmenu paste code'
        ],
        toolbar: 
        'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
        content_css: [      
      
        ],
      });  
    });
</script>
  @stop
