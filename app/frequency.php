<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class frequency extends Model
{
	protected $table='frequency_tbl';
    protected $primaryKey='frequency_id';
    protected $fillable=['frequency_name','description','created_at','updated_at'];
    }

