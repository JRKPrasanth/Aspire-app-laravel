<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class machinefiles extends Model
{
    protected $table='m_machine_file_t';
    protected $primaryKey='machine_file_id';
    protected $fillable=['department_id','machine_id','file_name','upload_file','created_by'];
     public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }

}
