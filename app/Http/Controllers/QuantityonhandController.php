<?php

namespace App\Http\Controllers;

use App\Quantityonhand;
use Illuminate\Http\Request;

class QuantityonhandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
    {
        $this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs();
        // dd($this->data['urlmenu']); 
    }

/*Gegrid data*/
    public function getGridqohData1()
{ 
            
    $wh='';
    if($_GET['_search']=='true')  
    { 
        $wh=$this->jqgridsearch('m_products_t',$_GET['filters']); 
    }

    $comp=\Session::get('companyid');
        $loc=\Session::get('location');
        $groupname=\Session::get('groupname');
        if($groupname=="Superadmin" || $groupname=="Admin")
        {
            $wh.="and m_products_t.company_id=$comp";
        }
        else{
            $wh.="and m_products_t.company_id=$comp  and m_products_t.location_id=$loc";
        }
    //dd($wh);
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if(!$sidx) 
        $sidx =1;
                                        
    $sql="select product_group_id,group_name from m_product_groups_t where group_name='".$_GET['prdgrpname']."' or group_name='".$_GET['prdgrpname1']."'"; 
    $sqlresult=\DB::select($sql);
    
    $result = \DB::select("SELECT COUNT(product_group_id) AS count FROM m_products_t where 1=1 and product_group_id='".$sqlresult[0]->product_group_id."' or product_group_id='".$sqlresult[1]->product_group_id."' $wh");

    $count = $result[0]->count;
    if( $count > 0 && $limit > 0) {
        $total_pages = ceil($count/$limit);
    } else {
        $total_pages = 0;
    }

    if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
    if($start <0) 
        $start = 0;

    $SQL = "SELECT m_products_t.product_id as id,m_products_t.concatenated_product,m_product_groups_t.group_name as product_group_id,m_product_category_t.category_name as product_category_id from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) where 1=1 and (m_product_groups_t.group_name='".$_GET['prdgrpname']."' or m_product_groups_t.group_name='".$_GET['prdgrpname1']."') $wh ORDER BY $sidx $sord  LIMIT $start , $limit";
	
		$download_SQL = "SELECT m_products_t.product_id as id,m_products_t.concatenated_product,m_product_groups_t.group_name as product_group_id,m_product_category_t.category_name as product_category_id from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) where 1=1 and (m_product_groups_t.group_name='".$_GET['prdgrpname']."' or m_product_groups_t.group_name='".$_GET['prdgrpname1']."') $wh ORDER BY $sidx $sord";
		
		$result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
    
    $result = \DB::select($SQL);
   

foreach($result as $key=>$val){

$qoh_qty=\DB::select("SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id') as qoh ,( SELECT SUM(reserv_trx_qty) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '$val->id') as res,(SELECT qoh - res ) as ava,(select sum(w_materialreceive_line_t.receive_qty) as receiveqty,w_jobcard_hdr_t.job_status from w_materialreceive_line_t left join w_materialreceive_hdr_t on(w_materialreceive_hdr_t.w_materialreceive_hdr_id=w_materialreceive_line_t.w_materialreceive_hdr_id) left join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=w_materialreceive_hdr_t.w_jobs_hdr_id) where w_jobcard_hdr_t.job_status='MATERIAL RECEIVED')  FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id'  GROUP BY i_qoh_detail_t.product_id ");
//dd($qoh_qty);
if(!empty($qoh_qty)){
$result[$key]->qoh=$qoh_qty[0]->qoh;
$result[$key]->res_qty=$qoh_qty[0]->res;
$result[$key]->ava_qty=$qoh_qty[0]->ava;
}else{
   $result[$key]->qoh="0";
$result[$key]->res_qty="0";
$result[$key]->ava_qty="0"; 
}

}


    $responce->rows[]='';
   
    $responce->rows=$result;

    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
//dd(json_encode($responce));
    echo json_encode($responce);

}
/*end*/

