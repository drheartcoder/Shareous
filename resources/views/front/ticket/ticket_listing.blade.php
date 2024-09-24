@extends('front.layout.master')                
@section('main_content')
    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>Tickets Listing</h1>
                    <div class="clearfix"></div>
                </div>
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
                        
                    <form  id="frm_search_transaction">
                        <?php 
                            $sort_by = "DESC";
                            if(Request::get('sort_by') == null || Request::get('sort_by') == "DESC") {
                                $sort_by = "ASC";
                            }
                            else {
                                $sort_by = "DESC";
                            }
                        ?>

                        <input type="hidden" name="field_name" id="field_name" value="{{ Request::get('field_name') }}">
                        <input type="hidden" name="sort_by" id="sort_by" value="{{ Request::get('sort_by') }}">

                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        @if(isset($arr_ticket['data']) && !empty($arr_ticket['data']))
                            <div class="user-tarnsaction-main">
                                <div class="change-pass-bady">
                                    <div class="transactions-table table-responsive">
                                        <div class="table">
                                            <div class="table-row heading">
                                                <div class="table-cell sort-option" data-type="query_type" data-sort="{{ $sort_by }}">Query Type <i class="fa fa-sort"></i></div>
                                                <div class="table-cell sort-option" data-type="query_subject" data-sort="{{ $sort_by }}">Subject <i class="fa fa-sort"></i></div>
                                                <div class="table-cell sort-option" data-type="status" data-sort="{{ $sort_by }}">Status <i class="fa fa-sort"></i></div>
                                                <div class="table-cell sort-option" data-type="created_at" data-sort="{{ $sort_by }}">Date <i class="fa fa-sort"></i></div>
                                                <div class="table-cell">Action</div>
                                            </div>
                                            @foreach($arr_ticket['data'] as $ticket)
                                                <?php
                                                    $query_type = isset($ticket['query_type']) ? $ticket['query_type'] : '-';
                                                    $query_subject = isset($ticket['query_subject']) ? $ticket['query_subject'] : '-';
                                                    $query_description = isset($ticket['query_description']) ? $ticket['query_description'] : '-';
                                                    $status = isset($ticket['status']) ? $ticket['status'] : '-';
                                                    if( $status == 1 ) {
                                                        $status_value = 'Open';
                                                    }
                                                    elseif( $status == 2 ) {
                                                        $status_value = 'Assigned';
                                                    }
                                                    else {
                                                        $status_value = 'Closed';
                                                    }

                                                    $ticket_date = isset($ticket['created_at']) ? $ticket['created_at'] : '';
                                                    if(!empty($ticket_date) && $ticket_date != null) { 
                                                        $ticket_date = get_added_on_date($ticket_date);
                                                    }
                                                ?>
                                                <div class="table-row">
                                                    <div class="table-cell tabe-discrip cargo-type">{{ $query_type }}</div>
                                                    <div class="table-cell vehical-category" style="width:100%;white-space: normal;display: inline-block;">{{ str_limit($query_subject, 25) }}</div>
                                                    <div class="table-cell vehical-category">{{ $status_value }}</div>
                                                    <div class="table-cell date">{{ $ticket_date }}</div>
                                                    <div class="table-cell actio-style action">
                                                        <a href="{{ $module_url_path.'/ticket-details/'.base64_encode($ticket['id']) }}"><i class="fa fa-eye"></i></a>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="paginations">{{ $page_link }}</div>
                            </div>
                        @else
                            <div class="list-vactions-details"><div class="no-record-found"></div></div>
                        @endif
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        $(function () {
            $("#search_date").datepicker({
                todayHighlight: true,
                autoclose: true,
            });
        });
        
        $("#search_date").datepicker({ dateFormat: 'yy-mm-dd' });
        $('.sort-option').click(function(){
            var field_name = $(this).attr('data-type');
            var sort_by    = $(this).attr('data-sort');

            $("#field_name").val(field_name);
            $("#sort_by").val(sort_by);
            $('#frm_search_transaction').submit();
        })
    </script>
@endsection
