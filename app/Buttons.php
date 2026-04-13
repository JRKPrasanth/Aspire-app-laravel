<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Buttons extends model  {

	protected $table='button_names_tbl';

    protected $primaryKey='button_id';
    protected $guarded = [];  


}