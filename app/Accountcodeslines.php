<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Accountcodeslines extends Model
{
     protected $table='f_account_codes_lines_t';
     protected $primaryKey='account_codes_line_id';
     public $foreignKey='account_codes_hdr_id';
     protected $fillable =['line_no','account_code','account_code_meaning','description','active'];
}
