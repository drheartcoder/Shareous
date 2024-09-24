<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImagesModel extends Model
{
     protected $table = 'property_images';
     protected $fillable = ['property_id','image']; 
}
