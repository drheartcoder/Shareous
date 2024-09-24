<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyConversionModel extends Model
{
    protected $table = 'currency_conversion';
    protected $fillable = [
     							'from_currency_id',
     							'to_currency_id',
     							'conversion_rate'
     						];

    public function from_currency_detail()
    {
        return $this->hasOne('App\Models\CurrencyModel','id','from_currency_id')->select('id', 'currency', 'currency_code');
    }

    public function to_currency_detail()
    {
        return $this->hasOne('App\Models\CurrencyModel','id','to_currency_id')->select('id', 'currency', 'currency_code');
    }
}
