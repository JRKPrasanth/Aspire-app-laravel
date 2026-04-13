<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesinvoice extends Model{
        protected $table='s_invoice_hdr_t';
	protected $primaryKey='invoice_hdr_id';
	protected $fillable =['invoice_number','invoice_type','invoice_date','round_off','invoice_status','remarks','ship_to_customer_id','ship_to_address_id','invoice_total','invoice_currency','other_tax_amount','other_frieght_amount','packaging_charges','insurance_charges','transport_charges','other_tax_amount_tax','other_frieght_amount_tax','transport_charges_tax','insurance_charges_tax','packaging_charges_tax','schemes','trade_discount','trade_discount_pre','cash_discount','due_date','cheque_no','cheque_amount','cheque_date','cheque_received_date','irn_no','irn_date','con_exc_rate'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
}
