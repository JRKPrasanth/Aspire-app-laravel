<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productionplan extends Model
{
     protected $table='w_productionplan_hdr_t';
     protected $primaryKey='productionplan_hdr_id';
	 protected $fillable =['plan_no','plan_date','reference_id','reference_no','product_id','uom_code_id','remarks','production_qty'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
