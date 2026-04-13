<?php

namespace App\Http\Controllers;
use yajra\datatables\datatables;
use App\Journalentry;
use App\Journalentrylines;
use Illuminate\Http\Request,DB;
use session;


class JournalentryController extends Controller
{
        public function __construct() {
        $this->data = array();
        $this->table = "f_journal_entry_t";
        $this->subtable = "f_journal_entry_lines_t";
        $this->pageModule = "journalentry";
        $this->model = new Journalentry;
        $this->submodel = new Journalentrylines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'journalentry',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
         if($this->data['pageMethod']=="journalapproval")
           {
              // $this->data['pageMethod']="poinvoiceapproval";
               $this->data['status']="SUBMITTED";
           }
        else if($this->data['pageMethod']=="journalposting")
          {
               $this->data['status']="APPROVED";
          }
          else{
               $this->data['status']="";
          }
          
        $this->modelname = new Journalentry();
        $this->data['pageFormtype'] = 'ajax';
    }
	
    public function index(Request $request)
    {
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

		//dd("ffd");
       $table = \DB::table('f_journal_entry_t')->get();
       $this->data['datas'] = $table;
       return view('journalentry.table',$this->data);
    }
	
	
	public function costcenterledgerreport(Journalentry $journalentry)
    {
    $this->data['vdata']=[];
    $this->data['account']=$this->jCombo('f_account_codes_lines_t', 'account_codes_line_id', 'account_code|account_code_meaning','');
        
    return view('journalentry.reportcostcenter',$this->data);
  }
   
	
	
  public function getcostcenterreportData(Request $request){

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : NULL;
	  
$SQL ="select v2.*,
v1.debit,
v1.credit,
sum(v1.balance)as opening_balance,
round(sum(v1.balance+v1.debit-v1.credit),2) as balance 
from
((SELECT f_journal_entry_t.journal_name,
round(sum(f_journal_entry_lines_t.debit_amount),2) as debit,
round(sum(f_journal_entry_lines_t.credit_amount),'2') as credit,
f_account_structure_t.concatenated_segments,
0 as balance,
f_journal_entry_lines_t.account_id 
FROM `f_journal_entry_t` 
LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id 
WHERE f_journal_entry_t.journal_status!='SUBMIT' and f_journal_entry_lines_t.journal_date between ? and ? GROUP BY f_journal_entry_lines_t.account_id)
union all 
(SELECT '' as journal_name,
0 as debit,
0 as credit,
f_account_structure_t.concatenated_segments,
round(sum(f_journal_entry_lines_t.debit_amount-f_journal_entry_lines_t.credit_amount),2) as balance,
f_journal_entry_lines_t.account_id 
FROM `f_journal_entry_t` 
LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id
WHERE f_journal_entry_t.journal_status='POSTED' and f_journal_entry_lines_t.journal_date < ? and  f_journal_entry_lines_t.journal_date >='2019-04-01'   GROUP BY f_journal_entry_lines_t.account_id))v1 
join (select f.f_account_structure_id,
m.account_class_name as main,
r1.account_code_meaning as sub1,
if(f.future_reference1>0,r2.account_code_meaning,'')as sub2,
if(f.future_reference2>0,r3.account_code_meaning,'')as sub3,
if(f.sub_account4_id>0,r4.account_code_meaning,'')as sub4,
if(f.costcenter_id>0,costcen.sub_department_name,'')as cos1,
if(f.subcostcenter1_id>0,costcen1.sub_department_name,'')as cos2,
if(f.subcostcenter2_id>0,costcen2.sub_department_name,'')as cos3,
if(f.subcostcenter3_id>0,costcen3.sub_department_name,'')as cos4
from f_account_structure_t as f 
left JOIN f_account_class_t as m ON m.account_class_id=f.main_account_id 
left JOIN f_account_codes_lines_t as r1 ON r1.account_codes_line_id=f.sub_account_id
left JOIN m_department_lines_t as costcen ON costcen.department_line_id=f.costcenter_id
left JOIN m_department_lines_t as costcen1 ON costcen1.department_line_id=f.subcostcenter1_id
left JOIN m_department_lines_t as costcen2 ON costcen2.department_line_id=f.subcostcenter2_id
left JOIN m_department_lines_t as costcen3 ON costcen3.department_line_id=f.subcostcenter3_id
JOIN f_account_codes_lines_t as r2 ON r2.account_codes_line_id=f.future_reference1 left JOIN f_account_codes_lines_t as r3 ON r3.account_codes_line_id=f.future_reference2 
left JOIN f_account_codes_lines_t as r4 ON r4.account_codes_line_id=f.sub_account4_id) as v2 on v2.f_account_structure_id=v1.account_id group by f_account_structure_id";

    $result = \DB::select($SQL, [$start_date, $end_date,$start_date]);

    return response()->json(['data' => $result]);
	  
    }
	
	
        public function getJournalentryData(Request $request) {
            
            
        $wh = '';
			
		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
		$status = $request->status ?? null;

		if ($start_date != '' && $status != '') {
			$wh = " and f_journal_entry_t.journal_status='$status' 
					and f_journal_entry_t.journal_date between '$start_date' and '$end_date' ";
		} 
		else if ($start_date != '') {
			$wh = " and f_journal_entry_t.journal_date between '$start_date' and '$end_date' ";
		} 

		else {
			$wh= "and f_journal_entry_t.journal_status=''";
		}

			
		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');	

		
        $SQL = "SELECT ROUND(sum(f_journal_entry_lines_t.debit_amount),2) as amount,
	f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_t.journal_date,f_journal_entry_t.journal_status,f_journal_entry_lines_t.reference_source,
	(CASE 
	WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' THEN hr_employee_t.first_name
	WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' THEN m_customers_t.customer_name
	WHEN f_journal_entry_lines_t.reference_source='PRODUCT' THEN m_products_t.concatenated_product
	WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' THEN m_supplier_t.supplier_name
	ELSE '' END) as namee
	FROM f_journal_entry_t 
	LEFT JOIN f_journal_entry_lines_t on (f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id)
	LEFT JOIN hr_employee_t ON (hr_employee_t.employee_id= f_journal_entry_lines_t.reference_id  and f_journal_entry_lines_t.reference_source='EMPLOYEE')
	LEFT JOIN m_customers_t ON (m_customers_t.customer_id= f_journal_entry_lines_t.reference_id  and f_journal_entry_lines_t.reference_source='CUSTOMER')
	LEFT JOIN m_products_t ON (m_products_t.product_id= f_journal_entry_lines_t.reference_id  and f_journal_entry_lines_t.reference_source='PRODUCT')
	LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id= f_journal_entry_lines_t.reference_id  and f_journal_entry_lines_t.reference_source='SUPPLIER')
	WHERE 1=1 $wh GROUP BY f_journal_entry_lines_t.journal_entry_id  ORDER BY f_journal_entry_t.journal_entry_id DESC";

