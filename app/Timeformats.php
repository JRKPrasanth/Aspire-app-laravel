<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeformats extends Model
{
    protected $table='time_formats_tbl';
    protected $primaryKey='time_formats_id';
    protected $fillable=['php_format','js_format','display_format'];
}
