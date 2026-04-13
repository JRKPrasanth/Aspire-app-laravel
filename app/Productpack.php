<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productpack extends Model
{
    protected $table="i_product_packs";
	protected $primaryKey = "packing_id";
    protected $fillable =['pack_name','description','created_by','created_at','last_updated_by','updated_at','remarks','organization_id','company_id','location_id','active'];
}
