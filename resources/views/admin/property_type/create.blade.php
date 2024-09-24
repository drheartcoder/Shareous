@extends('admin.layout.master') 
@section('main_content')

<div class="page-title"><div> </div></div>

<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
        </li>
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="fa {{$module_icon}}"></i>
            <a href="{{ url($module_url_path) }}">{{ $module_title or ''}}</a>
        </span> 
        <span class="divider">
            <i class="fa fa-angle-right"></i>
            <i class="fa fa-plus-square-o"></i>
        </span>
        <li class="active">{{ $page_title or ''}}</li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-title">
                <h3>
                    <i class="fa fa-plus-square-o"></i>
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
                        <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="store" enctype="multipart/form-data"  files ="true">
                            {{csrf_field()}}

                            <div class="form-group col-lg-11">
                                <label class="col-sm-4 col-lg-3 control-label" for="page_title" style="text-align: right;">Property Type <i class="red">*</i></label>
                                <div class="col-sm-8 col-lg-5 controls">
                                    <input type="text" name="name" class="form-control" value="{{old('name')}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="Property Type Name">
                                    <span class='error help-block'>{{ $errors->first('name') }}</span>
                                </div>          
                            </div>     
                            <div class="form-group col-lg-11">
                                <div class="col-sm-6 col-sm-offset-4 col-lg-6 col-lg-offset-3">
                                    <input type="submit" value="Save" class="btn btn btn-primary btn-custom">
                                    <a href="{{url($admin_panel_slug.'/propertytype')}}" type="button" class="btn btn-cancel">Cancel</a>
                                </div>
                            </div>
                        </form>      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        
        $(document).ready(function()
        {
            $('#validate_form').validate();
        });

    </script>

    @stop
