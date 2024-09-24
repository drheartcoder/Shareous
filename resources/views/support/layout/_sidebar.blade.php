<?php 
    $support_path       = config('app.project.support_panel_slug');
    $support_panel_slug = config('app.project.support_panel_slug');
    $support_level      = isset($shared_web_support_details['support_level'])?$shared_web_support_details['support_level']:'';

    $seg1 = $seg2 = '';
    $seg1 = Request::segment('2');
    $seg2 = Request::segment('3'); 

?>

<div id="sidebar" class="navbar-collapse collapse">
  <!-- BEGIN Navlist -->
  <ul class="nav nav-list">

    <!-- ========================================== Start Dashboard Settings ========================================== -->  

    <li class="@if($module_title=='Dashboard') active :''@endif">
      <a href="{{ url($support_panel_slug.'/dashboard')}}">
        <i class="fa fa-dashboard faa-vertical animated-hover"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- ========================================== End Dashboard Settings ========================================== -->

    <!-- ========================================== Start Account Settings ========================================== -->

    <li class="<?php  if(Request::segment(2) == 'profile'){ echo 'active'; } ?>">
      <a href="{{ url($support_panel_slug.'/profile')}}" >
        <i class="fa fa-cogs faa-vertical animated-hover"></i>
        <span>Account Settings</span>
      </a>
    </li>

    <!-- ========================================== End Account Settings ========================================== -->
    @if($support_level=="L1")
    <li class="<?php  if( $module_title == 'Verification Requests'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-id-card-o"></i>
        <span>Verification Request</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>

      <ul class="submenu">
        <li @if(($seg1 == "verification" && $seg2 == "") || ($seg1 == "verification" && $seg2 == "view"))  class="active" @endif ><a href="{{ url($support_panel_slug.'/verification')}}">Pending Requests </a></li>

        <li @if(($seg1 == "verification" && $seg2 == "approve") || ($seg1 == "verification" && $seg2 == "view_approve_data")) class="active" @endif><a href="{{ url($support_panel_slug.'/verification/approve')}}">Approved Requests </a></li>

        <li @if(($seg1 == "verification" && $seg2 == "reject_request") || ($seg1 == "verification" && $seg2 == "view_reject_data")) class="active" @endif><a href="{{ url($support_panel_slug.'/verification/reject_request')}}">Rejected Requests </a></li>
      </ul>
    </li>
    @endif

    <li class="<?php if( $module_title == 'Tickets' ||  $module_title == 'Generate Tickets'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-ticket"></i>
        <span>Tickets</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li @if(($seg1 == "ticket" && $seg2 == "") || ($seg1 == "ticket" && $seg2 == "view") || ($seg1 == "ticket" && $seg2 == "reply"))  class="active" @endif><a href="{{ url($support_panel_slug.'/ticket')}}"  >Assign Tickets</a></li>
        <li @if($seg1 == "ticket" && $seg2 == "generate-ticket") class="active" @endif ><a href="{{ url($support_panel_slug.'/ticket/generate-ticket')}}">Generate Tickets</a></li>
        <li @if(($seg1 == "ticket" && $seg2 == "closed_ticket") || ($seg1 == "ticket" && $seg2 == "view_closed_ticket")) class="active" @endif ><a href="{{ url($support_panel_slug.'/ticket/closed_ticket')}}">Closed Tickets</a></li>
      </ul>
    </li>

  </ul>

<!-- END Navlist -->

<!-- BEGIN Sidebar Collapse Button -->
<div id="sidebar-collapse" class="visible-lg">
  <i class="fa fa-angle-double-left"></i>
</div>
<!-- END Sidebar Collapse Button -->
</div>