<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soquotecopy extends Model
{
    //	
	public $table='s_quote_hdr_t';
	public $primaryKey='quote_hdr_id';
	public $fillable =['quote_no','quote_name','quote_date','order_type_id','pricelist'];

}
