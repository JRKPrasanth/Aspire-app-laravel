<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxcategory extends Model
{
      protected $table='f_tax_category_t';
      protected $primaryKey='tax_category_id';
      protected $fillable =['tax_category_name','tax_location_type','description','active'];

}
