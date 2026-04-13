<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchaseinvoice extends Model
{
     protected $table = 'p_po_invoice_hdr_t';
 	 protected $primaryKey ='po_invoice_id';
     protected $guarded = [];   
}
