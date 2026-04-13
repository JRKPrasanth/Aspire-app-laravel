<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soquote extends Model
{
	public $table='s_quote_hdr_t';
	public $primaryKey='quote_hdr_id';
	public $foreignKey='quote_hdr_id';
	public $fillable =['quote_no','quote_name','quote_date','quote_type','quote_status','customer_id','quote_pricelist_id',
            'salesperson_id','currency_id','project_id','organization_id','quote_tax_total','quote_grand_total','remarks','insurance_charges_tax','transport_charges_tax','transport_charges','insurance_charges','other_frieght_amount','other_tax_amount','packaging_charges','packaging_charges_tax','other_tax_amount_tax','other_frieght_amount_tax'];

    public function getTableColumns() 
	{
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
