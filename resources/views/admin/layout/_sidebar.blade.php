<?php 
$admin_path          = config('app.project.admin_panel_slug');
$admin_panel_slug    = config('app.project.admin_panel_slug');
$request_segment_two = Request::segment(2);
?>

<div id="sidebar" class="navbar-collapse collapse">
  <!-- BEGIN Navlist -->
  <ul class="nav nav-list" id="leftbar_menu_list">

    <!-- BEGIN Search Form -->
    <li>
      <span class="search-pan">
          <button type="submit">
              <i class="fa fa-search"></i>
          </button>
          <input type="text" id="search_menu" name="search" placeholder="Search..." autocomplete="on" />
      </span>
    </li>
    <!-- END Search Form -->

    <!-- ========================================== Start Dashboard Settings ========================================== -->  

    <li class="@if(isset($request_segment_two) && $request_segment_two=='dashboard') active :''@endif">
      <a href="{{ url($admin_panel_slug.'/dashboard')}}">
        <i class="fa fa-dashboard faa-vertical animated-hover"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- ========================================== End Dashboard Settings ========================================== -->

    <!-- ========================================== Start Account Settings ========================================== -->

    <li class="<?php  if(isset($request_segment_two) && $request_segment_two == 'profile'){ echo 'active'; } ?>">
      <a href="{{ url($admin_panel_slug.'/profile')}}" >
        <i class="fa fa-user faa-vertical animated-hover"></i>
        <span>Account Settings</span>
      </a>
    </li>

    <!-- ========================================== End Account Settings ========================================== -->

    <!-- ========================================== Start Site Settings ========================================== -->

     <li class="@if(isset($request_segment_two) && $request_segment_two=='site_settings') active :''@endif">
      <a href="{{ url($admin_panel_slug.'/site_settings')}}" >
        <i class="fa fa-cogs faa-vertical animated-hover"></i>
        <span>Site Settings</span>
      </a>
    </li> 

    <!-- ========================================== End Site Settings ========================================== -->

    <!-- ========================================== Start Commission ========================================== -->

     <li class="@if($module_title=='Admin Commission') active :''@endif">
      <a href="{{ url($admin_panel_slug.'/admin_commission')}}" >
        <i class="fa fa-percent faa-vertical animated-hover"></i>
        <span>Admin Commission</span>
      </a>
    </li> 

    <!-- ========================================== End Commission ========================================== -->


    <!-- ========================================== Start Api Credetials ========================================== -->



     <li class="@if($module_title=='Api Credentials') active :''@endif">
      <a href="{{ url($admin_panel_slug.'/api_credentials')}}" >
        <i class="fa fa-key faa-vertical animated-hover"></i>
        <span>Api Credentials</span>
      </a>
    </li> 

    <!-- ========================================== End Api Credetials ========================================== -->


    <!-- ========================================== Start Newsletter Subscriber ========================================== -->
    <li class="@if(isset($request_segment_two) && $request_segment_two=='newsletter_subscriber') active :''@endif">
      <a href="{{ url($admin_panel_slug.'/newsletter_subscriber')}}" >
        <i class="fa fa-envelope faa-vertical animated-hover"></i>
        <span>Newsletter Subscriber</span>
      </a>
    </li> 

    <!-- ========================================== End Newsletter Subscriber ========================================== -->

    

    <!-- ========================================== Start Categories ========================================== -->

    <!-- <li class="<?php  if( $module_title == 'Category'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-cubes"></i>
        <span>Categories</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li style="display: block;"><a href="{{ url($admin_panel_slug.'/categories')}}">Manage </a></li>
        <li style="display: block;"><a href="{{ url($admin_panel_slug.'/categories/create')}}">Create </a></li>
      </ul>
    </li> -->

    <!-- ========================================== End Categories ========================================== -->

    <!-- ========================================== Start Property Type ========================================== -->

    <li class="<?php if(Request::segment(2) == 'propertytype'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-home"></i>
        <span>Property Type</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'propertytype' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/propertytype')}}">Manage </a></li>
        <li class="<?php if(Request::segment(2) == 'propertytype' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/propertytype/create')}}">Create </a></li>
      </ul>
    </li>

    <!-- ========================================== End Property Type ========================================== -->

    <!-- ========================================== Start Categories ========================================== -->

    <li class="<?php  if( $module_title == 'All Property'  || $module_title == 'Pending Property' || $module_title == 'Confirm Property' || $module_title == 'Reject Property' || $module_title == 'Permanant Rejected Property'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-cubes"></i>
        <span>Property</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
      <li class="<?php if(Request::segment(2) == 'property' && Request::segment(3) == 'all' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/property/all')}}">All Properties</a></li>
      <li class="<?php if(Request::segment(2) == 'property' && Request::segment(3) == 'pending' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/property/pending')}}">Pending Properties </a></li>
      <li class="<?php if(Request::segment(2) == 'property' && Request::segment(3) == 'confirmed' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/property/confirmed')}}">Confirm Properties</a></li>
      <li class="<?php if(Request::segment(2) == 'property' && Request::segment(3) == 'rejected' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/property/rejected')}}">Rejected Properties</a></li>
      <li class="<?php if(Request::segment(2) == 'property' && Request::segment(3) == 'reject_permanant' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/property/reject_permanant')}}">Permanant Rejected</a></li>
      </ul>
    </li>

    <!-- ========================================== End Categories ========================================== -->

    <!-- ========================================== Start Aminity ========================================== -->

    <li class="<?php  if($module_title == 'Aminity'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-copy"></i>
        <span>Amenities</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'amenities' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/amenities')}}">Manage </a></li>
        <li class="<?php if(Request::segment(2) == 'amenities' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/amenities/create')}}">Create </a></li>
      </ul>
    </li>

    <!-- ========================================== End Aminity ========================================== -->   

    <!-- ========================================== Start Coupon ========================================== -->

    <li class="<?php  if( $module_title == 'Coupon'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-trophy"></i>
        <span>Coupon</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'coupon' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/coupon')}}">Manage </a></li>
        <li class="<?php if(Request::segment(2) == 'coupon' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/coupon/create')}}">Create </a></li>
      </ul>
    </li>

    <!-- ========================================== End Coupon ========================================== -->   

    <!-- ========================================== Start Other Services ========================================== -->

    <!-- <li class="<?php  if( $module_title == 'Other Services'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-bars"></i>
        <span>Other Services</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'other_services' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/other_services')}}">Manage </a></li>
        <li class="<?php if(Request::segment(2) == 'other_services' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/other_services/create')}}">Create </a></li>
      </ul>
    </li> -->

    <!-- ========================================== End Other Services ========================================== -->
    <li class="<?php  if( $module_title == 'Email Template' || $module_title == 'Notifications Template'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-envelope-square"></i>
        <span>Templates</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'email_template' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/email_template')}}">Email Template </a></li>
        <li class="<?php if(Request::segment(2) == 'notifications' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/notifications')}}">Notification Template </a></li>
      </ul>
    </li>

    <!-- ========================================== Start Query Type ========================================== -->

    <li class="<?php if( $module_title == 'Query Type'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-question"></i>
        <span>Query Type</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'query_type' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/query_type')}}">Manage </a></li>
        <li class="<?php if(Request::segment(2) == 'query_type' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/query_type/create')}}">Create </a></li>
      </ul>
    </li>

    <!-- ========================================== End Query Type ========================================== -->

    <!-- ========================================== Start Guest ========================================== -->

    <li class="<?php if( $module_title == 'Guest'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-user"></i>
        <span>Guest</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'guest' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/guest')}}">Manage </a></li>        
      </ul>
    </li>

    <!-- ========================================== End Guest ========================================== -->

    <!-- ========================================== Start Host ========================================== -->

    <li class="<?php if( $module_title == 'Host'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-server"></i>
        <span>Host</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'host' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/host')}}">Manage </a></li>        
      </ul>
    </li>

    <!-- ========================================== End Host ========================================== -->    


      <!-- ========================================== Start Review And Rating ========================================== -->

    <li class="<?php if( $module_title == 'Review & Ratings'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-star-half-empty"></i>
        <span>Review & Ratings</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'review-rating' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/review-rating')}}">Manage </a></li>        
      </ul>
    </li>

    <!-- ========================================== End Review And Rating ========================================== -->    

    <!-- ========================================== Start Blog ========================================== -->

    <li class="<?php if( $module_title == 'Blog' || $module_title == 'Blog Category' ){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-comment"></i>
        <span>Blog</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'blog_category' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/blog_category')}}">Manage Blog Category </a></li>
        <li class="<?php if(Request::segment(2) == 'blog' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/blog')}}">Manage Blog </a></li>
      </ul>
    </li>

    <!-- ========================================== End Blog ========================================== -->

    <!-- ========================================== Start Transaction========================================== -->

    <li class="<?php if( $module_title == 'Transaction'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-list fa-lg"></i>
        <span>Transaction</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'transaction' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/transaction')}}">Manage </a></li>
      </ul>
    </li>

    <!-- ========================================== End Transaction ========================================== -->


      <!-- ========================================== Start Booking========================================== -->

    <li class="<?php  if( $module_title == 'All Booking'  || $module_title == 'New Booking' || $module_title == 'Confirm Booking' || $module_title == 'Completed Booking' || $module_title == 'Cancel Booking'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-list fa-lg"></i>
        <span>Booking</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'booking' && Request::segment(3) == 'all' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/booking/all')}}">All Booking</a></li>
        <!-- <li class="<?php if(Request::segment(2) == 'booking' && Request::segment(3) == 'new' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/booking/new')}}">New Booking</a></li> -->
        <li class="<?php if(Request::segment(2) == 'booking' && Request::segment(3) == 'confirmed' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/booking/confirmed')}}">Confirm Booking</a></li>
        <li class="<?php if(Request::segment(2) == 'booking' && Request::segment(3) == 'completed' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/booking/completed')}}">Completed Booking</a></li>
        <li class="<?php if(Request::segment(2) == 'booking' && Request::segment(3) == 'cancel' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/booking/cancel')}}">Cancel Booking</a></li>
      </ul>
    </li>


    <!-- ========================================== End Booking ========================================== -->


     <!-- ========================================== Start My Earning========================================== -->

    <li class="<?php  if( Request::segment(2) == 'my-earning' ){ echo 'active'; } ?>">

      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-list fa-lg"></i>
        <span>My Earnings</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'my-earning'){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/my-earning')}}">Manage</a></li>
        <li class="<?php if(Request::segment(3) == 'host-request'){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/my-earning/host-request')}}">Host Request</a></li>
      </ul>
    </li>

    <!-- ========================================== End My Earning ========================================== -->


    <!-- ========================================== Start Support Team ========================================== -->

    <li class="<?php if( $module_title == 'Support Team'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-phone-square"></i>
        <span>Support Team</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'support_team' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/support_team')}}">Manage </a></li>
        <li class="<?php if(Request::segment(2) == 'support_team' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/support_team/create')}}">Create </a></li>
      </ul>
    </li>

    <!-- ========================================== End Support Team ========================================== -->



    <!-- ========================================== Start Support Team ========================================== -->

   
    <li class="<?php if( $module_title == 'Generate Ticket'){ echo 'active'; } ?>">
      <a href="{{ url($admin_panel_slug.'/generate_ticket')}}" >
        <i class="fa fa-ticket faa-vertical animated-hover"></i>
         <span>Generate Ticket</span>
      </a>
    </li>

    <!-- ========================================== End Support Team ========================================== -->

    <!-- ========================================== Start Reports ========================================== -->

   <li class="<?php  if( $module_title == 'Report' || $module_title == 'Registrations Report'  || $module_title == 'Booking Report' || $module_title == 'Refund Report' || $module_title == 'Cancellation Report' || $module_title == 'Ticket Generating Report'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-cubes"></i>
        <span>Report</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
      <li class="<?php if(Request::segment(2) == 'report' && Request::segment(3) == 'register' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/report/register')}}">Registrations Report </a></li>
      <li class="<?php if(Request::segment(2) == 'report' && Request::segment(3) == 'booking' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/report/booking')}}">Booking Report </a></li>
      <li class="<?php if(Request::segment(2) == 'report' && Request::segment(3) == 'cancellation' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/report/cancellation')}}">Cancellation Report </a></li>
      <li class="<?php if(Request::segment(2) == 'report' && Request::segment(3) == 'ticket' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/report/ticket')}}">Ticket Generating Report </a></li>
       <li class="<?php if(Request::segment(2) == 'report' && Request::segment(3) == 'ticket_statistics' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/report/ticket_statistics')}}">Ticket Statistics</a></li>
      </ul>
    </li>

    <!-- ========================================== End Reports ========================================== -->

    <!-- ========================================== Start Contact Enquiries ========================================== -->

    <li class="@if($module_title=='Contact Enquiries') active :''@endif">
      <a href="{{ url($admin_panel_slug.'/contact')}}" >
        <i class="fa fa-phone faa-vertical animated-hover"></i>
        <span>Contact Enquiries</span>
      </a>
    </li> 

    <!-- ========================================== End Contact Enquiries ========================================== -->


     <!-- ========================================== Start Front Pages ========================================== -->

    <li class="<?php if( $module_title == 'Front Pages'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-file"></i>
        <span>Front Pages</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'front_pages' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/front_pages')}}">Manage </a></li>
        <!-- <li class="<?php if(Request::segment(2) == 'front_pages' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/front_pages/create')}}">Create </a></li> -->
      </ul>
    </li>

    <!-- ========================================== End Front Pages ========================================== -->

     <!-- ========================================== Start FAQ ========================================== -->

    <li class="@if($module_title=='FAQ') active :''@endif">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-question-circle"></i>
        <span>FAQ</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'faq' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/faq')}}">Manage </a></li>
      </ul>
    </li>

    <!-- ========================================== End FAQ ========================================== -->

    <!-- ========================================== Start Social account Credetials ========================================== -->

    <!-- <li class="<?php  //if( $module_title == 'Social Credentials'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-key"></i>
        <span>Social Credentials</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li style="display: block;"><a href="{{ url($admin_panel_slug.'/social_credentials')}}">Manage </a></li>
      </ul>
    </li> -->

    <!-- ========================================== End Social account Credetials ========================================== -->


     <!-- ========================================== Start Social account Credetials ========================================== -->

    <li class="<?php  if( $module_title == 'Testimonials'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-commenting"></i>
        <span>Testimonials</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'testimonials' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/testimonials')}}">Manage </a></li>
        <li class="<?php if(Request::segment(2) == 'testimonials' && Request::segment(3) == 'create' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/testimonials/create')}}">Create </a></li>
      </ul>
    </li>

    <!-- ========================================== End Social account Credetials ========================================== -->

    <!-- ========================================== Start Notifications ========================================== -->

    <li class="<?php  if( $module_title == 'Notifications'){ echo 'active'; } ?>">
      <a href="javascript:void(0)" class="dropdown-toggle">
        <i class="fa fa-bell"></i>
        <span>Notifications</span>
        <b class="arrow fa fa-angle-right"></b>
      </a>
      <ul class="submenu">
        <li class="<?php if(Request::segment(2) == 'notification' && Request::segment(3) == '' ){ echo 'active'; } ?>" style="display: block;"><a href="{{ url($admin_panel_slug.'/notification')}}">Manage </a></li>
      </ul>
    </li>

    <!-- ========================================== End Notifications ========================================== -->

  </ul>

<!-- END Navlist -->

<!-- BEGIN Sidebar Collapse Button -->
<div id="sidebar-collapse" class="visible-lg">
  <i class="fa fa-angle-double-left"></i>
</div>
<!-- END Sidebar Collapse Button -->
</div>
  