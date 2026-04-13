<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machineequipmentshdr extends Model
{
    public $table='w_machine_equipments_hdr_t';
	public $primaryKey='machine_equipments_hdr_id';
	public $foreignKey='';	
	public $fillable =['machine_id','assembly_product','start_date','end_date','remarks','organization_id'];
	
	
	
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
}
