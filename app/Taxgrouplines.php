<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxgrouplines extends Model
{
       protected $table = 'm_tax_group_lines_t';
        protected $primaryKey ='tax_group_line_id';
        public $foreignKey='tax_group_id';
        protected $fillable =['tax_group_id','tax_category','tax_code_name','tax_rate','active','input_tax_account_id','output_tax_account_id'];

}
