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
      <i class="fa fa-home">
      </i>
      <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard
      </a>
    </li>
    <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="fa {{$module_icon}}">
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
          <i class="fa {{$module_icon}}">
          </i>{{ isset($page_title) ? $page_title:"" }} 
        </h3>
        <div class="box-tool">
        </div>
      </div>
      <div class="box-content">
        @include('admin.layout._operation_status')

        <form name="validation-form" id="site-setting-form" method="POST" class="form-horizontal" action="{{url($module_url_path.'/site_settings/update_site_setting')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <!---
          <div class="form-group">
              <label class="col-sm-3 col-lg-2 control-label">Site Logo<i class="red">*</i></label>
              <div class="col-sm-9 col-lg-4 controls">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new img-thumbnail" style="width: 200px; height: 150px;">
                       @if(isset($arr_site_settings['site_logo']) && !empty($arr_site_settings['site_logo']) && $arr_site_settings['site_logo']!=null && file_exists($logo_public_path.$arr_site_settings['site_logo'] ))                    
                       <img src="{{$logo_path.$arr_site_settings['site_logo'] }}">
                       @else
                       <img src="{{$logo_path.'default.png'}}">
                       @endif
                    </div>
                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                    <div>
                      <span class="btn btn-default btn-file" style="height:32px;">
                        <span class="fileupload-new">Change</span>
                        <span class="fileupload-exists">Change</span>
                        <input type="file"  data-validation-allowing="jpg, png, gif" class="file-input news-image validate-image" name="logo" id="image"  /><br>
                        <input type="hidden" class="file-input " name="old_logo" id="oldimage"  
                        value="{{$arr_site_settings['site_logo']}}"/>
                      </span>
                      <a href="#" id="remove" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                    </div>
                    <i class="red"> 
                        <span class="label label-important">NOTE!</span>
                        <i class="red"> Allowed only jpg | jpeg | gif | png <br/> 
                        Please upload image with Height and Width greater than or equal to 194 X 61 for best result. </i>
                        <input type="hidden" id="invalid_size" value="">
                        <input type="hidden" id="invalid_ext" value="">
                        <span for="cat_img" id="err_cat_image" class='help-block'>{{ $errors->first('image') }}</span> 
                    </i>
                  <div id="file-upload-error" class="error"></div>
                  <span for="image" id="err-image" class="help-block">{{ $errors->first('image') }}</span>
                </div>
                <span class='help-block'>{{ $errors->first('site_name') }}
                </span>
              </div>
          </div>
