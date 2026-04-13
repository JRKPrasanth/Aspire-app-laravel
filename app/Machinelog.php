<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machinelog extends Model
{
    protected $table = 'b_machine_log_t';
	protected $primaryKey = 'id';
	protected $fillable =['process_dept','machine_id','date','product_id','quantity','damages','running_hours','organization_id','remarks','batch_number'];
}