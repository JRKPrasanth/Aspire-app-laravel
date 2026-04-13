<?php

namespace App\Http\Controllers;
use App\Paymentforinvoice;
use App\Paymentdetails;
use Illuminate\Http\Request;
use DB;
use Session;
use Yajra\DataTables\DataTables;

class PaymentdetailsController extends Controller
{
   public $module="paymentdetails";
	public function __construct()
	{
		$this->data=array();
        $this->data=array();
		$this->table="p_payments_t";
		$this->pageModule="paymentdetails";
        $this->model=new Paymentdetails();
		$this->model=new Paymentdetails;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
        $this->table="p_payments_t";
        $this->data['urlmenu']=$this->indexs(); 
	}
	
	 public function paymentindex(Request $request){
     // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END

         $this->data['pageMethod']=\Request::route()->getName();
         $this->data['ledger'] = $this->jcustomselecttool("f_account_structure_t", "f_account_structure_id", "account_name", "", " AND (concatenated_segments LIKE '%TDS%' OR concatenated_segments LIKE '%TCS%')");
         
         return view('paymentdetails.payment_table',$this->data);
    }   
	
	 public function index(Request $request){
	     // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END
 	
		 
	  return view('paymentdetails.table',$this->data);
		 
	 }
	
      public function getpaymentdetailsData(){
		  
                $wh='';
                $wh1='';
                
        $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $groupname=\Session::get('groupname');

		$wh.=$grid_data=$this->grid_check('p_payments_t','payment_date');
        $emp=\Session::get('emp_id');     
		  
        if($emp=='148' || $emp=='157' || $emp=='159' || $emp=='1' || $emp=='152' || $emp=='82'){
             $wh.='';   
        }else{
            $wh.=" and p_payments_t.payment_source!='SALARYPAYMENT'";
        }

                $SQL = "SELECT * FROM (SELECT 
               p_payments_t.*,
                p_po_invoice_hdr_t.bill_number,
                m_supplier_t.supplier_name,
                hr_employee_t.first_name,
                f_bank_account_hdr_t.bank_name,
                m_customers_t.customer_name
                FROM p_payments_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_payments_t.supplier_id)
                left join hr_employee_t on (hr_employee_t.employee_id=p_payments_t.employee_id)
				left join m_customers_t on (m_customers_t.customer_id=p_payments_t.customer_id)
                left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
                left join f_bank_account_hdr_t on(f_bank_account_hdr_t.bank_account_hdr_id=p_payments_t.bank_id)
                where 1=1 and p_payments_t.batch_status !='GENERATED' and (p_payments_t.payment_type_id='NEFT' OR p_payments_t.payment_type_id='CHEQUE' OR p_payments_t.payment_type_id='IMPS' OR p_payments_t.payment_type_id='RTGS') $wh) as v1 where 1=1 $wh1 ORDER BY v1.payment_id DESC";

		$result = \DB::select( $SQL );
		return DataTables::of($result)->make(true);
		  
	}
	
	
	        public function paymentadvicedata($id=null){
               
           require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');     
				
            $SQL = "SELECT 
                p_payments_t.*,
                p_payments_t.payment_id,
                p_payments_t.payment_number,
                p_po_invoice_hdr_t.bill_number,
                m_supplier_t.supplier_name,
                hr_employee_t.first_name,
                f_bank_account_hdr_t.bank_name,
                p_payments_t.payment_date,
                p_payments_t.payment_amount,
            	p_payments_t.payment_type_id,
				p_payments_t.payment_reference,
        	    p_payments_t.bank_date,
                p_payments_t.cheque_no,
				m_customers_t.customer_name,
                p_payments_t.paid_amount
                FROM p_payments_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_payments_t.supplier_id)
                left join hr_employee_t on (hr_employee_t.employee_id=p_payments_t.employee_id)
				left join m_customers_t on (m_customers_t.customer_id=p_payments_t.customer_id)
                left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
                left join f_bank_account_hdr_t on(f_bank_account_hdr_t.bank_account_hdr_id=p_payments_t.bank_id)
                where 1=1 and   p_payments_t.payment_id in($id) ";


                    $ttot=\DB::select("SELECT 
                Sum(payment_amount) as alltot
                FROM p_payments_t 
                where 1=1 and  p_payments_t.payment_id in($id) ");

           $this->data['alltot']=$ttot[0]->alltot; 

                
            $result = \DB::select($SQL);
            $company_id=\Session::get('companyid');
            $company_name=DB::table('m_company_t')->where('company_id',$company_id)->get();
            $this->data['company_name']=$company_name[0]->company_name; 
            $this->data['company_logo'] =$company_name[0]->company_logo_name;
            $this->data['website_address']=$company_name[0]->website_address; 
            $this->data['cin_no']=$company_name[0]->cin_no; 
            $this->data['pan_no']=$company_name[0]->pan_no; 
            $this->data['gst_no']=$company_name[0]->gst_no; 
            $this->data['company_logo'] =$company_name[0]->company_logo_name;
                $address=$this->getLocationwiseaddress($company_name[0]->location_id);

	if($address!=0)
	{
		$location_name=$address[0]->location_name;
		$this->data['location_name']= $location_name;
		$this->data['address']=$address[0]->address;
		$this->data['street_name'] =$address[0]->street_name;
		$this->data['location']=$address[0]->location_name;
		$this->data['area']=$address[0]->area;
		//GET COMPANY ADDRESS
		$this->data['company_gst_no']= '';
		//$this->data['pan_no']=$address[0]->pan_no;
		$this->data['city']=$this->getCity($address[0]->city_id);
		$this->data['loc_city']=$this->getCity($address[0]->city_id);
		$this->data['state']=$this->getState($address[0]->state_id);
		$this->data['state_no']=$state=$this->data['state'][0]->state_id;
		$this->data['state_name']=$state=$this->data['state'][0]->state_name;
		$this->data['state_code']=$state=$this->data['state'][0]->state_code;
		$this->data['country']=$this->getCountry($address[0]->country_id);
		$this->data['loc_country']=$this->getCountry($address[0]->country_id);
		//$this->data['gst_no']=$address[0]->gst_no;
		$this->data['e_mail']=$address[0]->e_mail;
		$this->data['state_code']=$this->data['state'][0]->state_code;
		$this->data['pincode']=$address[0]->pincode;
		$this->data['phone']=$address[0]->Phone;
		$this->data['web']=$address[0]->Web;
		$this->data['cin']=$address[0]->CIN;
	}

		$this->data['company_address']=$this->data['address'].",";
$this->data['comp_address']=$this->data['city'].",".$this->data['pincode'].','.$this->data['state_name'].",".$this->data['country'];


$this->data['company_address']=ucwords($this->data['company_address']);

	if($address==0)
	{
		array_push($l_error,"Check Organisation or company Details");
	}
         
            $this->data['details']=$result;
            $this->data['id']=$id;
            return view('paymentdetails.paymentadvice',$this->data);
    
          }
	
		       function getState($id=null){
        $sql=array();

        $sql=\DB::SELECT('select state_id,state_name,state_code,state_code_no from m_states_t where state_id='.$id.'');
        if(!empty($sql)){
            return $sql;

        }else{
            return 0;
        }

    }
	
	 public function getpaymentdetail(){
                $wh='';
                $wh1='';
                ini_set('memory_limit', '-1');
                
        $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $groupname=\Session::get('groupname');

           $emp=\Session::get('emp_id');    
        if($emp=='148' || $emp=='157' || $emp=='159' || $emp=='1' || $emp=='152' ){
             $wh.='';   
        }else{
            $wh.=" and p_payments_t.payment_source!='SALARYPAYMENT'";
        }
        $wh.=$grid_data=$this->grid_check('p_payments_t','payment_date');
		
              $result = \DB::select("SELECT * FROM (SELECT 
                p_payments_t.*,
                p_po_invoice_hdr_t.bill_number,
                p_po_invoice_hdr_t.attachfile_name,
                m_supplier_t.supplier_name,
                hr_employee_t.first_name,
                imp.first_name as imprest_employee_name,
                f_bank_account_hdr_t.bank_name,
                m_customers_t.customer_name,(select narration from f_bankstmtupload_t where p_payments_t.stmtid=f_bankstmtupload_t.bankstmt_id) as narration
                FROM p_payments_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_payments_t.supplier_id)
                left join hr_employee_t on (hr_employee_t.employee_id=p_payments_t.employee_id)
                left join hr_employee_t imp on (imp.employee_id=p_payments_t.imprest_employee_id)
                left join m_customers_t on (m_customers_t.customer_id=p_payments_t.customer_id)
                left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
                left join f_bank_account_hdr_t on(f_bank_account_hdr_t.bank_account_hdr_id=p_payments_t.bank_id)
                where 1=1 and (p_payments_t.batch_status !='INITIATED' OR (p_payments_t.batch_status ='INITIATED' and p_payments_t.payment_type_id='CASH' OR p_payments_t.payment_type_id='IMPREST' OR p_payments_t.payment_type_id='ONLINE')) $wh) as v1 where 1=1 $wh1 ORDER BY v1.payment_id DESC");
		 
