<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class partialday extends Model
{
    protected $table='hr_partial_day';
      protected $primaryKey='id';
      protected $fillable =['id','partial_date','start_time','end_time','description','created_at','updated_at','created_by','last_updated_by','active','company_id','location_id','organization_id'];
}
