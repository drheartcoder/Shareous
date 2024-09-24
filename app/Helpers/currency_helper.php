<?php
use App\Models\PropertyModel;
use App\Models\CurrencyModel;
use App\Models\CurrencyConversionModel;

function currency_conversion_api($from_currency, $to_currency)
{
	try {
		$from_Currency      = urlencode($from_currency);
		$to_Currency        = urlencode($to_currency);
		$query              =  "{$from_Currency}_{$to_Currency}";
		$conversion_api_url = trim("http://free.currencyconverterapi.com/api/v6/convert?q={$query}&compact=ultra");
		$json               = file_get_contents($conversion_api_url);
		$obj                = json_decode($json);
		$val                = floatval($obj->$query);
		return $val;
	} catch(\Exception $e) {
		return $e;
	}
}

// Currency Conversion
function currencyConverter($from_Currency, $to_Currency, $amount)
{
	$data = [];
	
	if ($from_Currency == null && $to_Currency == null && $amount == null) {
		return 0;
	} else {
		if ($from_Currency != $to_Currency) {
			$conversion_rate = Session::get('conversion_rates.'.$from_Currency.'_'.$to_Currency);
			// dd($conversion_rate);
		} else {
			$conversion_rate = 1;
		}
		// dd($conversion_rate);
		$converted_amount = $conversion_rate * $amount;
		return $converted_amount;
	}
}

/*
| Function  : Convert to session currency or else in inr 
| Author    : Deepak Arvind Salunke
| Date      : 04/06/2018
| Output    : Return session currency or else in inr 
*/
function session_currency($amount = null, $property_id = null)
{
	if($amount == null) {
		$amount = 0;
	}

	$curreny_symbol = '₹';
	$new_amount     = $curreny_symbol.$amount;
	$obj_property = PropertyModel::where('id', $property_id)->select('currency_code', 'currency')->first();
	if ($obj_property) {
		$arr_property            = $obj_property->toArray();
		$property_currency       = $arr_property['currency_code'];
		$property_curreny_symbol = $arr_property['currency'];
		$session_currency = (null != Session::get('get_currency') && !empty(Session::get('get_currency'))) ? Session::get('get_currency') : 'INR';
	
        $conversion_rates = Session::get('conversion_rates');
		try {
			if ($property_currency != $session_currency) {
				$amount = $amount * $conversion_rates[$property_currency.'_'.$session_currency];
			} else {
				$curreny_symbol = $property_curreny_symbol;
			}
			// return $amount;
			$obj_currency = CurrencyModel::where('currency_code',Session::get('get_currency'))->first();
			if ($obj_currency) {
				$arr_currency   = $obj_currency->toArray();
				$curreny_symbol = $arr_currency['currency'];
			}
			$new_amount = $curreny_symbol.' '.number_format($amount, 2, '.', ',');
		} catch(\Exception $e) {
			$new_amount = $property_curreny_symbol.' '.number_format($amount, 2, '.', ',');
		}
	}
	return $new_amount;
}

function store_currency_session($currency_code = 'INR')
{
	$conversion_rate_arr = [];
    $conversion_rates_obj = CurrencyConversionModel::select('from_currency_id','to_currency_id','conversion_rate')
                            ->with(['from_currency_detail' =>function($q_fc) {
                            	$q_fc->select('id', 'currency_code');
                            },'to_currency_detail' => function($q_tc) {
                            	$q_tc->select('id', 'currency_code');
                            }])
                            ->whereHas('to_currency_detail', function($q_if_fc) use ($currency_code) {
                            	$q_if_fc->where('currency_code', $currency_code);
                            })
                            ->get();

	$conversion_rates_arr = $conversion_rates_obj->toArray();

	if (isset($conversion_rates_arr) && count($conversion_rates_arr) > 0) {
	    foreach ($conversion_rates_arr as $conversion_rates_data) {
	        $currency_str = $conversion_rates_data['from_currency_detail']['currency_code'].'_'.$conversion_rates_data['to_currency_detail']['currency_code'];
	        $conversion_rate = $conversion_rates_data['conversion_rate'];
	        $conversion_rate_arr[$currency_str] = $conversion_rate;
	    }
	}

	Session::put('conversion_rates', $conversion_rate_arr);
	Session::set('conversion_rates', $conversion_rate_arr);
	// dump(Session::get('conversion_rates'));
	return 1;
}

// Currency Conversion for API
function currencyConverterAPI($from_Currency, $to_Currency, $amount)
{
	$data = [];
	$conversion_rate = 1;

	if ($from_Currency == null && $to_Currency == null && $amount == null) {
		return 0;
	} else {
		if ($from_Currency != $to_Currency) {

		    $conversion_rates_obj = CurrencyConversionModel::select('from_currency_id', 'to_currency_id', 'conversion_rate')
		                            ->with(['from_currency_detail' =>function($q_fc) {
		                            	$q_fc->select('id', 'currency_code');
		                            }, 'to_currency_detail' => function($q_tc) {
		                               	$q_tc->select('id', 'currency_code');
		                            }])
		                            ->whereHas('to_currency_detail', function($q_if_tc) use ($to_Currency) {
		                            	$q_if_tc->where('currency_code', $to_Currency);
		                            })
		                            ->whereHas('from_currency_detail', function($q_if_fc) use ($from_Currency) {
		                            	$q_if_fc->where('currency_code', $from_Currency);
		                            })
		                        	->first();

		    if($conversion_rates_obj) {
		    	$conversion_rates_arr = $conversion_rates_obj->toArray();
		    }
			
			if (isset($conversion_rates_arr) && count($conversion_rates_arr) > 0) {
				$conversion_rate = $conversion_rates_arr['conversion_rate'];
			}
		}
		return $conversion_rate * $amount;
	}
}

function currency_list()
{
	$currency_arr = [];
	$currency_obj = CurrencyModel::select('id','currency','currency_code')->get();

	if (isset($currency_obj) && count($currency_obj) > 0) {
		$currency_arr = $currency_obj->toArray();
	}
	return $currency_arr;
}

function get_currency_detail($currency_code = '')
{
	$currency_arr = [];
	if($currency_code != '') {
		$currency_arr = CurrencyModel::select('id','currency','currency_code')->where('currency_code','=',$currency_code)->first();
	}
	
	return $currency_arr;
}