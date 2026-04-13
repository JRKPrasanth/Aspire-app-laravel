<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secondarydataupload extends Model
{
	public $table='sd_secondarydataupload_t';
	public $primaryKey='secondarydate_upload_id';
	public $foreignKey='';	
	public $fillable =['secondarydate_upload_id',	'data_type',	'emp_id',	'field_force_name',	'position',	'emp_with_level',	'ff_status',	'hq_name',	'current_reporting_manager',	'stockist_dist_name',	'stockist_code',	'type_d_s',	'type_sales_target',	'area',	'state',	'region',	'zone',	'c_year',	'f_year',	'month',	'month_y',	'till_month',	'product_name',	'sfg_product_name',	'product_category',	'kit',	'product_form_change',	'product_division',	'hq_division',	'division',	'branc_name',	'product_code',	'package',	'sales_kgs_lts',	'price_per_unit',	'opening_stock',	'opening_stock_value',	'purchase_stock',	'purchase_stock_value',	'sales_unit',	'sales_value',	'sales_return_stock',	'sales_return_stock_value',	'closing_stock',	'closing_stock_value',	'free_stock',	'free_stock_value',	'purchase_return_stock',	'purchse_retrun_stock_value',	'transit_stock',	'transit_stock_valu',	'expired_stock',	'expired_stock_value',	'damaged_stock',	'damaged_stock_value',	'status',	'stockist_peroid_validity',	'approved_date',	'user_name',	'date_of_upon',	'hq_old_name',	'cur_mgr_status',	'local_area_city',	'company_id',	'organization_id',	'created_by',	'created_at',	'last_updated_by',	'updated_at',	'location_id'];
	public function getTableColumns() 
	{
	    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
	}
}
