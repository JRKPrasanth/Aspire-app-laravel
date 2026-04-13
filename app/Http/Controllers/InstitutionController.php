<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Institution;

class InstitutionController extends Controller
{
	public function __construct()
	{
		  
		    $this->data=array(
             'pageModule'=> 'InstitutionController',
             'pageUrl'	=>  url('institution')
              );
        $this->data['urlmenu']=$this->indexs(); 
		    $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
	}
    public function index(){
    $this->data['pageMethod']=\Request::route()->getName();

       $table = \DB::table('app_institution_t')->get();   
        $this->data['data']=$table;
       
        return view("Institution.table",$this->data);

    }
    	public function institutionData()
   	{//dd("jbh");
		$wh='';
	   $table=array("tb_users");
		if($_GET['_search']=='true')
		{
		$wh.=$this->jqgridsearch('app_institution_t',$_GET['filters'],$table);
		}
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];          
	   $com=\Session::get('companyid');
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(app_institution_t.institution_id) AS count FROM app_institution_t where 1=1 and app_institution_t.company_id=$com  $wh");
		//dd($result);
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
		$SQL = "SELECT * FROM app_institution_t where 1=1 and app_institution_t.company_id=$com  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		//dd($SQL);
		$download_SQL = "SELECT * FROM app_institution_t where 1=1 and app_institution_t.company_id=$com  $wh ORDER BY $sidx $sord";
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
public function edit(Createuser $createuser)
    {
        //
    }
   public function create($id=null)
   {
       	$this->data['pageMethod']=\Request::route()->getName();	
      	// dd($id);
     if($id==null){

     $table=\DB::getSchemaBuilder()->getColumnListing('app_institution_t');
         // dd($table); 
           foreach($table as $v){
            $this->data[$v]="";
           }
 }
 else{
     $app_institution_t= \DB::table('app_institution_t')->where('institution_id',$id)->get();
     foreach($app_institution_t[0] as $key => $value){
        
            $this->data[$key]=$value;

           } 
      
 }
             return view('Institution.form',$this->data);
		
	}

 public function save(Request $request){
           if($_POST['institution_id'] =="")
               {
                
                $institution = new Institution();
                $institution->institution_id = $request->input('institution_id');
                 $institution->name = $request->input('name');
                $institution->key_contact1      = $request->input('key_contact1');
                $institution->contact_name1       = $request->input('contact_name1');
                $institution->key_contact2           = $request->input('key_contact2');
                $institution->contact_name2      = $request->input('contact_name2');
                $institution->desgination1          = $request->input('desgination1');
                $institution->mail1          = $request->input('mail1');
                $institution->desgination2    = $request->input('desgination2');
                 $institution->mail2    = $request->input('mail2');
                  $institution->associated_doctors    = $request->input('associated_doctors');
                  $institution->associated_distributors    = $request->input('associated_distributors');
                $institution->created_by        =    \Session::get('id');
                $institution->save();
                $edit_id= \DB::getPdo()->lastInsertId();
             $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"institution",$action,$_POST,"app_institution_t");
               
                return 1;
            } else 
            {
                $_POST['focus_product']= json_encode($_POST['focus_product']);
                 Institution::find($_POST['institution_id'])->update($_POST);
                 $edit_id = $_POST['institution_id'];
                  $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"institution",$action,$_POST,"app_institution_t");                       
         
         
                    return 2;
                }
           
          
            
      
             
                    

        }
  


}
