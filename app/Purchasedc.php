<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchasedc extends Model
{
      protected $table="p_dc_hdr_t";
      protected $primaryKey="dc_hdr_id";
      protected $fillable = [
        'dc_hdr_id','dc_number','dc_date', 'dc_status','grn_id','supplier_id','subcontract_supplier_id'
    ];
}
