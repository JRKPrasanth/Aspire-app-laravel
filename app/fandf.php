<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fandf extends Model
{
   protected $table='hr_ff_t';
    protected $primaryKey='hr_ff_id';
	protected  $fillable =['hr_ff_id','emp_id','imp_amount','exp_amount','sal_amount','balance_amount','addition','deduction','paid_amount','remarks','status'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}