@extends('layouts.header')
@section('content')
<h3 class="text-danger">Payment For Invoice</h3>
@include('layouts.breadcrumb')
<?php error_reporting(0); ?>


<form method="post" action="" id="paymentinv_form" data-parsley-validate>
    {{ csrf_field() }}


    <div class="card shadow-lg rounded-4 border-0">

        <div class="card-body card-block">
            <div class="row">
                <div class="col-md-4">
                    <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden"
                        value="{{ $row->payment_id }}" readonly>
                    <input class="form-control payment_number" id="payment_number" name="payment_number" size="16"
                        type="hidden" value="{{ $row->payment_number }}" readonly>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6"><span
                                style="color:red">*</span>Invoice No</label>
                        <div class="col-md-6 sel2">
                            <input type="text" class="form-control bill_number" id="bill_number" size="16"
                                value="{{ $bill_number }}" readonly>
                            <input type="text" class="form-control po_invoice_id" id="po_invoice_id"
                                name="po_invoice_id" size="16" value="{{ $row->po_invoice_id }}" hidden="true"
                                style="display:none;" readonly>
                            <input type="hidden" class="form-control po_hdr_id" id="po_hdr_id" name="po_hdr_id"
                                size="16" value="{{ $row->po_hdr_id }}" hidden="true" style="display:none;" readonly>

                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Payment Date</label>
                        <div class="col-md-6">
                            <div class="input-group form_date col-md-8">
                                <input class="form-control start_date payment_date" id="payment_date"
                                    name="payment_date" size="16" type="text" value="{{ $row->payment_date }}">

                            </div>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Invoice Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="invoice_amount" name="invoice_amount"
                                class="form-control invoice_amount chckclick" value="{{ $row->invoice_amount }}"
                                readonly>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">TDS Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="tds_amount" name="tds_amount" class="form-control tds_amount"
                                value="{{ $row->tds_amount }}" readonly>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Supplier Name</label>
                        <div class="col-md-6">
                            <select name='supplier_id' rows='5' class='form-control supplier_id' readonly>
                                {!! $supplier_id !!}
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Supplier Bank Name</label>
                        <div class="col-md-6">
                            <input type="text" id="supplier_bank_id" name="supplier_bank_id"
                                class="form-control supplier_bank_id" value="{{ $row->supplier_bank_id}}" required
                                readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Supplier Account Name</label>
                        <div class="col-md-6">
                            <input type="text" id="supplier_account_name" name="supplier_account_name"
                                class="form-control supplier_account_name" value="{{ $row->supplier_account_name}} "
                                readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Supplier Account No</label>
                        <div class="col-md-6">
                            <input type="text" id="supplier_account_no" name="supplier_account_no"
                                class="form-control supplier_account_no" value="{{ $row->supplier_account_no}}"
                                readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Supplier IFSC Code</label>
                        <div class="col-md-6">
                            <input type="text" id="supplier_ifsc_code" name="supplier_ifsc_code"
                                class="form-control supplier_ifsc_code" value="{{ $row->supplier_ifsc_code}}" readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Supplier Favouring Name</label>
                        <div class="col-md-6">
                            <input type="text" id="favouring_name" name="favouring_name"
                                class="form-control favouring_name" value="{{ $row->favouring_name}}">
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label col-md-6">Supplier Balance</label>
                        <div class="col-md-6">
                            <input type="text" id="supplier_balance" 
                                class="form-control supplier_balance"
                                value="{{ $row->supplier_balance }}" readonly>
                        </div>
                    </div>   

                </div>




                <div class="col-md-4">
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6"><span class="req"
                                style="color:red">*</span>Payment Type</label>
                        <div class="col-md-6 sel2">
                            <select name='payment_type_id' rows='5' class='form-control payment_type_id select2'
                                data-show-subtext="true" data-live-search="true">
                                <option value="">--Please Select--</option>
                                <option <?php if ($row->payment_type_id == "CHEQUE") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="CHEQUE">CHEQUE</option>
                                <option <?php if ($row->payment_type_id == "CASH") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="CASH">CASH</option>
                                <option <?php if ($row->payment_type_id == "NEFT") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="NEFT">NEFT</option>
                                <option <?php if ($row->payment_type_id == "MTPS") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="MTPS">MTPS</option>
                                <option <?php if ($row->payment_type_id == "IMPS") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="IMPS">IMPS</option>
                                <option <?php if ($row->payment_type_id == "RTGS") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="RTGS">RTGS</option>
                                <option <?php if ($row->payment_type_id == "ONLINE") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="ONLINE">ONLINE</option>
                                <option <?php if ($row->payment_type_id == "IMPREST") {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="IMPREST">IMPREST</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Advance Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="advance_amount" name="advance_amount"
                                class="form-control advance_amount" value="{{ $row->advance_amount }}" required
                                readonly>
                            <!--&nbsp;<input type="checkbox"  name="advance_deduction[]" value="1" class="advance_deduction ">-->

                        </div>
                        <div class="col-md-2 showinline">
                            <i class="fa fa-plus" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#advanceModal"
                                style="color: #142e78;
                font-size: 13px;
                padding: 5px;
                border: 1px solid;
                cursor: pointer;"></i>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6"><span
                                style="color:red">*</span>Payment Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="payment_amount" name="payment_amount"
                                class="form-control payment_amount chckclick" value="{{ $row->payment_amount }}"
                                required readonly>
                        </div>
                        <div class="col-md-2 showinline">
                            <i class="fa fa-plus" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#myModal" style="color: #142e78;
    font-size: 13px;
    padding: 5px;
    border: 1px solid;
    cursor: pointer;"></i>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6"><span
                                style="color:red">*</span>Credit Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="credit_amount" name="credit_amount"
                                class="form-control credit_amount creditchckclick" value="{{ $row->credit_amount }}"
                                readonly>
                        </div>
						<div class="col-md-2 d-flex align-items-center">
							<span class="showspan">
								<i class="fa fa-plus text-primary" style="cursor: pointer; font-size: 16px;"
								   data-bs-toggle="modal" data-bs-target="#mycreditModal" aria-hidden="true"></i>
							</span>
						</div>

                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6"><span
                                style="color:red">*</span>Debit Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="debit_amount" name="debit_amount"
                                class="form-control debit_amount debitchckclick" value="{{ $row->debit_amount }}"
                                readonly>
                        </div>
                        <div class="col-md-2 showinline">
                            <span class="showspan"><i class="fa fa-plus" aria-hidden="true" data-bs-toggle="modal"
                                    data-bs-target="#mydebitModal" style="margin::4px 0;"></i></span>

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Paid Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="paid_amount" name="paid_amount"
                                class="form-control paid_amount chckclick" value="{{ $row->paid_amount }}" readonly>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="row mb-3 bank_date">
                        <label for="inputIsValid" class="col-form-label col-md-6">Bank Date</label>
                        <div class="col-md-6">
                            <div class="input-group form_date col-md-8" >
                                <input class="form-control bank_date" id="bank_date" name="bank_date"
                                    size="16" type="text" value="{{ $row->bank_date }}">

                            </div>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Balance Amount</label>
                        <div class="col-md-6">
                            <input type="text" id="balance_amount" name="balance_amount"
                                class="form-control balance_amount chckclick" value="{{ $row->balance_amount }}"
                                readonly>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="row mb-3 chequediv">
                        <label for="inputIsValid" class="col-form-label col-md-6"><span class="bankdiv req"
                                style="color:red">*</span>Bank Name</label>
                        <div class="col-md-6 sel2">
                            <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id'>
                                {!! $bank_id !!}
                            </select>
                        </div>
                    </div>
					
                    <div class="row mb-3 chequediv">
                        <label for="inputIsValid" class="col-form-label col-md-6">Account Number</label>
                        <div class="col-md-6 supplier_div">
                            <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Payment Source</label>
                        <div class="col-md-6">
                            <select type="text" name="payment_source" id="payment_source"
                                class="form-control  payment_source" readonly>
                                <option value="">-- Please Select --</option>
                                <option <?php if ($row->payment_source == "INVOICE")
                                    echo "selected"; ?> value="INVOICE">
                                    INVOICE</option>

                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">UTR Number</label>
                        <div class="col-md-6">
                            <input type="text" id="payment_reference" name="payment_reference"
                                class="form-control payment_reference" value="{{ $row->payment_reference }}" />
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3 imprestemp_div">
                        <label for="inputIsValid" class="col-form-label col-md-6">Employee Name</label>
                        <div class="col-md-6">
                            <select name='imprest_employee_id' rows='5' class='select2 imprest_employee_id'>
                                {!! $imprest_employee_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6"><span class="req"
                                style="color:red;">*</span>Account Code</label>
                        <div class="col-md-6 ">
                            <select name='account_code_id' rows='5' class='select2 account_code_id'>
                                {!! $account_code_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>

                    <div class="row mb-3" style="display:none;">
                        <label for="inputIsValid" class="col-form-label col-md-6">Payment Status</label>
                        <div class="col-md-6">
                            <select type="text" name="payment_status" id="payment_status"
                                class="form-control  payment_status" readonly>
                                <option value="">-- Please Select --</option>
                                <option <?php if ($row->payment_status == "PAID")
                                    echo "selected"; ?> value="PAID">PAID
                                </option>
                                <option <?php if ($row->payment_status == "UNPAID")
                                    echo "selected"; ?> value="UNPAID">
                                    UNPAID</option>
                                <option <?php if ($row->payment_status == "OVERDUE")
                                    echo "selected"; ?> value="OVERDUE">
                                    OVERDUE</option>
                                <option <?php if ($row->payment_status == "PARTIALLY PAID")
                                    echo "selected"; ?>
                                    value="PARTIALLY PAID">PARTIALLY PAID</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Sender Information</label>
                        <div class="col-md-6">
                            <input type="text" name="sender_information" id="sender_information"
                                class="form-control sender_information" value="{{ $row->sender_information }}">

                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Narration</label>
                        <div class="col-md-6">
                            <input type="text" name="remarks" id="remarks" class="form-control remarks"
                                value="{{ $row->remarks }}">

                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="row mb-3 chequediv">
                        <label for="inputIsValid" class="col-form-label col-md-6 chequelabel">Cheque No</label>
                        <div class="col-md-6 supplier_div">
                            <input type="number"  step="0.01"  min="0" id="cheque_no" name="cheque_no"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control cheque_no"
                                value="{{ $row->cheque_no }}" readonly />
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputIsValid" class="col-form-label col-md-6">Cheque Date</label>
                        <div class="col-md-6">
                            <div class="input-group form_date col-md-8">
                                <input class="form-control cheque_date" id="cheque_date" name="cheque_date"
                                    size="16" type="text" value="{{ $row->cheque_date }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
          <!-- Invoice Balance Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="invoiceModalLabel">Invoice Balance Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Invoice (Bill) Number</th>
                            <th>Balance Amount</th>
                            <th>Payment Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($invoice_balamt as $k => $v) { ?>
                        <tr>
                            <td>{{$v->bill_number}}</td>
                            <td class="balance_amount text-end" id="{{$v->balance_amount}}">
                                {{$v->balance_amount}}
                            </td>
                            <td>
                                <input type="number"  step="0.01"  min="0" name="paymentamt[{{$v->po_invoice_id}}]" 
                                       value="{{$v->balance_amount}}" class="form-control form-control-sm paymentamt">
                            </td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success addedpayamt" data-bs-dismiss="modal">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Advance Amount Modal -->
<div class="modal fade" id="advanceModal" tabindex="-1" aria-labelledby="advanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="advanceModalLabel">PO Balance Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($poamt == 0) { ?>
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>PO Number</th>
                            <th>Advance Amount</th>
                            <th>Payment Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($po_balamt as $k => $v) { ?>
                        <tr>
                            <td>{{$v->po_number}}</td>
                            <td class="pobalance_amount text-end" id="{{$v->balance_amount}}">
                                {{$v->balance_amount}}
                            </td>
                            <td>
                                <input type="number"  step="0.01"  min="0" name="popaymentamt[{{$v->po_hdr_id}}]" 
                                       class="form-control form-control-sm popaymentamt" 
                                       value="{{$v->balance_amount}}">
                            </td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <div class="alert alert-warning text-center mb-0">
                    There is no advance amount for this PO.
                </div>
                <?php } ?>
            </div>
            <?php if ($poamt == 0) { ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-success addadvancepayamt" data-bs-dismiss="modal">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

            <!--end-->
			
            <div class="row mt-2 mb-2">
                <div class="col-lg-12 col-md-12">
                    <input type="hidden" name="submit_type" class="submit_type" value="" />
                    <div class="form-group text-center actionbtn">
                        <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                        <a class='btn btn-danger px-4 me-2' onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" class="pdtindex" value="" />

    <!-- PURPOSE:credit,debit details based on customer-->
   <!-- Invoice Credit Modal -->
<div class="modal fade" id="mycreditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="creditModalLabel">Invoice Credit Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Invoice Number</th>
                            <th>Credit Amount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        if (count($credit_balamt) > 0) {
                            foreach ($credit_balamt as $k => $v) { ?>
                                <tr>
                                    <td>{{$v->bill_number}}</td>
                                    <td class="text-end credit_note_balance" id="{{$v->credit_note_balance}}">
                                        {{$v->credit_note_balance}}
                                    </td>
                                    <td>
                                        <input type="number"  step="0.01"  min="0" name="creditamount[{{$v->po_invoice_id}}]" class="form-control form-control-sm creditamt"
                                               value="{{$v->credit_note_balance}}">
                                    </td>
                                </tr>
                                <?php $i++;
                            }
                        } else { ?>
                            <tr>
                                <td colspan="3" class="text-center text-warning">There is no credit amount for this supplier</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <?php if (count($credit_balamt) > 0) { ?>
                    <button type="button" class="btn btn-success addcreditamt" data-bs-dismiss="modal">Add</button>
                <?php } ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Debit Modal -->
<div class="modal fade" id="mydebitModal" tabindex="-1" aria-labelledby="debitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="debitModalLabel">Invoice Debit Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Invoice Number</th>
                            <th>Debit Amount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        if (count($debit_balamt) > 0) {
                            foreach ($debit_balamt as $k => $v) { ?>
                                <tr>
                                    <td>{{$v->bill_number}}</td>
                                    <td class="text-end debit_note_balance" id="{{$v->debit_note_balance}}">
                                        {{$v->debit_note_balance}}
                                    </td>
                                    <td>
                                        <input type="number"  step="0.01"  min="0" name="debitamount[{{$v->po_invoice_id}}]" class="form-control form-control-sm debitamt"
                                               value="{{$v->debit_note_balance}}">
                                    </td>
                                </tr>
                                <?php $i++;
                            }
                        } else { ?>
                            <tr>
                                <td colspan="3" class="text-center text-warning">There is no debit amount for this supplier</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <?php if (count($debit_balamt) > 0) { ?>
                    <button type="button" class="btn btn-success adddebitamt" data-bs-dismiss="modal">Add</button>
                <?php } ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!--end-->

</form>

@endsection
@push('scripts')

<script>

$(document).ready(function(){
  $('select,.accountcode_div').css('pointer-events', 'none');

$(document).on('click','.advance_deduction',function(){
    var check=$(this).val();
    var invoiceid=$('.po_invoice_id').val();
    var url="{{URL::to('getadvance')}}/"+invoiceid;
    $.get(url,function(data){
         if(check=='1'){
        $('.balance_amount').val(data.advance_deduction);
        $('.invoice_amount').val(data.advance_deduction);  
        $('.checkadv').html(data.advance_deduction);
    }
    else{
        $('.balance_amount').val(data.balance_amount);  
        $('.invoice_amount').val(data.invoice_grand_total); 
    }
        });
});
	
var decimal = "<?php echo \Session('decimal'); ?>";
 $('.account_code_id,.payment_type_id,.bank_id').attr('required',true);  
 $('.req').show();

/* bal */
var originalSupplierBalance = 0;
    $(document).ready(function(){
    var supplier_id = $('.supplier_id').val();

if (supplier_id) {
    $.get("{{ url('getsuppliercurrentbalance') }}",
        { supplier_id: supplier_id },
        function (data) {
            originalSupplierBalance = parseFloat(data.balance) || 0;

        $('.supplier_balance').val(
                    originalSupplierBalance.toFixed(decimal)
                );
        console.log(originalSupplierBalance);        
        }
    );
}
}); 

var originalBalance = parseFloat($('.balance_amount').val());
	
$(document).on('click','.addedpayamt',function(){
       var tot = 0;
       var index = $(this).closest('tr').index();
       var payment_amount=parseInt($(".payment_amount").val()); 
     var advance_amt=parseInt($(".advance_amount").val()); 
       var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
       var balamount=  $('.balance_amount').val();
      $(".paymentamt").each(function(){
          tot += parseFloat($(this).val());
      });

          <?php if($statement =="0") { ?>

         $('.payment_amount').val(tot.toFixed(decimal));
         var payment_amt=parseInt($(".payment_amount").val());
         var supplier_balance = parseFloat($('.supplier_balance').val());
         console.log(originalSupplierBalance);
            var new_supplier_balance = originalSupplierBalance - tot;
            console.log(new_supplier_balance);
           var balamt= originalBalance - tot;

         if(balamt==0){
         $('.account_code_id').attr('required',false);  
         $('.payment_type_id,.bank_id').attr('required',false);  
         $('.req').hide();
         }
            $('.balance_amount').val(balamt.toFixed(decimal));
            $('.supplier_balance').val(new_supplier_balance.toFixed(decimal));
        <?php } else { ?>
             
    if(tot > payment_amount || tot < payment_amount ){
                 showCustomAlert('Payment amount is not Equal to Statement amount','info');
                  $('.paymentamt').val('');
              } 
     
             
        <?php } ?>
     
  });
	
 
  /* purpose:add credit,debit amount*/ 
	
	$(document).on('click','.addcreditamt',function(){
       var tot = 0;
       var cn = 0;
       var index = $(this).closest('tr').index();console.log(index);
      $(".creditamt").each(function(i,v){
  	  tot += parseFloat($(this).val());
	    });
	 console.log(tot);
         var balance_amount= parseFloat($('.balance_amount').val());
	  
        var adbalamt=parseFloat(balance_amount)+parseFloat(tot);
		
        $('.balance_amount').val(adbalamt);
        $('.credit_amount').val(tot);
     

  }); 
	
	$(document).on('click','.adddebitamt',function(){
       var tot = 0;
		 var cn = 0;
       var index = $(this).closest('tr').index();
      $(".debitamt").each(function(i,v){
        tot += parseFloat($(this).val());
	   });
	 console.log(tot);
         var balance_amount=  parseFloat($('.balance_amount').val());
	
        var adbalamt=parseFloat(balance_amount)-parseFloat(tot);
	
        $('.balance_amount').val(adbalamt);
        $('.debit_amount').val(tot);
     

  }); 
	
/*end*/  
  /* purpose:add advance amount*/  
 $(document).on('click','.addadvancepayamt',function(){
       var tot = 0;
       var index = $(this).closest('tr').index();
      $(".popaymentamt").each(function(){
          tot += parseFloat($(this).val());
      });
         var balance_amount=  $('.balance_amount').val();
        var adbalamt=balance_amount-tot;
        $('.balance_amount').val(adbalamt);
        $('.advance_amount').val(tot.toFixed(decimal));

  });   
/*end*/
    $(document).on('keyup','.paymentamt',function(){
              var index = $(this).closest('tr').index();
             var payment=parseInt($(this).val());  
              var balance_amount=parseInt($('.balance_amount'+index).attr('id'));
               var payment_amount=$(".payment_amount").val(); 

               

            if(balance_amount > 0){
			 if(payment > balance_amount)
						  {
							 showCustomAlert('Payment amount should not more than balance amount','info');
							  $(this).val('');
						  }
			} else{
			  if(payment < balance_amount)
              {
                 showCustomAlert('Payment amount is not more than balance amount','info');
                  $(this).val('');
              }
}    

         
    });

 /* purpose:validation for pobalance amount   */
  $(document).on('keyup','.popaymentamt',function(){
              var index = $(this).closest('tr').index();
             var payment=parseInt($(this).val());  
              var balance_amount=parseInt($('.pobalance_amount'+index).attr('id'));
             
			if(balance_amount > 0){
			 if(payment > balance_amount)
						  {
							 showCustomAlert('Payment amount should not more than balance amount','info');
							  $(this).val('');
						  }
			} else{
			  if(payment < balance_amount)
						  {
							 showCustomAlert('Payment amount is not more than balance amount','info');
							  $(this).val('');
						  }
			}    
				});
 
	  /* purpose:qty validation*/
		$(document).on('keypress','.popaymentamt,.paymentamt', function(ev){
		  var regex = new RegExp("^[0-9.]+$");
			  var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
			  if (regex.test(str)) {
				return true;
			  }
			  ev.preventDefault();
			  return false;
		});
  /*end*/
  
     $(document).on('keyup','.paymentamt',function(){
     
              var payment=parseInt($(this).val());  
        var payment_amt=$(".payment_amount").val(); 
              if(payment > payment_amount)
              {
                 showCustomAlert('Payment amount is not more than Statement amount','info');
                  $(this).val('');
              }      
    });

	  $(document).on('keyup','.payment_amount',function(){
		  var invoice_amt=parseInt($(".invoice_amount").val());
		  var payment_amt=parseInt($(".payment_amount").val()); 
		  var advance_amt=parseInt($(".advance_amount").val()); 
		var paid_amt=parseInt($(".paid_amount").val());
        
		if(payment_amt > invoice_amt){
		  showCustomAlert("Payment Amount Exceeds","error");
		  $(".payment_amount").val("");
		}
		else{
		  var num1 = isNaN(parseInt(payment_amt)) ? 0 : parseInt(payment_amt);
		  $(".payment_amount").val(num1.toFixed(decimal));
			$(".paid_amount").val(paid_amt);
			  $(".balance_amount").val(balance_amt);
              
		}

	  });



          /*Validation*/
	  $(document).on('keypress','.payment_amount', function(ev){
		  var regex = new RegExp("^[0-9.]+$");
			  var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
			  if (regex.test(str)) {
				return true;
			  }
			  ev.preventDefault();
			  return false;
		});

$('#bank_id').on('change', function () {
    var bank = $(this).val();

    if (bank !== '') {
        var url = "{{ URL::to('jcomboform') }}" +
                  "?table=f_bank_account_lines_t:bank_account_line_id:account_number" +
                  "&order_by=account_number asc" +
                  "&parent=bank_account_hdr_id=" + bank;

        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                // Parse JSON if returned as string
                if (typeof data === "string") {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error("Invalid JSON response:", data);
                        return;
                    }
                }

                var $account = $(".account_no");
                $account.empty().append('<option value="">-- Select Account --</option>');

                $.each(data, function (i, item) {
                    $account.append(`<option value="${item.val}">${item.option_name}</option>`);
                });

                $account.trigger('change.select2'); // refresh Select2 if used
            },

            error: function () {
                console.error("Failed to load account numbers");
            }
        });
    } else {
        $(".account_no").empty().append('<option value="">-- Select Account --</option>');
    }
});



    $(".imprestemp_div").hide();
	
$('.payment_type_id').on('change',function(){
    var pmttypeid=$('.payment_type_id option:selected').text();
    var pmttype=$.trim(pmttypeid);
    if(pmttype!="CASH" && pmttype!="IMPREST"){
        $('.chequediv').css('display','block');
    }
  else{
    if(pmttype=="IMPREST"){
      $(".imprestemp_div").show();
     var url="{{URL::to('getimprestaccount')}}";
    $.get(url,function(data){
    $('.account_code_id').val(data[0].imprest_account_id).change();  
        });
    }else{
     var url="{{URL::to('getcashaccount')}}";
     $.get(url,function(data){
        $('.account_code_id').val(data[0].cash_account_id).change();  
        });     
    }
        $('#bank_id').prop('required',false);
        $('.chequediv,.bankdiv').css('display','none');
    
  }

 if(pmttype=="CHEQUE"){

          $('.chequelabel').html('Cheque No'); 
           $('.cheque_no').attr('readonly',false);
              $('.cheque_date').show();      
              }else{
                  $('.chequelabel').html('Reference No');
                  $('.cheque_no').attr('readonly',false);
                    $('.cheque_date').hide();      
              }
          if(pmttype=="CHEQUE" || pmttype=="CASH"|| pmttype=="IMPREST" || pmttype=="ONLINE")
  {
    $(".supplier_bank_id").attr('required',false);
    var form = $('#paymentinv_form');
                form.parsley().destroy();
  }
  else
  {
    $(".supplier_bank_id").attr('required',true);
    var form = $('#paymentinv_form');
                form.parsley().destroy();
  }    


}); 
	
/* Purpose for Bank based Account Code load*/
$(document).on('change','.account_no',function(){
    var account_no=$('.bank_id').val();
    var url="{{URL::to('getaccountdetails')}}/"+account_no;
    $.get(url,function(data){
    $('.account_code_id').val(data[0].account_code_id).change();  
    
        });
});



 /* Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
          var btnval = $(this).val();
      var invoice_amount=$('.invoice_amount').val();
      var payment_amount=$('.payment_amount').val();
            if(invoice_amount==payment_amount){
        $(".payment_status").val("PAID");
      }
      else if(invoice_amount != payment_amount ){
        $(".payment_status").val('PARTIALLY PAID');
      }
      else{
        $(".payment_status").val('UNPAID');
      }
            $('#savestatus').val(btnval);
            
    var url = "{{ url('paymentforinvoicesave') }}";
    var red_url = "{{url('paymentforinvoice')}}"
    validationrule('paymentinv_form');
    var formdata = $('#paymentinv_form').serialize();
    var form = $('#paymentinv_form');
            form.parsley().validate();
            var form = $('#paymentinv_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
           var formdata = $('#paymentinv_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg =  data.message;
            var id = data.id;
            var edit_url = "{{ url('paymentforinvoicecreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            else
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            });
            }
    });
    });


    $(document).on("focus", ".cheque_date", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: -90, 
            maxDate: +30,
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });


    
     // cheque number validation
    
        $(document).ready(function(){
        $('#cheque_no').on('input', function() {
            var chequeNo = $(this).val();
            if (chequeNo.length > 6) {
               showCustomAlert('Please Enter valid cheque number','info');
                $(this).val(chequeNo.slice(0, 6));
            }
        });

        $('#cheque_no').on('blur', function() {
            var chequeNo = $(this).val();
            if (chequeNo.length < 6 && chequeNo.length > 0) {
                showCustomAlert('Please Enter valid cheque number','error');
                $(this).focus(); 
            }
        });
    });
	
</script>

@endpush
