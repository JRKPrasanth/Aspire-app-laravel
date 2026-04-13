<?php

namespace App\Http\Controllers;

use App\Menuarrangement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuarrangementController extends Controller
{
    public $module="purchaseorder";
	public function __construct()
	{
		$this->data=array(
                    'pageModule'=> 'menuarrangement',
                    'pageUrl'	=>  url('menuarrangement')
                  );
		$this->table="p_po_hdr_t";
		$this->subtable="p_po_lines_t";
		$this->pageModule="menuarrangement";
		$this->model=new Menuarrangement;
		
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
	}
    public function index($id=null)
    {
        $this->data['return_url']='menuarrangement';
        return view("menuarrangement.form",$this->data);
    }

    public function create()
    {
      
      \DB::select("TRUNCATE TABLE tb_menus");
      $menu=json_decode($_POST['menu_data']);
      
      foreach($menu as $key=>$value){
          $data['menus_name']=$value->text;
          $data['controller_name']=$value->href;
          $data['createurl']=$value->createurl;
          $id = \DB::table('tb_menus')->insertGetId($data);
        if(isset($value->children)){
          foreach($value->children as $ke=>$val){
              
                 $datas['menus_name']=$val->text;
          $datas['controller_name']=$val->href;
           $datas['createurl']=$val->createurl;
          $datas['parent_id']=$id;
           $ids = \DB::table('tb_menus')->insertGetId($datas);
          if(isset($val->children)){
                foreach($val->children as $k=>$v){
                  
                            $datass['menus_name']=$v->text;
          $datass['controller_name']=$v->href;
          $datass['parent_id']=$ids;
           $datass['createurl']=$v->createurl;
           $idss= \DB::table('tb_menus')->insertGetId($datass);
                    
                }
          }
          }
        }
        
          
      }
  
      return 1;
      
    }
}
