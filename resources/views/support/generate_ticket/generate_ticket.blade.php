@extends('support.layout.master')
@section('main_content')

<script src="{{url('front/js/image_validation.js')}}"></script>
<!-- BEGIN Content -->
<style type="text/css">
  .file-upload-input {
    position: absolute;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    outline: none;
    opacity: 0;
    cursor: pointer;
}
.attach-ile-head {
    font-family: 'heebomedium';
    font-size: 16px;
    color: #374247;
    line-height: 16px;
    margin-bottom: 20px;
}
.image-upload-wrap {
    margin-top: 20px;
    border: 1px dashed #bebebe;
    position: relative;
    background-color: #f9f9f9;
    margin-bottom: 30px;
}
.drag-text {
    text-align: center;
    padding: 30px 0 40px;
}
.remove-image {
    width: 100%;
    margin: 0;
    color: #374247;
    background: #ededed;
    border: none;
    padding: 10px;
    border-radius: 4px;
    transition: all .2s ease;
    outline: none;
    text-transform: uppercase;
    font-weight: normal;
}
.image-title-wrap {
    padding: 0 15px 15px 15px;
    color: #222;
}
.file-upload-content {
    display: none;
    text-align: center;
}
.icon-uploads-generates {
    font-size: 60px;
    color: #afbac0;
}
.image-upload-wrap.for-error .error{margin-bottom: -24px;}

</style>
<div id="main-content">
  <!-- BEGIN Page Title -->
  <div class="page-title">
    <div>
      <h1><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h1>
      <!-- <h4>Overview, stats, chat and more</h4> -->
    </div>
  </div>

  <!-- END Page Title -->
  <!-- BEGIN Breadcrumb -->
  <div id="breadcrumbs">
    <ul class="breadcrumb">
      <li>
        <i class="fa fa-home"></i>
        <a href="{{url($support_panel_slug.'/dashboard')}}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      <li class="active"> <i class="fa {{$module_icon}}"></i>  {{$page_title or ''}}</li>
    </ul>
  </div>
  <!-- END Breadcrumb -->
  
  <!-- BEGIN Tiles -->
 @include('support.layout._operation_status')

  <!-- BEGIN Tiles -->
  <div class="row">
    <div class="col-md-12">
      <div class="box {{theme_color()}}">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
        </div>
        <div class="box-content">         
        
             <form name="validation-form" id="site-setting-form" method="POST" class="form-horizontal" action="{{url($module_url_path.'/store_ticket')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
        
          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">User<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-6 controls">
                <select class="form-control chosen-with-diselect" name="user_id" id="user_id" data-rule-required="true"> 
                    <option value="">--Select User--</option>
                     @if($arr_user)
                         @foreach($arr_user as $user)
                            <option value="{{$user['id']}}">{{!empty($user['first_name'])&& !empty($user['last_name']) || !empty($user['email'])  ? ucfirst($user['first_name'])."&nbsp;".ucfirst($user['last_name'])."&nbsp; <".$user['email'].">" : ''}}</option>
                         @endforeach
                     @endif
                </select>
                <span class="help-block" id="err_user_id">{{ $errors->first('user_id') }}</span>
            </div>
          </div>

          <div id="user_type_div"></div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Query Type<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-6 controls">
                <select class="form-control" name="query_type_id" id="query_type_id"  data-rule-required="true" > 
                    <option value="">--Select Query Type--</option>
                     @if($arr_query_type)
                         @foreach($arr_query_type as $type)
                            <option value="{{$type['id']}}">{{$type['query_type']}}</option>
                         @endforeach
                     @endif
                </select>
                <span class="help-block" id="err_query_type">{{ $errors->first('query_type_id') }}</span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Subject<i class="red">*</i></label>
             <div class="col-sm-9 col-lg-6 controls">
                  <input type="text"  placeholder="Subject"  id="subject" name="subject" class="form-control" data-rule-required="true"/>
                   <span class="help-block" id="err_subject">{{ $errors->first('subject') }}</span>
             </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Explanation<i class="red">*</i></label>
             <div class="col-sm-9 col-lg-6 controls">
                  <textarea name="query_description" id="query_description" rows="4"  class="form-control" data-rule-required="true"  data-rule-maxlength="300" data-msg-maxlength="Explaination should not be more than 300 characters" placeholder="Explanation"></textarea>
                   <span class="help-block" id="err_query_description">{{ $errors->first('query_description') }}</span>  
             </div>
          </div> 

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Attach File</label>
              <div class="col-sm-9 col-lg-6 controls">
                <div class="attach-your-file-block">
                    <div class="image-upload-wrap for-error">
                       <input class="file-upload-input" type='file' onchange="readURL(this);" name="attachment_file_name" id="attachment_file_name" data-rule-required="true" />
                      <div class="drag-text">
                          <div class="icon-uploads-generates"><i class="fa fa-cloud-upload"></i></div>
                              <h3>Please Attach your file here</h3>
                          </div>
                      </div>
                      <div style="color:red; margin-bottom: 15PX">
                          <i class="red">NOTE! Allowed only jpg | jpeg | png | pdf | txt | doc | docx </i>
                      </div>
                       <div class="file-upload-content">
                       <!-- <div class="image-title-wrap"> -->
                         <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                      <!--  </div> -->
                </div>
                <span class="help-block error" id="err_attach_file">{{ $errors->first('attachment_file_name') }}</span>
              </div>
            </div>
          </div>

          <br/>
          <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
              <button  type="submit" name="generate_ticket_btn" id="generate_ticket_btn" class="btn btn btn-primary btn-custom">Send</button>
            </div>
          </div>

        </form>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>  

    <!-- END Tiles -->
