<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productaccountsettings extends Model
{
    protected $table='f_product_accountsetting_t';
      protected $primaryKey='product_accountsetting_id';
      protected $fillable =['product_accountsetting_id','product_group_id','product_category_id','product_subcategory_id','product_acccode_id','disc_acccode_id','control_acccode_id'];
}
