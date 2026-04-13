<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pricelistupload extends Model
{
	protected $table="i_pricelist_upload_t";
	protected $primaryKey="pricelist_upload_id";
	public $foreignKey='';	
	protected $fillable=['pricelist_name','pricelist_type','start_date','end_date','product_name','unit_price','std_price','active','organization_id','company_id','created_at','updated_at','batch_name','batch_date','batch_status','batch_comments','batch_number'];	
}
