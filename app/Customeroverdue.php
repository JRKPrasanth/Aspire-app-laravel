<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customeroverdue extends Model
{
    protected $table='over_due_t';
    protected $primaryKey='over_due_id';
    protected $fillable=['over_due_id','customer_id','before_day','before_dis','after_day','after_int','calculation_id','overdue_date'];
}
