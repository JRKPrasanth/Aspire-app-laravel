<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Primarydataupload extends Model
{
	public $table='sd_primarydataupload_t';
	public $primaryKey='primarydata_upload_id';
	public $foreignKey='';	
	public $fillable =['primarydata_upload_id',	'data_type',	'id',	'stockist_dist_name',	'invoice_no',	'invoice_date',	'sales_month',	'name_type',	'active',	'current_manager',	'cost_type',	'area',		'state',	'region',	'zone',	'c_year',	'f_year',	'month',	'month_y',	'till_month',	'product_name',	'sfg_product_name',	'packing_qty', 'kit',	'product_form_change',	'product_category',	 'division',	'weight',	'rate',	'unit_sold',	'asseesable_value',	'discount',	'igst',	'cgst',	'sgst',	'taxable_value',	'med_cost_bf_bulk_dis',	'cash_amount',	'total_invoice_amount',	'app_man_power',	'mrp',	'MPQ',	'free_unit',	'package_weight',	'batch_number',	'last_6montth',	'working_days',	'status',	'state_consolidated',	'hq',	'sales_free_unit',	'company_id',	'organization_id',	'created_by',	'created_at',	'last_updated_by',	'updated_at',	'location_id'];
	public function getTableColumns() 
	{
	    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
	}
}
