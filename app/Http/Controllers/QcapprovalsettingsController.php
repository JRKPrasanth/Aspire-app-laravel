<?php

namespace App\Http\Controllers;

use App\qcapprovalsettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class QcapprovalsettingsController extends Controller
{
    public function __construct()
    {
        $this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs(); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=nulL)
    {

        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $sql = \DB::table('m_qcapproval_settings_t')->select('*')->where('qcapproval_id',$id)->get();

            $this->data['qc_checker_id'] = $sql[0]->qc_checker_id;
            $this->data['qc_approver_id'] =$sql[0]->qc_approver_id;
            $this->data['product_group_id']=$sql[0]->product_group_id;
            $this->data['product_category_id']=$sql[0]->product_category_id;
            $this->data['created_by']=\Session::get('id');
            $this->data['qcapproval_id'] = $sql[0]->qcapproval_id;

            return $this->data;
// dd($this->data['qc_approver_id']);
        } else {
    $this->data['qcapproval_id'] = "";
    $this->data['created_by']=$this->jCombologin('tb_users','id','employee_number|username',\Session::get('id'));
    $this->data['qc_checker_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name','');
    $this->data['qc_approver_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name','');
    $this->data['product_group_id']=$this->jCombologin('m_product_groups_t','product_group_id','group_name','');
    $this->data['product_category_id']='';

    $this->data['pageMethod']=\Request::route()->getName();
    return view('qcapprovalsettings.form',$this->data);
}


   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function qcapprovalsettingssave(Request $request){
//         dd($request['qc_approver_id']);
        $edit_id = $request['edit_id'];
        if($edit_id == '')
        {
        $qcapproval = new qcapprovalsettings();
        $qcapproval->product_group_id = $request['product_group_id'];
        $qcapproval->product_category_id = $request['product_category_id'];
        $qcapproval->qc_approver_id = implode(",",$request['qc_approver_id']);
        $qcapproval->qc_checker_id = implode(",",$request['qc_checker_id']);
        $qcapproval->created_by = $request['created_by'];
        $qcapproval->company_id = \Session::get('companyid');
        $qcapproval->location_id = \Session::get('location');
        // dd($qcapproval);
        if($qcapproval->save()){
        return response()->json(array('status'=>'Success','message'=>'Saved Successfully'));
          }else{
            return response()->json(array('status'=>'error','message'=>'Not Saved'));
          }
      }
      else{
         $qcapproval = new qcapprovalsettings();
        $qcapproval['qc_approver_id'] = implode(",",$request['qc_approver_id']);
        $qcapproval['qc_checker_id'] = implode(",",$request['qc_checker_id']);
        $data=\DB::table('m_qcapproval_settings_t')
    ->where('qcapproval_id', $edit_id)
    ->update(['product_group_id'=>$request['product_group_id'],'product_category_id'=>$request['product_category_id'],'qc_approver_id'=>$qcapproval['qc_approver_id'],'qc_checker_id'=>$qcapproval['qc_checker_id']]);
    if($data == 1){
        return response()->json(array('status'=>'Success','message'=>'Updated Successfully'));
    }
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\qcapprovalsettings  $qcapprovalsettings
     * @return \Illuminate\Http\Response
     */
    public function show(qcapprovalsettings $qcapprovalsettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\qcapprovalsettings  $qcapprovalsettings
     * @return \Illuminate\Http\Response
     */
    /*public function edit(qcapprovalsettings $qcapprovalsettings)
    {
        productco
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\qcapprovalsettings  $qcapprovalsettings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, qcapprovalsettings $qcapprovalsettings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\qcapprovalsettings  $qcapprovalsettings
     * @return \Illuminate\Http\Response
     */
    public function destroy(qcapprovalsettings $qcapprovalsettings)
    {
        //
    }
    public function getqcapprovaldata()
        {
        $wh='';
        if($_GET['_search']=='true')
        {
        $tables=[];
        $tables[]="m_qcapproval_settings_t";
        $wh=$this->jqgridsearch('m_product_groups_t',$_GET['filters'],$tables);

        }
        $comp=\Session::get('companyid');
        $loc=\Session::get('location');
        $groupname=\Session::get('groupname');
        // dd($groupname);
        if($groupname=="Superadmin" || $groupname=="Admin")
        {
        $wh.="and m_qcapproval_settings_t.company_id=$comp";
        }
        else{
        $wh.="and m_qcapproval_settings_t.company_id=$comp  and m_qcapproval_settings_t.location_id=$loc";
        }

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT COUNT(qcapproval_id) AS count FROM m_qcapproval_settings_t left join `tb_users` on (tb_users.id=m_qcapproval_settings_t.created_by) where 1=1 $wh ");
        $count = $result[0]->count;
        if( $count > 0 && $limit > 0) {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }

        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $SQL = "SELECT m_qcapproval_settings_t.*,tb_users.username,m_product_category_t.category_name,m_product_groups_t.group_name,qc_checker.username as qc_checker,qc_approver.username as qc_approver  FROM m_qcapproval_settings_t left join `tb_users` on (tb_users.id=m_qcapproval_settings_t.created_by) left join m_product_category_t on(m_product_category_t.product_category_id=m_qcapproval_settings_t.product_category_id) left join m_product_groups_t on (m_product_groups_t.product_group_id=m_qcapproval_settings_t.product_group_id) left join tb_users as qc_checker on (qc_checker.id=m_qcapproval_settings_t.qc_checker_id) left join tb_users as qc_approver on (qc_approver.id=m_qcapproval_settings_t.qc_approver_id) where 1=1 $wh   ORDER BY m_qcapproval_settings_t.qcapproval_id desc LIMIT $start , $limit";

        $download_SQL = "SELECT m_qcapproval_settings_t.*,tb_users.username,m_product_category_t.category_name,m_product_groups_t.group_name,qc_checker.username as qc_checker,qc_approver.username as qc_approver  FROM m_qcapproval_settings_t left join `tb_users` on (tb_users.id=m_qcapproval_settings_t.created_by) left join m_product_category_t on(m_product_category_t.product_category_id=m_qcapproval_settings_t.product_category_id) left join m_product_groups_t on (m_product_groups_t.product_group_id=m_qcapproval_settings_t.product_group_id) left join tb_users as qc_checker on (qc_checker.id=m_qcapproval_settings_t.qc_checker_id) left join tb_users as qc_approver on (qc_approver.id=m_qcapproval_settings_t.qc_approver_id) where 1=1 $wh   ORDER BY m_qcapproval_settings_t.qcapproval_id";
        $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }

        $result = \DB::select( $SQL );
// dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);

        }
}
