<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Taskcategory extends Model
{
    protected $table='a_task_category_t';
    protected $primaryKey='task_category_id';
	protected $fillable = [
        'task_category_id','department_id','category_name', 'description','active','created_by'
    ];
}
