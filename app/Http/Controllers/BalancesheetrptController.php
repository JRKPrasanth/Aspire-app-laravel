<?php

namespace App\Http\Controllers;

use App\Balancesheetrpt;
use Illuminate\Http\Request;
use DB;

class BalancesheetrptController extends Controller
{
    
    public function index()
    {
        //
    }

//   public function balancesheet(){
//            
//		$sql=\DB::select("SELECT f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_lines_t.journal_date,f_journal_entry_t.journal_type,f_journal_entry_lines_t.account_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_account_structure_t.future_reference2, f_account_codes_lines_t.account_code,f_account_codes_lines_t.account_code_meaning,f_journal_entry_lines_t.f_journal_entry_line_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on (f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id) LEFT JOIN f_account_structure_t ON(f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id) LEFT JOIN f_account_codes_lines_t ON (f_account_codes_lines_t.account_codes_line_id=f_account_structure_t.future_reference2) where f_journal_entry_t.journal_status='POSTED'");
//		$this->data['result']=$sql;
//	   	return view('balancesheetrpt.table',$this->data);	
//	}
        public function balancesheet(){
	$sql=\DB::select("SELECT
    f_account_codes_lines_t.account_codes_line_id,
    f_account_codes_lines_t.account_class_id,
f_account_codes_lines_t.account_code,
f_account_codes_lines_t.account_code_meaning,
    SUM(
        f_journal_entry_lines_t.debit_amount
    ) AS total_debit,
    SUM(
        f_journal_entry_lines_t.credit_amount
    ) AS total_credit,
    f_journal_entry_lines_t.f_journal_entry_line_id,
    f_account_structure_t.future_reference2
FROM
    `f_account_codes_lines_t`
LEFT JOIN f_account_structure_t ON
    (
        f_account_structure_t.future_reference2 = f_account_codes_lines_t.account_codes_line_id
    )
LEFT JOIN f_journal_entry_lines_t ON(
        f_journal_entry_lines_t.account_id = f_account_structure_t.f_account_structure_id
    )
	left join f_journal_entry_t on(f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id)
where f_journal_entry_t.journal_status='POSTED'
GROUP BY
    f_account_codes_lines_t.account_codes_line_id");
		$this->data['result']=$sql;
			dd($this->data['result']);
	   	return view('balancesheetrpt.table',$this->data);
	}
	
	function employee($id)
	{
		$user = DB::table('hr_employee_t')->where('employee_id',$id)->get();
		$user_name = $user[0]->first_name.'-'.$user[0]->last_name;
		return $user_name;
	}
	function product($id)
	{
		$product = DB::table('m_products_t')->where('product_id',$id)->get();
		$product_name = $product[0]->concatenated_product;
		return $product_name;
	}
	
	public function jobcardresult($id=null)
	{
		$job_card_id = $id;

		$job_card_query = \DB::select("SELECT
    m_products_t.concatenated_product,
    job_card_report_hdr.date,
    job_card_report_hdr.actual_cost,
    job_card_report_hdr.palnning_cost
FROM
    job_card_report_hdr
LEFT JOIN m_products_t on m_products_t.product_id = job_card_report_hdr.product_id
where job_card_report_hdr.job_card_id='$id'");
		$job_card_query1 = \DB::select("SELECT
   *
FROM
    job_card_report_lines

where job_card_id='$id'");
		if(count($job_card_query1)>0){
			$p_emp_list = array();
			$p_prd_list = array();
			$a_emp_list = array();
			$a_prd_list = array();
			
		foreach($job_card_query1 as $key => $value)
		{
			
			
			$type = $value->type;
			if($value->costtype == 1)
			{
				if($type == 1)
				{
					$employee = $this->employee($value->typeid);
					$p_emp_list[]= array($employee,$value->rate,$value->hour,'planning');
				}
				else
				{
					$product = $this->product($value->typeid);
					$p_prd_list[]=array($product,$value->rate,$value->hour,'planning');
				}
			}
			else
			{
				if($type == 1)
				{	
					$employee = $this->employee($value->typeid);
					$a_emp_list[]= array($employee,$value->rate,$value->hour,'actual');
				}
				else
				{
					$product = $this->product($value->typeid);
					$a_prd_list[]=array($product,$value->rate,$value->hour,'actual');
				}
			}
		}
			
		}

		
		
		$html = '';
		$html='<div class="invoice-box" id="section-to-print">
      
        <table cellpadding="0" cellspacing="0">
            <tbody>


            <h2 class="heads1">Report</h2>
            
            <tr class="information">
                <td colspan="6">

					<table border="1" >
								<tbody>
								<tr>
									<td>
										<p><b>Product Name:</b>'.$job_card_query[0]->concatenated_product.'</p> <br>
										<p><b>Date:</b>'.$job_card_query[0]->date.'</p><br>

									</td>
								</tr>
								</tbody>
					</table>

                    <table border="1" >
                        <tbody>
                        <tr>
                            <td >
                                <p><b>PLANNING COST:</b>'.$job_card_query[0]->palnning_cost.'</p> <br>
                                
                            </td>
                            <td class="text-right">
                                <p><b>ACTUAL COST</b>'.$job_card_query[0]->actual_cost.'</p><br>
                                                     
                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
            </tr>  
           
                       

            
     
        </tbody></table>
		<br>
		<div class="row">
		<div class="col-md-6">
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Employee</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
				foreach($p_emp_list as $key => $value){
					$html .='<tr>
						<td>'.$value[0].'</td>
						<td>'.$value[1].'</td>
						
						
					</tr>';
				}
				$html .='</tbody>
				
		</table>
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Product</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
				foreach($p_prd_list as $key => $value){
					$html .='<tr>
						<td>'.$value[0].'</td>
						<td>'.$value[1].'</td>
						
						
					</tr>';
				}
				$html .='</tbody>
				
		</table>
		</div>
		
		<div class="col-md-6">
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Employee</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
				foreach($a_emp_list as $key => $value){
					$html .='<tr>
						<td>'.$value[0].'</td>
						<td>'.$value[1].'</td>
					
						
					</tr>';
				}
				$html .='</tbody>
				
		</table>
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Product</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
				foreach($a_prd_list as $key => $value){
					$html .='<tr>
						<td>'.$value[0].'</td>
						<td>'.$value[1].'</td>
					
						
					</tr>';
				}
				$html .='</tbody>
				
		</table>
		</div>
		</div>
		
		
    </div>';
		
		return $html;
	} 
	
}
