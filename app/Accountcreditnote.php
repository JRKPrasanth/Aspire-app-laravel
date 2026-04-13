<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountcreditnote extends Model
{
    protected $table='f_creditnote_hdr_t';
    protected $primaryKey='creditnote_hdr_id';
    protected $fillable=['credit_number','credit_count','credit_date','credit_status','invoice_number','invoice_date','customerid','ship_to_address_id'];

}
