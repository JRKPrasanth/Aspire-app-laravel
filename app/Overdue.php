<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overdue extends Model
{


    protected $table='over_due_t';
    protected $primaryKey='over_due_id';
    protected $foreignKey='customer_id';
    protected $fillable=['customer_id','before_days','before_dis','after_days','after_int','overdue_date','calculation_id','created_at','updated_at','created_by','last_updated_by'];
}
