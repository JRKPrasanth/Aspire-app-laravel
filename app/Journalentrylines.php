<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journalentrylines extends Model
{
 protected $table='f_journal_entry_lines_t';
   public $foreignKey='journal_entry_id';
   protected $primaryKey='f_journal_entry_line_id';
   protected $fillable =['line_no','debit_account_id','credit_account_id','debit_amount','credit_amount'];
}
