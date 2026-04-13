<?php
namespace App\Http\Controllers;

use App\Mtltransactiontypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class MtltransactiontypesController extends Controller
{

    public function __construct()
    {
        $this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs(); 

    }

	
    public function getTransactionData()
    {
    $wh='';


        $comp=\Session::get('companyid');
        $loc=\Session::get('location');
        $groupname=\Session::get('groupname');
        if($groupname=="1" || $groupname=="Admin")
        {
            $wh.="and m_transaction_types_t.company_id=$comp";
        }
        else{
            $wh.="and m_transaction_types_t.company_id=$comp  and m_transaction_types_t.location_id=$loc";
        }
        
   
    $SQL = "SELECT m_transaction_types_t.*,src.lookup_meaning as transaction_source_id,actn.lookup_meaning as transaction_action_id,m_transaction_types_t.transaction_source_id as trans_id,tb_users.first_name,m_transaction_types_t.transaction_action_id  as trans_action_id FROM m_transaction_types_t LEFT JOIN a_lookuplines_t as src on m_transaction_types_t.transaction_source_id = src.lookuplines_id LEFT JOIN a_lookuplines_t as actn on m_transaction_types_t.transaction_action_id = actn.lookuplines_id  left join `tb_users` on (tb_users.id=m_transaction_types_t.created_by) where 1=1 $wh";


    $result = \DB::select( $SQL );
		
	return DataTables::of($result)->make(true);


        }


	/*Main Psge Load Function*/
    public function index()
    {
       $table = \DB::table('m_transaction_types_t')->get();
	  $this->data['datas'] = json_encode($table);
      $this->data['source']=$this->jqgridselect('a_lookuplines_t','lookuplines_id','lookup_meaning');
      
        $this->data['action']=$this->jqgridselect('a_lookuplines_t','lookuplines_id','lookup_meaning');
	   $this->data['pageMethod']=\Request::route()->getName();


       return view('mtltransactiontypes.table',$this->data);
    }




/*Create Function*/
    public function create()
    {
        $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
        $this->data['transaction_source_id']=$this->jcustomselect("a_lookuplines_t","lookuplines_id","lookup_meaning",""," and lookup_type='Transaction Source'");
        $this->data['transaction_action_id']=$this->jcustomselect("a_lookuplines_t","lookuplines_id","lookup_meaning",""," and lookup_type='Transaction Action'");
        $this->data['source']=$this->jqgridcustselect('a_lookuplines_t','lookuplines_id','lookup_meaning'," and lookup_type='Transaction Source'");
      
        $this->data['action']=$this->jqgridcustselect('a_lookuplines_t','lookuplines_id','lookup_meaning'," and lookup_type='Transaction Action'");
        $this->data['pageMethod']="mtltransactiontypes";


		 return view('mtltransactiontypes.form',$this->data);
    }
/*End*/

/*Edit Function*/
    public function edit(Mtltransactiontypes $mtltransactiontypes)
    {

		$id=$_GET['transaction_type_id'];
        $mtltraxtype =mtltransactiontypes::find($id);

		return $mtltraxtype;
    }

    public function getedit($edit_id)
    {
        $column = array('trx_type_id');
        $table = array('m_material_trx_t');
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
    }
/*End*/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /*save Function*/
    public function save(Request $request)
    {
			$mtltraxtype = new Mtltransactiontypes();
 			$edit_id = $request->input('edit_id');

        if($edit_id == '')
        {

        $mtltraxtype->transaction_type_code=$_POST['transaction_type_code'];
		$mtltraxtype->transaction_type_name=$_POST['transaction_type_name'];
		$mtltraxtype->transaction_source_id=$_POST['transaction_source_id'];
		$mtltraxtype->transaction_action_id=$_POST['transaction_action_id'];
		$mtltraxtype->description=$_POST['description'];
        $mtltraxtype->created_by=$_POST['created_by'];
		$mtltraxtype->active=$_POST['active'];
        $mtltraxtype->updated_at =  "";
        $mtltraxtype->created_at =  "";
        $mtltraxtype->location_id = \Session::get('location');
        $mtltraxtype->company_id =  \Session::get('companyid');
            $mtltraxtype->save();
            $edit_id= DB::getPdo()->lastInsertId();
            $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"mtltransactiontypes",$action,$_POST,"m_transaction_types_t");
         $status['status'] = 'success';
         $status['message'] = 'Saved Successfully';
         $status['id'] = $edit_id;
         $status['type'] = 'create';
            return $status;
        }
        else
        {
             $edit_id=$_POST['edit_id'];
             Mtltransactiontypes::find($edit_id)->update($_POST);
              $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"mtltransactiontypes",$action,$_POST,"m_transaction_types_t");
			 $status['status'] = 'success';
             $status['message'] = 'Updated Successfully';
             $status['id'] = $edit_id;
			 $status['type'] = 'edit';
            return $status;
        }


    }
