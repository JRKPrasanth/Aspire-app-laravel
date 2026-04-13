<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Misspunch extends Model
{
    protected $table='misspunch_tbl';
    protected $primaryKey='miss_id';
    protected $fillable = ['employee_id','in_time','out_time', 'date','reason','status','forwarded_id','last_updated_by'];
}
