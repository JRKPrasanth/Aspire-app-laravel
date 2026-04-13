<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class toolsmenuconfiglines extends Model
{
     protected $table='m_toolmenus_listing_lines_t';
	protected $primaryKey='m_toolmenus_listing_line_id';
    protected $foreignKey='m_toolmenus_listing_hdr_id';
    protected $fillable=[
  'submenu_name','submenu_label','submenu_url','submenu_icon','submenu_region','submenu_order'

  ];
}
