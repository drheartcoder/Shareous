@extends('admin.layout.master')
@section('main_content')

<div id="main-content">
    <div class="page-title">
        <div>
            <h1><i class="fa {{$module_icon or ''}}"></i> {{$page_title or ''}}</h1>
        </div>
    </div>

    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url($admin_panel_slug.'/dashboard')}}">Dashboard</a>
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>
            <li class="active"> <i class="fa {{$module_icon or ''}}"></i>  {{$page_title or ''}}</li>
        </ul>
    </div>
    @include('admin.layout._operation_status')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="fa {{$module_icon or ''}}"></i> {{$page_title or ''}}</h3>         
                </div>
                <div class="box-content">
                    <div class="btn-toolbar pull-right">
                        <div class="btn-group">
                            <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" href="{{url('/')}}/{{$admin_panel_slug}}/newsletter_subscriber"><i class="fa fa-refresh"></i></a>
                        </div>
                    </div>
                    <br/><br/>
                    <div class="table-responsive" style="border:0">
                        <table class="table table-advance" id="table_subscriber">
                            <thead>                       
                                <tr>
                                    <th>Email</th>
                                    <th>Unique id</th>
                                    <th>Added on</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Created timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($arr_subscriber) && sizeof($arr_subscriber)>0 && is_array($arr_subscriber))
                                @foreach($arr_subscriber as $subscriber)
                                <tr>
                                    <td>{{isset($subscriber['email_address']) ? $subscriber['email_address'] : 'NA'}}</td>
                                    <td>{{isset($subscriber['unique_email_id']) ? $subscriber['unique_email_id'] : 'NA'}}</td>
                                    <td>{{isset($subscriber['timestamp_opt']) ? get_added_on_date_time($subscriber['timestamp_opt']) : 'NA' }}</td>
                                    <td>
                                        @if($subscriber['status']!='subscribed')
                                            <span class="btn btn-sm btn-danger nocursor">Unsubscribed</span>
                                        @else
                                            <span class="btn btn-sm btn-success nocursor">Subscribed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Remove" href="javascript:void(0)" onclick='delete_record("{{$subscriber['id']}}")' ><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    <td>{{$subscriber['timestamp_opt']}}</td>
                                </tr>  
                                @endforeach
                                @endif        
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div> 

        <script type="text/javascript">
            function delete_record(id)
            {
                swal({
                    title: "Are you sure",
                    text: "Do you want to remove this user from subscriber list?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, remove it!",
                    closeOnConfirm: false
                },
                function(){
                    location.href="{{url($module_url_path)}}/delete/"+id;
                });
            }

            $(document).ready(function(){
                var oTable = $('#table_subscriber').dataTable({
                                    "aoColumnDefs": [
                                                        { "bSortable": false, "searchable": false, "aTargets": [4] }
                                                        ,{"aTargets": [ 5 ], "visible": false, "searchable": false }
                                                    ],
                                    "ordering": true, 
                                });
                                oTable.fnSort( [ [5, 'desc'] ] );
            });
        </script>
        @stop