<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileCountryCodeModel extends Model
{
    protected $table    = "mobile_country_code";
    protected $fillable = [
                            'iso',
                            'name',
                            'nicename',
                            'iso3',
                            'numcode',
                            'phonecode'
                        ];
}
