<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeedepartmentlines extends Model
{
  public $table='m_department_lines_t';
	public $primaryKey='department_line_id';
	public $foreignKey='department_id';
	public $fillable =['sub_department_code','sub_department_name','branch_name','branch_address','ifsc_code',
            'account_type','name_in_account','nickname_in_acoount','account_number','start_date','end_date','active','comments'];
}
