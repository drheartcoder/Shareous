@extends('front.layout.master')                
@section('main_content')
<div class="clearfix"></div>
<div class="overflow-hidden-section">
   <div class="titile-user-breadcrum">
      <div class="container">
          <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions"><h1>My Query</h1> <div class="clearfix"></div></div>
      </div> 
  </div>

  <div class="change-pass-bg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    

               <div id="left-bar-host">
                   @include('front.layout.left_bar_host')
               </div>
           </div>
           <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
           @if($records && is_array($records))

            <div class="user-tarnsaction-main">
                <div class="change-pass-bady">
                    <div class="transactions-table table-responsive">
                        <!--div format starts here-->
                        <div class="table">
                            <div class="table-row heading">
                                <div class="table-cell">Ticket Id</div>
                                <div class="table-cell">Category</div>
                                <div class="table-cell">Query</div>
                                <div class="table-cell">Date</div>
                                <div class="table-cell">Level</div>
                                <div class="table-cell">Status</div>
                                <div class="table-cell">Action</div>
                            </div>
                            
                            @foreach($records as $record)
                            <div class="table-row">

                                <div class="table-cell cargo-type">{{$record['id'] or ''}}</div>

                                <div class="table-cell vehical-category">{{str_limit($record['query_type_details']['query_type'],15)}}</div>

                                <div class="table-cell delevery-location">{{str_limit($record['query_subject'],15) }}</div>

                                <div class="table-cell date">{{isset($record['created_at']) ? get_added_on_date($record['created_at']):''}}</div>

                                <div class="table-cell rate">{{$record['support_level'] or ''}}</div>

                                {{-- <div class="table-cell rate">{{$record['support_level'] or ''}}</div> --}}
                                <div class="table-cell rate">

                                    @if(isset($record['status']) && $record['status'] =='1')
                                    Open
                                    @elseif(isset($record['status']) && $record['status'] =='2')
                                    Assigned
                                    @else
                                    Closed
                                    @endif

                                </div>

                                <div class="table-cell action"><a href="{{$module_path.'/view/'.base64_encode($record['id'])}}"><i class="fa fa-eye"></i></a></div>
                            </div>
                            @endforeach
                                    <div class="clearfix"></div>
                                </div>
                                <!--div format ends here-->
                            </div>
                        </div>
                        
{{--                         <div class="paginations">
                          <ul>
                              <li><a href="javascript:void(0)"><i class="fa fa-long-arrow-left"></i></a></li>
                              <li><a href="javascript:void(0)">1</a></li>
                              <li class="active"><a href="javascript:void(0)">2</a></li>
                              <li><a href="javascript:void(0)">3</a></li>
                              <li ><a href="javascript:void(0)"><i class="fa fa-long-arrow-right"></i></a></li>
                          </ul>
                      </div> --}}
             @else
                      <div class="list-vactions-details">
                          <div class="no-record-found"></div>
                          <!-- <div class="content-list-vact" style="color: red;font-size: 13px;">
                              <p>Sorry!, we couldn't find any Quesry!.</p>
                          </div> -->
                      </div>
              @endif

                @if(isset($obj_pagination) && $obj_pagination!=null)            
                    @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                @endif                  </div>

              </div>
          </div>
      </div>
  </div>
</div>

@endsection
