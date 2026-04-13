<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table ='m_products_t';
	protected $primaryKey ='product_id';
	protected $fillable=['product_code','product_subcategory_id','product_group_id','product_category_id','product_alternate_name','active','primary_uom_id','trx_uom_id','min_order_qty','max_order_qty','re-order_level','serial_control','serial_prefix','hsn_code','mpq_qty','expiry_days','defalut_hsn_code','subinventory_id','locator_id','tax_credit','remarks','active','disc_account_code','control_account_id','qc_check','batch_no','choosefile','barcode_number','product_classification','group_classification','gross_weight','net_weight'];
}
