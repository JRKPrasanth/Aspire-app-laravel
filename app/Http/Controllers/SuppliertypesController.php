<?php
namespace App\Http\Controllers;
use App\Suppliertypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class SuppliertypesController extends Controller{
	
public $module="suppliertypes";

	public function __construct()
	{
	$this->data=array();
	
		$this->model=new Suppliertypes;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data['pageModule']='suppliertypes';
		$this->middleware('auth');
                $this->data['urlmenu']=$this->indexs(); 
	}
      /*Karthigaa Purpose for Index Page*/  
        public function index(Request $request)
	{
	    // restrict illegal menu entry purpose - VIGNESH M

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


		$table = \DB::table('m_suppliertypes_t')->get();
        $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
		$this->data['datas'] = $table;

		return view('suppliertypes.form',$this->data);
	}

	
// table data	
 public function getSuppliertypesData()
	{
		$wh='';

        $com=\Session::get('companyid');
		$org=\Session::get('organization'); 
	 
          $SQL = "SELECT m_suppliertypes_t.*,tb_users.first_name,tb_users.employee_id,tb_users.id FROM m_suppliertypes_t left join tb_users on tb_users.id =m_suppliertypes_t.created_by  where 1=1 and m_suppliertypes_t.company_id=$com $wh order by m_suppliertypes_t.suppliertype_id DESC ";   

	
		$result = \DB::select( $SQL );
  return DataTables::of($result)->make(true);

 }

    	public function create($id=null)
	{
		  $suppliertypes =Suppliertypes::find($id);
		  $this->data['suppliertypes']=$suppliertypes;
		return view('suppliertypes.form',$this->data);
	}

 /*Karthigaa Purpose for Save Function*/
    	public function save(Request $request)
	    {

         $edit_id = $request->input('edit_id');

          if($edit_id == '')
          {
              $suppliertypes = new Suppliertypes();
              $suppliertypes->suppliertype_name=$_POST['suppliertype_name'];
              $suppliertypes->description=$_POST['description'];
              $suppliertypes->gst_required=$_POST['gst_required'];
              if(isset($_POST['active'])){
              $suppliertypes->active=$_POST['active'];
              }
              $suppliertypes->created_by=\Session::get('id');
              $suppliertypes->last_updated_by=\Session::get('id');
              $suppliertypes->organization_id=\Session::get('organization'); 
              $suppliertypes->company_id=\Session::get('companyid');
              $suppliertypes->location_id=\Session::get('location'); 
	      $suppliertypes->save();
            $edit_id= DB::getPdo()->lastInsertId();
            $action="Create";
            /**Auditlog**/
            $this->auditlog($edit_id,"suppliertypes",$action,$_POST,"m_suppliertypes_t");
            return response()->json(array('status' => 'success', 'message' => 'Supplier Types Saved Successfully','id'=>$edit_id));
           }
           else{
                  $action="Edit";
                  $edit_id=$_POST['edit_id'];
                  $_POST['last_updated_by']=\Session::get('id');
                Suppliertypes::find($edit_id)->update($_POST);
                    /**Auditlog**/
                    $this->auditlog($edit_id,"suppliertypes",$action,$_POST,"m_suppliertypes_t");
                    return response()->json(array('status' => 'success', 'message' => 'Supplier Types Updated Successfully','id'=>$edit_id));
                }


        }

/*Karthigaa Purpose for Delete Function*/
 public function delete(Request $request,$id=null)
      {
        
          $count=0;
    $queryquote = \DB::table('m_supplier_t')->where('supplier_type_id',$id)->count();
    if($queryquote >=1)
    {
     $count++;
    }
    if($count <= 0)
      {
      $query = \DB::table('m_suppliertypes_t')->where('suppliertype_id',$id)->delete();
       /**Auditlog**/
        $action = "Delete";
        $this->auditlog($id,"suppliertypes",$action,$id,"m_suppliertypes_t");
      if($query)
      {
        return 0;
      }
      else
      {
        return 1;
      }
    }
    else
    {
      return 2;
    }

  }
  /*Karthigaa Purpose for Used Data Should Not Allow to Edit Function*/
public function getedit(Request $request,$id=null)
    {
	$column = array('supplier_type_id');
        $table = array('m_supplier_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }


		return $j;
    }

    /*Karthigaa Purpose for Duplicate Validation Function*/
     public function getCheckname(Request $request)
       { 

           $edit_id = $_GET['edit_id'];
           if($edit_id == '')
               $department=DB::table('m_suppliertypes_t')->where('suppliertype_name',$_GET['suppliertype_name'])->get();
           else
           {
               $whereData = [['suppliertype_name', $_GET['suppliertype_name']],['suppliertype_id', '!=', $edit_id]];

               $department=DB::table('m_suppliertypes_t')->where($whereData)->get();
           }

           if(count($department)>0)
               return 1;
           else
               return 0;


       }



}
