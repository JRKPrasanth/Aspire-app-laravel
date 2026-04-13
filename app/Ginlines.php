<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ginlines extends Model
{
     protected $table='p_gin_lines_t';
      protected $primaryKey='p_gin_lines_id';
      protected $foreignKey='p_gin_hdr_id';
      protected $fillable =['p_gin_lines_id','p_gin_hdr_id','line_no','product_id','uom_code_id','qty','received_qty'];
}

