@extends('admin.layout.master')
@section('main_content')

<!-- BEGIN Page Title -->
<div class="page-title">
  <div>
    <h1>
      <i class="fa {{$module_icon_page}}"></i>
      {{ isset($page_title)?$page_title:"" }}
    </h1>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/admin/css/app.css">
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
      <a href="{{ $module_url_path}}">{{ $module_title or ''}}</a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$module_icon_page}}"></i>
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
          <i class="fa {{$module_icon_page}}"></i>
          {{ isset($page_title)?$page_title:"" }}
        </h3>
      </div>
      <div class="box-content">
        @include('admin.layout._operation_status')

        @if(isset($arr_bank_details) && sizeof($arr_bank_details)>0)
        <div class="row">
          @foreach($arr_bank_details as $bank_details)
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading font-bold">Bank Name: {{isset($bank_details['bank_name']) && $bank_details['bank_name']!=""?$bank_details['bank_name']:'NA'}}  @if($bank_details['selected']==1) <span class="badge badge-large badge-success bank-details">Selected</span> @endif  </div>
              <div class="panel-body">                                 
                 <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Bank Account No:</label>
                    <div class="col-lg-8">
                      {{isset($bank_details['account_number']) && $bank_details['account_number']!=""?$bank_details['account_number']:'NA'}}
                    </div>
                 </div>

                 <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Bank IFSC No.:</label>
                    <div class="col-lg-8">
                      {{isset($bank_details['ifsc_code']) && $bank_details['ifsc_code']!=""?$bank_details['ifsc_code']:'NA'}}
                    </div>
                 </div>

                 <div class="form-group col-sm-12">
                    <label class="col-lg-4 control-label">Account Type:</label>
                    <div class="col-lg-8">
                      @if($bank_details['account_type'] == 1) Saving Account @elseif($bank_details['account_type'] == 2) Current Account @elseif($bank_details['account_type'] == 3) Recurring Account @elseif($bank_details['account_type'] == 4) Demat Account @else NRI Account @endif
                    </div>
                 </div>
              </div>
           </div>
          </div>  
           @endforeach                                    
          

        <div class="form-group">
          <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
            <button type="button" onclick="location.href='{{ $module_url_path }}'" class="btn">Back</button>
          </div>
        </div>    
        <div class="clearfix"></div>                
        </div>
        @endif
      </div>

    </div>
  </div>


  @stop
