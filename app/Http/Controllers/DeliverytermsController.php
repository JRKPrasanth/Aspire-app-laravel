<?php
namespace App\Http\Controllers;
use App\Deliveryterms;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class DeliverytermsController extends Controller
{

  public function __construct()
  {
      $this->data=array();
	  $this->model=new Deliveryterms();
        $this->data['pageMethod']=\Request::route()->getName();
      $this->data['pageFormtype']='ajax';
	  $this->data['urlmenu']=$this->indexs(); 

  }
  
    public function index()
    {
		  $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
    }


// table data	
	
  public function getGridData($type)
  {   
        $wh='';
	  
         if($type!='')
  {
    $wh.=" and source_type_id='".$type."'";
  }
	  
            $org=\Session::get('organization'); 
            $com=\Session::get('companyid');
 
     
            $groupname=\Session::get('groupname');
              if($groupname=='1' || $groupname=='Admin'){
            $wh.='and  m_delivery_terms_t.company_id='.$com;  
            }else{
                $wh.='and  m_delivery_terms_t.company_id='.$com;  
            } 
            
             $SQL = "SELECT m_delivery_terms_t.* ,tb_users.first_name,tb_users.employee_id,tb_users.id FROM m_delivery_terms_t left join tb_users on tb_users.id =m_delivery_terms_t.created_by where 1=1 and m_delivery_terms_t.company_id=$com $wh order by m_delivery_terms_t.delivery_terms_id DESC";
	  

            $result = \DB::select( $SQL );
			 return DataTables::of($result)->make(true);
	  
  }
	
  


// Purpose for Create Function
	
         public function create($id=null){
		
                $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
                $this->data['urlname'] = \Request::route()->getName();
                if($this->data['urlname']=="purchasedeliveryterms")
                {
                    $this->data['source_type_id'] ="Purchase";
                }else{
                    $this->data['source_type_id'] ="Sales";
                }
			 
		$this->data['start_date']=date("d-m-Y");
        $deliveryterms = Deliveryterms::find($id);
			
        $this->data['deliveryterms']=$deliveryterms;
			 
        return view('deliveryterms.form',$this->data);
			 
      }

	

    // Purpose For Save Function
    public function save(Request $request)
    {
         $edit_id =$request->input('delivery_terms_id');
         $deliveryterms = new Deliveryterms();

        if($edit_id == '')
        {
        $deliveryterms->organization_id=\Session::get('organization'); 
        $deliveryterms->company_id=\Session::get('companyid');
        $deliveryterms->location_id="1";
        $deliveryterms->delivery_term_name=$_POST['delivery_term_name'];
        $deliveryterms->source_type_id=$_POST['source_type_id'];
        $deliveryterms->active=$_POST['active'];
        $deliveryterms->remarks=$_POST['remarks'];
        $deliveryterms->created_by=\Session::get('id');
	$deliveryterms->save();
        $edit_id= DB::getPdo()->lastInsertId();
        $action="Create";
        /**Auditlog**/
            $this->auditlog($edit_id,"deliveryterms",$action,$_POST,"m_delivery_terms_t");
        return response()->json(array('status' => 'success', 'message' => 'Deliveryterms Saved Successfully','id'=>$edit_id));

        }
        else
        {
          $action="Edit";
          $data['delivery_term_name']=$_POST['delivery_term_name'];
          $data['source_type_id']=$_POST['source_type_id'];         
          $data['remarks']=$_POST['remarks'];
          $data['active']=$_POST['active'];
          \DB::table('m_delivery_terms_t')->where('delivery_terms_id', $edit_id)->update($data);
          /**Auditlog**/
            $this->auditlog($edit_id,"deliveryterms",$action,$_POST,"m_delivery_terms_t");
        return response()->json(array('status' => 'success', 'message' => 'Deliveryterms Updated Successfully','id'=>$edit_id));
        }
          
        return 2;

        }
  /*END*/

/* Purpose for Delete Function*/
 public function delete(Request $request,$id=null,$type=null){
      if($type=="Sales")
        {
        $column = array('delivery_terms_id','delivery_terms_id','ar_delivery_terms_id','delivery_term_id');
        $table = array('m_customers_t','s_quote_hdr_t','s_salesorder_hdr_t','s_invoice_hdr_t');
        }
        else
        {
        $column = array('delivery_terms_id','delivery_terms_id','delivery_terms_id');
        $table = array('m_supplier_t','p_quotation_hdr_t','p_po_hdr_t');   
        }
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                return $j;
                break;
            }
        }
        if($j==0){
        $query = \DB::table('m_delivery_terms_t')->where('delivery_terms_id',$id)->delete();
        }
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id,"deliveryterms",$action,$id,"m_delivery_terms_t");
          return $j;
  }
  /*End*/

        /* Purpose for Used Data Should Not Allow to Edit Function*/
  public function getedit($edit_id,$type)
  {
   if($type=="Sales")
        {
        $column = array('delivery_terms_id','delivery_terms_id','ar_delivery_terms_id','delivery_term_id');
        $table = array('m_customers_t','s_quote_hdr_t','s_salesorder_hdr_t','s_invoice_hdr_t');
        }
        else
        {
        $column = array('delivery_terms_id','delivery_terms_id','delivery_terms_id');
        $table = array('m_supplier_t','p_quotation_hdr_t','p_po_hdr_t');   
        }
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
  /*END*/
  /* Purpose for Duplicate Validation Function*/
    public function getCheckname(Request $request)
    {
        $edit_id = $_GET['edit_id'];
        $source_type_id = $_GET['source_type_id'];
        if($edit_id == '')
            $department=DB::table('m_delivery_terms_t')->where('delivery_term_name',$_GET['delivery_term_name'])->where('source_type_id',$source_type_id)->get();
        else
        {
            $whereData = [['delivery_term_name', $_GET['delivery_term_name']],['delivery_terms_id', '!=', $edit_id]];
            $department=DB::table('m_delivery_terms_t')->where($whereData)->where('source_type_id',$source_type_id)->get();
        }
        if(count($department)>0)
            return 1;
        else
            return 0;
    }
    /*END*/

  }
