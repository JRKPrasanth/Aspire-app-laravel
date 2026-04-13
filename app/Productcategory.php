<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Productcategory extends Model
{
    protected $table='m_product_category_t';
    protected $primaryKey='product_category_id';
	protected $fillable = [
        'product_category_id','product_group_id','category_name', 'description','active','created_by'
    ];
}
