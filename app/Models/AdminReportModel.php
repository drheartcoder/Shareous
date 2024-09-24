<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminReportModel extends Model
{
    protected $table	= 'admin_report';
	protected $fillable = [
							'id',
							'report_id',
							'user_id',
							'username',
							'report_type',
							'fromdate',
							'todate',
							'report_user_type',
							'total_amount',
							'total_commission',
							'report_invoice',
							'status',
							'report_date',
							'created_at',
							'updated_at',
						  ];
}
