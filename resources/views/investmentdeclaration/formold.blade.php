@extends('layouts.header')
@section('content')
<style type="text/css">
	.panel-success>.panel-heading {
		
    color: #fff;
    background-color: #11256f;
    border-color: #d6e9c6;
}
.panel-success{
	border: none;
	margin-bottom: 0 !important;
}
</style>
<div class="ajaxLoading"></div>
<span class="ui_close_btn"></span>

<h2 class="heads">Investment Declarations</h2>
<div class="card">

            <form  action=""  id="save" >
                <div class="card-body card-block">
                 
                {{ csrf_field()}}
                <div class="row">
					
					<div class="col-md-6">

						<div class="form-group row">
							<label for="start_date" class="form-control-label col-md-4">Employee Name</label>
							<div class="col-md-6">
							  <select type="text" id="employee_name" name="employee_name" class="form-control select2">
							  </select>
							</div>
</div>

						 <div class="form-group row">
							<label for="start_date" class="form-control-label col-md-4">Date of Joining</label>
							<div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
							  <input type="text" id="date_of_joining" name="date_of_joining" class="form-control date_of_joining" value="" required="">
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
							</div>
</div>
 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Age</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <input type="text" id="age" name="age" class="form-control age" value="" >
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
                            </div>
                    </div>

					</div>

            		<div class="col-md-6">
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Pan Number</label>
                            <div class="col-md-6">
                                    <input type="text" id="pan_number" name="pan_number" class="form-control pan_number" value="" required="">
                            </div>
                    </div>
						
						 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Date of Birth</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <input type="text" id="date_of_birth" name="date_of_birth" class="form-control date_of_birth" value="" required="">
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
                            </div>
                    </div>
                    
            </div>
			
			<div class="row" id="allowancediv">                    
					

					</div>			
<div class="col-md-6">
<div class="form-group row">
</div>	
</div>
	<div class="col-md-6">
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Monthly House Rent Paid</label>
                            <div class="col-md-6">
                                    <input type="text" id="Monthly_House_Rent_Paid" name="Monthly_House_Rent_Paid" class="form-control Monthly_House_Rent_Paid" value="" required="">
                            </div>
                    </div>
						
						 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Select Rent Location</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <input type="text" id="rent_location" name="rent_location" class="form-control rent_location" value="" required="">
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
                            </div>
                    </div>
                    
            </div>
            				<!-- <div class="row" id="formm">                    
					</div>	
					 -->
	
<div class="col-md-12" >
	<table class="overflow-y preview table1">

<thead>
		<tr>
		
		<th>Taxability & Calculation as per old and New Tax Regime</th>
			<th>Old</th>
				<th>New</th>
			</tr>
		</thead>
		<tbody class="table_lines1">
			<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Gross Annual Salary Income (Salary  + Allowances + Perks) " readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_gasi" name="old_gasi" class="form-control old_gasi" value="" >	
				</td>
				<td>
				      <input type="text" id="new_gasi" name="new_gasi" class="form-control new_gasi" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Less: Official and Reimbursement Non Taxable Allowances : " readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ornta" name="old_ornta" class="form-control old_ornta" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ornta" name="new_ornta" class="form-control new_ornta" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Less: Tax on Employment (Professional Tax) :	" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_toe" name="old_toe" class="form-control old_toe" value="" >	
				</td>
				<td>
				      <input type="text" id="new_toe" name="new_toe" class="form-control new_toe" value="" >	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Less: Standard deduction :" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_sd" name="old_sd" class="form-control old_sd" value="" >	
				</td>
				<td>
				      <input type="text" id="new_sd" name="new_sd" class="form-control new_sd" value="" >	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Auto Calculation as per Rent amount	Less: HRA Exemption :" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_hrae" name="old_hrae" class="form-control old_hrae" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ltae" name="new_ltae" class="form-control new_ltae" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Amount of LTA Travel Bills (Annually)	Less: LTA Exemption :" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ltae" name="old_ltae" class="form-control old_ltae" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ltae" name="new_ltae" class="form-control new_ltae" value="" >	
				</td>
			</tr>						

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Income from Salary :	" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ins" name="old_ins" class="form-control old_ins" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ins" name="new_ins" class="form-control new_ins" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Less: Loss from House Property u/s(24) :	" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ins" name="old_ins" class="form-control old_ins" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ins" name="new_ins" class="form-control new_ins" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Income from House Property :	" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ihp" name="old_ihp" class="form-control old_ihp" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ihp" name="new_ihp" class="form-control new_ihp" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Income from Other sources : " readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ios" name="old_ios" class="form-control old_ios" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ios" name="new_ios" class="form-control new_ios" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Gross Total Income :	" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_gti" name="old_gti" class="form-control old_gti" value="" >	
				</td>
				<td>
				      <input type="text" id="new_gti" name="new_gti" class="form-control new_gti" value="" >	
				</td>
			</tr>			
			
		</tbody>
	</table>
