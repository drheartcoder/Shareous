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
      <i class="fa fa-file"></i>
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
         <label class="col-sm-3 col-lg-3 control-label" for="title" style="text-align: left;">{{$module_title or ''}} Title <i class="red">*</i></label>
         <div class="col-sm-7 col-lg-7 controls">
          <input type="text" name="title" class="form-control" value="{{isset($object['page_title']) ? ucfirst($object['page_title']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} Title">
          <span class='error help-block'>{{ $errors->first('title') }}</span>
        </div>
      </div>

      <div class="form-group col-lg-11">
       <label class="col-sm-3 col-lg-3 control-label" for="slug" style="text-align: left;">{{$module_title or ''}} Slug <i class="red">*</i></label>
       <div class="col-sm-7 col-lg-7 controls">
        <input type="text" name="slug" class="form-control" value="{{isset($object['page_slug']) ? $object['page_slug']:''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} Slug">
        <span class='error help-block'>{{ $errors->first('slug') }}</span>
      </div>
    </div>

<div class="form-group col-lg-11">
   <label class="col-sm-3 col-lg-3 control-label" for="meta_title" style="text-align: left;">{{$module_title or ''}} Meta Title <i class="red">*</i></label>
   <div class="col-sm-7 col-lg-7 controls">
    <input type="text" name="meta_title" class="form-control" value="{{isset($object['meta_title']) ? ucfirst($object['meta_title']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} Meta Title">
    <span class='error help-block'>{{ $errors->first('meta_title') }}</span>
  </div>
</div>

<div class="form-group col-lg-11">
   <label class="col-sm-3 col-lg-3 control-label" for="meta_keyword" style="text-align: left;">{{$module_title or ''}} Meta Keyword <i class="red">*</i></label>
   <div class="col-sm-7 col-lg-7 controls">
    <input type="text" name="meta_keyword" class="form-control" value="{{isset($object['meta_keyword']) ? ucfirst($object['meta_keyword']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} Meta Keyword">
    <span class='error help-block'>{{ $errors->first('meta_keyword') }}</span>
  </div>
</div>

<div class="form-group col-lg-11">
   <label class="col-sm-3 col-lg-3 control-label" for="meta_description" style="text-align: left;">{{$module_title or ''}} Meta Description <i class="red">*</i></label>
   <div class="col-sm-7 col-lg-7 controls">
    <input type="text" name="meta_description" class="form-control" value="{{isset($object['meta_description']) ? ucfirst($object['meta_description']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="{{$module_title or ''}} Meta Description">
    <span class='error help-block'>{{ $errors->first('meta_description') }}</span>
  </div>
</div>

<div class="form-group col-lg-11">
 <label class="col-sm-3 col-lg-3 control-label" for="description" style="text-align: left;">{{$module_title or ''}} Description <i class="red">*</i></label>
 <div class="col-sm-7 col-lg-7 controls">
  <textarea name="description" rows="7" class="form-control desc"  data-rule-textInMce="true" data-rule-required="true" placeholder="Content">{{isset($object['page_description']) ? $object['page_description']:''}}</textarea>
  <span class='error help-block'>{{ $errors->first('description') }}</span>
</div>
</div>

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