/*Get Grid Qoh Data*/
public function getGridqohData()
{
       $wh='';
        $wh1='';
        $search['table'] = ['m_product_groups_t','m_product_category_t'];
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('m_products_t',$_GET['filters'],$search['table']);
            // dd($wh);         
        }
        $wh1.='';
        if(isset($_GET['prdgrpname2']))
            {
                 $wh1.='and m_product_groups_t.group_name in("'.$_GET['prdgrpname'].'","'.$_GET['prdgrpname1'].'","'.trim($_GET['prdgrpname2']).'")';
            }
        else if($_GET['prdgrpname1']!=""){
            
            $wh1.='and m_product_groups_t.group_name in("'.$_GET['prdgrpname'].'","'.$_GET['prdgrpname1'].'")';
        }
        else{
            $wh1.='and m_product_groups_t.group_name="'.$_GET['prdgrpname'].'"';
        }
 
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');     
        $decimal=\Session::get('decimal');     
        
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) 
            $sidx =1;
        
        $sql="select product_group_id,group_name from m_product_groups_t where 1=1 $wh1";
        $sqlresult=\DB::select($sql);
        $grp="";
        
        if(count($sqlresult)==1){
           $grp='and m_products_t.product_group_id in("'.$sqlresult[0]->product_group_id.'")';
        }
        else if(count($sqlresult)>1)
        {
            $grp='and m_products_t.product_group_id in("'.$sqlresult[0]->product_group_id.'","'.$sqlresult[1]->product_group_id.'")';       
        }
        
        $result = \DB::select("SELECT COUNT(m_products_t.product_id) AS count FROM m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) and m_products_t.company_id=$compy where 1=1 and m_products_t.company_id=$compy $grp $wh");
    
        $count = $result[0]->count;
        if( $count > 0 && $limit > 0) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages) $page=$total_pages;
            $start = $limit*$page - $limit;
        if($start <0) 
            $start = 0;

        $SQL = "SELECT m_products_t.product_id as id,m_products_t.concatenated_product,m_products_t.product_code,m_product_groups_t.group_name,m_product_category_t.category_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) where 1=1 and m_products_t.company_id=$compy  $wh1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        $download_SQL = "SELECT m_products_t.product_id as id,m_products_t.concatenated_product,m_products_t.product_code,m_product_groups_t.group_name,m_product_category_t.category_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) where 1=1 and m_products_t.company_id=$compy $wh ORDER BY $sidx $sord";
       
       
        if(isset($_GET['download']))
    {
         $result = \DB::select( $download_SQL );
    }
    else
    {
    $result = \DB::select($SQL); 
    }      

        foreach($result as $key=>$val){
			 $qoh_qty=\DB::select("SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id' AND i_qoh_detail_t.company_id='$compy' and qualitystatus = '1' and i_qoh_detail_t.subinventory_id!=5) as qoh ,( SELECT SUM((reserv_trx_qty)) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '$val->id' AND i_reservation_detail_t.company_id='$compy' and i_qoh_detail_t.subinventory_id!=5) as res,(SELECT qoh - res) as ava,(select sum(w_materialreceive_line_t.receive_qty) as receiveqty from w_materialreceive_line_t left join w_materialreceive_hdr_t on(w_materialreceive_hdr_t.w_materialreceive_hdr_id=w_materialreceive_line_t.w_materialreceive_hdr_id) left join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=w_materialreceive_hdr_t.w_jobs_hdr_id) where w_jobcard_hdr_t.job_status='MATERIAL RECEIVED' and w_materialreceive_line_t.product_id = '$val->id') as receiveqty,( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id' AND i_qoh_detail_t.company_id='$compy' and qualitystatus = '1' and i_qoh_detail_t.subinventory_id=5) as wipqoh,( SELECT SUM((reserv_trx_qty)) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '$val->id' AND i_reservation_detail_t.company_id='$compy' and i_qoh_detail_t.subinventory_id=5) as wipres,(SELECT (wipqoh - IFNULL(wipres,0))) as avawip FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id' GROUP BY i_qoh_detail_t.product_id ");
      
            if(!empty($qoh_qty)){
                if($qoh_qty[0]->res <= 0)
                {
                    $res = 0;
                }
                else
                {
                    $res = number_format($qoh_qty[0]->res,$decimal,".","");    
                }
                $result[$key]->res_qty=$res;
                    
                if($qoh_qty[0]->qoh <= 0)
                {
                    $qoh = 0;
                }
                else
                {
                    $qoh = number_format($qoh_qty[0]->qoh,$decimal,".","");    
                }
                $result[$key]->qoh=$qoh;

                if($qoh_qty[0]->ava <= 0)
                {
                    $avail = 0;
                }
                else
                {
                    $avail = number_format($qoh_qty[0]->ava,$decimal,".","");  
                }
                $result[$key]->ava_qty=$avail;
                 if($qoh_qty[0]->receiveqty <= 0)
                {
                    $recqty = 0;
                }
                else
                {
                    $recqty =  number_format($qoh_qty[0]->receiveqty,$decimal,".","");  
                }
                $result[$key]->receiveqty=$recqty;
				  if($qoh_qty[0]->avawip <= 0)
                {
                    $wipqty = 0;
                }
                else
                {
                    $wipqty = number_format($qoh_qty[0]->avawip,$decimal,".","");  
                }
                $result[$key]->wipqoh=$wipqty;
				
				 if(!isset($_GET['prdgrpname2']))
				 {
				if($result[$key]->qoh<$result[$key]->res_qty || $result[$key]->qoh==0)
				{
					 $solines=\DB::select('select sum(qty) as soqty,product_id from s_salesorder_lines_t where product_id='.$val->id.' group by product_id');
					 
					  if(count($solines)>0){
					 $result[$key]->orderres_qty=$solines[0]->soqty-$result[$key]->qoh;
					 $result[$key]->res_qty=$result[$key]->qoh;
					  }else{
					 $result[$key]->orderres_qty=0;
                     $result[$key]->res_qty=$result[$key]->res_qty;					 
					  }
					 
				}else{
					 $result[$key]->orderres_qty="0";
				}
				 }
            }
            else{
                $result[$key]->qoh="0";
                $result[$key]->res_qty="0";
                $result[$key]->ava_qty="0"; 
                $result[$key]->receiveqty="0"; 
                $result[$key]->wipqoh="0"; 
				 $result[$key]->orderres_qty="0";
            }

        }
  //dd($result);
        $responce->rows[]='';       
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        if(isset($_GET['download']))
    {
         $result=collect($result)->map(function($x){ return (array) $x; })->toArray();
        return $result;
    }
        echo json_encode($responce);

    }
    /*End*/
   /*Get Sub Grid Qoh Data*/