</div>

<div class="col-md-12">
	<table class="overflow-y preview table2">

<thead>
		<tr>
		
		<th>Investments U/S 80C & 80CCC	</th>
			<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody class="table_lines2">
			<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Current Employer - PF" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_cepf" name="old_cepf" class="form-control old_cepf" value="" >	
				</td>
				<td>
				      <input type="text" id="new_cepf" name="new_cepf" class="form-control new_cepf" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Pervious Employer - PF" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_pr_pf" name="old_pr_pf" class="form-control old_pr_pf" value="" >	
				</td>
				<td>
				      <input type="text" id="new_pr_pf" name="new_pr_pf" class="form-control new_pr_pf" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Voluntary Provident Fund  - VPF" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_vpf" name="old_vpf" class="form-control old_vpf" value="" >	
				</td>
				<td>
				      <input type="text" id="new_vpf" name="new_vpf" class="form-control new_vpf" value="" readonly="true">	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Life Insurance Premiums - LIP" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_lip" name="old_lip" class="form-control old_lip" value="" >	
				</td>
				<td>
				      <input type="text" id="new_lip" name="new_lip" class="form-control new_lip" value="" readonly="true">	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Public Provident Fund - PPF" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ppf" name="old_ppf" class="form-control old_ppf" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ppf" name="new_ppf" class="form-control new_ppf" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Children Education Tuition Fees - CEF" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_cetf" name="old_cetf" class="form-control old_cetf" value="" >	
				</td>
				<td>
				      <input type="text" id="new_cetf" name="new_cetf" class="form-control new_cetf" value="" readonly="true">	
				</td>
			</tr>						

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Pension Funds – Section 80CCC" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_pfs80" name="old_pfs80" class="form-control old_pfs80" value="" >	
				</td>
				<td>
				      <input type="text" id="new_pfs80" name="new_pfs80" class="form-control new_pfs80" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Bank fixed deposits (5-Yr) - FD" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_bfd" name="old_bfd" class="form-control old_bfd" value="" >	
				</td>
				<td>
				      <input type="text" id="new_bfd" name="new_bfd" class="form-control new_bfd" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Unit linked Insurance Plan - ULIP	" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_ulip" name="old_ulip" class="form-control old_ulip" value="" >	
				</td>
				<td>
				      <input type="text" id="new_ulip" name="new_ulip" class="form-control new_ulip" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Home Loan Principal Repayment - HLP" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_hlpr" name="old_hlpr" class="form-control old_hlpr" value="" >	
				</td>
				<td>
				      <input type="text" id="new_hlpr" name="new_hlpr" class="form-control new_hlpr" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Equity Linked Savings Scheme - ELSS" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_elss" name="old_elss" class="form-control old_elss" value="" >	
				</td>
				<td>
				      <input type="text" id="new_elss" name="new_elss" class="form-control new_elss" value="" readonly="true">	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="National Savings Certificate - NSC" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_nsc" name="old_nsc" class="form-control old_nsc" value="" >	
				</td>
				<td>
				      <input type="text" id="new_nsc" name="new_nsc" class="form-control new_nsc" value="" readonly="true">	
				</td>
			</tr>	
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Stamp Duty and Registration Charges" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_sdpc" name="old_sdpc" class="form-control old_sdpc" value="" >	
				</td>
				<td>
				      <input type="text" id="new_sdpc" name="new_sdpc" class="form-control new_sdpc" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Other Investments E.g. Sukanya Account" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_oisa" name="old_oisa" class="form-control old_oisa" value="" >	
				</td>
				<td>
				      <input type="text" id="new_oisa" name="new_oisa" class="form-control new_oisa" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Total Investments U/S 80C & 80CCC" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_totalinv80" name="old_totalinv80" class="form-control old_totalinv80" value="" >	
				</td>
				<td>
				      <input type="text" id="new_totalinv80" name="new_totalinv80" class="form-control new_totalinv80" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Total Investments U/S 80C & 80CCC" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_totalinv80" name="old_totalinv80" class="form-control old_totalinv80" value="" >	
				</td>
				<td>
				      <input type="text" id="new_totalinv80" name="new_totalinv80" class="form-control new_totalinv80" value="" readonly="true">	
				</td>
			</tr>																		
			
		</tbody>
	</table>
