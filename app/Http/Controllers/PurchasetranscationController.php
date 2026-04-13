<?php

namespace App\Http\Controllers;

use App\purchasetranscation;
use Illuminate\Http\Request,DB;

class PurchasetranscationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchasetranscationreport.purchasetranscation');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report()
    {
        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? 	date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
		
		//$query_result1 = DB::table('f_journal_entry_t')->leftJoin('f_journal_entry_lines_t', 'f_journal_entry_lines_t.journal_entry_id', '=', 'f_journal_entry_t.journal_entry_id')
            //->select('f_journal_entry_t.*','f_journal_entry_lines_t.*')->where('f_journal_entry_t.journal_type','PO INVOICE')->where('f_journal_entry_lines_t.journal_date','>=',$start_date)->where('f_journal_entry_lines_t.journal_date','<=',$end_date)->get();
	
		
		
		$result ='';
		$result ='<div class="row grid-margin1 div_hide">
    	<div class="col-md-12">
            <div class="col-md-12 grid-margin">
              <div class="card">
                <div class="card-header" align="center">
                  <h5 >Purchase Transcation<h5>
              	</div>
                <div class="card-body">
                  <div class="table-responsive">	  
				  <div align="center">
				  	<b> From- '.$start_date.' To- '.$end_date.'</b>
					</div>';
				
					$result .='<div align="center">
				  	<b> test123</b>
					</div>
                      <table class="table table-bordered">
                      <thead>
					  <tr>
                         <th>Date</th>
				         <th>Account</th>
					     <th>Debit <div class="label label-success">INR </div></th>
			             <th>Credit <div class="label label-success"> INR </div></th>
					  </tr>
                      </thead>
                      <tbody>';
		
					
					$query1 = DB::table('f_journal_entry_t')->where('f_journal_entry_t.journal_type','PO INVOICE');
					if($start_date != '')
					{
						$query1->where('journal_date','>=', $start_date);
					}
					if($end_date != '')
					{
						$query1->where('journal_date','<=', $end_date);
					}
					$query1 = $query1->get();
					
					if(count($query1)>0)
					{
						foreach($query1 as $key=>$value)
						{
							$tot_deb =0;
							$tot_cre =0;
							$query2 = DB::table('f_journal_entry_lines_t')->where('journal_entry_id',$value->journal_entry_id)->get();
							foreach($query2 as $key=>$value)
							{
								$tot_deb += $value->debit_amount;
								$tot_cre += $value->credit_amount;
						$result .='<tr>
									<td>'.$value->journal_date.'</td>
									<td>'.$value->account_id.'</td>
									<td>'.$value->debit_amount.'</td>
									<td>'.$value->credit_amount.'</td>					
					  			</tr>';
								
							}
						}
					}
					else
					{
						$tot_deb =0;
						$tot_cre =0;
					}
					$result .='<tr>
							<td colspan="2"><b>Total</b></td>	
							<td>'.$tot_deb.'</td>	
							<td>'.$tot_cre.'</td>	
						</tr>
                      </tbody>
                    </table>';
										
						
								
                  $result .='</div>
                </div>
              </div>
            </div>
          </div>
      </div>';
		
		return  $result;
		
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

    /**
     * Display the specified resource.
     *
     * @param  \App\purchasetranscation  $purchasetranscation
     * @return \Illuminate\Http\Response
     */
    public function show(purchasetranscation $purchasetranscation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\purchasetranscation  $purchasetranscation
     * @return \Illuminate\Http\Response
     */
    public function edit(purchasetranscation $purchasetranscation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\purchasetranscation  $purchasetranscation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, purchasetranscation $purchasetranscation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\purchasetranscation  $purchasetranscation
     * @return \Illuminate\Http\Response
     */
    public function destroy(purchasetranscation $purchasetranscation)
    {
        //
    }
}
