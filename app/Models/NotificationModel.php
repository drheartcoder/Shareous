<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
	protected $table ='notifications';
	protected $fillable = [
									'sender_id',
									'receiver_id',
									'sender_type',
									'notification_text',
									'url',
									'is_read',
									'receiver_type',

								 ];
	
}
