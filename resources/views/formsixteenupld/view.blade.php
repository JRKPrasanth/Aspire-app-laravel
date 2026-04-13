@extends('layouts.header')
@section('content')


<style type="text/css">
      
      
.invoice-box{
  max-width: 1200px;
}
     
</style>



<form>
{{ csrf_field() }}


<div class="card">
<div class="card-header">
<!--<h2> Bank Account</h2>--> 
<span class="ui_close_btn"><a href="../bankaccount" class="collapse-close pull-right btn-danger" onclick="../bankaccount"></a></span>
</div>
     <div class="card-body card-block normalform">
       <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Bank Account Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p><b>Bank Name:</b> {!! $bank_name !!}</p>
                                            <br>
                                             <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                                            <br>
                                             <p><b>Active:</b> {!! $active !!}</p>
                                            <br>
                                        </td>
                                         <td>
                                            <p><b>Bank Type:</b> {!! $bank_type !!}</p>
                                            <br>
                                             <p><b>Customer Name:</b> {!! $customer_name !!}</p>
                                            <br>
                                            <p><b>Created By:</b> {!! $username !!}</p>
                                            <br>
                                        </td>
                                        <td class="text-right">
                                           <p><b>Bank Source:</b> {!! $bank_source !!}</p>
                                            <br> 
                                             <p><b>Company Name:</b> {!! $company_name !!}</p>
                                            <br>
                                            </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="heading">
                        <table class="table table-bordered table-hover ">
                           <thead>
      <tr>
            <th>Branch Name</th>
                        <th>Branch Address</th>
                        <th>IFSC Code</th>
                         <th>MICR Code</th>
                        <th>Account Type</th>
                        <th>Name In Account</th>
                        <th>NIckName In Account</th>
                        <th>Account Number</th>
                        <th>Account Number</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Active</th>
                        <th>Comments</th>
      </tr>
      </thead>
<tbody>
  
       @foreach ($vlinesdata as $key=>$value) 
<tr>
     
      <td>{{ $value->branch_name}}</td>
      <td>{{ $value->branch_address}}</td>
      <td>{{ $value->ifsc_code}}</td>
      <td>{{ $value->MICR_code}}</td>
      <td>{{ $value->account_type}}</td>
      <td>{{ $value->name_in_account}}</td>
      <td>{{ $value->nickname_in_acoount}}</td>
      <td>{{ $value->account_number}}</td>
      <td>{{ $value->favouring_name}}</td>
      <td>{{ $value->start_date}}</td>
      <td>{{ $value->end_date}}</td>
      <td>{{ $value->active}}</td>
      <td>{{ $value->comments}}</td>
      
</tr>
@endforeach

</tbody>
                        </table>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

</div> 
</div>
</div>

</form>	

@endsection


