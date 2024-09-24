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
         <i class="fa fa-home">
         </i>
         <a href="{{ url($support_panel_slug.'/dashboard') }}">Dashboard
         </a>
      </li>
      <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa fa-user">
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
            <i class="fa fa-user">
            </i>{{ isset($page_title)?$page_title:"" }} 
         </h3>
         <div class="box-tool">
         </div>
      </div>
      <div class="box-content">
         @include('support.layout._operation_status')

         <?php 
            if(isset($arr_data['support_level']) && !empty($arr_data['support_level']))
            {
               if($arr_data['support_level'] == 'L1')
               {
                  $support_level = "Highest Level (L1)";
               }
               elseif($arr_data['support_level'] == 'L2')
               {
                  $support_level = "Middle Level (L2)";
               }
               elseif($arr_data['support_level'] == 'L3')
               {
                  $support_level = "Lowest Level (L3)";
               }
            }
         ?>
         <form name="validation-form" id="account-setting-form" method="POST" class="form-horizontal" action="{{url($module_url_path.'/update/'.base64_encode($arr_data['id']))}}" enctype="multipart/form-data">
         
         {{ csrf_field() }}

         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Profile Image</label>
            <div class="col-sm-9 col-lg-10 controls">
               <div class="fileupload fileupload-new" data-provides="fileupload">
                  <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                     @if(isset($arr_data['profile_image']) && !empty($arr_data['profile_image']) && $arr_data['profile_image']!=null && file_exists($profile_image_base_path.$arr_data['profile_image'] ))                    
                     <img src="{{$profile_image_public_path.$arr_data['profile_image'] }}">
                     @else
                     <img src="{{url('/uploads').'/default-profile.png' }}">                    
                     @endif
                  </div>
                  <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                  <div>
                     <span class="btn btn-default btn-file" style="height:32px;">
                     <span class="fileupload-new">Select Image</span>
                     <span class="fileupload-exists">Change</span>
                     <input type="file"  data-validation-allowing="jpg, png,jpeg" class="file-input news-image validate-image" name="image" id="image"  /><br>
                     <input type="hidden" class="file-input " name="oldimage" id="oldimage"  
                        value="{{ $arr_data['profile_image'] }}"/>
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
            <div class="clearfix"></div>
         </div>

         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Support Level<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="support_level" id="support_level" readonly="" class="form-control"  value="{{$support_level or ''}}">
               <span class='help-block'>{{ $errors->first('support_level') }}
               </span>
            </div>
         </div> 
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">User Name<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="user_name" id="user_name" class="form-control" data-rule-required="true" data-rule-maxlength="255" placeholder="User Name" value="{{$arr_data['user_name'] or ''}}">
               <span class='help-block'>{{ $errors->first('user_name') }}
               </span>
            </div>
         </div> 
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">First Name<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="first_name" id="first_name" onkeyup="chk_validation(this)" class="form-control" data-rule-required="true" data-rule-lettersonly="true" data-rule-maxlength="255" placeholder="First Name" value="{{$arr_data['first_name'] or ''}}">
               <span class='help-block'>{{ $errors->first('first_name') }}
               </span>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Last Name
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="last_name" id="last_name" onkeyup="chk_validation(this)" class="form-control" data-rule-required="true" data-rule-lettersonly="true" data-rule-maxlength="255" placeholder="Last Name" value="{{$arr_data['last_name'] or ''}}">
               <span class='help-block'>{{ $errors->first('last_name') }}
               </span>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Email
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="email" id="email" readonly="" class="form-control" data-rule-required="true" data-rule-email="true" data-rule-maxlength="255" placeholder="Email" value="{{$arr_data['email'] or ''}}">
               <span class='help-block'>{{ $errors->first('email') }}</span>
               <div class="clearfix"></div>
               {{-- <span class="label label-important">NOTE!</span>
               <i class="red"> If you change this email then also changed support login email</i> --}}
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Contact Number
            <i class="red">*
            </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="contact" id="contact" class="form-control" data-rule-required="true" data-rule-pattern="[- +()0-9]+" data-rule-minlength="7" data-rule-maxlength="16" placeholder="Contact Number" data-msg-minlength="Contact no should be atleast 7 numbers" data-msg-maxlength="Contact no should not be more than 16 numbers"  value="{{$arr_data['contact'] or ''}}">
               <span class='help-block'>{{ $errors->first('contact') }}
               </span>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Gender<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
               <label class="radio-inline">
                <input name="gender" value="1" type="radio" @if(isset($arr_data['gender']) && $arr_data['gender']==1) checked="" @endif> Female
              </label>
              <label class="radio-inline">
                <input name="gender" value="0" type="radio" @if(isset($arr_data['gender']) && $arr_data['gender']==0) checked="" @endif> Male
              </label>
               <span class='help-block' id="err_gender">{{ $errors->first('gender_status') }}
               </span>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Address<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="address" id="autocomplete" class="form-control" data-rule-required="true" placeholder="Address" value="{{$arr_data['address'] or ''}}">
               <span class='help-block'>{{ $errors->first('address') }}
               </span>
            </div>
         </div> 
         <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">City<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
               <input type="text" name="city" id="locality" class="form-control" data-rule-required="true"   placeholder="City" data-rule-lettersonly="true"  value="{{$arr_data['city'] or ''}}">
               <span class='help-block'>{{ $errors->first('city') }}
               </span>
            </div>
         </div>
         
         <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
               <button class="btn btn btn-primary btn-custom" type="submit">Update</button>
               <a href="{{url($support_panel_slug)}}" class="btn btn-cancel">Cancel</a>
            </div>
         </div>
         </form>
      </div>
   </div>
