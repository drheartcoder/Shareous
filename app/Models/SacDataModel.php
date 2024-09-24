<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SacDataModel extends Model
{
    protected $table	= 'sac_data';
	protected $fillable = ['name','sac'];
}
