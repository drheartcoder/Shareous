@extends('web_admin.template.admin')                

@section('main_content')
<div class="row">
    <div class="col-md-6">
        <div class="box {{ $theme_color }}">
            <div class="box-title">
                <h3><i class="fa fa-wrench"></i> Site Settings</h3>
                <div class="box-tool">
                    <!-- <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
                    <a href="#" data-action="close"><i class="fa fa-times"></i></a> -->
                </div>
            </div>

            <div class="box-content">
                
                 @if(Session::has('success_settings'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get('success_settings') }}
                    </div>
                  @endif  

                  @if(Session::has('error_settings'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get('error_settings') }}
                    </div>
                  @endif

                <form class="form-horizontal" 
                         id="validation-form" 
                     method="POST" 
                     action="{{ url('/web_admin/site_settings/update')}}">

                     {{csrf_field()}}

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="site_name">Site name<i class="red">*</i></label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" 
                                    name="site_name" 
                      data-rule-required="true"  
                                   value="{{ isset($arr_site_settings['site_name'])?$arr_site_settings['site_name']:old('site_name') }}" />
                            <span class='help-block'>{{ $errors->first('site_name') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="site_email_address">Email<i class="red">*</i></label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" name="site_email_address" data-rule-required="true" 
                                   value="{{ isset($arr_site_settings['site_email_address'])?$arr_site_settings['site_email_address']:old('site_email_address') }}"   />
                            <span class='help-block'>{{ $errors->first('site_email_address') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="site_contact_number">Contact No<i class="red">*</i></label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" name="site_contact_number" data-rule-required="true" 
                            value="{{ isset($arr_site_settings['site_contact_number'])?$arr_site_settings['site_contact_number']:old('site_contact_number') }}"  />
                            <span class='help-block'>{{ $errors->first('site_contact_number') }}</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="site_address">Address</label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <textarea class="form-control" name="site_address" >{{ isset($arr_site_settings['site_address'])?$arr_site_settings['site_address'] :old('site_address')}}</textarea>
                            <span class='help-block'>{{ $errors->first('site_address') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="meta_desc">Meta Description</label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <textarea class="form-control" name="meta_desc" >{{ isset($arr_site_settings['meta_desc'])?$arr_site_settings['meta_desc'] :old('meta_desc')}}</textarea>
                            <span class='help-block'>{{ $errors->first('meta_desc') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="meta_keyword">Meta Keywords</label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" name="meta_keyword"  
                                value="{{ isset($arr_site_settings['meta_keyword'])?$arr_site_settings['meta_keyword']:old('meta_keyword') }}"
                            />
                            <span class='help-block'>{{ $errors->first('meta_keyword') }}</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="fb_url">Facebook Url</label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" name="fb_url" 
                            value="{{ isset($arr_site_settings['fb_url'])?$arr_site_settings['fb_url']:old('fb_url') }}"
                              />
                            <span class='help-block'>{{ $errors->first('fb_url') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="twitter_url">Twitter Url</label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" name="twitter_url"  
                                value="{{ isset($arr_site_settings['twitter_url'])?$arr_site_settings['twitter_url']:old('twitter_url') }}"  />
                            <span class='help-block'>{{ $errors->first('twitter_url') }}</span>
                        </div>
                    </div>   

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="youtube_url">Youtube Url</label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" name="youtube_url"  value="{{ isset($arr_site_settings['youtube_url'])?$arr_site_settings['youtube_url']:old('youtube_url') }}"  />
                            <span class='help-block'>{{ $errors->first('youtube_url') }}</span>
                        </div>
                    </div>  

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="rss_feed_url">RSS Feed Url</label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input class="form-control" name="rss_feed_url"  value="{{ isset($arr_site_settings['rss_feed_url'])?$arr_site_settings['rss_feed_url']:old('rss_feed_url') }}"  />
                            <span class='help-block'>{{ $errors->first('rss_feed_url') }}</span>
                        </div>
                    </div>                     

                    <div class="form-group">
                          <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-4">
                            <input type="submit"  class="btn btn-primary" value="Save">

                        </div>
                    </div>                    

                </form>

            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box">
            <div class="box-title">
                <h3><i class="fa fa-key"></i> Change Password</h3>
                <div class="box-tool">
                    <!-- <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
                    <a href="#" data-action="close"><i class="fa fa-times"></i></a> -->
                </div>
            </div>
            <div class="box-content">

                @if(Session::has('success_password'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get('success_password') }}
                    </div>
                  @endif  

                  @if(Session::has('error_password'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get('error_password') }}
                    </div>
                  @endif

                 <form class="form-horizontal" 
                         id="validation-form-change-password" 
                     method="POST" 
                     action="{{ url('/web_admin/password/update')}}">
                    
                    {{ csrf_field() }}    

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="current_password">Current Password<i class="red">*</i></label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input type="password" class="form-control" name="current_password" data-rule-required="true"   />
                            <span class='help-block'>{{ $errors->first('current_password') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="new_password">New Password<i class="red">*</i></label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input type="password" class="form-control" name="new_password" data-rule-required="true" data-rule-min="6" id="new_password"/>
                            <span class='help-block'>{{ $errors->first('new_password') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="new_password_confirmation">Confirm Password<i class="red">*</i></label>
                        <div class="col-sm-6 col-lg-6 controls">
                            <input  type="password" class="form-control" name="new_password_confirmation" data-rule-required="true" data-rule-equalto="#new_password"  />
                            <span class='help-block'>{{ $errors->first('new_password_confirmation') }}</span>
                        </div>
                    </div>
                                        

                    <div class="form-group">
                          <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-4">
                            <input type="submit"  class="btn btn-primary" value="Save">
                        </div>
                    </div>                    

                </form>
            </div>
        </div>
        
        <div class="box">
            <div class="box-title">
                <h3><i class="fa fa-key"></i> Site Status </h3>
                <div class="box-tool">
                    <!-- <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
                    <a href="#" data-action="close"><i class="fa fa-times"></i></a> -->
                </div>
            </div>
            <div class="box-content">
                
                @if(Session::has('success_site_status'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get('success_site_status') }}
                    </div>
                  @endif  

                  @if(Session::has('error_site_status'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get('error_site_status') }}
                    </div>
                  @endif

                <form class="form-horizontal" 
                         id="validation-form-site-status" 
                     method="POST" 
                     action="{{ url('/web_admin/status/update')}}">
                    
                    {{ csrf_field()}}    

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label" for="site_status">Status<i class="red">*</i></label>
                        <div class="col-sm-6 col-lg-6 controls">

                            <select class="form-control" name="site_status" data-rule-required="true" >
                                <option value="1" {{ $arr_site_settings['site_status']=="1"?'selected':''}} >Online</option>
                                <option value="0" {{ $arr_site_settings['site_status']=="0"?'selected':''}} >Offline</option>
                            </select>

                            <span class='help-block'>{{ $errors->first('site_status') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                          <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-4">
                            <input type="submit"  class="btn btn-primary" value="Save">
                        </div>
                    </div>                    

                </form>
            </div>
        </div>
        
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        applyValidationToFrom($("#validation-form-change-password"));
        applyValidationToFrom($("#validation-form-site-status"));
    })
</script>
@endsection