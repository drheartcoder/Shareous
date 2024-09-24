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
          
          <div class="btn-toolbar pull-right">
            <div class="btn-group">
              
              <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" href="{{url('/')}}/{{$admin_panel_slug}}/faq"><i class="fa fa-refresh"></i></a>

              <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Add New" href="{{$module_url_path}}/create"><i class="fa fa-plus"></i></a>

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
              <table class="table table-advance" id="faq_table">
                <thead>                       
                  <tr>
                    <th style="width:18px"> <input type="checkbox" name="mult_change" id="mult_change" value="delete" /></th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Added on</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(isset($arr_faq) && sizeof($arr_faq)>0 && is_array($arr_faq))
                  @foreach($arr_faq as $key => $faq)

                 <?php 
                 if(isset($faq['created_at']) && $faq['created_at']!='0000-00-00 00:00:00')
                  {
                    $date=get_added_on_date($faq['created_at']);                      
                  } 
                  ?>

                  <tr>
                    <td><input type="checkbox" name="checked_record[]" value="{{ base64_encode($faq['id']) }}" /></td>

                    <td>{{isset($faq['question'])? $faq['question']:'NA'}}</td>
                    <td>{{isset($faq['answer'])? str_limit($faq['answer'],50):'NA'}}</td>

                    <td>{{isset($date)?$date:'NA'}}</td>
                    <td>@if($faq['status']!='1')
                      <a href="{{$module_url_path.'/unblock/'.base64_encode($faq['id'])}}" class="btn btn-sm btn-danger">Inactive</a>
                      @else
                      <a href="{{$module_url_path.'/block/'.base64_encode($faq['id'])}}" class="btn btn-sm btn-success">Active</a>
                      @endif
                    </td>
                    <td>
                      <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Edit" href="{{ url($module_url_path.'/edit/'.base64_encode($faq['id'])) }}" ><i class="fa fa-pencil-square-o"></i></a>
                      <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Delete" href="javascript:void(0)" onclick='delete_record("{{base64_encode($faq['id'])}}")'><i class="fa fa-trash"></i></a>     
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
      var oTable = $('#faq_table').dataTable({
      "aoColumnDefs": [
                        { 
                          "bSortable": false, 
                          "aTargets": [0,4,5] 
                         }, 
                          { "searchable": false, "targets": [0,4,5] }
                      ],
                      "ordering": true, 
          });        
       oTable.fnSort( [ [1,'desc'] ] );
   });

  </script>
  @stop