        $result = \DB::select($SQL);
		
			
	return DataTables::of($result)->make(true);			
			
    }
    
	
   public function create($id=null,$aprv=null)
    {
		
		if($id ==null)
		{
		 $this->data['row'] = (object) array();
            $this->data['row']->journal_entry_id = "";
            $this->data['row']->journal_name = "";
            $this->data['row']->journal_date = date('Y-m-d');
            $this->data['row']->journal_type = "MANUAL";
            $this->data['row']->journal_category = "";
            $this->data['row']->journal_reference = "";
            $this->data['row']->journal_status = "";
            
            $this->data['id'] = '';
            $this->data['linedata'] = array();
             $this->data['account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
             $this->data['employee_id'] = $this->jCombologin('hr_employee_t','employee_id','employee_number|first_name','');
             $this->data['payment_id'] = $this->jCombologin('p_payments_t', 'payment_id', 'payment_number', '');
			 
		}
		else
		{	
                     $this->data['id'] = $id;
                     $table = \DB::table('f_journal_entry_t')->where('journal_entry_id', $id)->get();
                     $this->data['row'] = $table[0];
            
                    $tablelines = \DB::table('f_journal_entry_lines_t')->where('journal_entry_id', $id)->get();
                    $this->data['linedata'] = $tablelines;
//                    $aprvidenty=$id;
                      if($aprv!="")
                        { 
                           $this->data['aprvidenty']=$aprv;
                        }else
                        {
                         $this->data['aprvidenty']="";
                        }
         
                }  
                  if (count($this->data['linedata']) >= 1) {
                    foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['linedata'][$key]->account_id =  $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->account_id);
                    $this->data['linedata'][$key]->employee_id = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->employee_id);
                    $this->data['linedata'][$key]->payment_id = $this->jCombologin('p_payments_t', 'payment_id', 'payment_number', $value->payment_id);
                    }
                    }
                     if($aprv!="")
                        { 
                           $this->data['aprvidenty']=$aprv;
                        }else
                        {
                         $this->data['aprvidenty']="";
                        }
