<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class compoffapplication extends Model
{
    protected $table='hr_compoff_t';
    protected $primaryKey='compoff_id';
    protected $fillable=['employee_id','start_date','end_date','no_of_days','alloted_days','leave_type','leave_reason','leave_status'
						,'forwarded_id','approval_reason','approvel_comments','organization_id','leave_status','od_start_date','od_end_date'
						 ,'od_no_of_days','od_alloted_days'];
}
