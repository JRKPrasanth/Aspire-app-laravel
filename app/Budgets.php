<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budgets extends Model
{
  protected $table = 'f_budget_hdr_t';
   protected $primaryKey ='budget_hdr_id';
   protected $fillable =['budget_name','budget_date','budget_description','current_budget_level','budget_check_level','parent_budget_id',
       'original_budget_id','budget_status','budget_year','budget_from_period_id','budget_to_period_id','budget_from_date','budget_to_date',
       'budget_currency_id','budget_line_total','actual_line_total','variance_total','company_id','active'];
}