</div>

<div class="col-md-12">
	<table class="overflow-y preview table3">

<thead>
		<tr>
		
		<th>Investments U/S 80C & 80CCC	Deduction</th>
			<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody class="table_lines3">
			<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80D	" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80" name="old_dus80" class="form-control old_dus80" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80" name="new_dus80" class="form-control new_dus80" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80CCD(1B)" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80ccd1" name="old_dus80ccd1" class="form-control old_dus80ccd1" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80ccd1" name="new_dus80ccd1" class="form-control new_dus80ccd1" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80CCD2" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80ccd2" name="old_dus80ccd2" class="form-control old_dus80ccd2" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80ccd2" name="new_dus80ccd2" class="form-control new_dus80ccd2" value="" readonly="true">	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80DD" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80d" name="old_dus80d" class="form-control old_dus80d" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80d" name="new_dus80d" class="form-control new_dus80d" value="" readonly="true">	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80DDB" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80ddb" name="old_dus80ddb" class="form-control old_dus80ddb" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80ddb" name="new_dus80ddb" class="form-control new_dus80ddb" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80E" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80e" name="old_dus80e" class="form-control old_dus80e" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80e" name="new_dus80e" class="form-control new_dus80e" value="" readonly="true">	
				</td>
			</tr>						

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80EE" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80ee" name="old_dus80ee" class="form-control old_dus80ee" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80ee" name="new_dus80ee" class="form-control new_dus80ee" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80EEA" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80eea" name="old_dus80eea" class="form-control old_dus80eea" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80eea" name="new_dus80eea" class="form-control new_dus80eea" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80G" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80g" name="old_dus80g" class="form-control old_dus80g" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80g" name="new_dus80g" class="form-control new_dus80g" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80TTA" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80tta" name="old_dus80tta" class="form-control old_dus80tta" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80tta" name="new_dus80tta" class="form-control new_dus80tta" value="" readonly="true">	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80U" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80u" name="old_dus80u" class="form-control old_dus80u" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80u" name="new_dus80u" class="form-control new_dus80u" value="" readonly="true">	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Total Deduction U/C VIA" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_totducvia" name="old_totducvia" class="form-control old_totducvia" value="" >	
				</td>
				<td>
				      <input type="text" id="new_totducvia" name="new_totducvia" class="form-control new_totducvia" value="" readonly="true">	
				</td>
			</tr>	
			
		</tbody>
	</table>
</div>

<div class="col-md-12">
	<table class="overflow-y preview table4">

<thead>
		<tr>
		
		<th>Taxable Income</th>
			<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody class="table_lines4">
			<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Taxable Income" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_tottincome" name="old_tottincome" class="form-control old_tottincome" value="" >	
				</td>
				<td>
				      <input type="text" id="new_tottincome" name="new_dus80" class="form-control new_dus80" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Income Tax" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_intax" name="old_intax" class="form-control old_intax" value="" >	
				</td>
				<td>
				      <input type="text" id="new_intax" name="new_intax" class="form-control new_intax" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Less: Rebate 87A" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_rebate87a" name="old_rebate87a" class="form-control old_rebate87a" value="" >	
				</td>
				<td>
				      <input type="text" id="new_rebate87a" name="new_rebate87a" class="form-control new_rebate87a" value="" >	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80DD" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80d" name="old_dus80d" class="form-control old_dus80d" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80d" name="new_dus80d" class="form-control new_dus80d" value="" readonly="true">	
				</td>
			</tr>

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Balance Tax Liability" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_btlib" name="old_btlib" class="form-control old_btlib" value="" >	
				</td>
				<td>
				      <input type="text" id="new_btlib" name="new_btlib" class="form-control new_btlib" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Surcharge" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_adsur" name="old_adsur" class="form-control old_adsur" value="" >	
				</td>
				<td>
				      <input type="text" id="new_adsur" name="new_adsur" class="form-control new_adsur" value="" >	
				</td>
			</tr>						

