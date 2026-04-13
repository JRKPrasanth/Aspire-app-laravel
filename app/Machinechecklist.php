<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machinechecklist extends Model
{
    
    protected $table='m_checklist_hrd_tbl';
      protected $primaryKey='checklist_hrd_id';
      protected $fillable =['department_id','machine_id','frequency_id','created_by'];
	
	
}