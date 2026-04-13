<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountclass extends Model
{
    protected $table='f_account_class_t';
      protected $primaryKey='account_class_id';
      protected $fillable =['account_class_name','main_account_code','description','code_startwith','created_by','last_updated_by','active','company_id','location_id','organization_id'];
}