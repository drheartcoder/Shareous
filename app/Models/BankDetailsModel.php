<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetailsModel extends Model
{
    protected $table ='user_bank_account';
	protected $fillable = [
							'user_id', 
							'bank_name', 
							'account_number', 
							'ifsc_code', 
							'account_type',
							'selected'
							];
}
