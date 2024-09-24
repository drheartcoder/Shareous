@extends('admin.layout.master')
@section('main_content')
<!-- BEGIN Content -->

<style>
.margintop{margin-top: 23px;}
.form-field-txt .chosen-container{width: 100% !important;}
.form-field-txt .form-control{width: 100%;}
.icrcle-btm{position: absolute;right: 20px; top: 59px;}
.form-inline.bottm-noe{margin-bottom: 0; padding-right: 30px;}
    
    .form-inline .form-control{width: 100%;}
    .form-group select{width: 100% !important; display: block;}
    .chosen-container{width: 100% !important;}
    @media all and (max-width:991px){
        .icrcle-btm{position: static;margin-bottom: 20px;float: right;}
    }    
    
</style>
<div id="main-content">
    <!-- BEGIN Page Title -->
    <div class="page-title">
        <div>
            <h1><i class="fa {{$module_icon}}"></i> {{$page_title or ''}}</h1>
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

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ Session::get('error') }}
        </div>
    @endif
    
    <?php 
        $email   = Request::input('email');
        $keyword = Request::input('keyword');
    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="box {{ theme_color() }}">
                <div class="box-title">
                    <h3><i class="fa {{ $module_icon }}"></i> {{ $page_title or '' }}</h3>
                </div>
                <div class="box-content">         
                    <form name="frm-manage" id="frm-manage" method="get" action="{{ $module_url_path }}" class="form-inline" >

                        <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <label >Keyword: </label>
                            <input type="text" placeholder="Keyword" id="keyword" name="keyword" class="form-control" value="{{ trim($keyword) }}">
                        </div>

                        <div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3 margintop" >
                            <input id="submit_filter" class="btn btn-primary same_search" type="submit" value="Search" name="btn_search"> &nbsp;
                            <a href="{{ $module_url_path }}" class="btn btn-default">Reset </a>
                        </div>

                        <input type="hidden" name="htoken" value="{{ csrf_token() }}">
                        <div class="clearfix"></div>
                    </form>

                    <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{ $module_url_path }}/multi_action">
                        {{ csrf_field() }}

                        <div class="btn-toolbar pull-right">
                            <div class="btn-group"> 
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Refresh" onclick="window.location.reload()" style="text-decoration:none;">
                                    <i class="fa fa-repeat"></i>
                                </a> 
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Multiple Unblock" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','activate');" style="text-decoration:none;">
                                    <i class="fa fa-unlock"></i>
                                </a> 
                                <a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip btn-dangers" title="Multiple Block" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','deactivate');"  style="text-decoration:none;">
                                    <i class="fa fa-lock"></i>
                                </a>  
                                <a class="btn btn-circle btn-to-danger btn-bordered btn-fill show-tooltip" title="Multiple Delete" href="javascript:void(0);" onclick="javascript : return check_multi_action('frm_manage','delete');" style="text-decoration:none;">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <br/><br/>
                        <div class="clearfix"></div>

                        <input type="hidden" name="multi_action" value="" />
                        <div class="table-responsive" style="border:0">              
                            <table id="myTable" class="table table-advance">
                                <thead>
                                    <tr>
                                        <th style="width:18px"> 
                                            <input type="checkbox" name="mult_change" id="mult_change" value="delete" />
                                        </th>
                                        <th>Date</th>
                                        <th>User Name</th>
                                        <th>Name</th>
                                        <th>Email ID</th>
                                        <th>Mobile No.</th>              
                                        <th>Status</th>
                                        <th>Verify Email</th>
                                        <th>Verify Mobile</th>
                                        <th>Action</th>
                                    </tr>                  
                                </tr>
                            </thead>
                            <tbody>             
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript"> 
        var email           = $('#email').val();
        var keyword         = $('#keyword').val();
        var module_url_path = "{{ url($module_url_path) }}";
        var temp_url        = module_url_path+'/load_data';
        var url             = temp_url.replace(/&amp;/g, '&');
        
        table_module        = $('#myTable').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "paging": true,
                                "searching":false,
                                "ordering": true,
                                "destroy": true,
                                ajax: {
                                        'url' : temp_url,
                                        'data' : { 'keyword':keyword, 'email':email }
                                    },
                                "columnDefs": [{ targets: [0], orderable: false }]
                            });

        $('#check-all').click(function (e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });
    </script>
    @stop