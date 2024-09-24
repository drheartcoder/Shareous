<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSettingsModel extends Model
{
	
		protected $table	= 'site_settings';
		protected $fillable = [
			'site_name',
			'site_address',
			'site_contact_number',
			'site_status',
			'meta_desc',
			'meta_keyword',
			'admin_commission',
			'gst',
			'site_email_address',
			'site_logo',
			'fb_url',
			'twitter_url',
			'google_plus_url',
			'youtube_url',
			'rss_feed_url',
			'instagram_url',
			'play_store_url',
			'app_store_url',
			'linkedin_url',
			'fb_client_id',
			'fb_client_secret',
			'fb_status',
			'google_client_id',
			'google_client_secret',
			'google_api_credential',
			'google_status',
			'twitter_client_id',
			'twitter_client_secret',
			'twitter_status',
			'lon',
			'lat'
		];
	
}
