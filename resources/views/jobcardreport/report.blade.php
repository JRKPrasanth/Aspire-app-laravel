@extends('layouts.header')
@section('content')
<style type="text/css">
th{
    width: 250px;
    text-align: center;
}
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

.heads{
  background-size: 39px 39px;
}
</style>

            <h5 class="heads"><p>Journal Report </p> From 01/09/2018 To 31/09/2018</h5>
                  <h5>
                   </h5>
    
            
              <div class="card">

                
                <div class="card-body">
                  
                  <div class="table">
                  <?php $data=0; $credit=0;$debit=0;foreach($vdata as $key=>$value){
                    $id=$value->journal_entry_id;
                    
                    if($key!=0 && $id!=$data){
                        echo "<tr>
                              <td>
                              </td>
                              <td>
                              $debit
                              </td>
                               <td> 
                               $credit
                              </td>
                          </tr></tbody></table>";
                          $credit=0;
                          $debit=0;}
                   if($id!=$data){
                    $debit+=$value->debit_amount;
                    $credit+=$value->credit_amount;
                    $data=$id;
                   
                    ?>
                    
                  <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>
                          {{$value->journal_date}} - {{$value->journal_name}}
                          </th>
                          <th>
                          DEBIT 
                          </th>
                          <th>
                           CREDIT
                          </th>
                          
                       
                        </tr>
                      </thead>

                      <tbody>
                         <tr>
                         
                              <td>
                                 
                               {{$value->account_name}} 
                              </td>
                              
                              <td>
                               {{$value->debit_amount}} 
                              </td>
                         
                               <td>
                                {{$value->credit_amount}} 
                              </td>
                          </tr>
                       <?php } else{ $data=$id;
                        $debit+=$value->debit_amount;
                    $credit+=$value->credit_amount;?>
                          <tr>
                         
                              <td>
                                 
                               {{$value->account_name}} 
                              </td>
                              
                              <td>
                               {{$value->debit_amount}} 
                              </td>
                         
                               <td>
                                {{$value->credit_amount}} 
                              </td>
                          </tr>
                          
                         
                              
                             <?php }?> 
                    
                  <?php } echo "<tr>
                              <td>
                              </td>
                              <td>
                              $debit
                              </td>
                               <td> 
                               $credit
                              </td>
                          </tr></tbody></table>";  ?>
                    
                      
                 
               
                
              </div> </div>
          </div>
          
  	
    
@endsection




