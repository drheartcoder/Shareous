@extends('front.layout.master')                
@section('main_content')

    <!--Header section end here-->
    <div class="clearfix"></div>
     <div class="overflow-hidden-section">
    <div class="titile-user-breadcrum">
        <div class="container">
            <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                <h1>Generate Ticket</h1>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="change-pass-bg">
        <div class="container">
            <div class="row">
                <form name="TicketForm" id="TicketForm" method="post" enctype="multipart/form-data"  action="{{url('/ticket/ticket_store/')}}" >
                {{csrf_field()}}
                  <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                   <div id="left-bar">
                      @include('front.layout.left_bar_host')
                   </div>
                  </div>
                  <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                      <div class="generat-ticket-main">
                          <div class="change-pass-bady">
                          @include('front.layout._flash_errors') 
                              <div class="form-group">
                                  <div class="select-style">
                                      <select id="query_type_id" name="query_type_id" data-rule-required="true">
                                          <option value="">--Select Query Type--</option>
                                          @if($arr_query_type)
                                          @foreach($arr_query_type as $type)
                                          <option value="{{$type['id']}}">{{$type['query_type']}}</option>
                                          @endforeach
                                          @endif
                                      </select>
                                  <span class="error" id="err_query_type" style="color:red">{{$errors->first('query_type_id')}}</span>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <input type="text" id="query_subject" name="query_subject" value="{{old('query_subject')}}"  data-rule-required="true" />
                                  <label for="query_subject">Subject</label>
                                  <span class="error" id="query_subject" style="color:red">{{$errors->first('query_subject')}}</span>
                                  
                              </div>
                             <div class="form-group">
                                  <textarea rows="2" class="text-area" id="query_description" name="query_description" data-rule-required="true" data-rule-maxlength="300" data-msg-maxlength="Explaination should not be more than 300 characters" >{{old('query_description')}}</textarea>
                                  <label for="query_description">Explaination</label>
                                  <span class="error" id="query_description" style="color:red">{{$errors->first('query_description')}}</span>
                              </div>
                                                                                              </div>
                              <br>
                              <div class="attach-your-file-block">
                                <div class="attach-ile-head">Attach File</div><br>
                                 <div style="color:red;margin: -29px 0 15px;">
                                         <i class="red">NOTE! Allowed only jpg | jpeg | png | pdf | txt | doc | docx | zip
                                         </i>
                                  </div>
                                  <div class="image-upload-wrap">
                                    <input class="file-upload-input" type='file' onchange="readURL(this);" name="attachment_file_name" id="attachment_file_name"  />
                                    <div class="drag-text">
                                     <div class="icon-uploads-generates"><i class="fa fa-cloud-upload"></i></div>
                                      <h3>Please Attach your file here</h3>
                                    </div>
                                  </div>
                                  <div class="file-upload-content">
                                    <!--<img class="file-upload-image" src="#" alt="your image" />-->
                                    <div class="image-title-wrap">
                                      <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                                    </div>
                                  </div>
                            </div>

                              <div class="change-pass-btn">
                                  <a class="login-btn cancel" href="{{$module_url_path}}">Cancel</a>
                                  {{-- <a class="login-btn" href="javascript:void(0)">Send</a> --}}
                                  <button class="login-btn" type="submit" name="btn_submit" id="btn_submit">Send</button>
                              </div>

                              <div class="clearfix"></div>
                          </div>
                      </div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    </div>
   
    <input type="hidden" id="image_size_limit" value="{{ config('app.project.img_upload_size') }}">
  
     <script type="text/javascript">
         /*scrollbar start*/
         (function($){
           
         $(window).on("load",function(){
         
         $.mCustomScrollbar.defaults.scrollButtons.enable=true; //enable scrolling buttons by default
         $.mCustomScrollbar.defaults.axis="yx"; //enable 2 axis scrollbars by default
         
               $(".content-d").mCustomScrollbar({theme:"dark"});
         });
         })(jQuery);
      </script>

<!--   Upload Start -->
  <script type="text/javascript">
    var image_size_limit_kb = $("#image_size_limit").val();
    var image_size_limit_mb = image_size_limit_kb * 1000000;

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        var size   = input.files[0].size;
        var type   = input.files[0].type;
        var split_type = type.split('/');

        
        var blnValid = false;
        var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1);
        if(ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG" || ext == "png" || ext == "PNG" || ext == "pdf" || ext == "txt" || ext == "doc" || ext == "docx" || ext == "zip")
        {
                    blnValid = true;
        }

        if(blnValid ==false) 
        { 
            swal("Sorry, " + input.files[0]['name'] + " is invalid, allowed extensions are: jpeg , jpg , png , pdf , txt , doc , docx , zip","error");
            return false;
        } else if(size > image_size_limit_mb) {   
            swal("Invalid size",'Max size allowed is '+ image_size_limit_kb +'mb.','error');
            return false;
        }else{
          reader.onload = function(e) {
            
            var image = new Image();
            image.src = e.target.result;
            
            if(split_type[0] == 'image'){
              image.onload = function () 
              {
                    var height = this.height;
                    var width  = this.width;
                    if (height < 250 || width < 250 ) 
                    {
                        swal("Height and Width must be greater than or equal to "+250+" X "+250+"." ,"error");
                        return false;
                    }
                    else {
                      $('.file-upload-content').show();
                      $('.image-title').html(input.files[0].name);
                    }
              };
            } 
            else{
              $('.file-upload-content').show();
              $('.image-title').html(input.files[0].name);
            }
          };
          reader.readAsDataURL(input.files[0]);
        }
      } else {
        //removeUpload();
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
  var image_size_limit_kb = $("#image_size_limit").val();
  var image_size_limit_mb = image_size_limit_kb * 1000000;
  /*$(document).on("change",".file-upload-input", function()
  {    
      var file = this.files;
      validateQryImage(this.files,250,250);
      var size = this.files[0].size;
      if(size > image_size_limit_mb)
      {
          swal("Invalid size",'Max size allowed is '+ image_size_limit_kb +'mb.','error');
          return false;
      }
  });*/

  $(document).ready(function()
  {
      jQuery('#TicketForm').validate(
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

@endsection