if (count($result) > 0) {
    foreach ($result as $k => $v) {
        // --- Invoice section ---
        if (!empty($v->po_invoice_id)) {
            $query = \DB::select("SELECT * FROM `p_po_invoice_hdr_t` WHERE `po_invoice_id` IN ($v->po_invoice_id)");
            $poinvoices = json_decode(json_encode($query), true);

            $invoiceLinks = [];
            foreach ($query as $v1) {
                $url = \URL::to('invoiceDataview/' . $v1->po_invoice_id . '?return=purchaseinvoice');
                $invoiceLinks[] = "<a href='$url' target='_blank' class='badge bg-primary text-decoration-none me-1 mb-1'>"
                                . $v1->bill_number . "</a>";
            }
            $result[$k]->bill_number = implode(' ', $invoiceLinks);
        } else {
            $result[$k]->bill_number = "";
        }

        // --- Attachment section ---
        if (!empty($v->attachfile_name)) {
            $dataupload = json_decode($v->attachfile_name);
            $attachmentLinks = [];

            if (!empty($dataupload) && !empty($v->po_invoice_id)) {
                $query = \DB::select("SELECT * FROM `p_po_invoice_hdr_t` WHERE `po_invoice_id` IN ($v->po_invoice_id)");
                foreach ($query as $v2) {
                    foreach ($dataupload as $fileName) {
                        $poinvid = $v2->po_invoice_id;
                        $filePath = "../uploads/purchaseinvoice/PO$poinvid/$fileName";
                        $attachmentLinks[] = "<a href='$filePath' target='_blank' class='badge bg-success text-decoration-none me-1 mb-1'>"
                                           . $fileName . "</a>";
                    }
                }
            }

            $result[$k]->attachfile_name = implode(' ', $attachmentLinks);
        } else {
            $result[$k]->attachfile_name = "";
        }
    }
}

		 return DataTables::of($result)->rawColumns(['bill_number', 'attachfile_name'])  ->make(true);

		 
	}
         
	
	
     public function reportindex(){
         
      	 $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
         $this->data['invoicenoopt'] = $this->jqgridselect('p_po_invoice_hdr_t', 'po_invoice_id', 'bill_number');
         $this->data['bankopt'] = $this->jqgridselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name');
         $table = \DB::table('p_payments_t')->get();
   	 $this->data['datas'] = $table;
		  $org=\Session::get('organization');
        $loc="1";
        $compy=\Session::get('companyid');
        
                $SQL = "SELECT 
                p_payments_t.payment_id,
                p_payments_t.payment_number,
                p_po_invoice_hdr_t.bill_number as po_invoice_id,
                 m_supplier_t.supplier_name as supplier_id,
                p_payments_t.payment_date,
                p_payments_t.payment_amount,
		p_payments_t.payment_type_id,
                p_payments_t.paid_amount
                FROM p_payments_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_payments_t.supplier_id)
                left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
                where 1=1  and p_payments_t.company_id=$compy and p_payments_t.location_id=$loc  order by p_payments_t.payment_id desc";
		$result = \DB::select( $SQL );
		 $this->data['result']=json_encode($result);
         
         return view('paymentdetails.paymentdetailtable',$this->data);
    }
    

		public function getpaymentdetailsreportData(Request $request)
	{
			
		$compy=\Session::get('companyid');	
		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
			
    $SQL = "SELECT * from (
 SELECT 
                p_payments_t.payment_id,
                p_payments_t.payment_number,
                p_po_invoice_hdr_t.bill_number,
                m_supplier_t.supplier_name,
                f_bank_account_hdr_t.bank_name,
                p_payments_t.payment_date,
                p_payments_t.payment_amount,
		p_payments_t.payment_type_id,
                p_payments_t.paid_amount
                FROM p_payments_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_payments_t.supplier_id)
                left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
                left join f_bank_account_hdr_t on(f_bank_account_hdr_t.bank_account_hdr_id=p_payments_t.bank_id)
                where 1=1 AND p_payments_t.payment_date BETWEEN ? AND ? and p_payments_t.company_id=$compy) AS v1";

     $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
	
	function getpayablesreport($id=null){
		 $this->data['data']=$row=\DB::table('p_payments_t')->select('p_payments_t.*','p_po_invoice_hdr_t.*','p_po_invoice_hdr_t.po_invoice_id','m_supplier_t.supplier_id','m_supplier_t.supplier_name')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_payments_t.supplier_id')
				->leftJoin('p_po_invoice_hdr_t', 'p_po_invoice_hdr_t.po_invoice_id', '=', 'p_payments_t.po_invoice_id')
                ->where('payment_id',$id)->get();
				//dd($row);
				$duedate=$row[0]->due_date;
				$current_date=date('Y-m-d');
				//dd($duedate);
				$date = strtotime($duedate);
				$currentdate = strtotime($current_date);
				$datediff = $currentdate - $date;
                                $due_date=round($datediff / (60 * 60 * 24));
				//dd($due_date);
			 $this->data['duedate']=$due_date;
			
			  return view('paymentdetails.payablesreport',$this->data);
		  }
          
         /* Karthigaa purpose:For Payment details Download */           
Public function paymentsamedownloaddata(){
 $id=$_GET['payment_id'];
if($_GET['type']=="same")
{
	//dd($id);
	$payment=\DB::select("select * from p_payments_t where payment_id IN ($id) and (supplier_bank_id='IOB' or supplier_bank_id='INDIAN OVERSEAS BANK' or supplier_bank_id='IOB-OD') and (payment_type_id!='CASH' or payment_type_id!='CHEQUE') ");
//dd($payment);	
//$payment=\DB::table('p_payments_t')->whereIn('payment_id',explode(',',$id))->where('supplier_bank_id','IOB')->where('payment_type_id','!=','CASH')->where('payment_type_id','!=','CHEQUE')->get();
//dd($payment);

}
else if($_GET['type']=="bob"){
    $payment=\DB::select("select *,CONCAT(sender_information,'',supplier_account_name) as sender_info,LEFT(supplier_account_no, 4) as sup_ac_no from p_payments_t where payment_id IN ($id) and bank_id = 80 and (payment_type_id!='CASH' or payment_type_id!='CHEQUE') ");
}
else if($_GET['type']=="icicicrdcard"){
    $payment=\DB::select("select *,CONCAT(sender_information,'',supplier_account_name) as sender_info,LEFT(supplier_account_no, 4) as sup_ac_no from p_payments_t where payment_id IN ($id) and payment_source='Contraentry' ");
}
else
{
     $payment=\DB::table('p_payments_t')->select('p_payments_t.*','p_payments_t.supplier_ifsc_code as IFSCCode','p_payments_t.sender_information as sender_information',
             'm_supplier_t.supplier_name as SenderInformation','f_bank_account_lines_t.account_type_id as AccountType',
             'p_payments_t.supplier_account_no as AccountNumber','p_payments_t.supplier_account_name as NameoftheBeneficiary',
             'm_supplier_sites_t.supplier_site_id','m_customer_sites_t.customer_site_id','hr_emp_contact.current_city','suppliercity.city_name as supAddressoftheBeneficiary',
			 'customercity.city_name as cusAddressoftheBeneficiary','employeecity.city_name as empAddressoftheBeneficiary','p_payments_t.payment_amount as Amount')
            ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_payments_t.supplier_id')
			->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 'p_payments_t.customer_id')
			->leftJoin('m_supplier_sites_t',function($join){$join->on('m_supplier_sites_t.supplier_id','=','m_supplier_t.supplier_id')->where('m_supplier_sites_t.primary_address','=','Yes');
				})
		->leftJoin('m_customer_sites_t',function($join){$join->on('m_customer_sites_t.customer_id','=','m_customers_t.customer_id')->where('m_customer_sites_t.primary_address','=',"Yes");
				})
			->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'p_payments_t.employee_id')
			->leftJoin('hr_emp_contact', 'hr_emp_contact.employee_id', '=', 'hr_employee_t.employee_id')
			->leftJoin('m_cities_t as suppliercity', 'suppliercity.city_id', '=', 'm_supplier_sites_t.city')
			->leftJoin('m_cities_t as customercity', 'customercity.city_id', '=', 'm_customer_sites_t.city')
			->leftJoin('m_cities_t as employeecity', 'employeecity.city_id', '=', 'hr_emp_contact.current_city')
            ->leftJoin('f_bank_account_hdr_t', 'f_bank_account_hdr_t.bank_account_hdr_id', '=', 'p_payments_t.bank_id')
            ->leftJoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
            ->whereIn('payment_id',explode(',',$id))->where('supplier_bank_id','!=','IOB')->where('payment_type_id','!=','CASH')->where('payment_type_id','!=','CHEQUE')->get();
		
}

