<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gstcode extends Model
{
    protected $table='f_gst_code_hdr_t';
      protected $primaryKey='gst_code_hdr_id';
      protected $fillable =['classification_code','classification_name','description'];
}
