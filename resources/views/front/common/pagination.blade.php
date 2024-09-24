<div class="prod-pagination">
    <div class="text-center pagination-arrows-set" >
	    <?php
			$querystringArray = Input::only(['is_featured','property_type','search','location','checkin','checkout','guests','latitude','longitude','city','state','country','postal_code','price_max','price_min','min_bedrooms','min_bedrooms','min_bathrooms','room_category','cmb_rating','amenities','keyword','user_id','property_working_status','price_per','no_of_employee','build_type','available_area']); 
	    ?>
        {{ $obj_pagination->appends($querystringArray)->render() }}
    </div>
</div> 
