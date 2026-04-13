<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tasksubcategory extends Model
{
    protected $table='a_task_subcategory_t';
	protected $primaryKey='task_subcategory_id';
	protected $fillable =['department_line_id','task_category_id','subcategory_name','description','active','created_by','created_at','last_updated_by','updated_at','organization_id','location_id','company_id'];
}
