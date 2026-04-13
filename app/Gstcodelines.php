<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gstcodelines extends Model{
   protected $table='f_gst_code_lines_t';
   public $foreignKey='gst_code_hdr_id';
   protected $primaryKey='gst_code_line_id';
   protected $fillable =['gst_code_hdr_id','tax_group_id','start_date','end_date'];
}