<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Deduction u/s 80EE" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_dus80ee" name="old_dus80ee" class="form-control old_dus80ee" value="" >	
				</td>
				<td>
				      <input type="text" id="new_dus80ee" name="new_dus80ee" class="form-control new_dus80ee" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Total Tax" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_totaltax" name="old_totaltax" class="form-control old_totaltax" value="" >	
				</td>
				<td>
				      <input type="text" id="new_totaltax" name="new_totaltax" class="form-control new_totaltax" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Edu. Health Cess" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_addeduhc" name="old_addeduhc" class="form-control old_addeduhc" value="" >	
				</td>
				<td>
				      <input type="text" id="new_addeduhc" name="new_addeduhc" class="form-control new_addeduhc" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Net Annual Tax" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_netannualtax" name="old_netannualtax" class="form-control old_netannualtax" value="" >	
				</td>
				<td>
				      <input type="text" id="new_netannualtax" name="new_netannualtax" class="form-control new_netannualtax" value="" >	
				</td>
			</tr>
	<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Save Tax as per New Slab" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_savetax" name="old_savetax" class="form-control old_savetax" value="" >	
				</td>
				<td>
				      <input type="text" id="new_savetax" name="new_savetax" class="form-control new_savetax" value="" >	
				</td>
			</tr>		
		</tbody>
	</table>
</div>





					
                <div class="col-md-12 text-center ">
				
				
				<div class="row">
				<h5>Do You Want to Confirm Save ?</h5>
					Yes <input type="radio" name="confirm"  class="confirm" value="1" />
					No <input type="radio" name="confirm" class="confirm" value="0" />
				</div>
				<h4>After saving the details you can't edit.</h4>
                    <button type="button"  class="btn save savebut">Save</button> 
                    <button type="button"  class="btn save draftbut">Draft</button>
                   
                </div>
              </div>
					
        </div> 
        
</form>

</div>






	<script>
	$(document).ready(function(){
		
		
		var employee_id = '{{Session::get('emp_id')}}';
       //button readonly
	    //$('.save').prop('disabled',true);
// employee load
		$("#employee_name").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&order_by=first_name asc",
		{selected_value:employee_id});
		// get investment type
		$('#employee_name').on('change',function(){
		if(employee_id != '')
		{
	var employee_id=$("#employee_name").val();	   
			var url = "{{URL::to('getDeclaration')}}/"+employee_id;
			
			$.get(url,function(data)
			{
				
				$('#formm').html(data['table']);
					$('#allowancediv').html(data['alltbl']);
				$('#pan_number').val(data['pan_no']).prop('readonly',true);
				$('#date_of_birth').val(data['dob']).prop('readonly',true);
				$('#date_of_joining').val(data['doj']).prop('readonly',true);
				dob = new Date(data['dob']);
				var today = new Date();
				var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
				$('#age').val(age).prop('readonly',true);
			});
		}
		});
	// confirm click 	
		$(document).on('click','.confirm',function()
		{
			var action = $(this).val();
			 if(action == 1)
			 {
				 $('.savebut').show();
				 $('.draftbut').hide();
                                 $('.save').prop('disabled',false);
			 }
			 else
			 {
			         $('.savebut').hide();
				 $('.draftbut').show();
                                 $('.save').prop('disabled',false);
			 }
		});


//save
            $(document).on('click','.save',function(e){
$('.ajaxLoading').show();
                e.preventDefault();
                var data;
                data = $("#save").serialize();
                
              
                $.post('investmentsave', data, function(data)
                {
                     
                    if(data == 1)
                    {
                        notyMsg('success','Saved Successfully');
                       setTimeout(function(){
                           window.location.reload();
                       },300);
                      
                    }
                   
                });
               

            }); 
                
       
		
		
		// numbers only
			   $(document).on('keypress','.investment',function(e)
			   {
				    if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					  	$("#errmsg").html("Digits Only").show().fadeOut("slow");
							 return false;
				     }
     			});



          

	});
	</script>

@endsection
