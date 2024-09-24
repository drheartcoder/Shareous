<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyModel extends Model
{
     protected $table = 'property';
     protected $fillable = [
     							'user_id',
     							'category_id',
	     						'property_type_id',
	     						'property_name',
	     						'description',
	     						'address',
	     						'city',
                                'state',
	     						'country',
	     						'postal_code',
	     						'property_latitude',
	     						'property_longitude',
	     						'number_of_guest',
	     						'number_of_bedrooms',
	     						'number_of_bathrooms',
	     						'number_of_beds',
	     						'price_per_night',
	     						'service_charge',
	     						'admin_status',
	     						'admin_comment',
                                'currency',
                                'currency_code',
	     						'property_status',
                                'meta_keyword',
                                'meta_title',
                                'meta_description',
                                'property_name_slug',
                                'property_working_status',
                                'property_area',
                                'total_plot_area',
                                'total_build_area',
                                'custom_type',
                                'build_type',
                                'management',
                                'good_storage',
                                'admin_area',
                                'price_per_sqft',
                                'price_per_office',
                                'price_per',
                                'no_of_slots',
                                'available_no_of_slots',
                                'employee',
                                'no_of_employee',
                                'room',
                                'no_of_room',
                                'room_price',
                                'desk',
                                'no_of_desk',
                                'desk_price',
                                'cubicles',
                                'no_of_cubicles',
                                'cubicles_price',
                                'available_no_of_employee',
                                'nearest_railway_station',
                                'nearest_national_highway',
                                'nearest_bus_stop',
                                'working_hours',
                                'working_days',
                                'property_remark',
                                'is_featured'
     						]; 

    public function user_details()
    {
        return $this->hasOne('App\Models\UserModel','id','user_id')->select('id','first_name','display_name','user_name','last_name','email','mobile_number','address', 'gstin');
    }
    public function property_type()
    {
        return $this->hasOne('App\Models\PropertytypeModel','id','property_type_id')->select('id','name');
    }
    public function category()
    {
        return $this->hasOne('App\Models\CategoryModel','id','category_id')->select('id','category_name','slug');
    }
    public function property_rules()
    {
    	return $this->hasMany('App\Models\PropertyRulesModel','property_id','id')->select('id','property_id','rules');
    }
    public function property_images()
    {
    	return $this->hasMany('App\Models\PropertyImagesModel','property_id','id')->select('id','property_id','image');
    }
    public function property_unavailability()
    {
    	return $this->hasMany('App\Models\PropertyUnavailabilityModel','property_id','id')->select('id','property_id','type','from_date','to_date');
    }
    public function property_aminities()
    {
    	return $this->hasMany('App\Models\PropertyAminitiesModel','property_id','id');
    }
    public function property_bed_arrangment()
    {
        return $this->hasMany('App\Models\PropertyBedsArrangmentModel','property_id','id');
    }
    public function favourite_property()
    {
        return $this->hasMany('App\Models\FavouritePropertyModel','property_id','id');
    }
    public function review_details()
    {
        return $this->hasMany('App\Models\ReviewRatingModel','property_id','id');
    }
    public function guest_review_details()
    {
        return $this->hasOne('App\Models\ReviewRatingModel','property_id','id');
    }
    public function all_amenities()
    {
        return $this->hasMany('App\Models\AmenitiesModel','propertytype_id','property_type_id');
    }
}
