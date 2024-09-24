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
      <i class="fa fa-eye"></i>
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
     <i class="fa fa-eye"></i>
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
          <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;">Image </label>
          <div class="col-sm-8 col-lg-5 controls">            
              <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                  @if(isset($testimonial['image']) && !empty($testimonial['image']) && $testimonial['image']!=null && file_exists($testimonial_image_base_img_path.$testimonial['image'] ))
                    <img src="{{$testimonial_image_public_img_path.$testimonial['image'] }}">
                  @else
                    <img src="{{$testimonial_image_public_img_path.'default.png'}}">
                  @endif
                </div>
              </div>
          </div>
        </div>

        <div class="form-group col-lg-11">
           <label class="col-sm-3 col-lg-3 control-label" for="page_title" style="text-align: right;">User Name </label>
           <div class="col-sm-7 col-lg-7 controls">
            <input name="question" id="question" class="form-control" placeholder="Title" value="{{isset($testimonial['title'])? $testimonial['title'] :'' }}" readonly />
          </div>          
        </div>

        <div class="form-group col-lg-11">
           <label class="col-sm-3 col-lg-3 control-label" for="page_title" style="text-align: right;">Message </label>
           <div class="col-sm-7 col-lg-7 controls">            
            <textarea name="answer" id="answer" rows="10" class="form-control" placeholder="Message" readonly>{{isset($testimonial['message'])? strip_tags($testimonial['message']) :'' }}</textarea>
          </div>
        </div>

      <div class="form-group col-lg-11">
       <div class="col-sm-6 col-sm-offset-3 col-lg-6 col-lg-offset-3">
        <a href="{{url($admin_panel_slug.'/testimonials')}}" type="button" class="btn btn-primary btn-custom">Ok</a>
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