//        	dd($this->data);	
      return view('journalentry.form',$this->data);  
    }

     public function journalapproval($id=null,$aprv=null)
    {
		
	       $this->data['linedata'] = array();
                     $this->data['id'] = $id;
                     $table = \DB::table('f_journal_entry_t')->where('journal_entry_id', $id)->get();
                     $this->data['row'] = $table[0];
            
                    $tablelines = \DB::table('f_journal_entry_lines_t')->where('journal_entry_id', $id)->get();
                    $this->data['linedata'] = $tablelines;
             
                  if (count($this->data['linedata']) >= 1) {
                    foreach ($this->data['linedata'] as $key => $value) {
                        $this->data['linedata'][$key]->account_id =  $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->account_id);
                        $this->data['linedata'][$key]->employee_id = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->employee_id);
                        $this->data['linedata'][$key]->payment_id = $this->jCombologin('p_payments_t', 'payment_id', 'payment_number', $value->payment_id);
                    }
                 }
                      
        if($aprv!="")
        { 
           $this->data['aprvidenty']=$aprv; 
        }else
        {
         $this->data['aprvidenty']="";
        }
      return view('journalentry.form',$this->data);  
    }

    /* purpose for Save function */
	
    public function save(Request $request) {
	
           
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','enable-masterdetail',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$data['journal_date'] = $request->input('journal_date');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');

        $journalCategory = $request->journal_category;

        foreach ($request->bulk_account_id as $k => $v) {

            if ($request->journal_category == "EMPLOYEE") {

                $lines_data['reference_source'][$k] = "EMPLOYEE";
                $lines_data['reference_id'][$k] = $lines_data['employee_id'][$k];

            } 
            else if ($request->journal_category == "PURCHASE" 
                    && in_array($v, [42,43,44,45,46])) {

                $lines_data['reference_source'][$k] = "SUPPLIER";
                $lines_data['reference_id'][$k] = $request->sourcetype_id;

            } 
            else if ($request->journal_category == "OTHERS") {

                $lines_data['reference_source'][$k] = "";
                $lines_data['reference_id'][$k] = 0;

            } 
            else if ($request->journal_category == "SALES" 
                    && in_array($v, [57,58,59,60,61,62,63])) {

                $lines_data['reference_source'][$k] = "CUSTOMER";
                $lines_data['reference_id'][$k] = $request->sourcetype_id;

            } 
            else {

                $lines_data['reference_source'][$k] = "";
                $lines_data['reference_id'][$k] = 0;
            }
        }
		//dd($lines_data);
        \DB::beginTransaction();
        try {
			//*** kaviya purpose update journal for imprest **/
			if($_POST['journal_type']=="IMPREST"){
			   $query = DB::table('hr_imprest_tbl')->where('imprest_id',$_POST['journal_reference'])->update(['status'=>"JOURNAL"]); 
			
			}
			if($_POST['journal_type']=="TRAVEL"){
			 $query = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id',$_POST['journal_reference'])->update(['status'=>"JOURNAL"]); 
			}
                        
            $id = $this->model->insertRow($data);
            $lid=$this->submodel->subgridSave($lines_data,$id);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => $_POST['journal_status'].' Successfully','id' => $id,'lid' => $lid));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }

   
 /* purpose for Display View function*/
  public function show(request $request,$id=null){
      
        if(isset($id)){
            error_reporting(0);
          $vdata=\DB::table('f_journal_entry_t')->where('journal_entry_id',$id)->get();

          $this->data['journal_name']=$vdata[0]->journal_name;
          $this->data['journal_date']=$vdata[0]->journal_date;
          $this->data['journal_type']=$vdata[0]->journal_type;
          $this->data['journal_category']=$vdata[0]->journal_category;
          $this->data['journal_reference']=$vdata[0]->journal_reference;
          $this->data['journal_status']=$vdata[0]->journal_status;
          $this->data['sourcetype_id']=$vdata[0]->sourcetype_id;
          $this->data['source']=$vdata[0]->source;
          
          $vlinesdata=\DB::table('f_journal_entry_lines_t')
                  ->select('f_journal_entry_lines_t.*','f_account_structure_t.concatenated_segments','p_payments_t.payment_number','hr_employee_t.first_name')
                  ->leftjoin('f_account_structure_t','f_account_structure_t.f_account_structure_id','=','f_journal_entry_lines_t.account_id')
                  ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','f_journal_entry_lines_t.employee_id')
                  ->leftjoin('p_payments_t','p_payments_t.payment_id','=','f_journal_entry_lines_t.payment_id')
                  ->where('journal_entry_id',$id)->get();
           $this->data['vlinesdata']=$vlinesdata;
           foreach ($this->data['vlinesdata'] as $key => $value) {
             if($value->reference_source=='CUSTOMER')
             {
              $dat=\DB::select("select customer_name from m_customers_t where customer_id='$value->reference_id' ");
             $this->data['vlinesdata'][$key]->ref_name=$dat[0]->customer_name;
             }elseif($value->reference_source=='EMPLOYEE')
             {
              $dat=\DB::select("select first_name from hr_employee_t where employee_id='$value->reference_id' ");
             $this->data['vlinesdata'][$key]->ref_name=$dat[0]->first_name;
             }
             elseif($value->reference_source=='MACHINE')
             {
              $dat=\DB::select("select machine_name from w_machine_hdr_t where machine_hdr_id='$value->reference_id' ");
             $this->data['vlinesdata'][$key]->ref_name=$dat[0]->machine_name;
             }
             elseif($value->reference_source=='PRODUCT')
             {
              $dat=\DB::select("select concatenated_product from m_products_t where product_id='$value->reference_id' ");
             $this->data['vlinesdata'][$key]->ref_name=$dat[0]->concatenated_product;
             }
             elseif($value->reference_source=='SUPPLIER')
             {
              $dat=\DB::select("select supplier_name from m_supplier_t where supplier_id='$value->reference_id' ");
             $this->data['vlinesdata'][$key]->ref_name=$dat[0]->supplier_name;
             }else{
              $this->data['vlinesdata'][$key]->ref_name="";
             }
            }  
          $this->data['date']=$vlinesdata[0]->journal_date;
          $this->data['concatenated_segments']=$vlinesdata[0]->concatenated_segments;
          $this->data['debit_amount']=$vlinesdata[0]->debit_amount;
          $this->data['credit_amount']=$vlinesdata[0]->credit_amount;
          $this->data['payment_number']=$vlinesdata[0]->payment_number;
          $this->data['employee_name']=$vlinesdata[0]->first_name;
          
          return view('journalentry.view',$this->data);
        }
    }

   public function ledgerreport(Journalentry $journalentry)
    {
		$this->data['vdata']=[];
		$this->data['account']=$this->jCombo('f_account_codes_lines_t', 'account_codes_line_id', 'account_code|account_code_meaning','');
        
		return view('journalentry.reportledger',$this->data);
	}
   
 
   
