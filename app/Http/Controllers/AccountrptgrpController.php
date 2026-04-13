<?php

namespace App\Http\Controllers;

use App\Accountrptgrp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AccountrptgrpController extends Controller
{
    public function __construct()
	{
        
		    $this->data=array(
             'pageModule'=> 'Accountrptgrp',
             'pageUrl'	=>  url('accountrptgrp')
              );
                    $this->data['urlmenu']=$this->indexs(); 
			$this->model=new Accountrptgrp();
		    $this->data['pageFormtype']='ajax';	
                    $this->data['pageMethod']=\Request::route()->getName();
	}

    
/*Karthigaa purpose for display account class data in JQgrid*/
    public function getAccountrptgrpData()
    {
		$wh='';
		if($_GET['_search']=='true')
		{
            $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);
		}
        $compy=\Session::get('companyid');
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
    		$SQL = "SELECT * FROM(
        		        SELECT 
        		            f_account_reporting_group_t.acc_rpt_grp_id,
        		            f_account_reporting_group_t.rpt_grp,
                            f_account_reporting_group_t.rpt_type,
                            f_account_reporting_group_t.rpt_seq,
                            f_account_reporting_group_t.active,
                            tb_users.username
                        FROM 
                            f_account_reporting_group_t 
                        left join tb_users on tb_users.id =f_account_reporting_group_t.created_by)v1
                        where 1=1 $wh"; 		
            $result =\DB::select($SQL);
            
        $count = count($result);
        if($limit==0)
            $limit=$count;
        
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
        
    		$SQL = "SELECT * FROM(
        		        SELECT 
        		            f_account_reporting_group_t.acc_rpt_grp_id,
        		            f_account_reporting_group_t.rpt_grp,
                            f_account_reporting_group_t.rpt_type,
                            f_account_reporting_group_t.rpt_seq,
                            f_account_reporting_group_t.active,
                            tb_users.username
                        FROM 
                            f_account_reporting_group_t 
                        left join tb_users on tb_users.id =f_account_reporting_group_t.created_by)v1
                        where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit"; 	
	 
	    $download_SQL = "SELECT * FROM(SELECT 
        		            f_account_reporting_group_t.acc_rpt_grp_id,
        		            f_account_reporting_group_t.rpt_grp,
                            f_account_reporting_group_t.rpt_type,
                            f_account_reporting_group_t.rpt_seq,
                            f_account_reporting_group_t.active,
                            tb_users.username
                        FROM 
                            f_account_reporting_group_t 
                        left join tb_users on tb_users.id =f_account_reporting_group_t.created_by )v1
                        where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
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
    
   	public function create($id=null){
   	    
	    $acc_rpt_grp1=(object)array();
        $acc_rpt_grp=\DB::connection()->getSchemaBuilder()->getColumnListing('f_account_reporting_group_t');
	   // dd($acc_rpt_grp);
    	foreach($acc_rpt_grp as $key=>$value){
    		$acc_rpt_grp1->$value="";
    	}
    	$this->data['row']=$acc_rpt_grp1;
    	$this->data['row']->account_class_id ='';
        $user=\Session::get('id');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $user);
    	return view('accountrptgrp.form',$this->data);
	}
 

    
    public function save(Request $request){
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            $accountrptgrp = new Accountrptgrp();
            $accountrptgrp->rpt_grp=$_POST['account_group'];
            $accountrptgrp->rpt_type=$_POST['account_type'];
            $accountrptgrp->rpt_seq=$_POST['account_seqno'];
            $accountrptgrp->active=$_POST['active'];
            $accountrptgrp->created_by=$_POST['created_by'];  
            $accountrptgrp->created_at=date('Y-m-d h:i:s');
            $accountrptgrp->last_updated_by=$_POST['created_by'];
            $accountrptgrp->updated_at=date('Y-m-d h:i:s');
            $accountrptgrp->save();
        	return response()->json(array('status' => 'success', 'message' => 'Account Reporting Group Saved Successfully!!','id'=>$edit_id));
	    }
		else
		{
            $accountrptgrp  = Accountrptgrp::where('acc_rpt_grp_id',$edit_id)->firstOrFail();;
            $input_data= array( 
                    'rpt_gtp'=>$request->input('account_group'),
                    'rpt_type'=>$request->input('account_type'),
                    'rpt_seq'=>$request->input('account_seqno'),
                    'active'=> $request->input('active'),
                    'last_updated_by'=>$request->input('created_by')
                    );
            $accountrptgrp->fill($input_data)->save();  
			return response()->json(array('status' => 'success', 'message' => 'Account Reporting Group Updated Successfully!!','id'=>$edit_id));
		}
    }
 /*Karthigaa purpose for check Duplicate function*/
    public function getCheckname(Request $request){
        $edit_id = $_GET['edit_id'];
        if($edit_id == ''){
            $whereData = [['rpt_grp', $_GET['account_rpt_grp']]];
            $acc_class=DB::table('f_account_reporting_group_t')->where($whereData)->get();
        }
        else
        {
            $whereData = [['rpt_grp', $_GET['account_rpt_grp']],['acc_rpt_grp_id', '!=', $edit_id]];
            $acc_class=\DB::table('f_account_reporting_group_t')->where($whereData)->get();
        }
        
        if(count($acc_class)>0)
            return 1;
        else
            return 0;
    }
    /*Karthigaa purpose for delete function*/
  public function delete($del_id){
        $column = array('account_class_id');
        $table = array('f_account_codes_lines_t');
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
             $query = \DB::table('f_account_class_t')->where('account_class_id',$del_id)->delete();
        }
		return $j;
     }	

}
