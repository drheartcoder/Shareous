<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingModel extends Model
{
    protected $table	= 'booking';
	protected $fillable = [
							'property_id',
							'booking_id',
							'property_owner_id',
							'property_booked_by',
							'payment_type',
							'coupon_code_id',
							'no_of_guest',
							'check_in_date',
							'check_out_date',
							'no_of_days',
							'property_amount',
							'service_fee',
							'service_fee_gst_amount',
							'service_fee_percentage',
							'service_fee_gst_percentage',
							'admin_commission',
							'coupen_code_amount',
							'total_amount',
							'booking_status',
							'status_by',
							'host_accepted_date',
							'host_rejected_date',
							'cancelled_date',
							'reject_reason',
							'gst_amount',
							'gst_percentage',
							'total_night_price',
							'property_type_slug',
							'price_per_sqft',
							'selected_no_of_slots',
							'price_per_office',
							'selected_of_employee',
							'selected_of_room',
							'selected_of_desk',
							'selected_of_cubicles',
							'room_amount',
							'desk_amount',
							'cubicles_amount',
							'cancelled_by',
							'cancelled_reason'
						];

	public function property_details()
    {
		return $this->hasOne('App\Models\PropertyModel','id','property_id')->select('id','user_id','property_name','currency','address','city','state','country','number_of_guest','number_of_bedrooms','number_of_bathrooms','number_of_beds','price_per_night','currency_code','property_type_id','description','property_name_slug','price_per_night','number_of_guest','postal_code','property_working_status','property_area','total_plot_area','total_build_area','custom_type','build_type','management','good_storage','admin_area','price_per_sqft','price_per_office','price_per','no_of_slots','available_no_of_slots','available_no_of_employee','nearest_railway_station','nearest_national_highway','nearest_bus_stop','working_hours','working_days','property_remark','employee','no_of_employee','room','no_of_room','desk','no_of_desk','cubicles','no_of_cubicles','room_price','desk_price','cubicles_price');
    }

    public function user_details()
    {
		return $this->hasOne('App\Models\UserModel','id','property_owner_id')->select('id','user_name','user_type','profile_image','address','notification_type','first_name','last_name','email','mobile_number','wallet_amount','gender','birth_date','notification_by_email','notification_by_sms','notification_by_push');
    }

    public function booking_by_user_details()
    {
		return $this->hasOne('App\Models\UserModel','id','property_booked_by')->select('id','user_name','user_type','profile_image','notification_type','first_name','last_name', 'display_name', 'email','mobile_number','address','gender','birth_date','wallet_amount','notification_by_email','notification_by_sms','notification_by_push');
    }

    public function property_owner()
    {
		return $this->hasOne('App\Models\UserModel','id','property_owner_id')->select('id','user_name','user_type','profile_image','address','notification_type','first_name','last_name','email','mobile_number','wallet_amount','gender','birth_date','notification_by_email','notification_by_sms','notification_by_push');
    }

    public function transaction_details()
    {
		return $this->hasOne('App\Models\TransactionModel','booking_id','id');
    }

    public function review_rating_details()
    {
		return $this->hasOne('App\Models\ReviewRatingModel','rating_user_id','id');
    }
}
