    @extends('admin.layout.master')
    @section('main_content')

    <style type="text/css">
        .tmp-cms{
            margin-top: 100px;
            text-align: center;
            margin-bottom: 100px;
        }
    </style>
    

    <!-- BEGIN Page Title -->
    <div class="page-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
        </div>
    </div>
    <!-- END Page Title -->

    <!-- BEGIN Breadcrumb -->
    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li class="active"><i class="fa fa-home"></i> Home</li>
        </ul>
    </div>
    <!-- END Breadcrumb -->
    
    <!-- BEGIN Tiles -->
    <div class="row">
        <div class="col-md-12">

            @include('admin.layout._operation_status')
            
            <div class="col-md-12">
                <div class="box">
                    
                    <div class="box-title">
                        <h3>Site Statistics</h3>
                    </div>
                    
                    <div class="box-content">
                        <div style="margin-top:20px; position:relative; height: 980px;">
                            
                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/guest' }}">
                                    <div class="tile tile-dark-blue">
                                        <div class="img">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <div class="content">
                                            <p class="big">{{isset($total_user) ? $total_user :'0'}}</p>
                                            <p class="title">Total Guest</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/host' }}">
                                    <div class="tile tile-dark-blue">
                                        <div class="img">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <div class="content">
                                            <p class="big">{{isset($total_host) ? $total_host :'0'}}</p>
                                            <p class="title">Total Host</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                               
                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/support_team' }}">
                                    <div class="tile tile-magenta">
                                        <div class="img">
                                            <i class="fa fa-thumbs-o-up"></i>
                                        </div>
                                        <div class="content">
                                            <p class="big">{{isset($total_support_team) ? $total_support_team :'0'}}</p>
                                            <p class="title">Total Support Team</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/property/all' }}">
                                    <div class="tile tile-pink">
                                        <div class="img">
                                            <i class="fa fa-cubes"></i>
                                        </div>
                                        <div class="content">
                                            <p class="big">{{isset($total_property_upload) ? $total_property_upload :'0'}}</p>
                                            <p class="title">Total Property Upload</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                         
                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/profile' }}">
                                <div class="tile tile-green">
                                    <div class="img">
                                        <i class="fa fa-user "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Account Setting</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/site_settings' }}">
                                <div class="tile tile-blue">
                                    <div class="img">
                                        <i class="fa fa-cogs  "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Site Setting</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/admin_commission' }}">
                                <div class="tile tile-light-magenta">
                                    <div class="img">
                                        <i class="fa fa-percent"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Admin Commission</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/api_credentials' }}">
                                <div class="tile tile-magenta">
                                    <div class="img">
                                        <i class="fa fa-key  "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">API Credentials</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/newsletter_subscriber' }}">
                                <div class="tile tile-dark-blue">
                                    <div class="img">
                                        <i class="fa fa-envelope  "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Newsletter</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/propertytype' }}">
                                <div class="tile tile-pink">
                                    <div class="img">
                                        <i class="fa fa-home "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Property Type</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/amenities' }}">
                                <div class="tile tile-blue">
                                    <div class="img">
                                        <i class="fa fa-copy "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Amenities</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/coupon' }}">
                                <div class="tile tile-light-magenta">
                                    <div class="img">
                                        <i class="fa fa-trophy  "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Coupon</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/email_template' }}">
                                <div class="tile tile-dark-blue">
                                    <div class="img">
                                        <i class="fa fa-envelope-square  "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Templates</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/query_type' }}">
                                <div class="tile tile-pink">
                                    <div class="img">
                                        <i class="fa fa-question  "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Query Type</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/review-rating' }}">
                                <div class="tile tile-light-magenta">
                                    <div class="img">
                                        <i class="fa fa-star-half-empty"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Review & Ratings</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/blog' }}">
                                <div class="tile tile-magenta">
                                    <div class="img">
                                        <i class="fa fa-comment"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Blog</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/transaction' }}">
                                <div class="tile tile-dark-blue">
                                    <div class="img">
                                        <i class="fa fa-list "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Transaction</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/booking/all' }}">
                                <div class="tile tile-pink">
                                    <div class="img">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Booking</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/report/register' }}">
                                <div class="tile tile-blue">
                                    <div class="img">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Report</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/contact' }}">
                                <div class="tile tile-light-magenta">
                                    <div class="img">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Contact Enquiries</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/front_pages' }}">
                                <div class="tile tile-magenta">
                                    <div class="img">
                                        <i class="fa fa-file "></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Front Pages</p>
                                    </div>
                                </div>
                                </a>
                            </div>


                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/faq' }}">
                                <div class="tile tile-dark-blue">
                                    <div class="img">
                                        <i class="fa fa-question-circle"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">FAQ</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/testimonials' }}">
                                <div class="tile tile-pink">
                                    <div class="img">
                                        <i class="fa fa-commenting"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Testimonials</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/notification' }}">
                                <div class="tile tile-green">
                                    <div class="img">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Notifications</p>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ $admin_url_path.'/my-earning/host-request' }}">
                                <div class="tile tile-blue">
                                    <div class="img">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="content">
                                        <p class="title">Host Request</p>
                                    </div>
                                </div>
                                </a>
                            </div>
           
                        </div>
                    </div>
                </div>
            </div>

        </div>

@endsection