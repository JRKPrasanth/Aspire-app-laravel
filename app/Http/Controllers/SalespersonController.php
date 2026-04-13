<?php

namespace App\Http\Controllers;
use App\Salesperson;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class SalespersonController extends Controller
{

  public function __construct()
  {

  		$this->data=array();
  		$this->model=new Salesperson();

    $this->data['pageMethod']=\Request::route()->getName();
    $this->data['pageModule']=\Request::route()->getName();
    $this->data['pageFormtype']='ajax';
    $this->data['urlmenu']=$this->indexs(); 
  }

  public function index()
  {
	  $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
if(isset($_GET['open']))
{
	$this->data['content'] ='1';
}
      

    $table = \DB::table('s_salesperson_t')->get();
    $this->data['datas'] = $table;
	   $this->data['pageModule']=\Request::route()->getName();
	   $this->data['pageMethod']=\Request::route()->getName();
	  
$this->data['employee_id'] = $this->jCombo('hr_employee_t','employee_id','employee_number|first_name',\Session::get('emp_id'));
	
    return view('salesperson.form',$this->data);

  }
/* Start Jqgrid data */
  public function getGridData()
  {
    $wh='';
    if($_GET['_search']=='true')
    {
      $search_tables=array("hr_employee_t","tb_users");
    $wh=$this->jqgridsearch('s_salesperson_t',$_GET['filters'],$search_tables);
  
    }

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
   $com=\Session::get('companyid');
    $loc=\Session::get('location'); 
    $org=\Session::get('organization');
    $groupname=\Session::get('groupname');
      if($groupname=='Superadmin' || $groupname=='Admin'){
    $wh.='and  s_salesperson_t.company_id='.$com;  
    }else{
      $wh.='and  s_salesperson_t.company_id='.$com.' and s_salesperson_t.location_id='.$loc;    
    } 
    if(!$sidx) $sidx =1;
    $result = \DB::select("SELECT COUNT(s_salesperson_t.salesperson_id) AS count FROM s_salesperson_t  left join hr_employee_t on hr_employee_t.employee_id=s_salesperson_t.employee_id left join tb_users on tb_users.id =s_salesperson_t.created_by where 1=1  $wh");
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
    //$SQL = "SELECT s_salesperson_t.* ,tb_users.username as created_by,s_salesperson_t.active as actives,tb_users.id as created_id FROM s_salesperson_t left join tb_users on tb_users.id =s_salesperson_t.created_by where 1=1 and s_salesperson_t.company_id=$com and s_salesperson_t.location_id=$loc $wh ORDER BY $sidx $sord LIMIT $start , $limit";
    
    $SQL = "SELECT s_salesperson_t.*,hr_employee_t.*,tb_users.username,tb_users.id as created_id , s_salesperson_t.active FROM s_salesperson_t left join hr_employee_t on hr_employee_t.employee_id=s_salesperson_t.employee_id left join tb_users on tb_users.id =s_salesperson_t.created_by where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
	  
	  $download_SQL = "SELECT s_salesperson_t.*,hr_employee_t.*,tb_users.username,tb_users.id as created_id , s_salesperson_t.active FROM s_salesperson_t left join hr_employee_t on hr_employee_t.employee_id=s_salesperson_t.employee_id left join tb_users on tb_users.id =s_salesperson_t.created_by where 1=1  $wh ORDER BY $sidx $sord";
	  
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
  /* End Jqgrid data */

/* Start Create data */
public function create($id=null)
  {
  $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
  $maindate = $this->dateform('date');
  $salesperson =Salesperson::find($id);
  $this->data['salesperson']=$salesperson;
  return view('salesperson.form',$this->data);
  }
  /* End */
  /* Start Save data */
  public function save(Request $request)
  {
//dd("DFf");

        $edit_id = $request->input('edit_id');
     // dd($edit_id );
        if($edit_id == '')
        {
            $salesperson = new Salesperson();
            $salesperson->company_id=\Session::get('companyid');
            $salesperson->location_id=\Session::get('location'); 
            $salesperson->organization_id=\Session::get('organization');
            $salesperson->salesperson_name=$_POST['salesperson_name'];
            $salesperson->employee_id=$_POST['employee_id'];
            $salesperson->active=$_POST['active'];
            $seqno=$this->Seqnoe('SP','s_salesperson_t',"",'salesperson_site_count');
            $salesperson->salesperson_number= $seqno[0];
            $salesperson->salesperson_site_count= $seqno[1];
            $salesperson->active=$_POST['active'];
			      $salesperson->created_by=\Session::get('id');
          $salesperson->save();
          dd($salesperson);
          $edit_id = DB::getPdo()->lastInsertId();
          $action="Create";
            /**Auditlog**/
          $this->auditlog($edit_id,"Sale Person",$action,$_POST,"s_salesperson_t");
          return response()->json(array('status' => 'success', 'message' => 'Salesperson Saved Successfully','id'=>$edit_id));
        }
        else
        {

            $edit_id=$_POST['edit_id'];
      			$salesperson_number = $_POST['salesperson_number'];
            $salesperson_site_count = $_POST['salesperson_site_count'];
            Salesperson::find($edit_id)->update($_POST);
                  /**Auditlog**/
      $action="Edit";
      $this->auditlog($edit_id,"Sale Person",$action,$_POST,"s_salesperson_t");
            return response()->json(array('status' => 'success', 'message' => 'Salesperson Updated Successfully','id'=>$edit_id));
        }      
}
// End
/* Start Delete data */
public function delete(request $request,$id=null)
        {
        $column = array('salesperson_id','sales_person','salesperson_id','salesperson_id');
        $table = array('s_invoice_hdr_t','m_customers_t','s_quote_hdr_t','s_salesorder_hdr_t');   
        
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
        $query = \DB::table('s_salesperson_t')->where('salesperson_id',$id)->delete();
              /**Auditlog**/
      $action = "Delete";
      $this->auditlog($id,"Sale Person",$action,$id,"s_salesperson_t");
        }
     return $j;
}
//End
/* Start Show data */
  public function show($id=null)
  {
    
  $Salesperson =Salesperson::find($id);
  $this->data['value']=$Salesperson;
  return view('salesperson.view',$this->data);
  }
//End

/*start Show function */
 public function getShow(Request $request)
    {

          $out_put = array();
          $department=DB::table('s_salesperson_t')->where('salesperson_id',$_GET['salesperson_id'])->first();
          $out_put['salesperson_id'] = $department->salesperson_id;
          $out_put['salesperson_name'] = $department->salesperson_name;
          
          
          $out_put['active'] = $department->active;
  //$out_put['source_type_id'] = $department->source_type_id;
          return $out_put;

    }
    /*End Show function */

    /*start Edit data function */
   public function getedit($edit_id)
    {
     $column = array('salesperson_id','sales_person','salesperson_id','salesperson_id');
        $table = array('s_invoice_hdr_t','m_customers_t','s_quote_hdr_t','s_salesorder_hdr_t');   
        
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$edit_id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
       
     return $j;
    }/*End Edit data function */
  
    /*start Employee check data function */
  public function employeecheck($employee_id=null,$id=null)
    {
   if($id==0){
    $data=\DB::select("select * from s_salesperson_t where employee_id='$employee_id'");
    if(count($data)>0){
      return 1;
    }else{
      return 0;
    }
   }else{
     $data=\DB::select("select * from s_salesperson_t where employee_id='$employee_id' and salesperson_id !='$id'");
      if(count($data)>0){
      return 1;
    }else{
      return 0;
    }
   }
   
}
/*End Employee check data function */

/*start Employee Name check data function */
  public function getCheckname(Request $request)
    {

        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
            $department=DB::table('s_salesperson_t')->where('salesperson_name',$_GET['salesperson_name'])->get();
        else
        {
            $whereData = [['salesperson_name', $_GET['salesperson_name']],['salesperson_id', '!=', $edit_id]];

            $department=DB::table('s_salesperson_t')->where($whereData)->get();
        }


        if(count($department)>0)
            return 1;
        else
            return 0;


    }
    /*End Employee Name check data function */
}
