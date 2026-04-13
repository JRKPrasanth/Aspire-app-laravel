<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shiftdetail extends Model
{
    protected $table="shift_timing";
   	protected $primaryKey="shift_id";
	protected $fillable = ['shift_name','start_time','end_time'];
}