</div>

<script type="text/javascript">

   $(document).on("change",".validate-image", function()
    {            
        var file =this.files;
        validateImage(this.files, 250,250);
    });

     $(document).ready(function() 
       { 
         jQuery('#account-setting-form').validate({
               ignore: [],
               errorPlacement: function(error, element) 
               {
                 // if(element.attr("name") == "image")
                 // {
                 //   error.appendTo("#file-upload-error");
                 // }
                 // else
                 // {
                 //   error.insertAfter(element);
                 // }
                 // error.insertAfter(element);
                  var name = $(element).attr("name");
                  if(name === "gender") 
                  {
                    error.insertAfter('#err_gender');
                  }
                  else 
                  {
                    error.insertAfter(element);
                  }
              }
         });
       });

    function chk_validation(ref)
    {
        var yourInput = $(ref).val();
        re = /[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if(isSplChar)
        {
          var no_spl_char = yourInput.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
          $(ref).val(no_spl_char);
        }
    }
</script>
<!-- END Main Content --> 

<script type="text/javascript">
    var glob_autocomplete;
    var glob_component_form =
    {
      street_number: 'short_name',
      route: 'long_name',
      locality: 'long_name',
      sublocality: 'long_name',
      postal_code: 'short_name',
      country: 'long_name',
      administrative_area_level_1: 'long_name'
    };
    var glob_marker = false;
    var glob_map = false;
    var glob_options = {
    };
    glob_options.types = [];
    function changeCountryRestriction(ref)
    {
      var country_code = $(ref).val();
      destroyPlaceChangeListener(autocomplete);
      initAutocomplete();
      glob_autocomplete = false;
      glob_autocomplete = initGoogleAutoComponent($('#autocomplete')[0],glob_options,glob_autocomplete);
    }
    function initAutocomplete()
    {
      glob_autocomplete = false;
      glob_autocomplete = initGoogleAutoComponent($('#autocomplete')[0],glob_options,glob_autocomplete);
    }
    function initGoogleAutoComponent(elem,options,autocomplete_ref)
    {
      autocomplete_ref = new google.maps.places.Autocomplete(elem,options);
      autocomplete_ref = createPlaceChangeListener(autocomplete_ref,fillInAddress);
      return autocomplete_ref;
    }
    function createPlaceChangeListener(autocomplete_ref,fillInAddress)
    {
      autocomplete_ref.addListener('place_changed', fillInAddress);
      return autocomplete_ref;
    }
    function destroyPlaceChangeListener(autocomplete_ref)
    {
      google.maps.event.clearInstanceListeners(autocomplete_ref);
    }
    function fillInAddress()
    {
      // Get the place details from the autocomplete object.
      var place = glob_autocomplete.getPlace();
      for (var component in glob_component_form)
      {
        $("#"+component).val("");
        $("#"+component).attr('disabled',false);
      }
      if(place.address_components.length > 0 )
      {
        $.each(place.address_components,function(index,elem)
        {
          var addressType = elem.types[0];
          if(glob_component_form[addressType])
          {
            var val = elem[glob_component_form[addressType]];
            $("#"+addressType).val(val) ;
          }
        });
      }
    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&libraries=places&callback=initAutocomplete"
async defer>
</script>
@endsection

