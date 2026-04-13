<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesperson extends Model
{
  protected $table='s_salesperson_t';
  protected $primaryKey='salesperson_id';
  protected $fillable=[
'salesperson_id','salesperson_name','active','organization_id','employee_id','created_by'

];
  
}
