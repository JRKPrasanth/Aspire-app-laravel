<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dateformats extends Model
{
   protected $table='date_formats_tbl';
   protected $primaryKey ='date_formats_id';	
   protected $fillable=['php_format','javascript_format','status'];	
}
