<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseorderlines extends Model
{
    public $table='p_po_lines_t';
	public $primaryKey='po_line_id';
	public $foreignKey='po_hdr_id';
	protected $guarded = [];  

}