/*End*/
    /**
     * Display the specified resource.
     *
     * @param  \App\Mtltransactiontypes  $mtltransactiontypes
     * @return \Illuminate\Http\Response
     */
    /*View Function*/
    public function view($id=null)
    {
		if(isset($id))
		{
		   $this->data['values']=Mtltransactiontypes::find($id);
		   return view('mtltransactiontypes.view',$this->data);
		}


    }
    /*End*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mtltransactiontypes  $mtltransactiontypes
     * @return \Illuminate\Http\Response
     */
    /*Delete Function*/
     public function destroy($del_id)
    {
        $column = array('trx_type_id');
        $table = array('m_material_trx_t');
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
             $query = \DB::table('m_transaction_types_t')->where('transaction_type_id',$del_id)->delete();
              /**Auditlog**/
             $this->auditlog($del_id,"productgroup","delete","","m_product_groups_t");
        }

		return $j;

       /* if($j == 1){
			 $status['status'] = 'info';
             $status['message'] = 'Deletion Error Already Used In Somewhere!!!';
            return $status;

		} else if($j == 0){
			 $status['status'] = 'success';
             $status['message'] = 'Deleted Successfully!!!';
            return $status;
		} */

    }
/*End*/

/*Get Grid Data Function*/
	public function getGridMtlData()
	{

		$wh='';
		if($_GET['_search']=='true')
		{
		  $wh=$this->jqgridsearch($_GET['filters'],$tables);
		}

		$page=$_GET['page'];
		$limit = $_GET['rows']; //dd($limit);
		$sidx = $_GET['sidx']; //dd($sidx);
		$sord = $_GET['sord'];

		if(!$sidx)$sidx=1;


		   $result = \DB::select("SELECT COUNT(transaction_type_id) AS count FROM m_transaction_types_t where 1=1 $wh");
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
		 //dd($sidx);
		//$SQL = "SELECT * FROM products_subcategory_t where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		$SQL = "SELECT * FROM m_transaction_types_t where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";

		$result = \DB::select( $SQL );

		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		echo json_encode($responce);


	}
/*End*/

/*Get Check Name Function*/
	public function getCheckname(Request $request)
    {
        $arr = array();
        $edit_id = $_GET['edit_id'];
        if($edit_id == ''){
            $group=\DB::table('m_transaction_types_t')->where('transaction_type_name',$_GET['transaction_type_name'])->get();
            $group1=\DB::table('m_transaction_types_t')->where('transaction_source_id',$_GET['transaction_source_id'])->get();
            $group2=\DB::table('m_transaction_types_t')->where('transaction_action_id',$_GET['transaction_action_id'])->get();
        }else
        {
            $whereData = [['transaction_type_name',$_GET['transaction_type_name']],['transaction_source_id', $_GET['transaction_source_id']],['transaction_action_id', $_GET['transaction_action_id']],['transaction_type_id','!=', $edit_id]];

            $group=\DB::table('m_transaction_types_t')->where($whereData)->get();

        }
        if(count($group) > 0 && count($group1) > 0 && count($group) > 0){
            if(count($group) > 0 )
                array_push($arr, 1);
            if(count($group1) > 0 )
                array_push($arr, 2);
            if(count($group2) > 0 )
                array_push($arr, 3);
            return $arr;
        }
        else{
            array_push($arr, 0);
            return $arr;
        }


    }
    /*End*/
}
