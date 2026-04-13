<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productvariant extends Model
{
    protected $table="m_product_variants_t";
	protected $primaryKey="product_variant_id";
	protected $fillable=['product_variant_name','variant_description','active','created_by','created_at','last_updated_by','updated_at','variant_type','variant_code','company_id','location_id','organization_id'];
}
