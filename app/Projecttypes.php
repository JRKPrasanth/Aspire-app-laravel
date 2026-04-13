<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projecttypes extends Model
{
    protected $table='m_project_type_t';
    protected $primaryKey='project_type_id';
	protected $fillable =['project_type_id','project_type_name','description','active'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
