<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountdebitnote extends Model
{
      protected $table='f_debitnote_hdr_t';
    protected $primaryKey='debitnote_hdr_id';
    protected $fillable=['debit_number','debit_count','debit_date','invoice_number','invoice_date','supplier_id','po_number','debit_status'];

}
