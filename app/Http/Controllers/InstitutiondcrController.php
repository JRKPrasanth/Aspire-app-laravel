<?php

namespace App\Http\Controllers;

use App\Institutiondcr;
use App\Http\Controllers\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Database\Eloquent\Model,Input;

class InstitutiondcrController extends Controller
{
    
  public function __construct(){
        $this->data=array();
    $this->table="app_institution_dcr";
    //$this->data['urlmenu']=$this->indexs(); 
    $this->pageModule="institutiondcr";
    $this->model=new Institutiondcr;
    $this->data['pageModule']=$this->pageModule;
    $this->data['pageMethod']=\Request::route()->getName();
    $this->data['pageFormtype']='ajax';
		$this->data=array(
			'pageModule'=> 'institutiondcr',
			'pageUrl' =>  url($this->data['pageMethod']),
'pageMethod'=>$this->data['pageMethod']
		  );
    $this->modelname = new Institutiondcr();
    $this->data['pageFormtype']='ajax';
    $this->data['urlmenu']=$this->indexs();
               
  }

    public function index(){
    $this->data['pageMethod']=\Request::route()->getName();
        $table = \DB::table('app_institution_dcr')->get();   
        $this->data['data']=$table;
       
        return view("Institutiondcr.table",$this->data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {

      $this->data['pageModule']="institutiondcr";

            if(!empty($id))
            {
                
                 $institutiondcr= \DB::table('app_institution_dcr')->where('institution_dcr_id',$id)->get();
     foreach($institutiondcr[0] as $key => $value){
        
            $this->data[$key]=$value;

           } 
                
                 $this->data['institution_id']=$this->jCombocomp('app_institution_t','institution_id','name',$this->data['institution_id']);
           $this->data['activity_id']=$this->jCombocomp('m_activity_t','activity_id','activity_name',$this->data['activity_id']);
           $this->data['visit_with']=$this->jCombocomp('hr_employee_t','employee_id','first_name',$this->data['visit_with']);
           $this->data['area']=$this->jCombologin('m_area_t','area_id','area_name',$this->data['area']);
           $this->data['outcome_id']=$this->jCombocomp('m_outcome_t','outcome_id','outcome_name',$this->data['outcome_id']);
           if($this->data['focus_product'] != ''){
            $focus_product =implode(",",json_decode($this->data['focus_product']));
            $this->data['focus_product']=$this->jcustommultiselect('m_products_t','product_id','product_code|concatenated_product',$focus_product,'');
           }else{
            $this->data['focus_product']=$this->jcustommultiselect('m_products_t','product_id','product_code|concatenated_product',$this->data['focus_product'],'');
           }
           
           }
        else
        {
          // app_institution_dcr
          $table=\DB::getSchemaBuilder()->getColumnListing('app_institution_dcr');
          
           foreach($table as $v){
            $this->data[$v]="";
           }
           $this->data['institution_id']=$this->jCombocomp('app_institution_t','institution_id','name','');
           $this->data['activity_id']=$this->jCombocomp('m_activity_t','activity_id','activity_name','');
           $this->data['visit_with']=$this->jCombocomp('hr_employee_t','employee_id','first_name','');
           $this->data['area']=$this->jCombologin('m_area_t','area_id','area_name','');
           $this->data['outcome_id']=$this->jCombocomp('m_outcome_t','outcome_id','outcome_name','');
           $this->data['focus_product']=$this->jcustommultiselect('m_products_t','product_id','product_code|concatenated_product','','');
           // dd($table);

    }
          
        return view("Institutiondcr.form",$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

         public function save(Request $request){
           if($_POST['institution_dcr_id'] =="")
               {
                
                $institutiondcr = new Institutiondcr();
                $institutiondcr->institution_id = $request->input('institution_id');
                $institutiondcr->keycontact1      = $request->input('keycontact1');
                $institutiondcr->area       = $request->input('area');
                $institutiondcr->activity_id           = $request->input('activity_id');
                $institutiondcr->visit_with      = $request->input('visit_with');
                $institutiondcr->outcome_id          = $request->input('outcome_id');
                $institutiondcr->focus_product          = json_encode($request->input('focus_product'));
                $institutiondcr->timing    = $request->input('timing');
                $institutiondcr->created_by        =    \Session::get('id');
                $institutiondcr->save();
                $edit_id= DB::getPdo()->lastInsertId();
             $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"institutiondcr",$action,$_POST,"app_institution_dcr");
               
                return 1;
            } else 
            {
                $_POST['focus_product']= json_encode($_POST['focus_product']);
                 Institutiondcr::find($_POST['institution_dcr_id'])->update($_POST);
                 $edit_id = $_POST['institution_dcr_id'];
                  $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"institutiondcr",$action,$_POST,"app_institution_dcr");                       
         
         
                    return 2;
                }
           
          
            
      
             
                    

        }
  

    /**
     * Display the specified resource.
     *
     * @param  \App\Createuser  $createuser
     * @return \Illuminate\Http\Response
     */
    public function show(Createuser $createuser,$id=null)
    {
          if(isset($id)){
      $user=DB::table('tb_users')->leftjoin('m_organizations_t','m_organizations_t.organization_id','=','tb_users.org_id')
    ->leftjoin('m_location_t','m_location_t.location_id','=','tb_users.loc_id')
    ->leftjoin('m_company_t','m_company_t.company_id','=','tb_users.company_id')
     ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','tb_users.admindept_id') 
     ->leftjoin('a_m_group_t','a_m_group_t.group_id','=','tb_users.group_id') 
      ->select('tb_users.*','m_location_t.location_id','m_company_t.company_id','m_department_lines_t.sub_department_name','m_department_lines_t.department_line_id','a_m_group_t.group_id')
        ->where('id',$id)->get();
      $this->data['user_name']=$user[0]->username;
      $this->data['first_name']=$user[0]->first_name;
      $this->data['last_name']=$user[0]->last_name;
      $this->data['email']=$user[0]->email;
      $this->data['mobile_no']=$user[0]->mobile_no;
      $location = json_decode($user[0]->loc_id);
      $l_tion='';
        foreach ($location as $key => $value) {
          $l_tion.= $value.",";
        }
// dd($user[0]);
        $l_tion = rtrim($l_tion);
      $this->data['org_id']=$this->idname("organization_name","m_organizations_t","organization_id",$user[0]->org_id);
      $this->data['loc_id']=$this->idname("location_name","m_location_t","location_id",$l_tion);
      $this->data['company_id']=$this->idname("company_name","m_company_t","company_id",$user[0]->company_id);
      $this->data['admindept_id']=$user[0]->sub_department_name;
      $this->data['group_id']=$this->idname("group_name","a_m_group_t","group_id",$user[0]->group_id);
// dd($this->data);
 return view('createuser.view',$this->data);
      }
    }

     public function institutiondcrData($type=null){
	
    $wh='';
	$search_table=array("app_institution_t,m_area_t");
		
    if($_GET['_search']=='true')
    {
		
    $wh=$this->jqgridsearch('app_institution_dcr',$_GET['filters'],$search_table);
    }
      $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        // $wh.='and app_institution_dcr.company_id='.$compy; 
     
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if(!$sidx) $sidx =1;
$result = \DB::select("SELECT COUNT(app_institution_dcr.institution_dcr_id) AS count FROM
    app_institution_dcr
    left join app_institution_t ON
    app_institution_t.institution_id=app_institution_dcr.institution_id left join m_area_t on m_area_t.area_id=app_institution_dcr.area where 1=1 $wh");
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
    
   
   
   
    $SQL = "SELECT
    app_institution_dcr.institution_dcr_id,
  app_institution_dcr.keycontact1,
  app_institution_dcr.timing,
  app_institution_t.name,
  m_area_t.area_name
FROM
    app_institution_dcr
    left join app_institution_t ON
    app_institution_t.institution_id=app_institution_dcr.institution_id left join m_area_t on m_area_t.area_id=app_institution_dcr.area where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
 $download_SQL = "SELECT
    app_institution_dcr.institution_dcr_id,
  app_institution_dcr.keycontact1,
  app_institution_dcr.timing,
  app_institution_t.name,
  m_area_t.area_name
FROM
    app_institution_dcr
    left join app_institution_t ON
    app_institution_t.institution_id=app_institution_dcr.institution_id left join m_area_t on m_area_t.area_id=app_institution_dcr.area where 1=1 $wh ORDER BY $sidx $sord";
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Createuser  $createuser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Createuser $createuser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Createuser  $createuser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Createuser $createuser)
    {
        //
    }
}
