<?php

namespace App\Http\Controllers;

use App\Projecttypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjecttypesController extends Controller
{
     public function __construct(){
        $this->data=array();
        $this->table="m_project_type_t";
        
        $this->pageModule="projecttype";
        $this->model=new Projecttypes;
        
        $this->data['pageModule']=$this->pageModule;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
                $this->data=array(
                    'pageModule'=> 'location',
                    'pageUrl'   =>  url($this->data['pageMethod']),
            'pageMethod'=>$this->data['pageMethod']
                  );
         $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Projecttypes();
        $this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs();
    }
     public function index()
    {
         $table = \DB::table('m_project_type_t')->get();   
        $this->data['data']=$table;
        $this->data['row']= (object)array(); 
        $this->data['row']->project_type_id="";   
        $this->data['row']->project_type_name="";   
        $this->data['row']->description="";   
        $this->data['row']->active="";   
         $this->data['active']=":--Please Select--;YES:YES;NO:NO";
      
        return view("projecttype.form",$this->data);  
    }
  public function getprojecttypeData($type=null){
    $wh='';
    if($_GET['_search']=='true')
    {
    $wh=$this->jqgridsearch('m_project_type_t',$_GET['filters']);
    }

    //dd($type);
        if($type!='')   
    {
        $wh.=" and source_type_id='".$type."'";
    }   
        
        
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if(!$sidx) $sidx =1;
    $result = \DB::select("SELECT COUNT(project_type_id) AS count FROM `m_project_type_t` where 1=1 $wh");
    $count = $result[0]->count;
    if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
    $SQL = "SELECT * FROM `m_project_type_t`  where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        $download_SQL = "SELECT * FROM `m_project_type_t`  where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
 $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
    $result = \DB::select( $SQL );
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    echo json_encode($responce);
    }

      public function projecttypesave(Request $request)
    {
     
     
                        $id='';
            $data = $request->all();
            unset($data['_token']);
                        //dd($request->all());
              
            \DB::beginTransaction();
            try
            {
          if($data['project_type_id']=="")
            $msg="Project Type Saved";
        else
            $msg="Project Type Updated";
                            $id=$this->model->insertRow($data);
                //$lid=$this->submodel->subgridSave($lines_data,$id);
                \DB::commit();

                return response()->json(array('status' => 'success', 'message' => $msg,'id' => $id));
            }
            catch (\Illuminate\Database\QueryException$e)
            {
                $message = explode('(', $e->getMessage());
                $dbCode = rtrim($message[0], ']');
                $dbCode = trim($dbCode, '[');
                \DB::rollback();
                dd($dbCode);
                return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
            }
    }
       public function projecttypedelete(Request $request,$id=null)
    {

        $count = 0;
        $queryquote = \DB::table('m_projects_t')->where('project_type_id',$id)->count();
        if($queryquote >=1)
        {
            $count++;
        }
        if($count <= 0)
            {
            $query = \DB::table('m_project_type_t')->where('project_type_id',$id)->delete();
            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else{
            return 2;
        }
    }
	 public function getCheckprojecttype(Request $request)
    { 
        $project_type_id = $_GET['edit_id']; //dd($payment_term_id);
    	
        if($project_type_id == ''){
			$whereData = [['project_type_name', $_GET['project_type']]];
            $department=\DB::table('m_project_type_t')->where($whereData)->get();
        } else {
            $whereData = [['project_type_name', $_GET['project_type']],['project_type_id', '!=', $project_type_id]];
            $department=\DB::table('m_project_type_t')->where($whereData)->get();
        }
        
        if(count($department)>0)
            return 1;
        else
            return 0;
    }
}
