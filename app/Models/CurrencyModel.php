<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyModel extends Model
{
    protected $table = 'currency';
    protected $fillable = [
     							'currency',
     							'currency_code',
     							'html_code'
     						];
}
