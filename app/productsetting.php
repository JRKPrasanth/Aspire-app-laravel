<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productsetting extends Model
{
     protected $table='m_product_setting_t';
  protected $primaryKey='product_setting_id';

  protected $fillable=['module_name','product_group_id','company_id','organization_id','created_by','created_at','last_updated_by','updated_at'];
}
