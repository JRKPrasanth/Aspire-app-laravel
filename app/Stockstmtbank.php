<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockstmtbank extends Model
{
    protected $table='f_stkstmt_gne_hdr_t';
	protected $primaryKey='skt_id';
	protected $fillable=['generate_date','select_date','status','grand_total','total_stock','total_value_sum','trade_creditors','net_value','sum_margin','total_a',
	'sundry_debtor','margin_two','total_b','drawing_power','created_at','created_by'];

	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
