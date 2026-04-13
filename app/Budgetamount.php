<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budgetamount extends Model
{
    protected $table = 'f_budget_amount_t';
    protected $primaryKey ='budget_amount_id';
    public $foreignKey='budget_line_id';
    protected $fillable =['account_period','monthly_amount'];
}
