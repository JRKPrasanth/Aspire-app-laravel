<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formsixteenupld extends Model
{
   protected $table='f_formsixteen_upload_t';
      protected $primaryKey='formsixteenupld_hdr_id';
      protected $fillable =['choosefile','emp_id','acc_year'];
}
