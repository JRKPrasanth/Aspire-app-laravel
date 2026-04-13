<?php

namespace App\Http\Controllers;

use App\Outcome;
use Illuminate\Http\Request;
use Redirect,DB;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 public $module="outcome";

            public function __construct(){
		$this->data['urlmenu']=$this->indexs(); 
              $this->table="m_outcome_t";
              $this->model=new Outcome;
             $this->data['pageModule']=\Request::route()->getName();
			 
            }

public function outcomenamegriddata()
    { 
      $wh='';
    if($_GET['_search']=='true')
    {
      $tables=[];
 $tables[]="tb_users";
      $wh=$this->jqgridsearch('m_outcome_t',$_GET['filters'],$tables);
    }
    $comp=\Session::get('outcome_id');
    $page=$_GET['page'];
    $limit = $_GET['rows']; 
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    if(!$sidx)$sidx=1;
       $result = \DB::select("SELECT COUNT(outcome_id) AS count FROM m_outcome_t where 1=1 $wh");

      $count = $result[0]->count;
    if($count > 0 && $result > 0)
    {
      $total_pages = ceil($count/$limit);
    }
    else
    {
      $total_pages =0;
    }

    if($page > $total_pages) $page=$total_pages;

    $start = $limit*$page - $limit;

    if($start <0) $start = 0;
    
    $SQL="SELECT
           m_outcome_t.outcome_id,
           m_outcome_t.outcome_name,
           m_outcome_t.description,
           m_outcome_t.active
        FROM `m_outcome_t`
         where 1=1  $wh ORDER BY m_outcome_t.outcome_name $sord LIMIT $start , $limit"; 
         $download_SQL="SELECT
           m_outcome_t.outcome_id,
           m_outcome_t.outcome_name,
           m_outcome_t.description,
           m_outcome_t.active
        FROM `m_outcome_t` 
         where 1=1 $wh ORDER BY m_outcome_t.outcome_name $sord"; 

    
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
    /*End*/

  /*






/*Index Function For loading table*/
    public function index()
    {

	}
/*End*/
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /*Create Function*/
    public function create($id=null)
    {
//dd("fgf");
           $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
          $outcomename = \DB::connection()->getSchemaBuilder()->getColumnListing('m_outcome_t');  
          $outcomenames=(object)array();
            
            foreach($outcomename as $key=>$value)
              {
                $outcomenames->$value="";
              }
            $this->data['row']=$outcomenames; 
            $this->data['pageMethod']=\Request::route()->getName();
             return view('outcome.form',$this->data);
		
	}
/*End*/
  

      /*Product Save FUnction*/
    public function save(Request $request)
  { 
   // dd("fdfd");
        $outcome = new Outcome(); 

       // dd($outcomename);
        $edit_id = $request->input('edit_id');
        if($edit_id=="")
        {
          $outcome->outcome_name=$_POST['outcome_name'];
          $outcome->description=$_POST['description'];
            $outcome->active=$_POST['active'];
            
          $outcome->company_id = \Session::get('companyid');
          $outcome->location_id = \Session::get('location');
          $outcome->organization_id = \Session::get('organization');

          $outcome->save();
          $edit_id= DB::getPdo()->lastInsertId();
          $action="Create"; 
          /**Auditlog**/
            $this->auditlog($edit_id,"outcome",$action,$_POST,"m_outcome_t");
          return response()->json(array('status' => 'success', 'message' => 'Outcome Name Saved Successfully!!','id'=>$edit_id));
        }
        else
        {
            Outcome::find($edit_id)->update($_POST);
            $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"outcome",$action,$_POST,"m_outcome_t");
           
           return response()->json(array('status' => 'success', 'message' => 'Outcome Name Updated Successfully','id'=>$edit_id));
        }
         
    }
   
/*End*/

      /*Delete Funcion*/
    public function delete($del_id)
    {
       
        $column = array('outcome_id');
        $table = array('app_doctor_dcr_t');
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
             $query = \DB::table('m_outcome_t')->where('outcome_id',$del_id)->delete();
              /**Auditlog**/
            $this->auditlog($del_id,"outcomename","delete","","m_outcome_t");
        }

    return $j;
    }
  /*End*/

 /*Duplicate Name Check Function*/
    public function outcometypecheckname(Request $request)
    {
       $edit_id = $_GET['edit_id'];
        if($edit_id == '')
    {
      $group=\DB::table('m_outcome_t')->where('outcome_name',$_GET['outcome_name'])->get();
    }
        else
        {
        $whereData = [['outcome_name', $_GET['outcome_name']],['outcome_id', '!=', $edit_id]];
        $group=\DB::table('m_outcome_t')->where($whereData)->get();
        }
        if(count($group)>0)
            return 1;
        else
            return 0;
    }
  /*End*/  

 /*Edit Function*/
  public function outcomeeditchk($id=null)
    {
      $outid=$id;
      $column = array('outcome_id');
      $table = array('app_doctor_dcr_t');
        for($i=0; $i<count($table); $i++)
        {
         $j=0;
         $query = \DB::table($table[$i])->where($column[$i],$outid)->get();
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


/*Delete Function*/
  //  public function delete($del_id)
   // {
         
  //  }
  /*End*/     
        




	