<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $table='s_dispatch_hdr_t';
	protected $primaryKey='so_dispatch_hdr_id';
	protected $fillable=['so_dispatch_hdr_id','dispatch_number','dispatch_source','prepare_date','dispatch_date','preparer_id','dispatch_status','freight_carrier_id','contact_details',
'location_id','organization_id','deliver_to_location','remarks','sample_count','reference_source_id','total_qty'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
