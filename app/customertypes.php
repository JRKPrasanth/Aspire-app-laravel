<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customertypes extends Model
{
    	protected $table = 'm_customer_types_t';
	protected $primaryKey = 'customer_type_id';
protected $fillable=['customer_type','description','account_id','gst_required','created_by','active'];
	

}