public function journalledgerreport(Journalentry $journalentry)
    {
		
		if(isset($_GET['search']))
		   {
			   $id=$_GET['search'];
			   $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? 	date("Y-m-d", strtotime($_GET['start_date'])) : '';
			$end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
			   $month=date('m');
		
		$row=$this->data['vdata']=\DB::SELECT("SELECT
    f_journal_entry_t.journal_entry_id,
    f_journal_entry_t.journal_name,
    f_journal_entry_lines_t.journal_date,
    f_journal_entry_t.journal_type,
    f_journal_entry_lines_t.account_id,
    f_journal_entry_lines_t.debit_amount,
    f_journal_entry_lines_t.credit_amount,
    f_account_structure_t.future_reference2,
    f_account_codes_lines_t.account_code,
    f_account_codes_lines_t.account_code_meaning,
    CONCAT(
        f_account_codes_lines_t.account_code,
        f_account_codes_lines_t.account_code_meaning
    ) AS accountcode,
    f_journal_entry_lines_t.f_journal_entry_line_id
FROM
    `f_account_codes_lines_t`

LEFT JOIN f_account_structure_t ON(
       f_account_codes_lines_t.account_codes_line_id = f_account_structure_t.future_reference2 OR f_account_codes_lines_t.account_codes_line_id = f_account_structure_t.future_reference1 OR f_account_codes_lines_t.account_codes_line_id = f_account_structure_t.sub_account_id
    )
    
    LEFT JOIN f_journal_entry_lines_t ON
    (
        f_journal_entry_lines_t.account_id = f_account_structure_t.f_account_structure_id
    )
     LEFT JOIN f_journal_entry_t ON
    (
        f_journal_entry_lines_t.journal_entry_id = f_journal_entry_t.journal_entry_id
    )
WHERE
   f_account_codes_lines_t.account_codes_line_id=$id and f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'");

			$this->data['vdata1']=$start_date;
			$this->data['vdata2']=$end_date;
			 if($row!=null){
			$account_code_meaning=$row[0]->accountcode;	
			}
			else{
				$account_code_meaning="";
			}
			
			$this->data['vdata3']=$account_code_meaning;
			
			   return view('journalentry.ledgerreport',$this->data);
			
		   }else{
			 
		       $month=date('m');
          $this->data['vdata']=\DB::SELECT("SELECT f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_date,f_account_structure_t.account_name,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount FROM `f_journal_entry_t` left join f_journal_entry_lines_t on f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id left join f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where MONTH(f_journal_entry_t.journal_date)=$month");
		
		}

		return view('journalentry.report',$this->data);
	
	
    }
	
	
	  public function journalrpt(Journalentry $journalentry)
    {
		$this->data['vdata']=[];
		return view('journalentry.reportjournal',$this->data);
	}
	
  public function journalreport(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (SELECT
    f_journal_entry_t.journal_entry_id,
    f_journal_entry_t.journal_name,
    f_journal_entry_t.journal_type,
    (
        CASE WHEN f_journal_entry_t.journal_type = 'SRN' OR f_journal_entry_t.journal_type = 'EXPENSES' OR f_journal_entry_t.journal_type = 'DEBIT' OR f_journal_entry_t.journal_type = 'SALES RETURN' OR f_journal_entry_t.journal_type = 'GRN' OR f_journal_entry_t.journal_type = 'MANUAL' OR f_journal_entry_t.journal_type = 'EMPLOYEE EXPENSES' OR f_journal_entry_t.journal_type = 'DEBIT NOTE' THEN f_journal_entry_t.journal_name WHEN f_journal_entry_t.journal_type = 'PO INVOICE' THEN SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            'POINVOICE-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'SHIPMENT CONFIRM' OR f_journal_entry_t.journal_type = 'SALES INVOICE' OR f_journal_entry_t.journal_type = 'QUALITY APPROVE' OR f_journal_entry_t.journal_type = 'JOB CARD CLOSE' OR f_journal_entry_t.journal_type = 'MATERIAL RECEIVE' OR f_journal_entry_t.journal_type = 'CONSUMABLE' OR f_journal_entry_t.journal_type = 'CONTRA ENTRY' THEN SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'DIRECTPAYMENT' OR f_journal_entry_t.journal_type = 'DIRECTRECEIPTS' OR f_journal_entry_t.journal_type = 'MOVE TO INVENTORY' OR f_journal_entry_t.journal_type = 'PAYMENT' OR f_journal_entry_t.journal_type = 'SALARYPAYMENT' OR f_journal_entry_t.journal_type = 'REVERSE RECEIPT' OR f_journal_entry_t.journal_type = 'REVERSE' OR f_journal_entry_t.journal_type = 'REPLACEMENT' OR f_journal_entry_t.journal_type = 'RECEIPT' OR f_journal_entry_t.journal_type = 'RECEIPT' OR f_journal_entry_t.journal_type = 'ADVANCE PAYMENT' OR f_journal_entry_t.journal_type = 'ADVANCE RECEIPT' OR f_journal_entry_t.journal_type = 'EMP/EXPENSE' OR f_journal_entry_t.journal_type = 'IMPRESTPAYMENT' THEN SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ) WHEN f_journal_entry_t.journal_type = 'JOB CARD STORE MOVE' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'Store Move-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'PFPAYMENT' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                3
            ),
            'PFPAYMENT-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'ESIPAYMENT' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                3
            ),
            'ESIPAYMENT-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'PAYROLL' THEN CASE WHEN(
            SUBSTRING_INDEX(
                SUBSTRING_INDEX(
                    f_journal_entry_t.journal_name,
                    '-',
                    2
                ),
                'PAYROLL-',
                -1
            )
        ) = 'ARREARS' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                3
            ),
            'PAYROLL-ARREARS-',
            -1
        ) ELSE SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'PAYROLL-',
            -1
        )
    END WHEN f_journal_entry_t.journal_type = 'PAYROLL/PF/ESI' THEN CASE WHEN(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'PAYROLL/PF/ESI-',
            -1
        )
    ) = 'ARREARS' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            3
        ),
        'PAYROLL/PF/ESI-ARREARS-',
        -1
    ) ELSE SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            2
        ),
        'PAYROLL/PF/ESI-',
        -1
    )
