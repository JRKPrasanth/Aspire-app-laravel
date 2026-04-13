<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class salesenquiry extends Model
{
    protected $table='s_inquiry_hdr_t';
    protected $primaryKey='so_inquiry_hdr_id';
	protected $fillable =['so_inquiry_hdr_id','inquiry_no','inquiry_category','inquiry_type','inquiry_date','customer_id','customer_site_id','project_id','organization_id','remarks','quote_status'];
}
