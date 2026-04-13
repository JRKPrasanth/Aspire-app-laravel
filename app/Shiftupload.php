<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shiftupload extends Model
{
    protected $table="shift_timing";
   	protected $primaryKey="shift_id";
	protected $fillable = ['employee_id','shift_type'];
}
