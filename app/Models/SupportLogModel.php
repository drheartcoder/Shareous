<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportLogModel extends Model
{
    protected $table	= 'support_log';
		protected $fillable = [
			'query_id',
			'support_user_id',
			'support_level'			
		];
}
