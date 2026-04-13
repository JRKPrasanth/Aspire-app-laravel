<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class sinvoiceheader extends model  {
	
	protected $table = 's_invoice_hdr_t';
	protected $primaryKey = 'so_invoice_hdr_id';
       // protected $fillable = [ 'ar_invoice_hdr_id', 'invoice_number', 'invoice_type', 'invoice_date', 'remarks'];

	

}
