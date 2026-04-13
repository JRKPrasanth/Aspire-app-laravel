<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class contraentry extends Model{

    protected $table='f_account_contraentry_t';
    protected $primaryKey='contraentry_id';
    protected $fillable =['contraentry_no','contra_date','from_account_id','to_account_id','amount','remarks','reference_no','to_account_no','from_account_no','from_bank_id','to_bank_id','payment_type_id','cheque_no','favouring_name'];
}
