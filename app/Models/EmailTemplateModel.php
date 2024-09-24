<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateModel extends Model
{
		protected $table	= 'email_template';
		protected $fillable = [ 
		'template_name',
		'template_from',
		'template_variables',
		'template_from_mail',
		];
}
