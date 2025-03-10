@extends('admin.layout.master')
@section('main_content')

<style type="text/css">
.pagination, .dataTables_filter {
    float: right !important;
}
</style>
<div id="main-content">

    <div class="page-title">
        <div>
            <h1><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h1>
        </div>
    </div>

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
            <div class="box {{theme_color()}}">
                <div class="box-title">
                    <h3><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h3>
                </div>
                <div class="box-content">
                    <div class="btn-toolbar pull-left">
                        <form class="form-inline" method="get" action="{{url('/')}}/{{$admin_panel_slug}}/manage_categories" data-parsley-validate id="frm_page">
                            <div class="form-group">
                                <div class="col-sm-12 col-lg-12 controls">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="btn-toolbar pull-right">
                        <div class="btn-group">

                            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" href="{{$module_url_path}}"><i class="fa fa-refresh"></i></a>

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
                            <table class="table table-advance" id="amenities_table">
                                <thead>                       
                                    <tr>
                                        <th style="width:18px"> 
                                            <input type="checkbox" name="mult_change" id="mult_change" value="delete" />
                                        </th>
                                        <th>Property Type Name</th>
                                        <th>{{$module_title}} Name</th>
                                        <th>Added On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($objects) 
                                    @foreach($objects as $key => $object)
                                    <?php 
                                    if(isset($object['created_at']) && $object['created_at']!='0000-00-00 00:00:00')
                                    {
                                        $date=get_added_on_date($object['created_at']);                      
                                    }
                                    ?>
                                    <tr>

                                        <td>
                                            <input type="checkbox" name="checked_record[]" value="{{ base64_encode($object['id']) }}" />
                                        </td>
                                        <td>{{isset($object['propertytype']['name'])? ucfirst($object['propertytype']['name']):'NA'}}</td>
                                        <td>{{isset($object['aminity_name'])? ucfirst($object['aminity_name']):''}}</td>
                                        <td>{{isset($date) ? $date:'NA'}}</td>
                                        <td>
                                            @if($object['status']!='1')
                                            <a href="{{$module_url_path.'/unblock/'.base64_encode($object['id'])}}" class="btn btn-sm btn-danger">Block</a>
                                            @else
                                            <a href="{{$module_url_path.'/block/'.base64_encode($object['id'])}}" class="btn btn-sm btn-success">Unblock</a>
                                            @endif
                                        </td>
                                        <td>

                                            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Edit" href="{{url($module_url_path.'/edit/'.base64_encode($object['id']))}}"><i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            @if($object['property']['property_aminities'] == null && empty($object['property']['property_aminities']))
                                            <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Delete" href="javascript:void(0)" onclick='delete_record("{{base64_encode($object['id'])}}")'><i class="fa fa-trash"></i>
                                            </a>
                                            @elseif($object['property']['property_aminities'] != null && !empty($object['property']['property_aminities']))
                                            <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Delete" href="javascript:void(0)" onclick='error_record()'><i class="fa fa-trash"></i>
                                            </a>
                                            @endif
                                        </td>                  
                                    </tr>  
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function submit_form(){
                jQuery('#frm_page').submit();
            }

            function error_record()
            {
                swal({
                    title: "Access Denied",
                    text: "Are ready in use. So, this value cannot be delete.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Cancel!",
                    closeOnConfirm: false
                });
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
                var oTable = $('#amenities_table').dataTable({
                    "aoColumnDefs": [
                    { 
                        "bSortable": false, 
                        "aTargets": [0,4,5] ,
                    }, 
                    { "searchable": false, "targets": [0,4,5] },
                    ],
                    "ordering": true,       
                }); 
                oTable.fnSort( [ [3,'desc'] ] );
            });

        </script>
        @stop