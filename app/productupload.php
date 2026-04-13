<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productupload extends Model
{
    protected $table="i_productupload_t";
	protected $primaryKey="product_upload_id";
	public $foreignKey='';	
	protected $fillable=['product_group_name','control_account','product_category_name','product_subcategory_name','product_type_name','product_code','concatenated_product','product_variant_name','product_packtype_name','product_pack_name','product_alternate_name','active','company_name','company_code','created_at','updated_at','batch_name','batch_date','batch_status','batch_comments','primary_uom_name','trx_uom_name','commodity_code','hsn_code','default_hsn_code','subinventory_name','sublocator_name','locator_control','account_code_name','min_stock_level1','min_stock_level2','min_stock_level3','max_order_qty','re_order_level','tax_credit','qc_type','mpq_qty','expiry_days','packing_cotton_box','subinventory_name_ref','disc_account_code_name','product_name'];	
}
