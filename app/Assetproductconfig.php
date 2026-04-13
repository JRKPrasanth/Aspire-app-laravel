<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class assetproductconfig extends Model
{
    protected $table='asset_product_config';
    protected $primaryKey='asset_config_id';
    protected $foreignKey='product_id';
			protected $fillable = ['expiry_on','renewal_date','brand_name', 'qty','asset_number','uom','serial_number','warrenty_from','warrenty','area','purchase_date','supplier_id','department','created_by','created_at','updated_by','updated_at','company_id','location_id','organization_id','replace_date','assigned_to','asset_category','asset_type','asset_status','life_period','location','po_number','po_invoice_numer','capacity','remarks','work_group','system_name','operating_system','windows_key','processor_name','hdd_size','ram_size','printer_name','monitor','anti_virus','ip_address','ms_office','ms_office_key','add_software','mouse','keyboard','network_type','cd_dvd_drive','anydesk_number','anydesk_pw','login_type'];

     }
