<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bankcheque extends Model
{
  protected $table='f_bank_cheque_hdr_t';
      protected $primaryKey='bank_cheque_hdr_id';
      protected $fillable =['bank_cheque_hdr_id','bank_id','bank_branch_id','account_number'];
}
