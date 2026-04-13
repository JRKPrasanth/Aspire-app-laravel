<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budgetlines extends Model
{
  protected $table = 'f_budget_lines_t';
    protected $primaryKey ='budget_line_id';
    public $foreignKey='budget_hdr_id';
    protected $fillable =['line_no','line_account1','line_account2','line_account3','line_account4','budget_amount'];
}
