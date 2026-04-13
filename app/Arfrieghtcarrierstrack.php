<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class arfrieghtcarrierstrack extends Sximo  {
	
	protected $table = 'ar_frieghtcarriers_track_t';
	protected $primaryKey = 'ar_frieghtcarriers_track_hdr_id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT ar_frieghtcarriers_track_t.* FROM ar_frieghtcarriers_track_t  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE ar_frieghtcarriers_track_t.ar_frieghtcarriers_track_hdr_id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
