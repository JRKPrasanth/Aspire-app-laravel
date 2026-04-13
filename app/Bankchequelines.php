<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bankchequelines extends Model
{
  public $table='f_bank_cheque_lines_t';
	public $primaryKey='bank_cheque_line_id';
	public $foreignKey='bank_cheque_hdr_id';
	public $fillable =['cheque_no','check_book_no','start_date','end_date'];
}
