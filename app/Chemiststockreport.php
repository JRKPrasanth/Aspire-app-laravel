<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chemiststockreport extends Model
{
    protected $table ='app_chemist_stk_rpt_t';
	protected $primaryKey ='chemist_stk_id';
	protected $fillable=['chemist_id','chemist_dcr_id','date','product_id','opening_bal','free_qty','sales_return','receipt','sale_qty','closing_bal','created_by','created_at','last_updated_by','updated_at','organization_id','location_id','company_id'];
}
