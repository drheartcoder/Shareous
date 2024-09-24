<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplateModel extends Model
{
    protected $table	= 'notification_template';
	protected $fillable = [
							'template_name',
							'template_subject',	
							'template_variables',
							'template_text',
						  ];



}
