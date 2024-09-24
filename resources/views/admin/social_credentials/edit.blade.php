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
    {{-- <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$module_icon}}"></i>
      <a href="{{ url($module_url_path) }}">{{ $module_title or ''}}</a>
    </span>  --}}
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
            <form name="validation-form" id="validation_form" method="POST" class="form-horizontal" action="{{$module_url_path}}/update/{{base64_encode($record['id'])}}" enctype="multipart/form-data"  files ="true">
              {{csrf_field()}}

              <div class="col-md-12">
                <div class="tabbable">
                  <ul id="myTab1" class="nav nav-tabs">
                    <li class="active"><a href="#facebook" data-toggle="tab"><i class="fa fa-facebook"></i> Facebook</a></li>
                    <li><a href="#google" data-toggle="tab"><i class="fa fa-google-plus"></i> Google+</a></li>
                    <li><a href="#twitter" data-toggle="tab"><i class="fa fa-twitter"></i> Twitter</a></li>                   
                  </ul>

                  <div id="myTabContent1" class="tab-content">

                    <div class="tab-pane fade in active" id="facebook">

                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Facebook Client Id<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <input type="text" name="fb_client_id" id="fb_client_id" class="form-control" data-rule-required="true" placeholder="Facebook Client Id" value="{{$record['fb_client_id']}}">
                          <span class='help-block'>{{ $errors->first('fb_client_id') }}
                          </span>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Facebook Client Secret<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <input type="text" name="fb_client_secret" id="fb_client_secret" class="form-control" data-rule-required="true" placeholder="Facebook Client Secret" value="{{$record['fb_client_secret']}}">
                          <span class='help-block'>{{ $errors->first('fb_client_secret') }}
                          </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Status<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <label class="radio-inline"><input type="radio" value="1" name="fb_status" @if($record['fb_status']=='1') checked="checked" @endif>On</label>
                          <label class="radio-inline"><input type="radio" value="0" name="fb_status" @if($record['fb_status']=='0') checked="checked" @endif>Off</label>
                          <span class='help-block'>{{ $errors->first('fb_status') }}
                          </span>
                        </div>
                      </div>

                    </div>

                    <div class="tab-pane fade" id="google">
                    
                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Google+ Client Id<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <input type="text" name="google_client_id" id="google_client_id" class="form-control" data-rule-required="true" placeholder="Google+ Client Id" value="{{$record['google_client_id']}}">
                          <span class='help-block'>{{ $errors->first('google_client_id') }}
                          </span>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Google+ Client Secret<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <input type="text" name="google_client_secret" id="google_client_secret" class="form-control" data-rule-required="true" placeholder="Google Client Secret" value="{{$record['google_client_secret']}}">
                          <span class='help-block'>{{ $errors->first('google_client_secret') }}
                          </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Google+ API Credential<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <input type="text" name="google_api_credential" id="google_api_credential" class="form-control" data-rule-required="true" placeholder="Google+ Api Credential" value="{{$record['google_api_credential']}}">
                          <span class='help-block'>{{ $errors->first('google_api_credential') }}
                          </span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Status<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <label class="radio-inline"><input type="radio" value="1" name="google_status" @if($record['google_status']=='1') checked="checked" @endif>On</label>
                          <label class="radio-inline"><input type="radio" value="0" name="google_status" @if($record['google_status']=='0') checked="checked" @endif>Off</label>
                          <span class='help-block'>{{ $errors->first('google_status') }}
                          </span>
                        </div>
                      </div>

                    </div>

                     <div class="tab-pane fade" id="twitter">
                    
                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Twitter Client Id<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <input type="text" name="twitter_client_id" id="twitter_client_id" class="form-control" data-rule-required="true" placeholder="Twitter Client Id" value="{{$record['twitter_client_id']}}">
                          <span class='help-block'>{{ $errors->first('twitter_client_id') }}
                          </span>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Twitter Client Secret<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <input type="text" name="twitter_client_secret" id="twitter_client_secret" class="form-control" data-rule-required="true" placeholder="Twitter Client Secret" value="{{$record['twitter_client_secret']}}">
                        <div class="col-sm-9 col-lg-4 controls">
                          <span class='help-block'>{{ $errors->first('twitter_client_secret') }}
                          </span>
                        </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-3 col-lg-2 control-label">Status<i class="red">*</i></label>
                        <div class="col-sm-9 col-lg-4 controls">
                          <label class="radio-inline"><input type="radio" value="1" name="twitter_status" @if($record['twitter_status']=='1') checked="checked" @endif>On</label>
                          <label class="radio-inline"><input type="radio" value="0" name="twitter_status" @if($record['twitter_status']=='0') checked="checked" @endif>Off</label>
                          <span class='help-block'>{{ $errors->first('twitter_status') }}
                          </span>
                        </div>
                      </div>
                      
                    </div>
                                      
                    <div class="form-group">
                      <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                        <button class="btn btn-primary btn-custom">Update</button>
                        <a href="{{url($admin_panel_slug)}}" class="btn btn-cancel">Cancel</a>
                      </div>
                    </div>
                    </div>  
                  </div>
                </div>
              </form>      
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <script>
      $(document).ready(function() 
      { 
        jQuery('#validation_form').validate({
          
        });
      });
    </script>
    @stop
