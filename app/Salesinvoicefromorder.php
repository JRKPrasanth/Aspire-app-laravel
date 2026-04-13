<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class salesinvoicefromorder extends Sximo  {
	
	protected $table = 's_salesorder_hdr_t';
	protected $primaryKey = 'sales_hdr_id';
	

}