END
END
) AS journal_ref_name,
f_journal_entry_lines_t.journal_date,
f_journal_entry_lines_t.reference_source,
CONCAT(
    LEFT(
        MONTHNAME(
            f_journal_entry_lines_t.journal_date
        ),
        3
    ),
    '-',
    YEAR(
        f_journal_entry_lines_t.journal_date
    )
) AS monyr,
(
    CASE WHEN f_journal_entry_t.journal_type = 'PFPAYMENT' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'PFPAYMENT',
        -1
    ) WHEN f_journal_entry_t.journal_type = 'ESIPAYMENT' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'ESIPAYMENT-',
        -1
    ) WHEN f_journal_entry_t.journal_type = 'PAYROLL' THEN CASE WHEN(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'PAYROLL-',
            -1
        )
    ) = 'ARREARS' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'PAYROLL-ARREARS-',
        -1
    ) ELSE SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'PAYROLL-',
        -1
    )
END WHEN f_journal_entry_t.journal_type = 'PAYROLL/PF/ESI' THEN CASE WHEN(
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            2
        ),
        'PAYROLL/PF/ESI-',
        -1
    )
) = 'ARREARS' THEN SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        f_journal_entry_t.journal_name,
        '-',
        -2
    ),
    'PAYROLL/PF/ESI-ARREARS-',
    -1
) ELSE SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        f_journal_entry_t.journal_name,
        '-',
        -2
    ),
    'PAYROLL/PF/ESI-',
    -1
)
END
END
) AS paymonyr,
CASE WHEN f_journal_entry_lines_t.reference_source = 'SUPPLIER' THEN m_supplier_t.supplier_name WHEN f_journal_entry_lines_t.reference_source = 'MACHINE' THEN w_machine_hdr_t.machine_name WHEN f_journal_entry_lines_t.reference_source = 'PRODUCT' THEN m_products_t.concatenated_product WHEN f_journal_entry_lines_t.reference_source = 'CUSTOMER' THEN m_customers_t.customer_name WHEN f_journal_entry_lines_t.reference_source = 'EMPLOYEE' THEN hr_employee_t.first_name ELSE ''
END AS ref_name,
f_account_structure_t.concatenated_segments,
f_journal_entry_lines_t.product_qty,
round(CASE WHEN (f_journal_entry_lines_t.product_qty > 0 AND round(f_journal_entry_lines_t.debit_amount,2) > 0) THEN round(f_journal_entry_lines_t.debit_amount,2)/f_journal_entry_lines_t.product_qty WHEN (f_journal_entry_lines_t.product_qty > 0 AND round(f_journal_entry_lines_t.credit_amount,2) > 0) THEN round(f_journal_entry_lines_t.credit_amount,2)/f_journal_entry_lines_t.product_qty ELSE 0 END,2) AS rate_per,
CONCAT(
    ' ',
    f_journal_entry_lines_t.batch_number
) AS batch_number,
round(f_journal_entry_lines_t.debit_amount,2) AS debit,
round(f_journal_entry_lines_t.credit_amount,2) AS credit
FROM
    f_journal_entry_t
LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_lines_t.journal_entry_id = f_journal_entry_t.journal_entry_id
LEFT JOIN m_supplier_t ON(
        m_supplier_t.supplier_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'SUPPLIER'
    )
LEFT JOIN w_machine_hdr_t ON(
        w_machine_hdr_t.machine_hdr_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'MACHINE'
    )
LEFT JOIN m_products_t ON(
        m_products_t.product_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'PRODUCT'
    )
LEFT JOIN m_customers_t ON(
        m_customers_t.customer_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'CUSTOMER'
    )
LEFT JOIN hr_employee_t ON(
        hr_employee_t.employee_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'EMPLOYEE'
    )
LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
WHERE
    f_journal_entry_t.journal_date BETWEEN ? AND ?) AS v1 WHERE v1.journal_date!=''  order by v1.journal_date asc";


        $query = \DB::table(\DB::raw("($SQL) as v1"))->setBindings([$start_date, $end_date]);

        return DataTables::of($query)->make(true);

      
}
	
	
	 public function getpaymentamount($payid=null){
        $sql=\DB::select("select payment_amount from p_payments_t where payment_id='$payid'");
        return $sql;
    }
	
	
  public function getledgerreport(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (SELECT
    v2.*,
    SUM(v1.debit) AS debit,
    SUM(v1.credit) credit,
    SUM(v1.balance) AS opening_balance,
    ROUND(
        SUM(v1.balance + v1.debit - v1.credit),
        2
    ) AS balance
FROM
    (
        (
        SELECT
            f_journal_entry_t.journal_name,
            ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount
                ),
                2
            ) AS debit,
            ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount
                ),
                '2'
            ) AS credit,
            f_account_structure_t.concatenated_segments,
            0 AS balance,
            f_journal_entry_lines_t.account_id
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        WHERE
            f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
    )
UNION ALL
    (
    SELECT
        '' AS journal_name,
        0 AS debit,
        0 AS credit,
        f_account_structure_t.concatenated_segments,
        ROUND(
            SUM(
                f_journal_entry_lines_t.debit_amount - f_journal_entry_lines_t.credit_amount
            ),
            2
        ) AS balance,
        f_journal_entry_lines_t.account_id
    FROM
        `f_journal_entry_t`
    LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
    WHERE
        f_journal_entry_lines_t.journal_date < '$start_date' AND f_journal_entry_lines_t.journal_date >= '2019-04-01'
    GROUP BY
        f_journal_entry_lines_t.account_id
)
    ) v1
