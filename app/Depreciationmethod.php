<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depreciationmethod extends Model
{
     protected $table='f_depreciation_method_t';
      protected $primaryKey='depreciation_method_id';
      protected $fillable =['depreciation_method_id','depreciation_method_name','asset_type_id','salvage_value','depreciation_percentage','depreciation_value','po_hdr_id','product_id','unit_price'];
}
