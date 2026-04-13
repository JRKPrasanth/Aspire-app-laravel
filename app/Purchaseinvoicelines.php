<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseinvoicelines extends Model
{
     protected $table = 'p_po_invoice_lines_t';
	 protected $primaryKey ='po_invoice_lines_id';
     public $foreignKey='po_invoice_id';
     protected $guarded = [];   
}
