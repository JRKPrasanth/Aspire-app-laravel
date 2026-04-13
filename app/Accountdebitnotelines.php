<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountdebitnotelines extends Model
{
   protected $table='f_debitnote_lines_t';
    protected $primaryKey='debitnote_line_id';
    protected $foreignKey='debitnote_hdr_id';
    protected $fillable=['debitnote_hdr_id','line_no','product_id','uom_code_id','reject_qty','unit_price'
  ,'tax_group_id','tax_group_id','line_total','comments','line_subtotal'];
}
