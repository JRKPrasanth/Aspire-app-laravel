<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journalentry extends Model
{
      protected $table='f_journal_entry_t';
      protected $primaryKey='journal_entry_id';
      protected $fillable =['journal_name','journal_date','journal_type','journal_reference','journal_status','debit_account_id','credit_account_id','debit_amount','credit_amount'];

}
