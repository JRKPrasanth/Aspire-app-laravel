<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drawing extends Model
{
	protected $table='drawing_tbl';
    protected $primaryKey='drawing_id';
    protected $fillable=['drawing_id','department','file','document'];
    }