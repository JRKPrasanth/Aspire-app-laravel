<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
   protected $table='m_projects_t';
    protected $primaryKey='project_id';
	protected $fillable =['project_id','project_name','project_type_id','description','customer_id','active','organization_id','start_date','end_date'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
