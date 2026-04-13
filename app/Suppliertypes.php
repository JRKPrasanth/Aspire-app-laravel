<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suppliertypes extends Model
{
       protected $table='m_suppliertypes_t';
      protected $primaryKey='suppliertype_id';
      protected $fillable =['suppliertype_name','gst_required','description','remarks','last_updated_by','active'];
}
