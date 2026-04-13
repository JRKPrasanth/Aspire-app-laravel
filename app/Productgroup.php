<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productgroup extends Model
{
//dd("ff");
  protected $table='m_product_groups_t';
  protected $primaryKey='product_group_id';

  protected $fillable=['group_name','description','active','organization_id','created_by','created_at','last_updated_by','updated_at','company_id','location_id'];
}
