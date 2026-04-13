<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productionstoremove extends Model
{
    protected $table ='w_storemove_t';
	protected $primaryKey ='w_storemove_id';
	protected $fillable =['reference_source','reference_source_id','rejection_type','product_id','uom_code_id','qty','subinventory_id','sublocator_id'];
}