JOIN(
    SELECT
        f.f_account_structure_id,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
) AS v2
ON
    v2.f_account_structure_id = v1.account_id
GROUP BY
    f_account_structure_id) AS vikki";

   $results = \DB::select($SQL);

   return DataTables::of($results)->make(true);
}
	

public function journalreportExport(Request $request)
{
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date   = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (SELECT
    f_journal_entry_t.journal_entry_id,
    f_journal_entry_t.journal_name,
    f_journal_entry_t.journal_type,
    (
        CASE WHEN f_journal_entry_t.journal_type = 'SRN' OR f_journal_entry_t.journal_type = 'EXPENSES' OR f_journal_entry_t.journal_type = 'DEBIT' OR f_journal_entry_t.journal_type = 'SALES RETURN' OR f_journal_entry_t.journal_type = 'GRN' OR f_journal_entry_t.journal_type = 'MANUAL' OR f_journal_entry_t.journal_type = 'EMPLOYEE EXPENSES' OR f_journal_entry_t.journal_type = 'DEBIT NOTE' THEN f_journal_entry_t.journal_name WHEN f_journal_entry_t.journal_type = 'PO INVOICE' THEN SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            'POINVOICE-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'SHIPMENT CONFIRM' OR f_journal_entry_t.journal_type = 'SALES INVOICE' OR f_journal_entry_t.journal_type = 'QUALITY APPROVE' OR f_journal_entry_t.journal_type = 'JOB CARD CLOSE' OR f_journal_entry_t.journal_type = 'MATERIAL RECEIVE' OR f_journal_entry_t.journal_type = 'CONSUMABLE' OR f_journal_entry_t.journal_type = 'CONTRA ENTRY' THEN SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'DIRECTPAYMENT' OR f_journal_entry_t.journal_type = 'DIRECTRECEIPTS' OR f_journal_entry_t.journal_type = 'MOVE TO INVENTORY' OR f_journal_entry_t.journal_type = 'PAYMENT' OR f_journal_entry_t.journal_type = 'SALARYPAYMENT' OR f_journal_entry_t.journal_type = 'REVERSE RECEIPT' OR f_journal_entry_t.journal_type = 'REVERSE' OR f_journal_entry_t.journal_type = 'REPLACEMENT' OR f_journal_entry_t.journal_type = 'RECEIPT' OR f_journal_entry_t.journal_type = 'RECEIPT' OR f_journal_entry_t.journal_type = 'ADVANCE PAYMENT' OR f_journal_entry_t.journal_type = 'ADVANCE RECEIPT' OR f_journal_entry_t.journal_type = 'EMP/EXPENSE' OR f_journal_entry_t.journal_type = 'IMPRESTPAYMENT' THEN SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ) WHEN f_journal_entry_t.journal_type = 'JOB CARD STORE MOVE' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'Store Move-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'PFPAYMENT' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                3
            ),
            'PFPAYMENT-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'ESIPAYMENT' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                3
            ),
            'ESIPAYMENT-',
            -1
        ) WHEN f_journal_entry_t.journal_type = 'PAYROLL' THEN CASE WHEN(
            SUBSTRING_INDEX(
                SUBSTRING_INDEX(
                    f_journal_entry_t.journal_name,
                    '-',
                    2
                ),
                'PAYROLL-',
                -1
            )
        ) = 'ARREARS' THEN SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                3
            ),
            'PAYROLL-ARREARS-',
            -1
        ) ELSE SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'PAYROLL-',
            -1
        )
    END WHEN f_journal_entry_t.journal_type = 'PAYROLL/PF/ESI' THEN CASE WHEN(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'PAYROLL/PF/ESI-',
            -1
        )
    ) = 'ARREARS' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            3
        ),
        'PAYROLL/PF/ESI-ARREARS-',
        -1
    ) ELSE SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            2
        ),
        'PAYROLL/PF/ESI-',
        -1
    )
END
END
) AS journal_ref_name,
f_journal_entry_lines_t.journal_date,
f_journal_entry_lines_t.reference_source,
CONCAT(
    LEFT(
        MONTHNAME(
            f_journal_entry_lines_t.journal_date
        ),
        3
    ),
    '-',
    YEAR(
        f_journal_entry_lines_t.journal_date
    )
) AS monyr,
(
    CASE WHEN f_journal_entry_t.journal_type = 'PFPAYMENT' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'PFPAYMENT',
        -1
    ) WHEN f_journal_entry_t.journal_type = 'ESIPAYMENT' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'ESIPAYMENT-',
        -1
    ) WHEN f_journal_entry_t.journal_type = 'PAYROLL' THEN CASE WHEN(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                f_journal_entry_t.journal_name,
                '-',
                2
            ),
            'PAYROLL-',
            -1
        )
    ) = 'ARREARS' THEN SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'PAYROLL-ARREARS-',
        -1
    ) ELSE SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            -2
        ),
        'PAYROLL-',
        -1
    )
