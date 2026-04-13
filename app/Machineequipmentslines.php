<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machineequipmentslines extends Model
{
    public $table='w_machine_equipments_lines_t';
	public $primaryKey='machine_equipments_lines_id';
	public $foreignKey='machine_equipments_hdr_id';	
	public $fillable =['line_no','range_from','range_to','hours','comments'];
	
	
	
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
}
