<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqModel extends Model
{
    protected $table	= 'faq';
	protected $fillable = [
			'question',
			'answer',
			'status'
		];
}
