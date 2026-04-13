<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use DB;
class ProjectController extends Controller
{

  public function __construct(){
        $this->data=array();
        $this->table="m_projects_t";

        $this->pageModule="project";
        $this->model=new Project;

        $this->data['pageModule']=$this->pageModule;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
                $this->data=array(
                    'pageModule'=> 'project',
                    'pageUrl'   =>  url($this->data['pageMethod']),
            'pageMethod'=>$this->data['pageMethod']
                  );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Project();
        $this->data['pageFormtype']='ajax';
    }
     public function index()
    {
         $table = \DB::table('m_projects_t')->get();
        $this->data['data']=$table;
        $this->data['row']= (object)array();
        $this->data['row']->project_id="";
        $this->data['row']->project_name="";
        $this->data['row']->project_type_id="";
        $this->data['row']->customer_id="";
        $this->data['row']->organization_id="";
        $this->data['row']->start_date="";
        $this->data['row']->end_date="";
        $this->data['row']->description="";
        $this->data['row']->active="";
         $this->data['active']=":--Please Select--;YES:YES;NO:NO";
		 $this->data['pageMethod']='project';
        $this->data['project_type']=$this->jqgridselect('m_project_type_t','project_type_id','project_type_name');

        return view("project.form",$this->data);
    }
  public function getprojectData($type=null){
    $wh='';
	  $searchtable= array('m_customers_t','m_project_type_t');
    if($_GET['_search']=='true')
    {
    $wh=$this->jqgridsearch('m_projects_t',$_GET['filters'],$searchtable);

    }

    if($type!='')
    {
        $wh.=" and source_type_id='".$type."'";
    }
	  


    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if(!$sidx) $sidx =1;
    $result = \DB::select("SELECT COUNT(m_projects_t.project_id) AS count FROM `m_projects_t` left join m_project_type_t  on(m_project_type_t.project_type_id=m_projects_t.`project_type_id`) left join m_customers_t on (m_customers_t.customer_id = m_projects_t.customer_id)  where 1=1 $wh");
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
    $SQL = "SELECT m_projects_t.* ,m_project_type_t.project_type_name,m_projects_t.project_type_id,m_customers_t.customer_name,m_customers_t.customer_id  FROM `m_projects_t`  left join m_project_type_t  on(m_project_type_t.project_type_id=m_projects_t.`project_type_id`) left join m_customers_t on (m_customers_t.customer_id = m_projects_t.customer_id)  where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
	 
    $result = \DB::select( $SQL );
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    echo json_encode($responce);
    }

      public function projectsave(Request $request)
    {


                        $id='';
            $data = $request->all();
            unset($data['_token']);
                        //dd($request->all());
      
            \DB::beginTransaction();
            try
            {
          if($data['project_id']=="")
            $msg="Project Saved";
        else
            $msg="Project Updated";
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
  
   public function getCheckname(Request $request)
    { 
        $project_id = $_GET['edit_id']; //dd($payment_term_id);
    	
        if($project_id == ''){
			$whereData = [['project_name', $_GET['project_name']]];
            $department=DB::table('m_projects_t')->where($whereData)->get();
        } else {
            $whereData = [['project_name', $_GET['project_name']],['project_id', '!=', $project_id]];
            $department=DB::table('m_projects_t')->where($whereData)->get();
        }
        
        if(count($department)>0)
            return 1;
        else
            return 0;
    }
  
  
  
  
  

       public function projectdelete(Request $request,$id=null)
    {

        $column = array('project_id','project_id','project_id','project_id');
        $table = array('s_quote_hdr_t','s_salesorder_hdr_t','s_invoice_hdr_t','p_po_invoice_hdr_t');
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

        if($j==0)
        {
            $query = \DB::table('m_projects_t')->where('project_id',$id)->delete();            
        }

        return $j;
    }

}
