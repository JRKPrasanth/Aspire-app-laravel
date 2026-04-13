<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imprest extends Model
{
    protected $table='hr_imprest_tbl';
      protected $primaryKey='imprest_id';
      protected $fillable =['employee_id','imprest_date','reason','amount','active','status','created_by','created_at','last_updated_by','updated_at','location_id','organization_id','company_id'];
}