public function getSubGridqohData()
{
    $wh='';
    if($_GET['_search']=='true')
    {
        $wh=$this->jqgridsearch('i_qoh_detail_t',$_GET['filters']);
    }
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if(!$sidx) 
        $sidx =1;

    $result = \DB::select("SELECT COUNT(product_id) AS count FROM i_qoh_detail_t where 1=1 $wh");
    
    $count = $result[0]->count;
    if( $count > 0 && $limit > 0) {
        $total_pages = ceil($count/$limit);
    } else {
        $total_pages = 0;
    }
    $pd_id = $_GET['product_id'];
    if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
    if($start <0) 
        $start = 0;


    if(isset($_GET['product_id'])){
        if($_GET['product_id']!=""){	
            $id = "and i_qoh_detail_t.product_id=".$_GET['product_id'];
        }else{
        	$id ="";
        }
    }

    $SQL = "SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$pd_id') as qoh ,( SELECT SUM(reserv_trx_qty) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '$pd_id') as res,(SELECT qoh - res ) as ava FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$pd_id'  GROUP BY i_qoh_detail_t.product_id ";

       
    $result = \DB::select($SQL);
    // dd($result);
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
    echo json_encode($responce);

}
		/*end*/

        /*Loading Index Data*/
         public function index()
    {
        $routename=\Request::route()->getName();
        // dd($routename);
       if($routename=="rawquantityonhand"){
           $this->data['productgroup']='RAW MATERIALS'; 
           $this->data['productgroup1']='PACKING MATERIALS';
           $this->data['pageMethod']=\Request::route()->getName();
           
       }else if($routename=="fgquantityonhand"){
            $this->data['productgroup']='FINISHED GOODS';
            $this->data['productgroup1']='SEMI FINISHED GOODS';
            $this->data['pageMethod']="fgquantityonhand";           
       }else{
        $this->data['productgroup']='CONSUMABLES';
            $this->data['productgroup1']='PROMOTIONAL ITEMS';
            $this->data['productgroup2']='ACCESSORIES   ';
            $this->data['pageMethod']="quantityonhand";
       }
        
        $condition = " and group_name='".$this->data['productgroup']."' or group_name='".$this->data['productgroup1']."'";
            $this->data['totalqoh']=$this->getTotalqoh($this->data['productgroup']);
            
            $this->data['prdgrpopt']=$this->jqgridcustselect('m_product_groups_t','product_group_id','group_name',$condition);
        
            $this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
            $this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');

        //dd($this->data);
            $wh='';
        $wh1='';
            $this->data['urlmenu']=$this->indexs();
            $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
        
            $wh1.='and m_product_groups_t.group_name in("'.$this->data['productgroup'].'","'.$this->data['productgroup1'].'")';

            $SQL = "SELECT m_products_t.product_id as id,m_products_t.concatenated_product,m_products_t.product_code,m_product_groups_t.group_name as product_group_id,m_product_category_t.category_name as product_category_id from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) where 1=1 and m_products_t.company_id=$compy  $wh1 $wh ";
        $result = \DB::select($SQL);       

        foreach($result as $key=>$val){

             if($val->product_group_id=="FINISHED GOODS" || $val->product_group_id=="SEMI FINISHED GOODS") 
        {
            $qoh_qty=\DB::select("SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id' AND i_qoh_detail_t.company_id='$compy' and qualitystatus = '1') as qoh ,( SELECT SUM(ABS (reserv_trx_qty)) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '$val->id' AND i_reservation_detail_t.company_id='$compy') as res,(SELECT qoh - res) as ava,(select sum(w_materialreceive_line_t.receive_qty) as receiveqty from w_materialreceive_line_t left join w_materialreceive_hdr_t on(w_materialreceive_hdr_t.w_materialreceive_hdr_id=w_materialreceive_line_t.w_materialreceive_hdr_id) left join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=w_materialreceive_hdr_t.w_jobs_hdr_id) where w_jobcard_hdr_t.job_status='MATERIAL RECEIVED' and w_materialreceive_line_t.product_id = '$val->id') as receiveqty FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id' GROUP BY i_qoh_detail_t.product_id ");
 			 }else{   
            $qoh_qty=\DB::select("SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id' AND i_qoh_detail_t.company_id='$compy') as qoh ,( SELECT SUM(ABS (reserv_trx_qty)) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '$val->id' AND i_reservation_detail_t.company_id='$compy') as res,(SELECT qoh - res) as ava,(select sum(w_materialreceive_line_t.receive_qty) as receiveqty from w_materialreceive_line_t left join w_materialreceive_hdr_t on(w_materialreceive_hdr_t.w_materialreceive_hdr_id=w_materialreceive_line_t.w_materialreceive_hdr_id) left join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=w_materialreceive_hdr_t.w_jobs_hdr_id) where w_jobcard_hdr_t.job_status='MATERIAL RECEIVED' and w_materialreceive_line_t.product_id = '$val->id') as receiveqty FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id' GROUP BY i_qoh_detail_t.product_id ");
        }

                
            if(!empty($qoh_qty)){
                if($qoh_qty[0]->res <= 0)
                {
                    $res = 0;
                }
                else
                {
                    $res = $qoh_qty[0]->res;    
                }
                $result[$key]->res_qty=$res;
                    
                if($qoh_qty[0]->qoh <= 0)
                {
                    $qoh = 0;
                }
                else
                {
                    $qoh = $qoh_qty[0]->qoh;    
                }
                $result[$key]->qoh=$qoh;

                if($qoh_qty[0]->ava <= 0)
                {
                    $avail = 0;
                }
                else
                {
                    $avail = $qoh_qty[0]->ava;  
                }
                $result[$key]->ava_qty=$avail;
                 if($qoh_qty[0]->receiveqty <= 0)
                {
                    $recqty = 0;
                }
                else
                {
                    $recqty = $qoh_qty[0]->receiveqty;  
                }
                $result[$key]->receiveqty=$recqty;
            }
            else{
                $result[$key]->qoh="0";
                $result[$key]->res_qty="0";
                $result[$key]->ava_qty="0"; 
                $result[$key]->receiveqty="0"; 
            }

        }

        $responce->rows[]='';       
        $responce->rows=$result;
        
           $this->data['result'] = json_encode($result);
// dd($this->data);

       return view('Quantityonhand.index',$this->data);
    }
