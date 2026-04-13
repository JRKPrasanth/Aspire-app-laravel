<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountcreditnotelines extends Model
{
    protected $table='f_creditnote_lines_t';
    protected $primaryKey='creditnote_line_id';
    protected $foreignKey='creditnote_hdr_id';
    protected $fillable=['creditnote_hdr_id','line_no','product_id','uom_code_id','return_qty','unit_price','tax_group_id','tax_group_id','line_total','comments'];

}
