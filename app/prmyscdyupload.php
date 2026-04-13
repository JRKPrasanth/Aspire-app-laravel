<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class prmyscdyupload extends Model
{
	public $table='sd_prmyscdyupload_t';
	public $primaryKey='prmyscdy_upload_id';
	public $foreignKey='';	
	public $fillable =['prmyscdy_upload_id',	'type_of_data',	'currrent_reporting_MGR',	'state',	'region',	'zone',	'c_year',	'f_year',	'month',	'month_y',	'product_pack_name',	'product_name',	'pack_size',	'product_kit',	'product_division',	'HQ_name',	'area',	'type_d_s',	'name_of_person_party',	'sales_kgs_lts',	'active_inactive',	'employee_name',	'product_category',	'type',	'purchase_stock',	'purchase_stock_value',	'sales_unit',	'sales_value',	'closing_stock',	'closing_stock_value',	'free_stock',	'free_stock_value',	'sales_free_units',	'rate',	'concatenate',	'extra_offer',	'camp_product',	'note',	'free_value_classical_sale_value',  'company_id',	'organization_id',	'created_by',	'created_at',	'last_updated_by',	'updated_at',	'location_id'];
	public function getTableColumns() 
	{
	    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
	}
}
