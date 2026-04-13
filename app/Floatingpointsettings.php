<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Floatingpointsettings extends Model
{
    protected $table = 'settings_tbl';
	protected $primaryKey ='settings_tbl_id';
	protected $fillable = ['decimal_points'];
}