\DB::table('p_payments_t')->whereIn('payment_id',explode(',',$id))->update(['batch_status'=>'GENERATED']);
//dd($payment);

   $data1 ='';
   if($_GET['type']=="same")
{
   $data1 .= "Account Number".","."Amount".","."Narration"."\r\n";
        foreach($payment as $row)
            {
				
				
                     $amount=number_format($row->payment_amount,2,'.','');
                     $name=str_replace('.',' ',$row->supplier_account_name);
                 $data1 .= $row->supplier_account_no.",".$amount.",".$row->sender_information." ".$name."\r\n";
            }
}
else if($_GET['type']=="bob"){
    $data1 .= "1281".","."12810200001010".","."INR".","."DR".","."".","."BY JRK"."\r\n";
        foreach($payment as $row)
            {
				
				
                     $amount=number_format($row->payment_amount);
                     $name=str_replace('.',' ',$row->supplier_account_name);
                 $data1 .= $row->sup_ac_no.",".$row->supplier_account_no.","."INR".","."CR".",".$amount.",".$row->sender_info."\r\n";
            }
}
else if($_GET['type']=="icicicrdcard"){
    $data1 .= "IFSC Code".","."Account Type".","."Account Number".","."Name of the Beneficiary".","."Address of the Beneficiary".","."Sender Information".","."Amount"."\r\n";
        foreach($payment as $row)
            {
				
				
                     $amount=number_format($row->payment_amount,2,'.','');
                     $ifsc = 'ICIC0000004';
                     $acc_type='11';
                     $acc_no='4102021164782000';
                     $name='Rajagopal J K';
                     $AddressoftheBeneficiary='MUMBAI';
                     $send_info='ICICICRDCARDPMT';
                 $data1 .= $ifsc.",".$acc_type.",".$acc_no.",".$name.",".$AddressoftheBeneficiary.",".$send_info.",".$amount."\r\n";
            }
}
else
{
	
$data1 .= "IFSC Code".","."Account Type".","."Account Number".","."Name of the Beneficiary".","."Address of the Beneficiary".","."Sender Information".","."Amount"."\r\n";
  
        foreach($payment as $row)
            {
				
				if($row->supAddressoftheBeneficiary!=null){
					$AddressoftheBeneficiary=$row->supAddressoftheBeneficiary;
				}
				elseif($row->cusAddressoftheBeneficiary!=null){
					$AddressoftheBeneficiary=$row->cusAddressoftheBeneficiary;
				}else{
					$AddressoftheBeneficiary=$row->empAddressoftheBeneficiary;
				}
				
                $amount=number_format($row->payment_amount,2,'.','');
                $data1 .= $row->IFSCCode.",".$row->AccountType.",".$row->AccountNumber.",".$row->NameoftheBeneficiary.",".$AddressoftheBeneficiary.",".$row->sender_information.",".$amount."\r\n";
            }
}
    
            
         
$month=(string)date('M');
$date=(string)date('d');
$year=(string)date('Y');
$count=count($payment);
                        $my_file =$month.$date.$year.$count.'.txt';
                        $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
                                fwrite($handle,$data1);

                      return response()->json(array('data'=> $data1,'download_ling'=>$my_file));

                    }
                    