END WHEN f_journal_entry_t.journal_type = 'PAYROLL/PF/ESI' THEN CASE WHEN(
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            f_journal_entry_t.journal_name,
            '-',
            2
        ),
        'PAYROLL/PF/ESI-',
        -1
    )
) = 'ARREARS' THEN SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        f_journal_entry_t.journal_name,
        '-',
        -2
    ),
    'PAYROLL/PF/ESI-ARREARS-',
    -1
) ELSE SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        f_journal_entry_t.journal_name,
        '-',
        -2
    ),
    'PAYROLL/PF/ESI-',
    -1
)
END
END
) AS paymonyr,
CASE WHEN f_journal_entry_lines_t.reference_source = 'SUPPLIER' THEN m_supplier_t.supplier_name WHEN f_journal_entry_lines_t.reference_source = 'MACHINE' THEN w_machine_hdr_t.machine_name WHEN f_journal_entry_lines_t.reference_source = 'PRODUCT' THEN m_products_t.concatenated_product WHEN f_journal_entry_lines_t.reference_source = 'CUSTOMER' THEN m_customers_t.customer_name WHEN f_journal_entry_lines_t.reference_source = 'EMPLOYEE' THEN hr_employee_t.first_name ELSE ''
END AS ref_name,
f_account_structure_t.concatenated_segments,
f_journal_entry_lines_t.product_qty,
round(CASE WHEN (f_journal_entry_lines_t.product_qty > 0 AND round(f_journal_entry_lines_t.debit_amount,2) > 0) THEN round(f_journal_entry_lines_t.debit_amount,2)/f_journal_entry_lines_t.product_qty WHEN (f_journal_entry_lines_t.product_qty > 0 AND round(f_journal_entry_lines_t.credit_amount,2) > 0) THEN round(f_journal_entry_lines_t.credit_amount,2)/f_journal_entry_lines_t.product_qty ELSE 0 END,2) AS rate_per,
CONCAT(
    ' ',
    f_journal_entry_lines_t.batch_number
) AS batch_number,
round(f_journal_entry_lines_t.debit_amount,2) AS debit,
round(f_journal_entry_lines_t.credit_amount,2) AS credit
FROM
    f_journal_entry_t
LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_lines_t.journal_entry_id = f_journal_entry_t.journal_entry_id
LEFT JOIN m_supplier_t ON(
        m_supplier_t.supplier_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'SUPPLIER'
    )
LEFT JOIN w_machine_hdr_t ON(
        w_machine_hdr_t.machine_hdr_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'MACHINE'
    )
LEFT JOIN m_products_t ON(
        m_products_t.product_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'PRODUCT'
    )
LEFT JOIN m_customers_t ON(
        m_customers_t.customer_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'CUSTOMER'
    )
LEFT JOIN hr_employee_t ON(
        hr_employee_t.employee_id = f_journal_entry_lines_t.reference_id AND f_journal_entry_lines_t.reference_source = 'EMPLOYEE'
    )
LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
WHERE
    f_journal_entry_t.journal_date BETWEEN ? AND ?) AS v1 WHERE v1.journal_date!=''  order by v1.journal_date asc";

    $data = DB::select($SQL, [$start_date, $end_date]);

    $filename = "JournalReport.csv";

    $headers = [
        "Content-Type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function () use ($data) {

        $file = fopen('php://output', 'w');

        // Headings
        fputcsv($file, [
            'Journal Id',
            'Journal Name',
            'Journal Type',
            'Journal Ref Name',
            'Journal Date',
            'Month/Year',
            'Reference Source',
            'Ref Name',
            'Batch Number',
            'Product Qty',
            'Rate',
            'Segments',
            'Debit',
            'Credit'
        ]);

        foreach ($data as $row) {
            fputcsv($file, [
                $row->journal_entry_id,
                $row->journal_name,
                $row->journal_type,
                $row->journal_ref_name,
                $row->journal_date,
                $row->monyr,
                $row->reference_source,
                $row->ref_name,
                $row->batch_number,
                $row->product_qty,
                $row->rate_per,
                $row->concatenated_segments,
                $row->debit,
                $row->credit
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


}
