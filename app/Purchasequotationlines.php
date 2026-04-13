<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchasequotationlines extends Model
{
	  public $table='p_quotation_lines_t';
	  public $primaryKey='quotation_line_id';
	  public $foreignKey='quotation_hdr_id';
	  protected $guarded = []; 

}