/* Karthigaa purpose: to get cheque details */

    public function getPaymentcheque($id = null) {
		
		require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        $acpayee=$_GET['acpayee'];
        $updatestatus=\DB::Select("update p_payments_t set batch_status='GENERATED',ac_payee_status='$acpayee' where payment_id='$id' " );

        $sql = \DB::Select('select p_payments_t.*,f_bank_account_hdr_t.bank_name,f_bank_account_lines_t.account_number,f_bank_account_lines_t.name_in_account,p_payments_t.favouring_name 
                            from p_payments_t 
                            left join f_bank_account_hdr_t on (f_bank_account_hdr_t.bank_account_hdr_id=p_payments_t.bank_id)
                            left join f_bank_account_lines_t on (f_bank_account_lines_t.bank_account_hdr_id=f_bank_account_hdr_t.bank_account_hdr_id)
                            where payment_id=' . $id);
						//	dd($sql);


        $arr = array();
        foreach ($sql as $key => $value) {
            $arr[$key]['cheque_no'] = $value->cheque_no;
            $arr[$key]['batch_line_amount'] = $value->payment_amount;
            $arr[$key]['batch_total'] = $value->payment_amount;
        }$this->data['cheque_no'] = $sql[0]->cheque_no;
        $this->data['batch_line_amount'] = $sql[0]->payment_amount;
        $this->data['bank_name'] = $sql[0]->bank_name;

        $this->data['account_number'] = '';
		if($_GET['account_type_name']=="favouring_name"){
			$this->data['name_in_account'] = $sql[0]->favouring_name;
            $this->data['ac_payee_status'] = $sql[0]->ac_payee_status;
		}else{
			$this->data['name_in_account'] = $sql[0]->supplier_account_name;
            $this->data['ac_payee_status'] = $sql[0]->ac_payee_status;
		}
        
        $this->data['payment_date'] = str_replace('-', '', date("d-m-Y", strtotime($sql[0]->payment_date)));
        $this->data['payment_amount'] = $sql[0]->payment_amount;
        $this->data['result'] = $arr;

        return view('paymentdetails.cheque_print', $this->data);
    }
	
  /*  purpose: to get voucher details */
    public function getPaymentvoucher($id = null) {
		
			require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
			$company_id = \Session::get('companyid');

 /***To Get company name ***/
            $company=$this->getCompany($company_id);
            if(!empty($company))
            {
            $company_name=$company[0]->company_name;
            $company_logo_name=$company[0]->company_logo_name;
            $this->data['company_name']= $company_name;
            $this->data['company_logo_name']= $company_logo_name;
            $this->data['cin_no']= $company[0]->cin_no;
            $this->data['gst_no']=$company[0]->gst_no;
            }

       $comp = \DB::table('m_company_t')->leftjoin('m_company_line_t','m_company_line_t.companyid','m_company_t.company_id')->leftjoin('m_location_t','m_location_t.location_id','m_company_line_t.locationid')->leftjoin('m_countries_t','m_location_t.country_id','m_countries_t.country_id')->leftjoin('m_cities_t','m_location_t.city_id','m_cities_t.city_id')->leftjoin('m_states_t','m_location_t.state_id','m_states_t.state_id')->where('m_company_t.company_id',$company_id)->get();
        $this->data['company_name'] = $comp[0]->company_name;
        $company_address='';
         $this->data['address0'] = $comp[0]->address;
         $this->data['address2'] = $comp[1]->address;
         $this->data['location_name1'] = $comp[0]->location_name;
         $this->data['location_name2'] = $comp[1]->location_name;
         $this->data['city_name1'] = $comp[0]->city_name;
         $this->data['city_name2'] = $comp[1]->city_name;
         $this->data['pincode1'] = $comp[0]->pincode;
         $this->data['pincode2'] = $comp[1]->pincode;
         $this->data['country_name1'] = $comp[0]->country_name;
         $this->data['country_name2'] = $comp[1]->country_name;
         $this->data['contact_no1'] = $comp[0]->contact_no;
         $this->data['contact_no2'] = $comp[1]->contact_no;
         $this->data['email_id'] = $comp[0]->email_id;
          $this->data['contact_no'] = $comp[0]->contact_no;
           $this->data['website_address'] =$comp[0]->website_address;  
         
         $company_address.="CIN : ".$comp[0]->cin_no;
        $this->data['company_address'] = $company_address;
        
        
        $p_id=explode(",",$id);
        
       foreach($p_id as $pk=>$id)
       {

       $sql = \DB::Select('select p_payments_t.*,f_bank_account_hdr_t.bank_name,f_bank_account_lines_t.account_number,f_bank_account_lines_t.name_in_account,m_supplier_t.supplier_name,hr_employee_t.employee_number,hr_employee_t.first_name,hr_employee_t.last_name,m_supplier_sites_t.address,m_supplier_sites_t.pincode,m_cities_t.city_name,m_states_t.state_name,m_countries_t.country_name,m_customers_t.customer_name,m_customer_sites_t.address as c_address,c_city.city_name as c_city,c_states.state_name as c_state,c_countries.country_name as c_country,m_customer_sites_t.pincode as c_pincode
                            from p_payments_t 
                           left join m_supplier_t on (m_supplier_t.supplier_id=p_payments_t.supplier_id)
                           left join m_customers_t on (m_customers_t.customer_id=p_payments_t.customer_id)
                           left join m_customer_sites_t on (m_customer_sites_t.customer_id=m_customers_t.customer_id And m_customer_sites_t.primary_address="Yes" and m_customer_sites_t.site_type="SHIP_TO")
                           left join m_cities_t as c_city on (m_customer_sites_t.city=c_city.city_id)
                           left join m_states_t as c_states on (m_customer_sites_t.state=c_states.state_id)
                           left join m_countries_t as c_countries on (m_customer_sites_t.country=c_countries.country_id)
                           left join hr_employee_t on (hr_employee_t.employee_id=p_payments_t.employee_id)
                           left join m_supplier_sites_t on (m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id And m_supplier_sites_t.primary_address="Yes")
                           left join m_cities_t on (m_supplier_sites_t.city=m_cities_t.city_id)
                           left join m_states_t on (m_supplier_sites_t.state=m_states_t.state_id)
                           left join m_countries_t on (m_supplier_sites_t.country=m_countries_t.country_id)
                           left join f_bank_account_hdr_t on (f_bank_account_hdr_t.bank_account_hdr_id=p_payments_t.bank_id)
                           left join f_bank_account_lines_t on (f_bank_account_lines_t.bank_account_hdr_id=f_bank_account_hdr_t.bank_account_hdr_id)
                            where p_payments_t.payment_id='.$id );
       
      //dd($sql);
      
        $arr = array();
$polines=array();
  $tot="0";
  $tdstot="0";
  $tdsadd="0";

$this->data['payment_source'][$pk] =$sql[0]->payment_source;
$this->data['debit_brkup'][$pk] =$sql[0]->debit_brkup;
$this->data['debit_amount'][$pk] =$sql[0]->debit_amount;
//dd("dfgdf");
if($sql[0]->payment_source=="INVOICE"){
        //Invoice Amount
       $invid = $this->data['po_invoice_id'][$pk] = $sql[0]->po_invoice_id;
      if($invid!=''){
      $invid= \DB::select("select tds_applicable,tds_amount,bill_number,invoice_date,invoice_grand_total,paid_amount from p_po_invoice_hdr_t where po_invoice_id in($invid)");
    foreach ($invid as $key => $value) {              
              if($invid !='0'){
            if($value->tds_applicable=='Yes' || $value->tds_applicable=='YES'){
                $polines[$key]['tds_applicable'] =$value->tds_applicable;
                $tdsadd=$value->tds_amount;
                 $tdstot+=$polines[$key]['tds_amount'] =$value->tds_amount;
             }
             else{
                 $polines[$key]['tds_applicable'] ="";
                 $tdsadd=$polines[$key]['tds_amount'] =$value->tds_amount;
             }
			// $tot+=$polines[$key]['paid_amount'] = $value->paid_amount+$tdsadd;
            $polines[$key]['bill_number'] = $value->bill_number;
             $polines[$key]['invoice_date'] = $value->invoice_date;
             $polines[$key]['invoice_grand_total'] = $value->invoice_grand_total+$tdsadd;
          }
	}
if($sql[0]->invoice_brkup!=""){
$as=explode(',', $sql[0]->invoice_brkup);
 foreach ($as as $key => $vlue) {
    $tot+= $polines[$key]['paid_amount'] =$vlue;
   // dd($vlue);
 }  
 
}else{
    foreach ($invid as $key => $value) {              
    $tot+=$polines[$key]['paid_amount'] = $value->paid_amount+$tdsadd;
    }
}    


          
      }
}


if($sql[0]->payment_source=="RMA"){
$invid= \DB::select("select tds_applicable,tds_amount,total_amount,rma_ref_no,reference_no,return_date,paid_amount from so_rma_hdr_t where so_rma_hdr_id in(".$sql[0]->reference_id.")");
//dd($invid);
 $tot="0";
  foreach ($invid as $key => $value) {
              
              if($invid !='0'){
             $polines[$key]['bill_number'] = $value->rma_ref_no;
             $polines[$key]['invoice_date'] = $value->return_date;
             $tot+=$polines[$key]['invoice_grand_total'] = $value->total_amount;
			 $polines[$key]['paid_amount'] = $value->paid_amount;
              $polines[$key]['tds_applicable'] ="";
                 $polines[$key]['tds_amount'] =0;
            }
            
          }
    }
if($sql[0]->payment_source=="CREDIT/DEBIT"){

$invid= \DB::select("select tds_applicable,tds_amount,debitcredit_amount,debitcredit_no,debitcredit_date,paid_amount from f_debitcredit_t where debitcredit_id in(".$sql[0]->reference_id.")");
//dd($invid);
 $tot="0";
  foreach ($invid as $key => $value) {
              
              if($invid !='0'){
             $polines[$key]['bill_number'] = $value->debitcredit_no;
             $polines[$key]['invoice_date'] = $value->debitcredit_date;
             $polines[$key]['invoice_grand_total'] = $value->debitcredit_amount;
			 $tot+=$polines[$key]['paid_amount'] = $value->paid_amount;
              $polines[$key]['tds_applicable'] ="";
                 $polines[$key]['tds_amount'] =0;
            }
            
          }

    }
if($sql[0]->payment_source=="TRAVEL"){
          // travel
              $travel = $sql[0]->reference_id;
       $tot="0";
       $tdstot="0";
      if($travel!=''){
      $invid= \DB::select("select claim_title,travel_date,approve_amount from hr_employee_travel_claim_t where travel_claim_id = $travel");


    foreach ($invid as $key => $value) {
              
              if($invid !='0'){
             $polines[$key]['bill_number'] = $value->claim_title;
             $polines[$key]['invoice_date'] = $value->travel_date;
             $polines[$key]['invoice_grand_total'] = $value->approve_amount;
			 $tot+=$polines[$key]['paid_amount'] = $value->approve_amount;
              $polines[$key]['tds_applicable'] ="";
                 $polines[$key]['tds_amount'] =0;
            }
            
          }
      }
}
          //imprest
if($sql[0]->payment_source=="IMPREST"){
        $imprest= $sql[0]->reference_id;
       $tot="0";
      if($imprest!=''){
      $invid= \DB::select("select imprest_number,imprest_date,amount from hr_imprest_tbl where imprest_id =$imprest");


    foreach ($invid as $key => $value) {
              
              if($invid !='0'){
             $polines[$key]['bill_number'] = $value->imprest_number;
             $polines[$key]['invoice_date'] = $value->imprest_date;
             $polines[$key]['invoice_grand_total'] = $value->amount;
			 $tot+=$polines[$key]['paid_amount'] = $value->amount;
              $polines[$key]['tds_applicable'] ="";
                 $polines[$key]['tds_amount'] =0;
            }
            
          }
      }
}
if($sql[0]->payment_source=="ADVANCE"){
        $po_id= $sql[0]->po_hdr_id;
       $tot="0";
       $tdstot="0";
      if($po_id!=''){ 
      $invid= \DB::select("select po_hdr_id,po_number,po_date,po_grand_total,reverse_charge,advance_amount from p_po_hdr_t where po_hdr_id IN ($po_id)");
//dd($sql);

//dd($as);
//mydd($as);
if($sql[0]->advance_brkup){
$as=explode(',', $sql[0]->advance_brkup);
 foreach ($as as $key => $vlue) {
  //   dd($vlue);
    $tot+= $polines[$key]['paid_amount'] =$vlue;
 }
}else{
	foreach ($invid as $key => $value) {
		 $tot+=$polines[$key]['paid_amount'] = $value->advance_amount;
	}
}
   //dd($as);
    foreach ($invid as $key => $value) {
             
              if($invid !='0'){
                 $po_tax_total =0;
                if($value->reverse_charge=='1'){
                   // dd($po_tax_total);
                   $poidd = $value->po_hdr_id;
                    $rev= \DB::select("select po_tax_total,po_grand_total,reverse_charge from p_po_hdr_t where po_hdr_id = $poidd and reverse_charge='1' ");
                 $po_tax_total += $rev[0]->po_tax_total;                    
                 //dd($po_tax_total);     
                }else{
                    $po_tax_total +=0;        
                }
				//dd($sql[0]->tds_amount);
				//dd($sql[0]->payment_amount);
				$polines[$key]['tds_applicable'] =$sql[0]->tds_applicable;
                 $tdstot+=$polines[$key]['tds_amount'] =$sql[0]->tds_amount;
             $polines[$key]['bill_number'] = $value->po_number;
             $polines[$key]['invoice_date'] = $value->po_date;	
             $polines[$key]['invoice_grand_total'] = $value->po_grand_total-$po_tax_total;
              
            }
            
          }
		  
      }
}
if($sql[0]->payment_source=="EXPENSE"){
        $exp_id= $sql[0]->reference_id;
       $tot="0";
       $tdstot="0";
 
      if($exp_id!=''){
		  //dd($exp_id);
      $invid= \DB::select("select tds_applicable,tds_amount,invoice,bill_date as expense_date,expense_amount,paid_amount,expense_id from f_expenses_t where expense_id in ($exp_id)");

       if($sql[0]->expense_brkup){
$as=explode(',', $sql[0]->expense_brkup);
 foreach ($as as $key => $vlue) {
  //   dd($vlue);
    $tot+= $polines[$key]['paid_amount'] =$vlue;
 }
}else{
    foreach ($invid as $key => $value) {
          if($invid !='0'){
            if($value->tds_applicable=="YES"){
                $invidlines= \DB::select("select sum(expense_line_amount) as expense_line_amount,sum(tax_amount) as tax_amount from f_expenses_lines_t where expense_id ='$value->expense_id' ");

                     $invidhdr= \DB::select("select reverse_charge from f_expenses_t  where expense_id ='$value->expense_id' ");
         $tot+=$polines[$key]['paid_amount'] = $value->paid_amount;
             if($invidhdr[0]->reverse_charge!='1'){
                    $polines[$key]['tds_amount'] =$value->tds_amount; 
                    $tot+=$polines[$key]['paid_amount'] = $value->paid_amount+$value->tds_amount; 
                    }else{
                       $tot+=$polines[$key]['paid_amount'] = $value->paid_amount+$value->tds_amount;   
             }
     }else{

         $tot+=$polines[$key]['paid_amount'] = $value->paid_amount;
     }
    
    }
}
}

   //   dd($invid);
    foreach ($invid as $key => $value) {
              
              if($invid !='0'){
				    if($value->tds_applicable=="YES"){
                     $invidlines= \DB::select("select sum(expense_line_amount) as expense_line_amount,sum(tax_amount) as tax_amount from f_expenses_lines_t where expense_id ='$value->expense_id' ");

                     $invidhdr= \DB::select("select reverse_charge from f_expenses_t  where expense_id ='$value->expense_id' ");
                     
             if($invidhdr[0]->reverse_charge!='1'){
             $polines[$key]['tds_applicable'] =$value->tds_applicable;
             $tdstot+=$polines[$key]['tds_amount'] =$value->tds_amount;  
             $polines[$key]['bill_number'] = $value->invoice;
             $polines[$key]['invoice_date'] = $value->expense_date;
             //if($invidlines[0]->expense_line_amount)
             $polines[$key]['invoice_grand_total'] = $invidlines[0]->expense_line_amount + $invidlines[0]->tax_amount;
          //  $tot+=$polines[$key]['paid_amount'] = $value->paid_amount+$value->tds_amount;
                            }else{
                                $polines[$key]['tds_applicable'] =$value->tds_applicable;
                                $tdstot+=$polines[$key]['tds_amount'] =$value->tds_amount;  
                                $polines[$key]['bill_number'] = $value->invoice;
                                $polines[$key]['invoice_date'] = $value->expense_date;
                                //if($invidlines[0]->expense_line_amount)
                                $polines[$key]['invoice_grand_total'] = $invidlines[0]->expense_line_amount;
                             //   $tot+=$polines[$key]['paid_amount'] = $value->paid_amount+$value->tds_amount;
                                 }
                  }else{
                       $polines[$key]['tds_applicable'] =$value->tds_applicable;
                 $polines[$key]['tds_amount'] =0;  
             $polines[$key]['bill_number'] = $value->invoice;
             $polines[$key]['invoice_date'] = $value->expense_date;
           ///  $tot+=$polines[$key]['paid_amount'] = $value->paid_amount;
             $polines[$key]['invoice_grand_total'] = $value->expense_amount;
                  }
            // $tot+=$polines[$key]['invoice_grand_total'] = $value->expense_amount;
            
            }
            
          }
		  
      }
}
// direct expense
if($sql[0]->payment_source=="DIRECTEXPENSE"){
    
   
             $polines[0]['bill_number'] = "-";
             $polines[0]['invoice_date'] ="-";
             $polines[0]['invoice_grand_total'] = 0;
			 $tot+=$polines[0]['paid_amount'] = 0;
   				 $polines[0]['tds_applicable'] ="";
                 $polines[0]['tds_amount'] =0;
}
            $this->data['tdstotal'][$pk] =$tdstot;
           $this->data['grand_total'][$pk] =$tot;
          // dd($polines);
           if(count($polines)==0){
                 $polines[0]['bill_number'] = "-";
             $polines[0]['invoice_date'] ="-";
             $polines[0]['invoice_grand_total'] = 0;
			 $tot+=$polines[0]['paid_amount'] = 0;
              $polines[0]['tds_applicable'] ="";
                 $polines[0]['tds_amount'] =0;
           }
$this->data['linedata'][$pk]  = $polines;
    
        foreach ($sql as $key => $value) {
            $arr[$key]['cheque_no'][$pk] = $value->cheque_no;
            $arr[$key]['cheque_date'][$pk] = $value->cheque_date;
            $arr[$key]['payment_amount'][$pk] = $value->payment_amount;
        }
         if(count($sql)>0){
        $this->data['payment_number'][$pk] = $sql[0]->payment_number;
        $this->data['payment_name'][$pk] = $sql[0]->payment_type_id;
        if($sql[0]->payment_type_id !="CHEQUE")
        {
           $this->data['cheque_no'][$pk] = "";  
           $this->data['cheque_date'][$pk] = "";
        }
        else {
            $this->data['cheque_no'][$pk] = $sql[0]->cheque_no;
            $this->data['cheque_date'][$pk] = $sql[0]->cheque_date;
        }
        $this->data['payment_reference'][$pk] = $sql[0]->payment_reference;
        $this->data['remarks'][$pk] = $sql[0]->remarks;

        if($sql[0]->supplier_id !=0){
        $this->data['supplier_name'][$pk] = $sql[0]->supplier_name;
		 $this->data['favouring_name'][$pk] = $sql[0]->favouring_name;
        $this->data['supplier_address'][$pk] = $sql[0]->address.",".$sql[0]->city_name.",".$sql[0]->state_name.",".$sql[0]->country_name."-".$sql[0]->pincode;
        }
        else if(($sql[0]->employee_id !=0)){
        $this->data['supplier_name'][$pk] = $sql[0]->first_name." ".$sql[0]->last_name." (".$sql[0]->employee_number.")"; 
        $this->data['supplier_address'][$pk]= "";
		 $this->data['favouring_name'][$pk] = "";
        }
		else if(($sql[0]->customer_id !=0)){
         $this->data['supplier_name'][$pk] = $sql[0]->customer_name;
		  $this->data['favouring_name'][$pk] = "";
        $this->data['supplier_address'][$pk] = $sql[0]->c_address.",".$sql[0]->c_city.",".$sql[0]->c_state.",".$sql[0]->c_country."-".$sql[0]->c_pincode;
        }
       else if($sql[0]->payment_source=='Contraentry'){
            $refid=$sql[0]->reference_id;
    $cont=\DB::SELECT("SELECT * FROM f_account_contraentry_t left join f_bank_account_lines_t on f_bank_account_lines_t.bank_account_line_id=f_account_contraentry_t.to_account_no left join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=f_account_contraentry_t.to_bank_id where f_account_contraentry_t.contraentry_id=$refid and f_account_contraentry_t.payment_type_id!='CASH' ");
         if(COUNT($cont)>0){
$this->data['supplier_name'][$pk] = $cont[0]->name_in_account;
          $this->data['favouring_name'][$pk] = "";
        $this->data['supplier_address'][$pk] = $cont[0]->account_number;
    }else{
        $this->data['supplier_name'][$pk] = 'CASH';
          $this->data['favouring_name'][$pk] = "";
        $this->data['supplier_address'][$pk] = '';
    }
}
     else if($sql[0]->payment_source=='DIRECTPAYMENT')
{       
    if($sql[0]->favouring_name!=''){
     $this->data['supplier_name'][$pk] = $sql[0]->favouring_name;
         }else{
            $this->data['supplier_name'][$pk] = '';
            }
    $this->data['favouring_name'][$pk] = '';
    $this->data['supplier_address'][$pk] = '';
      }
        else{
          $this->data['supplier_name'][$pk] =""; 
        $this->data['supplier_address'][$pk] = "";  
		 $this->data['favouring_name'][$pk] = "";
        }
        
        $this->data['payment_amount'][$pk] = $sql[0]->payment_amount;
        $this->data['bank_name'][$pk] = $sql[0]->bank_name;
        $this->data['account_number'][$pk] = $sql[0]->account_number;
        $this->data['name_in_account'][$pk] = $sql[0]->name_in_account;
        if($sql[0]->payment_source!="ADVANCE"){
        $this->data['advance_amount'][$pk] = $sql[0]->advance_amount;
    }else
    {
    	$this->data['advance_amount'][$pk] =0;
    }
        $this->data['payment_date'][$pk] = date("d-m-Y", strtotime($sql[0]->payment_date));
         }else{
        $this->data['advance_amount'][$pk] = 0;
        $this->data['payment_number'][$pk] = "";
        $this->data['payment_name'][$pk] = "";
        $this->data['payment_reference'][$pk] = "";
        $this->data['cheque_no'][$pk] = "";
        $this->data['cheque_date'][$pk] = ""; 
        $this->data['supplier_name'][$pk] = "";
		 $this->data['favouring_name'][$pk] = "";
        $this->data['supplier_address'][$pk] = "";
        $this->data['payment_amount'][$pk] = "0";
        $this->data['bank_name'][$pk] = "";
        $this->data['account_number'][$pk] ="";
        $this->data['name_in_account'][$pk] ="";
        $this->data['payment_date'][$pk] = "";  
         }
         $this->data['result'][$pk] = $arr;
       }
       
       $this->data['p_id']=$p_id;

        $company = \Session::get('companyid');
        $company_name = \DB::select("select * from m_company_t where company_id='$company'");
        $this->data['company_name'] = $company_name[0]->company_name;
        $address = $this->getLocationwiseaddress();
        if ($address != 0) {
            $location_name = @$address[0]->location_name;
            $this->data['location_name'] = $location_name;
            $address1 = $address[0]->address;
            $this->data['address1'] = $address1;
            $street = $address[0]->street_name;
            $this->data['street'] = $street;
            $location = $address[0]->location_name;
            $this->data['location'] = $location;
            $area = $address[0]->area;
            $this->data['area'] = $area;
            $this->data['city'] = $address[0]->city_name;
            $this->data['state'] = $address[0]->state_name;
            $this->data['country'] = $address[0]->country_name;
        }
        

        return view('paymentdetails.voucher', $this->data);
    }      
	
    /* Purpose for getting Location Details for Payment Voucher*/
     function getLocationwiseaddress() {
        $sql = array();
        $location = \Session::get('location'); 
        $sql = \DB::SELECT("SELECT m_location_t.*,m_countries_t.country_name,m_states_t.state_name,m_cities_t.city_name
                            FROM `m_location_t` 
                            left join m_countries_t on (m_countries_t.country_id=m_location_t.country_id)
                            left join m_states_t on (m_states_t.state_id=m_location_t.state_id)
                            left join m_cities_t on (m_cities_t.city_id=m_location_t.city_id)
                            WHERE m_location_t.location_id='$location'");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    
       // challan update purpose - vignesh m
   
       public function challanrefupdate()
    {
       // dd($_GET['id']);
      $check=\DB::update("update p_payments_t set challan_num ='".$_GET['challan_num']."', challan_date='".$_GET['challan_date']."',challan_amt='".$_GET['challan_amt']."',bsr_code='".$_GET['bsr_code']."',challan_acc_code='".$_GET['account_id']."',challan_month_for='".$_GET['date_select']."' where payment_id='".$_GET['id']."'");
      
      if($check){
        return 1;
	  }else{
		            
        return 0;

    }
    }
    
    	public function paymentreference()
    {

   	$sql=\DB::select("select payment_reference from p_payments_t where payment_id='".$_GET['id']."'");
		  if(isset($sql)){
			  if($sql[0]->payment_reference!=''){
			  $data['payment_reference']=$sql[0]->payment_reference;

			  }
		  }
        return $data;

    }
    public function view($id=null){


      $this->data['payment_id'] = $id;
  $table = DB::table('p_payments_t')
  ->select('p_payments_t.payment_number','p_payments_t.payment_id','p_payments_t.payment_date','m_supplier_t.supplier_name','f_bank_account_hdr_t.bank_name','f_bank_account_lines_t.account_number','p_po_invoice_hdr_t.bill_number','p_payments_t.*')
        ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_payments_t.supplier_id')
        ->leftJoin('f_bank_account_hdr_t','f_bank_account_hdr_t.bank_account_hdr_id','=', 'p_payments_t.bank_id')
        ->leftjoin ('f_bank_account_lines_t','f_bank_account_lines_t.bank_account_hdr_id','=','f_bank_account_hdr_t.bank_account_hdr_id') 
         ->leftjoin ('p_po_invoice_hdr_t','p_po_invoice_hdr_t.po_invoice_id','=','p_payments_t.po_invoice_id')

        ->where('payment_id',$id)->get();
        $this->data['row'] = $table[0];

  // dd($this->data);        
        return view('paymentdetails.view',$this->data);
    }
    public function paymentreferenceupdate()
    {
      $check=\DB::update("update p_payments_t set payment_reference='".$_GET['payment_reference']."' where payment_id='".$_GET['id']."'");
      if($check){
        return 1;
	  }else{
		  
        return 0;

    }
    }
   
/*Purpose To get Company Details For Print*/
        function getCompany($company=null)
        {
            $sql=array();
            $sql=\DB::SELECT("SELECT company_id,company_name,company_logo_name,cin_no,gst_no FROM `m_company_t` WHERE `company_id`=".$company."");
            if(!empty($sql))
            {
            return $sql;
            }
            else
            {
            return 0;
            }
        }
public function statementupdate()
{
	$payment_input = $_GET['payment'];

    // If it's an array, use it directly. If it's a string, explode it by comma.
    if (is_array($payment_input)) {
        $payment_id = $payment_input;
    } else {
        $payment_id = explode(',', $payment_input);
    }
	$date=date('Y-m-d',strtotime($_GET['date']));
	$stmtid=$_GET['stmtid'];
  //  dd($date);
	\DB::table('p_payments_t')
            ->whereIn('payment_id', $payment_id)
            ->update(['bank_date' =>$date,'stmtid' =>$stmtid]);
    
	\DB::table('f_bankstmtupload_t')->where('bankstmt_id',$_GET['stmtid'])->update(['status'=>'1']);
	return 1;
}
public function receiptupdate()
{
	$payment_input = $_GET['payment'];

    // If it's an array, use it directly. If it's a string, explode it by comma.
    if (is_array($payment_input)) {
        $payment_id = $payment_input;
    } else {
        $payment_id = explode(',', $payment_input);
    }
	$date=date('Y-m-d',strtotime($_GET['date']));
    //dd($date);
	$stmtid=$_GET['stmtid'];
	\DB::table('s_receipts_t')
            ->whereIn('receipt_id', $payment_id)
            ->update(['bank_date' =>$date,'stmtid' =>$stmtid]);
	\DB::table('f_bankstmtupload_t')->where('bankstmt_id',$_GET['stmtid'])->update(['status'=>'1']);
	return 1;
}

 public function getpaymentdetailsDataforpayment(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | DataTables Params
    |--------------------------------------------------------------------------
    */
    $draw   = intval($request->draw);
    $start  = intval($request->start ?? 0);
    $length = intval($request->length ?? 10);
    $search = $request->search['value'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Session Values
    |--------------------------------------------------------------------------
    */
    $org       = session('organization');
    $loc       = session('location');
    $compy     = session('companyid');
    $bank_nam  = session('bank_name');

    /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */
    $baseQuery = "
        FROM p_payments_t 
        LEFT JOIN m_supplier_t 
            ON m_supplier_t.supplier_id = p_payments_t.supplier_id
        LEFT JOIN m_customers_t 
            ON m_customers_t.customer_id = p_payments_t.customer_id
        LEFT JOIN hr_employee_t 
            ON hr_employee_t.employee_id = p_payments_t.employee_id
        LEFT JOIN f_expenses_t 
            ON f_expenses_t.expense_id = p_payments_t.reference_id
        LEFT JOIN p_po_invoice_hdr_t 
            ON p_po_invoice_hdr_t.po_invoice_id = p_payments_t.po_invoice_id
        LEFT JOIN f_bank_account_hdr_t 
            ON f_bank_account_hdr_t.bank_account_hdr_id = p_payments_t.bank_id
        WHERE 
            (p_payments_t.bank_date = '' OR p_payments_t.bank_date IS NULL)
            AND p_payments_t.stmtid = 0
            AND (p_payments_t.cancel_status IS NULL OR p_payments_t.cancel_status = '')
            AND f_bank_account_hdr_t.bank_account_hdr_id = ?
            AND p_payments_t.payment_type_id != 'IMPREST'
            AND p_payments_t.payment_amount != '0'
            AND p_payments_t.company_id = ?
            AND p_payments_t.location_id = ?
    ";

    $bindings = [$bank_nam, $compy, $loc];

    /*
    |--------------------------------------------------------------------------
    | Search (DataTables)
    |--------------------------------------------------------------------------
    */
    if (!empty($search)) {
        $baseQuery .= "
            AND (
                p_payments_t.payment_number LIKE ?
                OR m_supplier_t.supplier_name LIKE ?
                OR m_customers_t.customer_name LIKE ?
                OR hr_employee_t.first_name LIKE ?
                OR p_payments_t.cheque_no LIKE ?
                OR p_payments_t.payment_amount LIKE ?
            )
        ";
        $like = "%$search%";
        array_push($bindings, $like, $like, $like, $like, $like, $like);
    }

    /*
    |--------------------------------------------------------------------------
    | Total Records
    |--------------------------------------------------------------------------
    */
    $totalRecords = \DB::selectOne("
        SELECT COUNT(*) AS total $baseQuery
    ", $bindings)->total;

    /*
    |--------------------------------------------------------------------------
    | Paged Data
    |--------------------------------------------------------------------------
    */
    $data = \DB::select("
        SELECT 
            p_payments_t.payment_id,
            p_payments_t.payment_number,
            p_payments_t.payment_date,
            p_payments_t.payment_type_id,
            m_supplier_t.supplier_name,
            m_customers_t.customer_name,
            hr_employee_t.first_name,
            p_po_invoice_hdr_t.bill_number,
            f_expenses_t.invoice,
            p_payments_t.cheque_no,
            p_payments_t.payment_amount,
            f_bank_account_hdr_t.bank_name
        $baseQuery
        ORDER BY p_payments_t.payment_date DESC
        LIMIT $start, $length
    ", $bindings);

    /*
    |--------------------------------------------------------------------------
    | DataTables Response
    |--------------------------------------------------------------------------
    */
    return response()->json([
        "draw"            => $draw,
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data
    ]);
}


  
    public function chequecancellation()
    {
      //  $data=array('');
    $sql=\DB::select("select payment_reference from p_payments_t where payment_id='".$_GET['id']."'");
   // dd($sql);
      if(isset($sql)){
        // dd($data);
        if($sql[0]->payment_reference!=''){
        $data['payment_reference']=$sql[0]->payment_reference;
        // dd($data);
        }
      }
       
        return $data;

    }
	
	
	/*Direct Payment*/ 
	public function directpaymentcreate(){
	  $this->data['pageModule']="paymentsindex";
 	  $this->data['']=url('paymentsindex');
 	  $this->data['row']= (object) array();
         
      $this->data['row']->payment_id = "";
      $this->data['row']->payment_number = "";
      $this->data['row']->payment_date=date('Y-m-d');
      $this->data['row']->cheque_date=date('Y-m-d');
      $payamt=$this->data['row']->payment_status="";
      $this->data['row']->payment_amount="";
      $this->data['row']->advance_amount="";
      $this->data['row']->payment_type_id="";
      $this->data['row']->payment_reference="";
      $this->data['row']->cheque_no="";
      $this->data['row']->remarks="";
      $this->data['row']->reference_id="";
      $this->data['row']->payment_source="DIRECTPAYMENT";
      $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','',"and bank_source='Company Account'");
      $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
      //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
      //$this->data['direct_accountcodeid']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
      $this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','account_name','');
      $this->data['direct_accountcodeid']= $this->jCombo('f_account_structure_t','f_account_structure_id','account_name','');
	  $this->data['imprest_employee_id']= $this->jCombologin('hr_employee_t','employee_id','employee_number|first_name','');
      
      return view('paymentdetails.directpaymentform',$this->data);
      }
	
	
		  /* purpose for Save function*/
			public function directpaymentsave(Request $request){
	
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
                /*karthigaa Purpose for Auto Number*/
               if ($_POST['payment_number'] =="")
				{
                    $seqno=$this->Seqnoe('PMT-','p_payments_t','','payment_count');
                    $data['payment_number'] = $seqno[0];
                    $data['payment_count'] = $seqno[1];
                }
                else
                {
                        $seqno[0] = $_POST['payment_number'];
                }
      
      \DB::beginTransaction();
                     try{
             $id=$this->model->insertRow($data);
			 $paymenttype=$_POST['payment_type_id'];
      if($paymenttype =='CHEQUE'){
 //Cheque no count update
       $chequeno=$_POST['cheque_no'];
       $accno=$_POST['account_no'];
       $chequeupdate=\DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
           }
		           
          /**Karthigaa Purpose For Jouranl Entry Insert**/           
                $paymt_no="DIRECTPAYMENT-".$data['payment_number'];
                $paydate=$_POST['payment_date'];
                $org=\Session::get('organization');
                $loc=\Session::get('location');
                $compy=\Session::get('companyid');
                   //Journal Header Insert
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$paymt_no','DIRECTPAYMENT','$paydate','$id','APPROVED','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();
           
                        //Journal lINES Insert       
                    $tkey=0;
                    $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                    $journal_lines_data[$tkey]['journal_date']=$_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source']="";
                    $journal_lines_data[$tkey]['reference_id']="";
                    $journal_lines_data[$tkey]['account_id']=$_POST['direct_accountcodeid'];
                    $journal_lines_data[$tkey]['debit_amount']=$_POST['payment_amount'];
                    $journal_lines_data[$tkey]['credit_amount']='';
                    $journal_lines_data[$tkey]['line_no']=$tkey+1;
                    $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                    $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                    $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                    $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id']=\Session::get('location');   
                     $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                    $journal_lines_data[$tkey]['journal_date']=$_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source']="";
                    $journal_lines_data[$tkey]['reference_id']='';
                    $journal_lines_data[$tkey]['account_id']=$_POST['account_code_id'];
                    $journal_lines_data[$tkey]['debit_amount']='';
                    $journal_lines_data[$tkey]['credit_amount']=$_POST['payment_amount'];
                    $journal_lines_data[$tkey]['line_no']=$tkey+1;
                    $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                    $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                    $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                    $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id']=\Session::get('location');                           
                    \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
				
        \DB::commit();
        return response()->json(array('status' => 'success', 'message' => 'Payment Saved','id' => $id));
      }
      catch (\Illuminate\Database\QueryException$e){
        $message = explode('(', $e->getMessage());
        $dbCode = rtrim($message[0], ']');
        $dbCode = trim($dbCode, '[');
        \DB::rollback();
        return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
      }

        }
		
		public function getpaymentcanclconfirm($id=null)
    {  
      //  dd($id);
      $date = request()->query('payment_date');
      //dd($date);
      //$date = $_GET['payment_date'];
      
    $sql=\DB::select("SELECT f_journal_entry_t.*,
        f_journal_entry_lines_t.*,
        p_payments_t.po_invoice_id,
        p_payments_t.so_invoice_id,
        p_payments_t.payment_reference,
        p_payments_t.payment_type_id,
        p_payments_t.po_hdr_id,
        p_po_invoice_hdr_t.payment_status,
        p_po_invoice_hdr_t.balance_amount,
        p_po_invoice_hdr_t.paid_amount, 
        p_payments_t.payment_amount,
        p_payments_t.payment_id,
        p_payments_t.payment_source,
        p_payments_t.employee_id,
        p_payments_t.reference_id as p_ref,
        p_po_hdr_t.advance_amount as po_ad_pay,
        p_po_hdr_t.balance_amount as po_bal_amt,
        p_payments_t.reference_id as imp_id
        FROM f_journal_entry_lines_t 
        left join f_journal_entry_t on (f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id)
        left join p_payments_t on (p_payments_t.payment_id = f_journal_entry_t.journal_reference)
        left join p_po_invoice_hdr_t on (p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id = p_payments_t.po_hdr_id )
         WHERE 1=1 AND  f_journal_entry_t.journal_reference='$id' and f_journal_entry_t.journal_date = '$date' and (f_journal_entry_t.journal_type='PAYMENT' or f_journal_entry_t.journal_type='DIRECTPAYMENT'  
         or f_journal_entry_t.journal_type='EXPENSES' or f_journal_entry_t.journal_type='IMPRESTPAYMENT' or f_journal_entry_t.journal_type='SALARYPAYMENT' or f_journal_entry_t.journal_type='SALESINVOICE' 
         or f_journal_entry_t.journal_type='ADVANCE PAYMENT') ");
//dd($sql);
 if(count($sql)!=0){

    foreach ($sql as $key => $value) {
    
        if($value->journal_type =="TRAVELPAYMENT")
        {
            if($value->reference_source !=""){
            $imp_id = $value->imp_id;
        $update = \DB::update("update hr_employee_travel_claim_t set status='1' where travel_claim_id ='$imp_id' ");
                }
        }
         if($value->journal_type =="SALESINVOICE")
        {
        
            if($value->reference_source !=""){
               // dd($value->payment_source);
            $so_invoice_id = $value->so_invoice_id; 
          //  dd($po_invoice_id);

            $invhdrid=\DB::select("select invoice_hdr_id,balance_amount,paid_amount,invoice_grand_total from s_invoice_hdr_t where invoice_hdr_id in ($so_invoice_id) ");
        
            foreach ($invhdrid as $ikey => $ivalue) {
                    $balance_amount=$ivalue->balance_amount;
                    $paid_amount=$ivalue->paid_amount;
                    $invoice_grand_total=$ivalue->invoice_grand_total;
                    $payed= $value->payment_amount;
                    $currt=$payed-$balance_amount;
                    
                  //  dd($value);
                  $credit_amount = $value->credit_amount;
             $debit_amount = $value->debit_amount;  
            
                    if($currt<0){
                        $update = \DB::update("update s_invoice_hdr_t set paid_amount= '$paid_amount',receipt_status='0', balance_amount='$balance_amount' where invoice_hdr_id ='$so_invoice_id' ");                
                    }else{
                            $curbal=$paid_amount+$balance_amount;
                           
                            $curpay=0;
                        $update = \DB::update("update s_invoice_hdr_t set paid_amount= '$curpay',receipt_status='0', balance_amount='$curbal' where invoice_hdr_id ='$so_invoice_id' ");                
                    }
                }  
        
                 }
        
           
         }
         $payment_id = $value->payment_id;
        $update = \DB::update("update p_payments_t set cancel_status='Cancelled',batch_status='GENERATED' where payment_id ='$payment_id' ");
        
   if($value->journal_type =="PAYMENT" || $value->journal_type =="IMPRESTPAYMENT" ||  $value->journal_type="SALARYPAYMENT"){

 if($value->payment_source =="EXPENSE"){
             if($value->reference_source !=""){
                $expid= $value->p_ref;
               $sqlexp = \DB::select("select * from f_expenses_t left join p_payments_t on (p_payments_t.reference_id = f_expenses_t.expense_id)  where f_expenses_t.expense_id='$expid' "); 

              $expense_id = $expid;
             $credit_amount = $value->credit_amount;  
             $debit_amount = $value->debit_amount;  
            $balance_amount = $sqlexp[0]->balance_amount+$debit_amount;
            $paid_amount= $sqlexp[0]->payment_amount-$debit_amount;
         // dd($debit_amount); 
        $update = \DB::update("update f_expenses_t set balance_amount='$balance_amount',paid_amount='$paid_amount', payment_status='0' where expense_id ='$expense_id' ");
                }

            }
 if($value->payment_source =="DIRECTEXPENSE"){
     if($value->reference_source !=""){
        $update = \DB::update("update p_payments_t set cheque_cancel_status='Cancelled',batch_status='GENERATED' where payment_id ='$payment_id' ");
                }
 }
 if($value->payment_source =="IMPREST"){
if($value->reference_source !=""){
    $imp_id = $value->imp_id;
   // dd($imp_id);
           $payment_id = $value->payment_id;
            $update = \DB::update("update hr_imprest_tbl set status='APPROVE' where imprest_id ='$imp_id' "); 
        }

 }
 if($value->payment_source =="DIRECTPAYMENT"){
     $payment_id = $value->payment_id;
$update = \DB::update("update p_payments_t set cheque_cancel_status='Cancelled',batch_status='GENERATED' where payment_id ='$payment_id' ");

 }

 if($value->payment_source =="ADVANCE"){
    if($value->reference_source !=""){
       // dd($value->payment_source);
            $po_hdr_id = $value->po_hdr_id;   
            $credit_amount = $value->credit_amount;  
            $debit_amount = $value->debit_amount;  
            $balance_amount = $value->po_bal_amt+$credit_amount;
            $paid_amount= $value->po_ad_pay-$debit_amount;
        $update = \DB::update("update p_po_hdr_t set advance_amount= '$paid_amount',advance_status='0', balance_amount='$balance_amount' where po_hdr_id ='$po_hdr_id' ");
            }
               }
  if($value->payment_source =="PF"){  
      
    //   $emp=$value->employee_id;
    //       $update = \DB::update("update hr_company_contribute_pf set advance_amount= '$paid_amount'  where emp_id ='$emp' ");
                      }
  if($value->payment_source =="SALARYPAYMENT"){
    if($value->reference_source !=""){
     //   dd($value->reference_source);
    $employee_id = $value->employee_id;
    $update = \DB::update("update hr_employee_payroll_lists set payment_status='0' where employee_id ='$employee_id' "); 
        }
         }
        
  if($value->payment_source =="INVOICE"){
  //	dd("not work");
   
            if($value->reference_source !=""){
               // dd($value->payment_source);
            $po_invoice_id = $value->po_invoice_id; 
          //  dd($po_invoice_id);

            $invhdrid=\DB::select("select po_invoice_id,balance_amount,paid_amount,invoice_grand_total from p_po_invoice_hdr_t where po_invoice_id in ($po_invoice_id) ");
        
            foreach ($invhdrid as $ikey => $ivalue) {
                    $balance_amount=$ivalue->balance_amount;
                    $paid_amount=$ivalue->paid_amount;
                    $invoice_grand_total=$ivalue->invoice_grand_total;
                    $payed= $value->payment_amount;
                    $currt=$payed-$balance_amount;
                    
                  //  dd($value);
                  $credit_amount = $value->credit_amount;
             $debit_amount = $value->debit_amount;  
            
                    if($currt<0){
                        $update = \DB::update("update p_po_invoice_hdr_t set paid_amount= '$paid_amount',payment_status='0', balance_amount='$balance_amount' where po_invoice_id ='$po_invoice_id' ");                
                    }else{
                            $curbal=$paid_amount+$balance_amount;
                           
                            $curpay=0;
                        $update = \DB::update("update p_po_invoice_hdr_t set paid_amount= '$curpay',payment_status='0', balance_amount='$curbal' where po_invoice_id ='$po_invoice_id' ");                
                    }
                }  

                 }
                 }

        }
      
    }


//dd($sql);

    /*Payment Reverse to Receipt Insert*/

 $paymntid=$sql[0]->journal_reference; 
      
      $revpay=\DB::Select("Select * from p_payments_t where payment_id='$paymntid' ");
      
       
        

if($sql[0]->journal_type=='IMPRESTPAYMENT'){
   $journaltype='REVERSE-IMPRESTPAYMENT'; 
}else{
    $journaltype=$sql[0]->journal_type;
}
    //*Reverse Journal Insert*/
         $journal_hdr['journal_name']="Reverse-".$sql[0]->journal_name; 
         $journal_hdr['journal_category']=$sql[0]->journal_category; 
         $journal_hdr['journal_date']=date('Y-m-d');; 
         $journal_hdr['journal_type']='REVERSE'; 
         $journal_hdr['journal_reference']=$sql[0]->journal_reference; 
         $journal_hdr['journal_status']=$sql[0]->journal_status;
         $journal_hdr['created_by']=\Session::get('id');
         $journal_hdr['organization_id']=\Session::get('organization');
         $journal_hdr['created_at']=date('Y-m-d H:i:s');
            $journal_hdr['last_updated_by']=\Session::get('id');
            $journal_hdr['updated_at']=date('Y-m-d H:i:s');;
            $journal_hdr['location_id']=\Session::get('companyid');
            $journal_hdr['company_id']=\Session::get('location'); 
//dd($journal_hdr);
              $hdr_id=\DB::table('f_journal_entry_t')->insertGetId($journal_hdr);  
             $jid = DB::getPdo()->lastInsertId();
    // dd($jid);  
 
  $sql1=\DB::select("SELECT f_journal_entry_t.*,
        f_journal_entry_lines_t.*
        FROM f_journal_entry_lines_t 
        left join f_journal_entry_t on (f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id)
  WHERE 1=1 AND  f_journal_entry_t.journal_reference='$id' and (f_journal_entry_t.journal_type='PAYMENT' or f_journal_entry_t.journal_type='DIRECTPAYMENT' 
  or f_journal_entry_t.journal_type='PFPAYMENT' or f_journal_entry_t.journal_type='EXPENSES'
  or f_journal_entry_t.journal_type='IMPRESTPAYMENT' or f_journal_entry_t.journal_type='SALARYPAYMENT' or f_journal_entry_t.journal_type='SALESINVOICE'  or f_journal_entry_t.journal_type='ADVANCE PAYMENT') ");


       foreach ($sql1 as $tkey => $tvalue) {
             
         $journal_lines['journal_entry_id']= $jid; 
         $journal_lines['reference_source']=$tvalue->reference_source; 
         $journal_lines['journal_date']=date('Y-m-d');; 
         $journal_lines['reference_id']=$tvalue->reference_id; 
         $journal_lines['status']=$tvalue->status; 
        // $journal_lines[$tkey]['line_no']=$v->line_no; 
// $data['organization_id'] =\Session::get('organization');
         $journal_lines['organization_id']=\Session::get('organization');
         $journal_lines['account_id']=$tvalue->account_id; 
         $journal_lines['credit_amount']=$tvalue->debit_amount; 
         $journal_lines['debit_amount']=$tvalue->credit_amount; 
         $journal_lines['employee_id']=$tvalue->employee_id; 
         $journal_lines['payment_id']=$tvalue->payment_id; 
         $journal_lines['payment_amount']=$tvalue->payment_amount; 
         $journal_lines['reference_source_id']=$tvalue->reference_source_id;

            $journal_lines['line_no']=$tkey+1;
            $journal_lines['created_by']=\Session::get('id');
            $journal_lines['created_at']=date('Y-m-d H:i:s');
            $journal_lines['last_updated_by']=\Session::get('id');
            $journal_lines['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines['location_id']=\Session::get('companyid');
            $journal_lines['company_id']=\Session::get('location');  

           $journal_lines_data= \DB::table('f_journal_entry_lines_t')->insert($journal_lines); 
    }   
 }

    return 1;
    }


    public function editpayment($id = null)
    {
 
            $table = \DB::table('p_payments_t')->where('payment_id', $id)->get();

            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];
            $this->data['row']->payment_id = $table[0]->payment_id;
			$this->data['row']->payment_number = $table[0]->payment_number;
            $this->data['row']->payment_date = $table[0]->payment_date;
			$this->data['row']->payment_type_id = $table[0]->payment_type_id;
			$this->data['row']->payment_reference = $table[0]->payment_reference;
            $this->data['row']->cheque_no = $table[0]->cheque_no;
            $this->data['row']->bank_id = $table[0]->bank_id;
            $this->data['row']->payment_source = $table[0]->payment_source;
            $this->data['row']->payment_amount = $table[0]->payment_amount;

            $this->data['bank_id'] = $this->jcustomselect(
                'f_bank_account_hdr_t',
                'bank_account_hdr_id',
                'bank_name',
                $table[0]->bank_id,
                "AND bank_source='Company Account'"
            );

            $this->data['account_no'] = $this->jcustomselect(
                'f_bank_account_lines_t',
                'bank_account_line_id',
                'account_number',
                $table[0]->account_no,"AND bank_account_line_id = '".$table[0]->account_no."'");
    

                $this->data['row']->supplier_id = $table[0]->supplier_id;
                $this->data['row']->customer_id = $table[0]->customer_id;
                $this->data['row']->employee_id = $table[0]->employee_id;

                if (!empty($table[0]->employee_id)) {

                    $this->data['payee'] = $this->jcustomselect(
                        'hr_employee_t',
                        'employee_id',
                        'first_name',
                        $table[0]->employee_id,
                        "AND employee_id = '".$table[0]->employee_id."'"
                    );

                } elseif (!empty($table[0]->supplier_id)) {

                    $this->data['payee'] = $this->jcustomselect(
                        'm_supplier_t',
                        'supplier_id',
                        'supplier_name',
                        $table[0]->supplier_id,
                        "AND supplier_id = '".$table[0]->supplier_id."'"
                    );

                } elseif (!empty($table[0]->customer_id)) {

                    $this->data['payee'] = $this->jcustomselect(
                        'm_customers_t',
                        'customer_id',
                        'customer_name',
                        $table[0]->customer_id,
                        "AND customer_id = '".$table[0]->customer_id."'"
                    );

                } else {
                    $this->data['payee'] = ''; 
                }


            $this->data['pageMethod'] = "paymentdetailindex";

            return view('paymentdetails.paymenteditform', $this->data);

    }

    public function updatepayment(Request $request){

    $payment_id = $request->payment_id;
    $payment_number = $request->payment_number;
    $payment_date = $request->payment_date;
    $payment_type_id = $request->payment_type_id;
    $cheque_no = $request->cheque_no;
    $payment_source = $request->payment_source;
    $payment_amount = $request->payment_amount;
    $bank_id = $request->bank_id;
    $account_no = $request->account_no;


    $check=\DB::update("update p_payments_t set payment_date ='$payment_date', payment_amount='$payment_amount',payment_type_id='$payment_type_id',cheque_no='$cheque_no',account_no='$account_no',bank_id='$bank_id',payment_source='$payment_source' where payment_id='$payment_id'");

    $check1 = \DB::update("update f_journal_entry_t set journal_date='$payment_date',  where journal_reference ='$payment_id' AND journal_name LIKE '%$payment_number%'");

    



    }




}
