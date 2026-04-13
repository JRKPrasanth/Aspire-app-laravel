<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productsubcategory extends Model
{
    protected $table='m_product_subcategory_t';
	protected $primaryKey='product_subcategory_id';
	protected $fillable =['product_group_id','product_category_id','subcategory_name','description','active','created_by','created_at','last_updated_by','updated_at','organization_id','location_id','company_id'];
}
