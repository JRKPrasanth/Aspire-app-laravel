

<style type="text/css">
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.table-responsive > .table-bordered {
        text-align: center;
    border: 0;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.table.table-bordered thead {
    border: 1px solid #f2f2f2;
    border-bottom: none;
}

.table-responsive tbody tr > td:first-child {
    display: table-cell;
}
.card{
    border-radius: 5px;
    margin-bottom: 40px;
}
.card-body {
    padding: 14px 10px;
}
table tbody tr:nth-child(1) {
    background: none;
}
tbody{
    vertical-align: middle;
    font-size: 13px;
    line-height: 1;
    white-space: nowrap;
}
table-bordered td {
    border: 1px solid #f2f2f2;
}
.table th, .table td {
    padding: 18px 30px;
    
}
.table>thead:first-child>tr:first-child>th {
   color: #000;
    font-weight: bolder;
    text-align: center;
    background: none;
    padding: 10px 0px 10px 0px;
}
.table .progress{
    text-align: center;
    height: 8px;
    border-radius: 3px;
    width: 75%;
}

.table.table-bordered thead tr th {
    border-left: none;
    border-right: none;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #f2f2f2;
}
.table-bordered th, .table-bordered td {
    border: 1px solid #f2f2f2;
}
.table>tbody>tr>td{
    padding: 15px;

    vertical-align: middle;
    border-top: 1px solid #f2f2f2;
}

</style>



<h5 class="heads">Account Transactions<h5>

    
           
              <div class="card div_hide">
                
                <div class="card-body">
                  
                  <div class="table-responsive">
					  <div align="center"><b> From  {{$start_date}} To  {{$end_date}} </b></div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                         
                         <th>Journal Date</th>
						 <th>Journal Name</th>
				         <th>Journal Type</th>
				         <th>Ledger Name</th>
				         <th>Account Name</th>
				         <th>Credit <div class="label label-success"> INR </div></th>
			             <th>Debit <div class="label label-success">INR </div></th>
                       
                        </tr>
                      </thead>
                      <tbody>
                        
                       <?php  
						  foreach($result as $key=>$value) { ?>
			<tr>
			<td>{!! $value->journal_date !!}</td>
			<td>{!! $value->journal_name !!}</td>
			<td>{!! $value->journal_type !!}</td>
			<td>{!! $value->account_code_meaning !!}</td>
			<td>{!! $value->concatenated_segments !!}</td>
			<td>{!! $value->credit_amount !!}</td>
         	<td>{!! $value->debit_amount !!}</td>
                         
							  
						  </tr>
                       <?php } ?>
						
                      </tbody>
                    </table>
                  </div>
                </div>
           
              </div>
            
         

