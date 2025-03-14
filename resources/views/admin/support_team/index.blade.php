@extends('admin.layout.master')
@section('main_content')
<!-- BEGIN Content -->
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
        <a href="{{url($admin_panel_slug.'/dashboard')}}">Dashboard</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
      </li>
      <li class="active"> <i class="fa {{$module_icon}}"></i>  {{$page_title or ''}}</li>
    </ul>
  </div>
  <!-- END Breadcrumb -->
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
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-title">
          <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>         
        </div>
        <div class="box-content">
          <div class="btn-toolbar pull-left">
            <div class="form-group">
              <div class="col-sm-12 col-lg-12 controls">
              </div>
            </div>
          </div>
          <div class="btn-toolbar pull-right">
            <div class="btn-group">
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Add New" href="{{url('/')}}/{{$admin_panel_slug}}/support_team/create"><i class="fa fa-plus"></i></a>
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" href="{{url('/')}}/{{$admin_panel_slug}}/support_team"><i class="fa fa-refresh"></i></a>
                <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Block multiple records" href="javascript:void(0)" onclick="javascript : return check_multi_action('frm_manage','deactivate');"><i class="fa fa-lock"></i></a>
                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Unblock multiple records" href="javascript:void(0)" onclick="javascript : return check_multi_action('frm_manage','activate');"><i class="fa fa-unlock"></i></a>
                <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Delete multiple records" href="javascript:void(0)" onclick="javascript : return check_multi_action('frm_manage','delete');"><i class="fa fa-trash"></i></a>
            </div>
          </div>
          <br/><br/>

          <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
           {{ csrf_field() }}

           <input type="hidden" name="multi_action" value="" />
           <div class="table-responsive" style="border:0">
            <table class="table table-advance" id="support_table">
              <thead>                       
                <tr>
                  <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" value="delete" /></th>
                  <th>Name </th>
                  <th>Email ID</th>
                  <th>Support Level</th>     
                  <th>Added On</th>     
                  <th>Status</th>                    
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($objects)
                @foreach($objects as $key => $object)

                <tr>
                  <td><input type="checkbox" name="checked_record[]" value="{{ base64_encode($object['id']) }}" /></td>
                  <td>{{isset($object['first_name'])? ucfirst($object['first_name']):''}} {{isset($object['last_name'])? ucfirst($object['last_name']):''}}</td>
                  <td>{{isset($object['email'])? $object['email']:''}}</td>

                  {{-- <td>{{isset($object['support_level'])? $object['support_level']:''}}</td> --}}
                  <td>@if($object['support_level'] =='L1')
                    Highest Level
                    @elseif($object['support_level'] =='L2')
                    Middle Level
                    @else
                    Lowest Level
                    @endif
                  </td>
                  <td>{{isset($object['created_at'])? get_added_on_date($object['created_at']):''}}</td>
                  {{-- <td>{{isset($object['created_at'])? $object['created_at']:''}}</td> --}}

                  <td>
                    @if($object['status']!='0')
                      <a href="{{$module_url_path.'/block/'.base64_encode($object['id'])}}" class="btn btn-sm btn-success">Unblock</a>
                    @else 
                      <a href="{{$module_url_path.'/unblock/'.base64_encode($object['id'])}}" class="btn btn-sm btn-danger">Block</a>
                    @endif
                  </td>
                  <td>
                    <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Edit" href="{{ url($module_url_path.'/edit/'.base64_encode($object['id'])) }}" ><i class="fa fa-pencil-square-o"></i></a>
                    <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Delete" href="javascript:void(0)" onclick='delete_record("{{base64_encode($object['id'])}}")'><i class="fa fa-trash"></i></a>     

                  </td>                 
                </tr>  
                @endforeach
                @else
                <tr> <td colspan="6">No records found.... </td></tr>
                @endif        
              </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div>  
  <!-- END Tiles -->

  <script type="text/javascript">
    function submit_form(){
      jQuery('#frm_page').submit();
    }

    function delete_record(id)
    {
      swal({
        title: "Are you sure",
        text: "Do you want to delete records?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      },
      function(){
        location.href="{{url($module_url_path)}}/delete/"+id;
      });
    }

    $(document).ready(function(){
      var oTable = $('#support_table').dataTable({
        "aoColumnDefs": [
        { 
          "bSortable": false, 
          "aTargets": [0,4,5,6] ,
        }, 
        { "searchable": false, "targets": [0,5,6] },
        ],
      }); 
      oTable.fnSort( [ [4,'desc'] ] );
    } );


    function resent_password(id) 
    {

    }

  </script>
  @stop