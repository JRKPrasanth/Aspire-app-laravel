<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockstmtbanklines extends Model
{
   protected $table='f_stkstmt_gne_lines_t';
	protected $primaryKey='id';
	protected $foreignKey='skt_id';
	protected $fillable=['particulars','stock_on_date','unit_weight','mar_purc_rate','total_value'];
	
	
	
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
 }