/*End*/
   
	/*Get Total Qoh Data*/
    function getTotalqohold($groupname){

        $sql="select product_group_id,group_name from m_product_groups_t where group_name='".$groupname."'";
        $sqlresult=\DB::select($sql);    

        $qoh="SELECT sum(qoh_trx_qty) FROM `i_qoh_detail_t` WHERE product_id = '500' ";
        
        $qohresult=\DB::Select($qoh);
        // dd($qoh);
        return $qohresult[0]->totalqoh;
    }

/*End*/
/*Get Total Qoh Data*/
    function getTotalqoh($groupname){
    	$sql="select product_group_id,group_name from m_product_groups_t where group_name='".$groupname."'";
        $sqlresult=\DB::select($sql);	

        $qoh="SELECT sum(qohtbl.available_qoh) as totalqoh from (SELECT
                i_qoh_detail_t.qoh_trx_qty,
                i_reservation_detail_t.reserv_trx_qty,
                (
                    i_qoh_detail_t.qoh_trx_qty - i_reservation_detail_t.reserv_trx_qty
                ) AS available_qoh
            FROM
                i_qoh_detail_t
            LEFT JOIN i_reservation_detail_t ON
                (
                    i_reservation_detail_t.product_id = i_qoh_detail_t.product_id
                )
            LEFT JOIN m_products_t ON
                (
                    m_products_t.product_id = i_qoh_detail_t.product_id
                )
            WHERE
                1 = 1 AND m_products_t.product_group_id = ".$sqlresult[0]->product_group_id."
            group by
             i_qoh_detail_t.product_id
            ORDER BY
                i_qoh_detail_t.product_id) qohtbl";
    	$qohresult=\DB::Select($qoh);
        // dd($qoh);
    	return $qohresult[0]->totalqoh;

    }
/*End*/

}