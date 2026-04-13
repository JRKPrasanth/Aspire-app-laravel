<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productpacktype extends Model
{
    protected $table="i_product_packs_types_t";
	protected $primaryKey = "product_packs_type_id";
    protected $fillable =['product_pack_type_name','description','created_by','updated_by','created_at','updated_at','organization_id','company_id','location_id','active'];
}
