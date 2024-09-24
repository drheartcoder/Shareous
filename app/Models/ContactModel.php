<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    protected $table	= 'contact_enquiry';
	protected $fillable = [
								'name',
								'email_id',
								'contact',
								'subject',
								'message',
								'is_read_status',
								'created_at',
						  ];
}
