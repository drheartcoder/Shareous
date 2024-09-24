@extends('admin.layout.master') 
<style>
/*.main-email-temps{margin-left: 35px;}*/
</style>
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
      <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="{{url($module_url_path).'/update/'.$id}}" enctype="multipart/form-data"  files ="true">
        {{csrf_field()}}
        <div class="form-group col-lg-11">
         <label class="col-sm-3 col-lg-3 control-label" for="name" style="text-align: left;">{{$module_title or ''}} Name <i class="red">*</i></label>
         <div class="col-sm-7 col-lg-7 controls">
          <input type="text" name="name" class="form-control" value="{{isset($object['template_name']) ? ucfirst($object['template_name']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} Name">
          <span class='error help-block'>{{ $errors->first('name') }}</span>
        </div>
      </div>

      <div class="form-group col-lg-11">
       <label class="col-sm-3 col-lg-3 control-label" for="from" style="text-align: left;">{{$module_title or ''}} From <i class="red">*</i></label>
       <div class="col-sm-7 col-lg-7 controls">
        <input type="text" name="from" class="form-control" value="{{isset($object['template_from']) ? ucfirst($object['template_from']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} From">
        <span class='error help-block'>{{ $errors->first('from') }}</span>
      </div>
    </div>

    <div class="form-group col-lg-11">
     <label class="col-sm-3 col-lg-3 control-label" for="from_email" style="text-align: left;">{{$module_title or ''}} From Email <i class="red">*</i></label>
     <div class="col-sm-7 col-lg-7 controls">
     <input type="text" name="from_email" class="form-control" value="{{isset($object['template_from_mail']) ? ($object['template_from_mail']):''}}" data-rule-required="true"  data-rule-email="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} From Email">
      <span class='error help-block'>{{ $errors->first('from_email') }}</span>
    </div>
  </div>

  <div class="form-group col-lg-11">
   <label class="col-sm-3 col-lg-3 control-label" for="subject" style="text-align: left;">{{$module_title or ''}} Subject <i class="red">*</i></label>
   <div class="col-sm-7 col-lg-7 controls">
    <input type="text" name="subject" class="form-control" value="{{isset($object['template_subject']) ? ucfirst($object['template_subject']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} Subject">
    <span class='error help-block'>{{ $errors->first('subject') }}</span>
  </div>
</div>

<div class="form-group col-lg-11">
 <label class="col-sm-3 col-lg-3 control-label" for="body" style="text-align: left;">{{$module_title or ''}} Body <i class="red">*</i></label>
 <div class="col-sm-7 col-lg-7 controls">
  <textarea name="body" rows="7" class="form-control desc"  data-rule-textInMce="true" data-rule-required="true" placeholder="Content">{{isset($object['template_html']) ? $object['template_html']:''}}</textarea>
  <span class='error help-block'>{{ $errors->first('body') }}</span>
</div>
</div>

<div class="form-group col-lg-11">
 <label class="col-sm-3 col-lg-3 control-label" for="body" style="text-align: left;">{{$module_title or ''}} Variables</label>
  <div class="col-sm-7 col-lg-7 controls">
  @if($variables)
  @foreach($variables as $key => $variable)
  @if($key!=0) ,  @endif {{' '.$variable}}
  @endforeach
  @endif
  <br/>
<i class="red"> NOTE : Please don't change above variable's for proper functioning.</i>
</div>
</div>

<div class="form-group col-lg-11">
 <div class="col-xs-12 col-sm-12 col-sm-offset-0  col-md-6 col-lg-6 col-lg-offset-3 col-md-offset-3 col-xs-offset-0">
 <div class="main-email-temps">
  <input type="submit" value="Update" class="btn btn btn-primary btn-custom">
  <a href="{{url($module_url_path.'/preview/'.$id)}}" type="button" class="btn btn-primary btn-preview" target="_blank">Preview</a>
  <a href="{{url($module_url_path)}}" type="button" class="btn btn-cancel">Cancel</a>
  </div>
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

        //'//www.tinymce.com/css/codepen.min.css'
        ],
      });  
});
</script>

@stop
