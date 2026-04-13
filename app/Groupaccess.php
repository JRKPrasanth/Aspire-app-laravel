<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groupaccess extends Model
{
  protected $table='a_m_group_t';
    protected $primaryKey='group_id';
    protected $fillable=['group_id','group_name','description'];
}
