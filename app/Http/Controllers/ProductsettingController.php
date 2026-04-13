<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productsetting;

class ProductsettingController extends Controller
{
    public function index()
    {
        $this->data['product_group_id'] = $this->jcustommultiselect('m_product_groups_t','product_group_id','group_name','','');
        $this->data['product_category_id'] = $this->jcustommultiselect('m_product_category_t','product_category_id','category_name','','');
        $this->data['product_subcategory_id'] = $this->jcustommultiselect('m_product_subcategory_t','product_subcategory_id','subcategory_name','','');
        $this->data['module_id'] = $this->jcustommultiselect('m_product_setting_t','product_setting_id','module_name','','');
        $this->data['user'] = $this->jcustommultiselect('tb_users','id','first_name','','');
        return view('productsetting.table',$this->data);
    }

  
	public function getproductsetting(Request $request){
            
        $prddata=array();
        $type=$_GET['type'];
        $prddata = \DB::select("select * from m_product_setting_t where module_name='$type'");
        // $data=$this->data['product_group_id'] = $this->jcustommultiselect('m_product_groups_t','product_group_id','group_name',$prddata[0]->product_group_id,'');
        if(!empty($prddata)){
			return $prddata;
		}else{
			return 0;
		}
    }
      

    
	public function productsettingsave(Request $request){
	
	  
    	$product_group_id=implode(",",$_POST['product_group_id']);
        $select_option=implode(',"-",',$_POST['select_option']);
       
      	$type=$_POST['type'];
      	$prdgroup=$product_group_id;
      	\DB::update("UPDATE m_product_setting_t SET product_group_id = '$prdgroup',select_option = '$select_option' WHERE  module_name='$type'");
		 return response()->json(array('status' => 'success', 'message' => 'Saved Successfully'));
        return redirect('productsetting');
	}

}