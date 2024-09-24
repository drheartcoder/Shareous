    <div class="dash-left-menu">
        <div class="dashboard-menu">Dashboard Menu<i class="fa fa-bars" aria-hidden="true"></i></div>
       <div class="profile-tp">
          <div class="pro-bottom-down">
            <div class="profile-img-block">
                @if(isset($user_details['profile_image']) && $user_details['profile_image'] != '')
                    @if(strpos($user_details['profile_image'], 'http') !== false)
                        <?php $profile_img_src = $user_details['profile_image']; ?>
                    @else
                        @if(file_exists($user_image_base_path.$user_details['profile_image'] ))
                            <?php $profile_img_src = $user_image_public_path.$user_details['profile_image']; ?>
                        @else
                            <?php $profile_img_src = url('/uploads').'/default-profile.png'; ?> 
                        @endif
                    @endif
                @else
                    <?php $profile_img_src = url('/uploads').'/default-profile.png'; ?> 
                @endif
                <img src="{{$profile_img_src}}" alt="{{$profile_img_src}}" >
               
            </div>
            <div class="user-name-block">
                {{isset($user_details['first_name'])?$user_details['first_name']:'NA'}} {{isset($user_details['last_name'])?$user_details['last_name']:'NA'}}
            </div>
            <div class="user-email-block email-user">
                 @if(Session::get('user_type') != null &&  Session::get('user_type') == '1') Guest User
                 @elseif(Session::get('user_type') != null &&  Session::get('user_type') == '4') Property Owner @endif
            </div>
            </div>
                                                                                 
            <!-- <a class="edit-profile-btn" href="{{url('/profile')}}"><i class="fa fa-pencil"></i></a>  -->
       </div>
        <ul class="left-profile menu-show">
            <?php
                $seg1 = $seg2 = '';
                $seg1 = Request::segment('1');
                $seg2 = Request::segment('2');

                if( Session::get('user_type') == null) {
                    Session::put('user_type','1');
                }
            ?>

            <!-- Both User Menu -->
                <li><a href="{{ url('/profile/') }}" @if($seg1 == "profile" && $seg2 =='' ) class="active" @endif>My Account</a></li>
                @if($user_details['social_login']=='no')
                <li><a href="{{url('/profile/change_password')}}" @if($seg1 == "profile" && $seg2 == "change_password") class="active" @endif>Change Password</a></li>
                @endif

            <!-- Guest User Menu -->
            @if(Session::get('user_type') != null &&  Session::get('user_type') == '1')

                <li><a href="{{ url('/my-booking/confirmed') }}" @if( ($seg1 == "my-booking" && $seg2 == "new") || ($seg1 == "my-booking" && $seg2 == "completed" ) || ($seg1 == "my-booking" && $seg2 == "confirmed") ) class="active" @endif >My Bookings</a></li>

                <li><a href="{{ url('/property/favourite') }}" @if($seg1 == "property") class="active" @endif >My Favourite</a></li>
                <li><a href="{{ url('/wallet') }}"  @if($seg1 == "wallet") class="active" @endif >My Wallet</a></li>
                <li><a href="{{ url('/transactions') }}"  @if($seg1 == "transactions") class="active" @endif>My Transaction</a></li>

                <li><a href="{{ url('/my-booking/cancelled') }}" @if($seg1 == "my-booking" && $seg2 == "cancelled" ) class="active" @endif>Cancellation</a></li>

                <li><a href="{{ url('/ticket/')}}" @if($seg1 == "ticket") class="active" @endif>Generate Ticket</a></li>
                <li><a href="{{ url('/ticket-listing/')}}" @if($seg1 == "ticket-listing") class="active" @endif>Tickets Listing</a></li>
                <!-- <li><a href="{{ url('/query')}}" @if($seg1 == "query") class="active" @endif>My Query</a></li> -->
                <li><a href="{{ url('/notifications/') }}" @if($seg1 == "notifications") class="active" @endif>Notification</a></li>
                <!-- <li><a href="user-message.html">Message</a></li> -->
                <li><a href="{{ url('/review-rating')}}" @if($seg1 == "review-rating") class="active" @endif>My Review &amp; Rating</a></li>
            
            <!-- Host User Menu -->
            @elseif(Session::get('user_type') != null &&  Session::get('user_type') == '4')
                 <li><a href="{{ url('/profile/my_documents') }}"  @if($seg1 == "profile" && $seg2 == "my_documents") class="active" @endif>My Documents</a></li>
                <!--  <li><a href="{{ url('/booking') }}"  @if($seg1 == "booking") class="active" @endif>New Requests</a></li> -->
                <li><a href="{{ url('/property/create_step1') }}" @if($seg1 == "property" && $seg2 == "create_step1") class="active" @endif>Add Property</a></li>
                <li><a href="{{ url('/property/listing') }}" @if($seg1 == "property" && $seg2 == "listing") class="active" @endif>My Listings</a></li>

                <li><a href="{{ url('/my-booking/confirmed') }}"@if(($seg1 == "my-booking" && $seg2 == "new") || ($seg1 == "my-booking" && $seg2 == "completed" ) || ($seg1 == "my-booking" && $seg2 == "confirmed") || ($seg1 == "my-booking" && $seg2 == "cancelled")) ) class="active" @endif >My Bookings</a></li>

                <li><a href="{{ url('/property/favourite') }}" @if($seg1 == "property" && $seg2 == "favourite") class="active" @endif>My Favourite</a></li>
                <!--<li><a href="host-reservations.html">Reservations</a></li>-->
             
                <li><a href="{{ url('/transactions') }}" @if($seg1 == "transactions") class="active" @endif>My Transactions</a></li>
                 <li><a href="{{ url('/my-earning') }}"  @if($seg1 == "my-earning") class="active" @endif>My Earnings</a></li>

                <!-- <li><a href="{{ url('/my-request') }}"  @if($seg1 == "my-request") class="active" @endif>My Request</a></li> -->
                
                <li><a href="{{ url('/bank_details/') }}" @if($seg1 == "bank_details") class="active" @endif>Add Bank Account</a></li>
                <li><a href="{{url('/ticket/')}}" @if($seg1 == "ticket") class="active" @endif>Generate Ticket</a></li>
                <li><a href="{{ url('/ticket-listing/')}}" @if($seg1 == "ticket-listing") class="active" @endif>Tickets Listing</a></li>
                <!-- <li><a href="{{url('/query')}}" @if($seg1 == "query") class="active" @endif>My Query</a></li> -->
                <li><a href="{{ url('/notifications/') }}" @if($seg1 == "notifications") class="active" @endif">Notification</a></li>
                <li><a href="{{url('property-review-rating')}}"  @if($seg1 == "property-review-rating") class="active" @endif>My Review &amp; Rating</a></li>

            @endif
        </ul>
    </div>

<!-- <script>
    $(".setting-block").on("click", function(){
        $(".setting-dropdown").slideToggle("slow");
        $(this).toggleClass("active");
    });
</script> -->

<!-- dashboard menu show and hide in mobile start here -->
<script type="text/javascript"> 
$(document).ready(function(){
    $(".dashboard-menu").click(function() {
        $(".menu-show").slideToggle("slow");
    });
});
</script>
<!-- dashboard menu show and hide in mobile end here -->