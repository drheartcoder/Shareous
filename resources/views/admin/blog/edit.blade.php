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
             <label class="col-sm-2 col-lg-2 control-label" for="page_title" style="text-align: right;"> Blog Category <i class="red">*</i></label>
             <div class="col-sm-8 col-lg-8 controls">
                <select class="form-control" tabindex="1" data-rule-required="true" name="category_id" onchange="getData();">
                  <option value="">Select blog category </option>
                  @foreach($blog_category as $key => $category)
                  <option value="{{$category['id']}}" @if($blog['blog_category_id']==$category['id']) selected="selected" @endif>{{$category['category_name']}}</option>
                  @endforeach
                </select>
            </div>          
          </div>

          <div class="form-group col-lg-11">
              <label class="col-sm-2 col-lg-2 control-label" for="page_title" style="text-align: right;">Title <i class="red">*</i></label>
              <div class="col-sm-8 col-lg-8 controls">
                  <input type="text" name="title" class="form-control" value="{{isset($blog['title']) ? ucfirst($blog['title']):''}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="Enter Title">
                  <span class='error help-block'>{{ $errors->first('title') }}</span>
              </div>
          </div>
          <div class="form-group col-lg-11">
              <label class="col-sm-2 col-lg-2 control-label" for="page_title" style="text-align: right;">Descriptions <i class="red">*</i></label>
              <div class="col-sm-10 col-lg-10 controls">

                  <textarea name="descriptions" class="form-control ckeditor" data-rule-required="true" >  {{isset($blog['description']) ? ucfirst($blog['description']):''}} </textarea>
                  <span class='error help-block'>{{ $errors->first('descriptions') }}</span>
              </div>
          </div>

         <div class="form-group col-lg-11">
            <label class="col-sm-2 col-lg-2 control-label">Blog Image <i class="red">*</i></label>
            <div class="col-sm-8 col-lg-8 controls">
              <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new img-thumbnail" style="width: 150px; height: 150px;">
                  @if(isset($blog['blog_image']) && !empty($blog['blog_image']))
                  <img src={{ $blog_image_public_img_path.$blog['blog_image']}} alt="" />
                  @else
                  <img src="{{url('/web_admin')}}/images/default-blog-thumb.png" alt="" style="width: 200px; height: 150px;"/>                        
                  @endif
                </div>
                <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                  @if(isset($blog['blog_image']) && !empty($blog['blog_image']))  
                  <img src={{ $blog_image_public_img_path.$blog['blog_image']}} alt="" />
                  @else
                  <img src="{{url('/web_admin')}}/images/default-blog-thumb.png" alt="" style="width: 200px; height: 150px;"/>                        
                  @endif   
                </div>
                <div>
                  <span class="btn btn-default btn-file"><span class="fileupload-new" >Select Image</span> 
                  <span class="fileupload-exists">Change</span>

                  <input type="file" name="blog_image" id="blog_image" class="file-input news-image validate-image">

                  </span> 
                  <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                  <span>
                  </span> 
                </div>
              </div>             
              <i class="red"> 
                    <span class="label label-important">NOTE!</span>
                    <i class="red"> Allowed only jpg | jpeg | png <br/> 
                    Please upload image with Width and Height greater than or equal to 767 X 238 for best result. </i>
                    <input type="hidden" id="invalid_size" value="">
                    <input type="hidden" id="invalid_ext" value="">
                    <span class='help-block'>{{ $errors->first('blog_image') }}</span>
                  </i>
            </div>
          </div>

          <input type="hidden" name="oldimage" value="{{isset($blog['blog_image']) ? $blog['blog_image'] :''}}">

          <div class="form-group col-lg-11">
             <div class="col-sm-6 col-sm-offset-2 col-lg-6 col-lg-offset-3">
              <input type="submit" value="Update" class="btn btn btn-primary btn-custom">
              <a href="{{url($module_url_path)}}" type="button" class="btn btn-cancel">Cancel</a>
            </div>
          </div>

</form>      
</div>
</div>
</div>
</div>
</div>
<script src="//cdn.ckeditor.com/4.10.0/full/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('#validate_form').validate();
    });

    $(document).on("change",".validate-image", function()
    {            
        var file=this.files;
        validateImage(this.files, 238,767);
    });


    CKEDITOR.editorConfig = function( config ) {
              config.language = 'es';
              config.uiColor = '#F7B42C';
              config.height = 300;
              config.toolbarCanCollapse = true;
            };

</script>

@stop
