<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producttype extends Model
{
     protected $table="m_product_type_t";
    protected $primaryKey = "product_type_id";
    protected $fillable =['product_type','remarks','active','created_by','company_id','location_id','organization_id','created_at','updated_at','last_updated_by'];
}