---->

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Site Name<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="site_name" id="site_name" class="form-control" data-rule-required="true" data-rule-maxlength="255" placeholder="Site Name" value="{{$arr_site_settings['site_name'] or ''}}">
              <span class='help-block'>{{ $errors->first('site_name') }}
              </span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Site Status<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-10 controls">
              <label class="radio-inline">
                <input name="site_status" value="1" type="radio" @if(isset($arr_site_settings['site_status']) && $arr_site_settings['site_status']==1) checked="" @endif> Online
              </label>
              <label class="radio-inline">
                <input name="site_status" value="0" type="radio" @if(isset($arr_site_settings['site_status']) && $arr_site_settings['site_status']==0) checked="" @endif> Offline
              </label>
            </div>
          </div>
          <br/>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Address<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="address" id="autocomplete" class="form-control" data-rule-required="true" data-rule-maxlength="255" placeholder="Address" value="{{$arr_site_settings['site_address'] or ''}}">
              <span class='help-block'>{{ $errors->first('address') }}
              </span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Email
              <i class="red">*
              </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="email_address" id="email_address" class="form-control" data-rule-required="true" data-rule-email="true" data-rule-maxlength="255" placeholder="Email" value="{{$arr_site_settings['site_email_address'] or ''}}">
              <span class='help-block'>{{ $errors->first('email_address') }}</span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Contact Number
              <i class="red">*
              </i>
            </label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="contact_number" id="contact_number" class="form-control" data-rule-required="true" data-rule-pattern="[- +()0-9]+" data-rule-minlength="7" data-rule-maxlength="16" placeholder="Contact Number" data-msg-minlength="Contact no should be atleast 7 numbers" data-msg-maxlength="Contact no should not be more than 16 numbers"  value="{{$arr_site_settings['site_contact_number'] or ''}}">
              <span class='help-block'>{{ $errors->first('contact_number') }}
              </span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Meta Keyword<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="meta_keyword" id="meta_keyword" class="form-control" data-rule-required="true" data-rule-maxlength="255" placeholder="Meta Keyword" value="{{$arr_site_settings['meta_keyword'] or ''}}">
              <span class='help-block'>{{ $errors->first('meta_keyword') }}
              </span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Meta Description<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <textarea name="meta_desc" id="meta_desc" class="form-control" data-rule-required="true" placeholder="Meta Description">{{$arr_site_settings['meta_desc'] or ''}}</textarea>
              <span class='help-block'>{{ $errors->first('meta_desc') }}</span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Facebook URL<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="facebook_url" id="facebook_url" class="form-control" data-rule-required="true" data-rule-pattern="(\b((?:https?|ftp):\/\/|www\.)([0-9A-Za-z]+\.?)+\b)" data-rule-maxlength="500" placeholder="Facebook URL" value="{{$arr_site_settings['fb_url'] or ''}}">
              <span class='help-block'>{{ $errors->first('facebook_url') }}
              </span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Twitter URL<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="twitter_url" id="twitter_url" class="form-control" data-rule-required="true" data-rule-pattern="(\b((?:https?|ftp):\/\/|www\.)([0-9A-Za-z]+\.?)+\b)" data-rule-maxlength="500" placeholder="Twitter URL" value="{{$arr_site_settings['twitter_url'] or ''}}">
              <span class='help-block'>{{ $errors->first('twitter_url') }}
              </span>
            </div>
          </div>

           <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Linked In URL<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="linkedin_url" id="linkedin_url" class="form-control" data-rule-required="true" data-rule-pattern="(\b((?:https?|ftp):\/\/|www\.)([0-9A-Za-z]+\.?)+\b)" data-rule-maxlength="500" placeholder="Linkdin URL" value="{{$arr_site_settings['linkedin_url'] or ''}}">
              <span class='help-block'>{{ $errors->first('linkedin_url') }}
              </span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Instagram URL<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="instagram_url" id="instagram_url" class="form-control" data-rule-required="true" data-rule-pattern="(\b((?:https?|ftp):\/\/|www\.)([0-9A-Za-z]+\.?)+\b)" data-rule-maxlength="500" placeholder="Instagram URL" value="{{$arr_site_settings['instagram_url'] or ''}}">
              <span class='help-block'>{{ $errors->first('instagram_url') }}
              </span>
            </div>
          </div>
          
          <input type="hidden" name="lat" id="lat">
          <input type="hidden" name="lon" id="lon">

          <!---
          
          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Google+ URL<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="google_plus_url" id="google_plus_url" class="form-control" data-rule-required="true" data-rule-url="true" data-rule-maxlength="500" placeholder="Google+ URL" value="{{$arr_site_settings['google_plus_url'] or ''}}">
              <span class='help-block'>{{ $errors->first('google_plus_url') }}
              </span>
            </div>
          </div> 


          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Play Store URL</label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="play_store_url" id="play_store_url" class="form-control" data-rule-maxlength="500" placeholder="Play Store URL" value="{{$arr_site_settings['play_store_url'] or ''}}">
              <span class='help-block'>{{ $errors->first('play_store_url') }}
              </span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">App Store URL<i class="red">*</i></label>
            <div class="col-sm-9 col-lg-4 controls">
              <input type="text" name="app_store_url" id="app_store_url" class="form-control" data-rule-required="true" data-rule-url="true" data-rule-maxlength="500" placeholder="App Store URL" value="{{$arr_site_settings['app_store_url'] or ''}}">
              <span class='help-block'>{{ $errors->first('app_store_url') }}
              </span>
            </div>
          </div>

         
        ---->
          <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
              <button class="btn btn btn-primary btn-custom">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script type="text/javascript">
    $(document).ready(function() 
    {
      $(document).on("change",".validate-image", function()
      {            
        var file=this.files;
        validateImage(this.files, 61,194);
      }); 
      jQuery('#site-setting-form').validate({
        ignore: [],
        errorPlacement: function(error, element) 
        {  error.insertAfter(element);
        } 
      });
    });
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
for (var component in glob_component_form){
  $("#"+component).val("");
  $("#"+component).attr('disabled',false);
}
if(place.address_components.length > 0 ){
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
$('#lat').val(place.geometry.location.lat());
$('#lon').val(place.geometry.location.lng());
}


</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&libraries=places&callback=initAutocomplete"
async defer>
</script>
<script type="text/javascript">
  $('#site-setting-form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
      e.preventDefault();
      return false;
    }
  });
</script>

@endsection


