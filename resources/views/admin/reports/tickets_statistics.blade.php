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

                      <?php if(isset($user_id) && !empty($user_id)){?>
                      <li>
                        <i class="fa {{ $guest_user_icon }}"></i>
                        <a href="{{$guest_user_path}}">Guest</a>
                        <span class="divider"><i class="fa fa-angle-right"></i></span>
                      </li>
                      <?php } ?>

                       <?php if(isset($host_user_id) && !empty($host_user_id)){?>
                      <li>
                        <i class="fa {{ $host_user_icon }}"></i>
                        <a href="{{$host_user_path}}">Host</a>
                        <span class="divider"><i class="fa fa-angle-right"></i></span>
                      </li>
                      <?php } ?>

                      <li class="active"> <i class="fa {{$module_icon}}"></i>  {{$page_title or ''}}</li>
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
                                    <h3>Ticket Statistics</h3>
                                  
                                </div>
                                <div class="box-content">
                                    <div  style="margin-top:20px; position:relative; height: 290px;">
                                        <div class="col-md-3">
                                            <a href="{{url('/')}}/admin/report/ticket">
                                                <div class="tile tile-dark-blue">
                                                    <div class="img">
                                                        <i class="fa fa-ticket"></i>
                                                    </div>
                                                    <div class="content">
                                                        <p class="big">{{isset($total_no_tickets) ? $total_no_tickets :'0'}}</p>
                                                        <p class="title">Total No Of Tickets</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                                <a href="{{url('/')}}/admin/report/ticket?type=closed">
                                                    <div class="tile tile-dark-blue">
                                                            <div class="img">
                                                                <i class="fa fa-ticket"></i>
                                                            </div>
                                                            <div class="content">
                                                                <p class="big">{{isset($total_answer_tickets) ? $total_answer_tickets :'0'}}</p>
                                                                <p class="title">Total Answered Tickets</p>
                                                            </div>
                                                    </div>
                                                </a>
                                        </div>
                                          <div class="col-md-3">
                                                <a href="{{url('/')}}/admin/report/ticket?type=open">
                                                    <div class="tile tile-dark-blue">
                                                            <div class="img">
                                                                <i class="fa fa-ticket"></i>
                                                            </div>
                                                            <div class="content">
                                                                <p class="big">{{isset($total_unanswer_tickets) ? $total_unanswer_tickets :'0'}}</p>
                                                                <p class="title">Total Unanswered Tickets</p>
                                                            </div>
                                                    </div>
                                                </a>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <div class="tile tile-magenta">
                                                <div class="img">
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                </div>
                                                <div class="content">
                                                    <p class="big">{{isset($total_support_team) ? $total_support_team :'0'}}</p>
                                                    <p class="title">Total Support Team</p>
                                                </div>
                                            </div>
                                        </div> -->                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                                       
                        
                    </div>
@stop                    