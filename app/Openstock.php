<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Openstock extends Model
{
    protected $table="i_product_openstock_upload_t";
    protected $primaryKey="product_openstock_upload_id";
	protected $fillable = [
        'product_openstock_upload_id','item_name','subinventory_name','batch_number', 'locator_code','qty','batch_name','batch_date','batch_status','batch_comments'
    ];
}
