<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxcode extends Model
{
     protected $table='f_tax_code_t';
      protected $primaryKey='tax_code_id';
      protected $fillable =['tax_category_id','tax_code_name','description','tax_type','tax_rate','tax_precision','tax_amount','active','company_id','location_id','organization_id','created_by','last_updated_by'];

}
