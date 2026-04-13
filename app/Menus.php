<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Menus extends model  {

	protected $table='tb_menus';

    protected $primaryKey='menus_id';
    protected $guarded = [];  


}