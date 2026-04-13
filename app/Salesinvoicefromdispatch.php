<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class salesinvoicefromdispatch extends Sximo  {
	
	protected $table = 'so_dispatch_hdr_t';
	protected $primaryKey = 'so_dispatch_hdr_id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT so_dispatch_hdr_t.* FROM so_dispatch_hdr_t  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE so_dispatch_hdr_t.so_dispatch_hdr_id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
