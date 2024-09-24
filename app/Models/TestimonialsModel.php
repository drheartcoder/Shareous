<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialsModel extends Model
{
	protected $table    = 'testimonials';

    protected $fillable = [                          
                            'title',
                            'message',
                            'image',
                            'status',
                            'created_at',
                            'updated_at'
                         ];

   
}
