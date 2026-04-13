<?php

namespace App\Http\Controllers;

use App\Producttype;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;


class ProducttypeController extends Controller
{ 
    public function __construct()
	{
		$this->data['pageFormtype']='ajax';
            $this->data['urlmenu']=$this->indexs(); 
	}
	
    /*Grid  Function For Loading Data in Tables*/
    public function producttypegriddata()
    { 
      $wh='';


    $comp=\Session::get('companyid');


    $SQL="SELECT
           m_product_type_t.product_type_id,
           m_product_type_t.product_type,
           m_product_type_t.remarks,
           m_product_type_t.created_by,
           tb_users.first_name,
           m_product_type_t.active
        FROM `m_product_type_t` left join `tb_users` on (tb_users.id=m_product_type_t.created_by) 
         where 1=1 and m_product_type_t.company_id=$comp $wh"; 

    $result = \DB::select( $SQL );
	return DataTables::of($result)->make(true);
		
    }


  /* Page Load Function with Create Form*/
    public function create(Request $request,$id=null)
    { 
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END

           $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
          $Producttype = \DB::connection()->getSchemaBuilder()->getColumnListing('m_product_type_t');  
          $Producttypes=(object)array();
            
            foreach($Producttype as $key=>$value)
			  {
		        $Producttypes->$value="";
          	  }
            $this->data['row']=$Producttypes; 
            $this->data['pageMethod']=\Request::route()->getName();
             return view('producttype.form',$this->data);
    }
/*End*/
  
/*Save Function*/
    public function save(Request $request)
    { 
        $producttype = new Producttype(); 
        $edit_id = $request->input('edit_id');
        if($edit_id=="")
        {
          $producttype->product_type=$_POST['product_type'];
          $producttype->remarks=$_POST['remarks'];
            $producttype->active=$_POST['active'];
            $producttype->created_by=$_POST['created_by'];
          $producttype->company_id = \Session::get('companyid');
            $producttype->location_id = \Session::get('location');
          $producttype->save();
          $edit_id= DB::getPdo()->lastInsertId();
          $action="Create"; 
          /**Auditlog**/
            $this->auditlog($edit_id,"producttype",$action,$_POST,"m_product_type_t");
          return response()->json(array('status' => 'success', 'message' => 'Product Type Saved Successfully','id'=>$edit_id));
        }
        else
        {
            $pro['product_type']=$_POST['product_type'];
          $pro['remarks']=$_POST['remarks'];
            $pro['active']=$_POST['active'];
            $pro['created_by']=$_POST['created_by'];
          $pro['company_id'] = \Session::get('companyid');
            $pro['location_id'] = \Session::get('location');
           $pro['last_updated_by']=\Session::get('id');
            $action="Edit";
            $edit_id=$_POST['edit_id'];
            /**Auditlog**/
            $this->auditlog($edit_id,"producttype",$action,$_POST,"m_product_type_t");
           $update = \DB::table('m_product_type_t')->where('product_type_id',$edit_id)->update($pro);
           
           return response()->json(array('status' => 'success', 'message' => 'Product Type Updated Successfully','id'=>$edit_id));
        }
         
    }
    /*End*/

      /*Delete Funcion*/
    public function delete($del_id)
    {
       
        $column = array('product_type_id');
        $table = array('m_products_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$del_id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
        
        if($j==0)
        {
             $query = \DB::table('m_product_type_t')->where('product_type_id',$del_id)->delete();
              /**Auditlog**/
            $this->auditlog($del_id,"producttype","delete","","m_product_type_t");
        }

    return $j;
    }
  /*End*/
    
    /*Duplicate Name Check Function*/
    public function producttypecheckname(Request $request)
    {
       $edit_id = $_GET['edit_id'];
        if($edit_id == '')
		{
      $group=\DB::table('m_product_type_t')->where('product_type',$_GET['product_type'])->get();
		}
        else
        {
        $whereData = [['product_type', $_GET['product_type']],['product_type_id', '!=', $edit_id]];
        $group=\DB::table('m_product_type_t')->where($whereData)->get();
        }
        if(count($group)>0)
            return 1;
        else
            return 0;
    }
  /*End*/  

  /*Edit Function*/
	public function producttypeeditchk($id=null)
    {
      $prdgrpid=$id;
      $column = array('product_type_id');
      $table = array('m_products_t');
        for($i=0; $i<count($table); $i++)
        {
         $j=0;
         $query = \DB::table($table[$i])->where($column[$i],$prdgrpid)->get();
         if(count($query)>0)
          {
            $j=1;
            break;
          }
        }       
		return $j;
    }
    /*End*/
}
