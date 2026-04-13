<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesreplacement extends Model{
        protected $table='s_replacement_hdr_t';
	protected $primaryKey='replacement_hdr_id';
	protected $fillable =['replacement_number','invoice_type','invoice_date','round_off','invoice_status','remarks','ship_to_customer_id','ship_to_address_id','invoice_total','invoice_currency','other_tax_amount','other_frieght_amount','packaging_charges','insurance_charges','transport_charges','other_tax_amount_tax','other_frieght_amount_tax','transport_charges_tax','insurance_charges_tax','packaging_charges_tax','schemes','trade_discount','trade_discount_pre'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
}
