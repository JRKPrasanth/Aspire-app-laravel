<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialreturnlines extends Model
{
       protected $table = 'w_material_return_lines';
        protected $primaryKey ='material_lines_id';
        public $foreignKey='material_return_hdr_id';
        protected $fillable =['material_return_hdr_id','product_id','uom_code_id','qty','production_qty','scrap_qty','batchno','subinventory_id','sublocator_id','comments','created_by','company_id','location_id','organization_id','created_at','updated_at','last_updated_by'];

	
}
