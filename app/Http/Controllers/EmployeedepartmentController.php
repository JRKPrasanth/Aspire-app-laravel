<?php

namespace App\Http\Controllers;
use App\Employeedepartment;
use App\Employeedepartmentlines;
use Illuminate\Http\Request;
use DB;


class EmployeedepartmentController extends Controller
{
   
    public function __construct() {
        $this->data = array();
    
        $this->subtable = "m_department_lines_t";
        $this->pageModule = "employeedepartment";
        $this->model = new Employeedepartment;
        $this->submodel = new Employeedepartmentlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'employeedepartment',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Employeedepartment();
        $this->data['pageFormtype'] = 'ajax';
    }
	/*** CREATE FUNCTION FOR DEPARTMENT START **/
	public function createnew(Request $request){ 
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

        
            $comp=\Session::get('companyid');
                $linedata=\DB::table('m_department_lines_t')->where('company_id',$comp)->get();
        $this->data['linedata'] = array();
        /**Tree Menu Structure*/
        

         $query1 = \DB::select("SELECT m_department_lines_t.* FROM m_department_lines_t where   m_department_lines_t.active='Yes' and m_department_lines_t.company_id='".$comp."' ");
		
        $menus=[];
		if(count($query1)>0){
foreach ($query1 as $key => $value) { 
                    $menus['items'][$value->department_line_id]         = $value; // Creates list of all items with children
                    $menus['parents'][$value->parent_class_id][] = $value->department_line_id;
            }
                if($menus)
                {
                  $this->data['tree_menu'] = $this->createTreeView(0, $menus);
                }
		else
		{
		 $this->data['tree_menu'] ='';	
		}
		}
		else{
			 $this->data['tree_menu'] ='';	
		}



        return view('employeedepartment.formnew',$this->data);
    }
    /*** CREATE FUNCTION FOR DEPARTMENT END **/
	/** FUNCTION TO CREATE TREE START  **/
	function createTreeView($parent, $menu)
        {

            $html = "";
            if (isset($menu['parents'][$parent])) {
                $html .= "<ol class='tree'>";
                foreach ($menu['parents'][$parent] as $itemId) {

                    if (!isset($menu['parents'][$itemId])) {
                       $id=$menu['items'][$itemId]->department_line_id;
                        $html .= "<li><a href='javascript:void(0);' id='subacc_btn' class='list-group-item subacc_btn'  data-id='$id'>" . $menu['items'][$itemId]->sub_department_code."(".$menu['items'][$itemId]->sub_department_name.")</a></li>";
                    }
                    if (isset($menu['parents'][$itemId])) {
                         $id=$menu['items'][$itemId]->department_line_id;
                        $html .= "<li><a href='javascript:void(0);' id='subacc_btn' class='subacc_btn' data-id='$id'>" . $menu['items'][$itemId]->sub_department_code."(".$menu['items'][$itemId]->sub_department_name.")</a></li>";
                        $html .= $this->createTreeView($itemId, $menu);
                        $html .= "</li>";
                    }
                }
                $html .= "</ol>";
            }
            return $html;
        }
	
	
	/** FUNCTION TO CREATE TREE END  **/
   
	/** FUNCTION FOR DEPARTMENT SAVE START ****/
	 public function savenew(Request $request, $id = 0){ 
             
         $id='';

         $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');

         $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $userid=\Session::get('id');
              if(isset($_POST['child_parent_id'])){
               
        	 $parent_id=$_POST['child_parent_id'];
                 $parent_account_code=$_POST['parent_account_code'];
        	 $parent_account_code_meaning=$_POST['parent_account_code_meaning'];
                 
                 $old_row=\DB::select("SELECT department_line_id FROM `m_department_lines_t` WHERE `parent_class_id`='$parent_id'");
                 $old_rows=[];
                foreach($old_row as $i=>$val)
                {
                 $old_rows[$val->department_line_id]=(string)$val->department_line_id;
                }
                foreach($_POST['bulk_department_line_id'] as $index=>$v)
                {
                    $v1=(string)$v;
                    $data['department_line_id']=$v;
                    $line_no=$data['line_no']=$_POST['bulk_line_no'][$index];
                    $acc_code=$data['sub_department_code']=$_POST['bulk_sub_department_code'][$index];
                    $acc_codemean=$data['sub_department_name']=$_POST['bulk_sub_department_name'][$index];
                    
                    $parentclass=$data['parent_class_id']=$_POST['child_parent_id'];
                    $active=$data['active']=$_POST['bulk_active'][$index];
                    $companyid=$data['company_id']=$compy;
                    $orgid=$data['organization_id']=$org;
                    $locid=$data['location_id']=$loc;
                    $created_by=$data['created_by']=$userid;
                    $updated_by=$data['last_updated_by']=$userid;
                    $created_at=$data['created_at']=date('Y-m-d H:i:s');
                    $updated_at=$data['updated_at']=date('Y-m-d H:i:s');
                    $id=array_search($v1, $old_rows);
                if($id){
                    unset($old_rows[$id]);
           //update  department query
                    \DB::update("UPDATE m_department_lines_t SET line_no = '$line_no',sub_department_code = '$acc_code',sub_department_name = '$acc_codemean',parent_class_id='$parentclass',company_id='$companyid',location_id='$locid',organization_id='$orgid',created_by='$created_by',last_updated_by='$updated_by',created_at='$created_at',updated_at='$updated_at',active='$active' where department_line_id='$v'");
                    \DB::update("UPDATE m_department_lines_t SET line_no = '$line_no',sub_department_code = '$parent_account_code',sub_department_name = '$parent_account_code_meaning',company_id='$companyid',location_id='$locid',organization_id='$orgid',created_by='$created_by',last_updated_by='$updated_by',created_at='$created_at',updated_at='$updated_at' where department_line_id='$parent_id'");
                    
               $action="edit";
                    }
                else{
                    //new department insert query
                   \DB::insert("insert into m_department_lines_t(line_no,sub_department_code,sub_department_name,parent_class_id,company_id,location_id,organization_id,active,created_by,last_updated_by,created_at,updated_at)values('$line_no','$acc_code','$acc_codemean','$parentclass','$companyid','$locid','$orgid','$active','$created_by','$updated_by','$created_at','$updated_at')");
         $data_id=  \DB::SELECT("select MAX(department_line_id) AS id FROM m_department_lines_t ");
    $id=$data_id[0]->id;
                    $action="create";
                }
                  /** audit log  for insert **/
                     $this->auditlog($id,"employeedepartmentnew",$action,$_POST,"m_department_lines_t");
                }
                
               // $removed_line_id=$_POST['removed_line_id'];			
		//	unset($_POST['removed_line_id']);	
			
			
			//if($removed_line_id!='')
		//	{
		//		$removed_line_id=explode(',',$removed_line_id);				
		//		DB::table('m_department_lines_t')->whereIn('department_line_id',$removed_line_id)->delete();			
		//	}

             }
            
              \DB::commit();
       
       return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id));
    }
	/** FUNCTION FOR DEPARTMENT END ****/
	/** function to load sub department start **/
	   public function subdepartment($dep=null){
            $data['query']=$query1 = \DB::select("SELECT * FROM `m_department_lines_t`  WHERE department_line_id='$dep'");
            $data['sub'] = \DB::select("SELECT * FROM `m_department_lines_t`  WHERE parent_class_id='$dep'");
            return  $data;
        } 
        	/** function to load sub department end **/
        
   
}
