    @extends('admin.layout.master')
    <style>
        .control-label{font-weight: 600;}
</style>
    @section('main_content')
    <!-- BEGIN Content
    -->
    <div id="main-content">
       <!-- BEGIN Page Title -->
       <div class="page-title">
        <div>
            <h1><i class="fa {{$page_icon}}"></i> {{$page_title or ''}}</h1>
            <!-- <h4>Overview, stats, chat and more</h4> -->
        </div>
    </div>
    <!-- END Page Title -->

    <!-- BEGIN Breadcrumb -->
    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li> <i class="fa fa-home"></i> 
                <a href="{{url($admin_panel_slug.'/dashboard')}}">Dashboard</a> 
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>

            <span class="divider">
              
              <i class="fa {{$module_icon}}"></i>
              <a href="{{ url($module_url_path) }}">{{ $module_title or ''}}</a>
              <span class="divider"><i class="fa fa-angle-right"></i></span>
            </span>

            <li class="active"> <i class="fa {{$page_icon}}"></i>  {{$page_title or ''}}</li>           
        </ul>
    </div>
    <!-- END Breadcrumb-->
    <!-- BEGIN Tiles --> 
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissable">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{Session::get('success')}}
  </div>
  @endif
  @if(Session::has('error'))
  <div class="alert alert-danger alert-dismissable">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{Session::get('error')}}
  </div>
  @endif
    <div class="row"> <div class="col-md-12"> 
        <div class="box box-black">

        <div class="box-title">
            <h3><i class="fa {{$page_icon}}"></i>{{$page_title or ''}}</h3>
        </div>
         <div class="box-content">
            <form action="{{url('/')}}/admin/update-front-page" class
            ="form-horizontal" method="POST" enctype="multipart/form-data"
            data-parsley-validate>
           <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label" for="email_id">Name</label>
                <div class="col-sm-6 col-lg-4 controls" style="margin-top: 7px">
                    {{ isset($contact_enquiry['name']) && $contact_enquiry['name'] !="" ? 
                   ucfirst($contact_enquiry['name']):'' }} 
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label" for="email_id">Email</label>
                <div class="col-sm-6 col-lg-4 controls" style="margin-top: 7px">
                   {{ isset($contact_enquiry['email_id']) && $contact_enquiry['email_id'] !="" ? 
                   $contact_enquiry['email_id']:'NA' }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label" for="email_id">Contact no</label>
                <div class="col-sm-6 col-lg-4 controls" style="margin-top: 7px">
                    {{ isset($contact_enquiry['contact']) && $contact_enquiry['contact'] !="" ? 
                   $contact_enquiry['contact']:'NA' }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label" for="email_id">Message</label>
                <div class="col-sm-6 col-lg-4 controls" style="margin-top: 7px">
                    {{ isset($contact_enquiry['message']) && $contact_enquiry['message'] !="" ? 
                   $contact_enquiry['message']:'NA' }}
                </div>
            </div>
           
            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                  <a href="{{ url($module_url_path) }}" class="btn btn-primary btn-custom"> Back
                </a>
              </div>
            </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    <!-- END Tiles -->
    @stop
