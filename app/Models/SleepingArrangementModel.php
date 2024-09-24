<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SleepingArrangementModel extends Model
{
    protected $table	= 'sleeping_arrangement';
		protected $fillable = [
			'id',
			'value',
			'is_active'
		];
}