<script type="text/javascript">
  
  $("#user_id").on('change', function(){
        
        var user_id         = $(this).val();
       
        var module_url_path = "{{url($module_url_path)}}";
        var temp_url        = module_url_path+'/get_user_type';
        var token           = $('input[name="_token"]').val();   


        if(user_id != '')
        {
          $.ajax({

            url : temp_url,
            type : 'POST',
            data : {_token: token,user_id:user_id},
            success:function(resp)
            {
               if(resp.status == 'success')
               {
                  $('#user_type_div').html(resp.html_data);
               }
               else
               {
                  $('#user_type_div').html('');
               }
              
            }
          });

        }
  })

</script>
<script type="text/javascript">
      function readURL(input) {
      if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function(e) {
        /*  $('.image-upload-wrap').hide();*/

         /* $('.file-upload-image').attr('src', e.target.result);*/
          $('.file-upload-content').show();

          $('.image-title').html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);

      } else {
        removeUpload();
      }
    }

    function removeUpload() {
      $('.file-upload-input').replaceWith($('.file-upload-input').clone());
      $('.file-upload-content').hide();
      $('.image-upload-wrap').show();
      $('#attachment_file_name').val('');
    }
    $('.image-upload-wrap').bind('dragover', function () {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function () {
            $('.image-upload-wrap').removeClass('image-dropping');
    });
</script>
<!--   Upload End -->
<script type="text/javascript">
  $(document).on("change",".file-upload-input", function()
    {     
        var file=this.files;
        validateQryImage(this.files,250,250);
    });


   $(document).ready(function()
  {
      jQuery('#site-setting-form').validate(
      {
           ignore: [],
           errorElement: 'div',
           highlight: function(element) { },
           errorPlacement: function(error, element) 
           { 
              error.insertAfter(element);
              // var name = $(element).attr("name");
              // if(name === "query_type_id") 
              // {
              //   error.insertAfter('#err_query_type');
              // }
              // else 
              // {
              //   error.insertAfter(element);
              // }

              error.appendTo(element.parent());
           }
      }); 
    });
  $("[type=file]").change(function(){
    if($(this).val()!="")
    {
       $("[for=attachment_file_name]").remove();
    }
  });
</script>

 @stop