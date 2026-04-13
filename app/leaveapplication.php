<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leaveapplication extends Model
{
    protected $table='hr_leaves_t';
    protected $primaryKey='leave_id';
    protected $fillable=['employee_id','start_date','end_date','no_of_days','alloted_days','leave_type','leave_reason','leave_status'
						,'forwarded_id','approval_reason','approvel_comments','organization_id','leave_status','od_start_date','od_end_date'
						 ,'od_no_of_days','od_alloted_days','start_date_time','end_date_time','no_of_hrs','alloted_hrs'];
}
