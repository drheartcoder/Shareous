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
            <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="{{$module_url_path}}/store" enctype="multipart/form-data" files ="true">
              {{csrf_field()}}

              <div class="form-group col-lg-11">
              <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;">Image </label>
              <div class="col-sm-8 col-lg-5 controls">            
                  <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                      <img src="{{$testimonial_image_public_img_path.'default.png'}}">   
                    </div>
                      <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                      </div>
                      <div>
                         <span class="btn btn-default btn-file" style="height:32px;">
                         <span class="fileupload-new">Select Image</span>
                         <span class="fileupload-exists">Change</span>
                         <input type="file"  data-validation-allowing="jpg, jpeg, png" class="file-input news-image validate-image" name="image" id="image"/><br>
                         </span>
                         <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                      </div>
                      <i class="red"> 
                        <span class="label label-important">NOTE!</span>
                        <i class="red"> Allowed only jpg | jpeg | png <br/> 
                        Please upload image with Height and Width greater than or equal to 250 X 250 for best result. </i>
                        <input type="hidden" id="invalid_size" value="">
                        <input type="hidden" id="invalid_ext" value="">
                        <span for="cat_img" id="err_cat_image" class='help-block'>{{ $errors->first('image') }}</span> 
                      </i>
                      <div id="file-upload-error" class="error"></div>
                      <span for="image" id="err-image" class="help-block">{{ $errors->first('image') }}</span>
                   </div>
              </div>
            </div>

              <div class="form-group col-lg-11">
                <label class="col-sm-3 col-lg-3 control-label" for="page_title" style="text-align: right;">User Name <i class="red">*</i> </label>
                <div class="col-sm-7 col-lg-7 controls">
                  <input name="title" id="title" data-rule-maxlength="255" class="form-control" data-rule-required="true" placeholder="User Name" value="{{old('title')}}">
                  <span class='error help-block' id="err_title">{{ $errors->first('title') }}</span>
                </div>          
              </div>

              <div class="form-group col-lg-11">
               <label class="col-sm-3 col-lg-3 control-label" for="message" style="text-align: right;">Message <i class="red">*</i></label>
               <div class="col-sm-7 col-lg-7 controls">
                <textarea name="message" id="message" rows="7" class="form-control desc"  data-rule-textInMce="true" data-rule-required="true" data-rule-minlength="255" data-rule-maxlength="300" placeholder="Content"></textarea>
                <span class='error help-block' id="err_message">{{ $errors->first('message') }}</span>
              </div>
              </div>

              <div class="form-group col-lg-11">
                <div class="col-sm-6 col-sm-offset-3 col-lg-6 col-lg-offset-3">
                  <input type="submit" value="Save" id="btnAddTestimonials" class="btn btn btn-primary btn-custom">
                  <a href="{{url($admin_panel_slug.'/testimonials')}}" type="button" class="btn btn-cancel">Cancel</a>
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
      //$('#validate_form').validate();
    });
  </script>
  <script type="text/javascript">
 function saveTinyMceContent()
 {
  tinyMCE.triggerSave();
}


$('#btnAddTestimonials').click(function()
{
   var body = tinymce.get("message").getBody();  
   var content = tinymce.trim(body.innerText || body.textContent);
   var image            = $('#image').val();
   var title       = $("#title").val(); 
   var flag = 1;

    if($.trim(image)=='')
    {
      $("#err-image").html("Please select image.");     
      $("#image").on('change',function(){ $("#err-image").html("");});
      $("#image").focus();
      flag = 0;
    }
    if($.trim(content)=='')
    {
      $("#err_message").html("Please enter message.");     
      $("#message").on('keyup',function(){ $("#err_message").html("");});
      //$("#message").focus();
      flag = 0;
    }
    else if($.trim(content).length<275)
    {
      $("#err_message").html("Please enter message more than 275 characters.");     
      $("#message").on('keyup',function(){ $("#err_message").html("");});
      $("#message").focus();
      flag = 0;
    }
    else if($.trim(content).length>300)
    {
      $("#err_message").html("Please enter message less than 300 characters.");     
      $("#message").on('keyup',function(){ $("#err_message").html("");});
      $("#message").focus();
      flag = 0;
    }
    if($.trim(title)=='')
    {
      $("#err_title").html("Please enter name.");     
      $("#title").on('keyup',function(){ $("#err_title").html("");});
      //$("#title").focus();
      flag = 0;
    }
    else if($.trim(title).length<3)
    {
      $("#err_title").html("Please enter name greater than 3 characters.");     
      $("#title").on('keyup',function(){ $("#err_title").html("");});
      //$("#title").focus();
      flag = 0;
    }
    else if($.trim(title).length>40)
    {
      $("#err_title").html("Please enter name less than 40 characters.");     
      $("#title").on('keyup',function(){ $("#err_title").html("");});
      //$("#title").focus();
      flag = 0;
    }
    if(flag==1)
    {
      return true;
    }
    else
    {
      return false;
    }
});
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

$('#title').on('blur',function(){

  $('#err_title').html('');
  var title = $(this).val();

  if($.trim(title)!='')
  {
     var token = $('input[name="_token"]').val();
     
     $('#validate_form').attr('disabled',true);

     $.ajax({
           url   : "{{ url('/') }}/admin/testimonials/duplicate",
           type : "POST",
           dataType:'json',
           data: {_token:token,title:title},
           success : function(res){

              if($.trim(res)=='error')
              {
                 $('#err_title').show();
                 $('#err_title').html('User Name already exists');
                 $('#validate_form').attr('disabled',true);
                 $('#title').focus();   
                 return false; 
              }
              else if($.trim(res)=='success')
              {
                 $('#err_title').show();
                 $('#validate_form').attr('disabled',false);
                 return true;
              }
              else
              {
                 $('#err_title').show();
                 $('#title').focus();
                 $('#err_title').html('Something has get wrong please try again later.');
                 return false;
              }
           }
     });
  }
});

var fileExtension = ['jpg','jpeg','png'];
$('#image').on('change', function(evt) {
    if($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        $('#file-upload-error').show();
        $('#image').focus();
        $('#file-upload-error').html("Please upload valid image with valid extension i.e "+fileExtension.join(', '));
        $('#file-upload-error').fadeOut(8000);
        $("#image").val('');
        $('.upload-photo').remove();
        return false;
    }
    if(this.files[0].size > 5000000)
    {
        $('#file-upload-error').show();
        $('#image').focus();
        $('#file-upload-error').html('Max size allowed is 5mb.');
        $('#file-upload-error').fadeOut(8000);
        $("#image").val('');
        return false;
    }
});
</script>
  @stop
