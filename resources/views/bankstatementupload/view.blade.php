@extends('layouts.header')
@section('content')
<style>
.modal-header {
    position: relative;
}

.modal-header .close {
    position: absolute;
    right: 15px;
    top: 15px;
    font-size: 24px;
    opacity: 1;
    color: #fff;
}

.modal-header .close:hover {
    color: #ffdddd;
    opacity: 1;
}    
.modal-xl {
    width: 90%;
    max-width: 90%;
}

.modal-body {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

table.dataTable {
    width: 100% !important;
}

.dataTables_scrollHeadInner,
.dataTables_scrollHeadInner table {
    width: 100% !important;
}

.dataTables_scrollBody {
    overflow-x: auto !important;
}
#paymentdetails .modal-body {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

#getpaymentdetailsData {
    width: 100% !important;
}
</style>
<h3 class="text-danger">Bank Statement Details</h3>
@include('layouts.breadcrumb')
<!-- karthigaa purpose Po Invoice Pending jqgrid model-->
    <div class="modal fade" id="receiptmodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Receipt Details</h4>
                <button type="button"
                class="close text-white modal-close-btn"
                data-dismiss="modal"
                aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <!-- Receipt Summary -->
                <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead>
                    <tr style="background:#2d6a9e;color:#fff;font-size:12px;">
                        <th>Amount</th>
                        <th>Cheque No</th>
                        <th>Date</th>
                        <th>Narration</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="text" class="form-control amttrpt" id="amttrpt" readonly></td>
                        <td><input type="text" class="form-control chq_notrpt" id="chq_notrpt" readonly></td>
                        <td><input type="text" class="form-control stmttdate" id="stmttdate" readonly></td>
                        <td><input type="text" class="form-control narrtrpt" id="narrtrpt" readonly></td>
                    </tr>
                    </tbody>
                </table>
                </div>

                <!-- jqGrid Table -->
                <div class="table-responsive">
                <table id="receiptjqgrid"
                        class="table table-bordered table-striped"
                        style="width:100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Receipt Number</th>
                        <th>Receipt Date</th>
                        <th>Receipt Type</th>
                        <th>Customer Name</th>
                        <th>Invoice Number</th>
                        <th>Cheque No</th>
                        <th>Receipt Amount</th>
                        <th>Bank Name</th>
                    </tr>
                    </thead>
                </table>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button"
                class="btn btn-secondary modal-close-btn"
                data-dismiss="modal">
          Close
        </button>
            </div>

            </div>
        </div>
    </div>

<!--end-->

<div class="modal fade" id="moveConfirmModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">Confirm Move</h5>
      </div>

      <div class="modal-body text-center">
        <p>Are you sure you want to move this record?</p>
        <input type="hidden" id="move_stmt_id">
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button class="btn btn-primary" id="confirmMoveBtn">Yes</button>
      </div>

    </div>
  </div>
</div>



        <div class="modal fade" id="paymentdetails" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Payment Details</h4>
        <button type="button" class="close modal-close-btn" data-dismiss="modal">
          &times;
        </button>
      </div>

      <!-- Modal Body (SINGLE) -->
      <div class="modal-body">

        <!-- Summary Section -->
        <div class="table-responsive mb-3">
          <table class="table table-bordered mb-0">
            <thead style="background:#2d6a9e;color:#fff;font-size:12px;">
              <tr>
                <th>Amount</th>
                <th>Cheque No</th>
                <th>Date</th>
                <th>Narration</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input class="form-control amttrpt" readonly></td>
                <td><input class="form-control chq_notrpt" readonly></td>
                <td><input class="form-control stmttdate" readonly></td>
                <td><input class="form-control narrtrpt" readonly></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- DataTable Section -->
        <div class="table-responsive">
          <table id="getpaymentdetailsData"
                 class="table table-bordered table-striped nowrap w-100">
            <thead>
              <tr>
                <th></th>
                <th>Payment Number</th>
                <th>Payment Date</th>
                <th>Payment Type</th>
                <th>Supplier Name</th>
                <th>Customer Name</th>
                <th>Employee Name</th>
                <th>Invoice Number</th>
                <th>Bill Ref Number</th>
                <th>Cheque No</th>
                <th>Payment Amount</th>
                <th>Bank Name</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modal-close-btn" data-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>

        <input type="hidden"  class="stmtid" value="">
          <input type="hidden"  class="stmtval" value=""> 
      <input type="hidden"  class="stmtdate" value="">
        <!--end-->    
<div class="modal fade" id="savedetailsModal">
  <div class="modal-dialog" style="width:40%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title">BRS Confirmation? </h4>
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <input type="hidden" class="soindex" value="">
      </div>
        <!-- Modal Body -->
      <div class="modal-body ">
          <h4><b>Either Amount Or Date does not match.</b><br><br>Do You Want to Continue?</h4>
      </div>
        
        <div style="text-align:center;"> <button type="button" class="btn add savedata" id="savedata"> Yes</button><button type="button" class="btn cancel" id="canceldata"> No</button></div>
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="savereceiptdetailsModal">
  <div class="modal-dialog" style="width:40%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">
          <h4 class="modal-title">BRS Confirmation? </h4>
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <input type="hidden" class="soindex" value="">
      </div>
        <!-- Modal Body -->
      <div class="modal-body ">
          <h4><b>Either Amount Or Date does not match.</b><br><br>Do You Want to Continue?</h4>
      </div>
        
        <div style="text-align:center;"> <button type="button" class="btn add receiptsavedata" id="receiptsavedata"> Yes</button><button type="button" class="btn cancel" id="receiptcanceldata"> No</button></div>
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--end--> 

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-bank2 fs-3 me-3"></i> Bank Statement Search
    </div>

    <div class="card-body p-4">
        <form method="post" action="" id="job_card_reprot" autocomplete="off">
            @csrf

            <div class="row g-4 mb-3">

                <div class="col-md-6">
                    <label class="form-label required">Bank Name</label>
                    <select name="bank_name" class="form-select select2 bank_name" required>
                        {!! $bank_name !!}
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label required">Account Number</label>
                    <select name="account_no" class="form-select select2 account_no" required>
                        {!! $account_no !!}
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">From Date</label>
                    <input type="text" class="form-control start_date" autocomplete="off">
                </div>

                <div class="col-md-6">
                    <label class="form-label">To Date</label>
                    <input type="text" class="form-control end_date" autocomplete="off" disabled>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-primary px-4 searchdata">
                        <i class="bi bi-search-heart me-1"></i> Search
                    </button>
                </div>
            </div>

            <div class="col-md-12 mt-4 invoice-box" style="display:none;">
                <table class="table table-bordered table-hover stmtdetails">
                    <thead>
                        <tr class="table-dark">
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Value Date</th>
                            <th>Chq No</th>
                            <th>Narration</th>
                            <th>Cod</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
var lastSearchParams = {
    bank_name: '',
    account_no: '',
    from_date: '',
    to_date: ''
};
$(function () {

    /* ================= BANK → ACCOUNT ================= */
    $('.bank_name').change(function () {

        let bank = $(this).val();
        $(".account_no").html('<option value="">-- Select Account No --</option>');

        if (!bank) return;

        let url = "{{ URL::to('jcomboform1') }}" +
                  "?table=f_bank_account_lines_t:bank_account_line_id:account_number" +
                  "&order_by=account_number asc" +
                  "&parent=and bank_account_hdr_id=" + bank;

        $.get(url, function (data) {
            if (typeof data === 'string') data = JSON.parse(data);

            $.each(data, function (_, item) {
                $(".account_no").append(
                    `<option value="${item.val}">${item.option_name}</option>`
                );
            });

            $(".account_no").trigger('change.select2');
        });
    });

    /* ================= SEARCH ================= */
    $('.searchdata').click(function () {

        let bank_name  = $('.bank_name').val();
        let account_no = $('.account_no').val();
        let from_date  = $('.start_date').val();
        let to_date    = $('.end_date').val();

        if (!bank_name && !account_no) {
            notyMsg("info", "Please select bank name or account number");
            return;
        }

        if (!from_date || !to_date) {
            notyMsg("info", "Please select date");
            return;
        }

        // ✅ SAVE LAST SEARCH
    lastSearchParams = {
        bank_name: bank_name,
        account_no: account_no,
        from_date: from_date,
        to_date: to_date
    };

    loadStatementTable();   // ⬅ reusable function

        let url = "{{ URL::to('viewstatement') }}/" +
                  bank_name + "/" + account_no + "/" + from_date + "/" + to_date;

        $.getJSON(url, function (data) {

            if (!data || data.length === 0) {
                notyMsg("info", "No records found");
                return;
            }

    $("#clearsearch").click(function() {
    var grid = $("#getpaymentdetailsData");
    grid.jqGrid('setGridParam',{search:false});

    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
                $('input[id*="gs_"]').val("");
                

  });        

    $("#clearsearch1").click(function() {
    var grid = $("#receiptjqgrid");
    grid.jqGrid('setGridParam',{search:false});
    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
                $('input[id*="gs_"]').val("");
                

  });   

            $('.invoice-box').show();
            let html = '';

            $.each(data, function (i, row) {
                html += `
                <tr>
                    <td class="row_id${i}" id="${row.bankstmt_id}">${i+1}</td>
                    <td>${row.date}</td>
                    <td>${row.value_date}</td>
                    <td>${row.chq_no}</td>
                    <td>${row.narration}</td>
                    <td>${row.cod}</td>
                    <td class="paymentdetailspopup"
                        data-id="${i}"
                        data-value="${row.debit}"
                        data-date="${row.value_date}"
                        data-status="${row.status}"
                        data-chq_no="${row.chq_no}"
                        data-narr="${row.narration}">
                        <a>${row.debit}</a>
                    </td>
                    <td class="receiptinvoicepopup"
                        data-id="${i}"
                        data-value="${row.credit}"
                        data-date="${row.value_date}"
                        data-status="${row.status}"
                        data-chq_no="${row.chq_no}"
                        data-narr="${row.narration}">
                        <a>${row.credit}</a>
                    </td>
                    <td>${row.balance}</td>
                    <td>
                        ${row.status == 0 ? 
                            `<a href="#" class="moveStmt text-primary fw-bold"
                                data-id="${row.bankstmt_id}">
                                MOVE
                            </a>` 
                            : '<span class="text-success">Moved</span>'
                        }
                    </td>
                </tr>`;
            });

            $('.stmtdetails tbody').html(html);
        });
    });
});

function loadStatementTable() {

    let p = lastSearchParams;

    if (!p.bank_name || !p.from_date || !p.to_date) return;

    let url = "{{ URL::to('viewstatement') }}/" +
              p.bank_name + "/" + p.account_no + "/" +
              p.from_date + "/" + p.to_date;

    $.getJSON(url, function (data) {

        if (!data || data.length === 0) {
            $('.stmtdetails tbody').html('');
            return;
        }

        let html = '';

        $.each(data, function (i, row) {

            html += `
            <tr>
                <td class="row_id${i}" id="${row.bankstmt_id}">${i + 1}</td>
                <td>${row.date}</td>
                <td>${row.value_date}</td>
                <td>${row.chq_no}</td>
                <td>${row.narration}</td>
                <td>${row.cod}</td>
                <td class="paymentdetailspopup"
                    data-id="${i}"
                    data-value="${row.debit}"
                    data-date="${row.value_date}"
                    data-status="${row.status}"
                    data-chq_no="${row.chq_no}"
                    data-narr="${row.narration}">
                    <a>${row.debit}</a>
                </td>
                <td class="receiptinvoicepopup"
                    data-id="${i}"
                    data-value="${row.credit}"
                    data-date="${row.value_date}"
                    data-status="${row.status}"
                    data-chq_no="${row.chq_no}"
                    data-narr="${row.narration}">
                    <a>${row.credit}</a>
                </td>
                <td>${row.balance}</td>
                <td>
                    ${row.status == 0 ? 
                        `<a href="#" class="moveStmt text-primary fw-bold"
                            data-id="${row.bankstmt_id}">
                            MOVE
                        </a>` 
                        : '<span class="text-success">Moved</span>'
                    }
                </td>
            </tr>`;
        });

        $('.invoice-box').show();
        $('.stmtdetails tbody').html(html);
    });
}


$(document).on('click change','.receiptinvoicepopup',function(){
    var Row=$(this).attr('data-id');
    var Row_val=$(this).attr('data-value');
    var Row_narr=$(this).attr('data-narr');
    var Row_chq_no=$(this).attr('data-chq_no');
    var Row_date=$(this).attr('data-date');
    var Row_status=$(this).attr('data-status');
    if(Row_status=="1")
    {
        notyMsg('error',"Statement Already Mapped");
    }
    else
    {
        var row_id = $('.row_id'+Row).attr('id');
        $(".stmtid").val(row_id);
        $(".stmtval").val(Row_val);
        $(".amttrpt").val(Row_val);
        $(".chq_notrpt").val(Row_chq_no);
        $(".narrtrpt").val(Row_narr);
        $(".stmttdate").val(Row_date);
        $(".stmtdate").val(Row_date);
        $('#receiptmodal').modal('show');
        $('#receiptmodal').width("100%");
    }
         
});

$(document).ready(function () {

    var supplieropt = "{{$supplieropt}}";
    var date_format = "{{ \Session::get('p_date_format') }}";

    // ---------------------------
    // DataTable Initialization
    // ---------------------------
    var receiptTable = $('#receiptjqgrid').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "getReceiptsData",
            type: "GET",
            dataSrc: function (json) {
                // jqGrid compatibility
                return json.rows || json.data || json;
            }
        },
        scrollX: true,
        scrollY: "50vh",
        paging: true,
        pageLength: 10,
        lengthMenu: [10, 20, 100, 1000],
        order: [],
        searching: true,
        info: true,

        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                width: "40px",
                className: "text-center",
                render: function () {
                    return '<input type="checkbox" class="row-select">';
                }
            },
            { data: "receipt_number" },
            { data: "receipt_date" },
            { data: "receipt_type_id" },
            { data: "customer_name", className: "text-center" },
            { data: "invoice_number", className: "text-center" },
            { data: "cheque_no", className: "text-center" },
            { data: "receipt_amount" },
            { data: "bank_name" }
        ],

        initComplete: function () {
            $('#receiptjqgrid_filter input')
                .attr('placeholder', 'Search...');
        }
    });

    // ---------------------------
    // Select Row on Click
    // ---------------------------
    $('#receiptjqgrid tbody').on('click', 'tr', function (e) {
        if ($(e.target).hasClass('row-select')) {
            $(this).toggleClass('selected', $(e.target).prop('checked'));
        } else {
            var checkbox = $(this).find('.row-select');
            checkbox.prop('checked', !checkbox.prop('checked'));
            $(this).toggleClass('selected', checkbox.prop('checked'));
        }
    });

    // ---------------------------
    // Add "Select Receipt" Button
    // ---------------------------
    $('<button type="button" class="btn btn-primary mb-2" id="selectReceiptBtn">' +
        '<i class="bi bi-plus"></i> Select Receipt</button>')
        .insertBefore('#receiptjqgrid');

    // ---------------------------
    // Button Click Logic (UNCHANGED)
    // ---------------------------
    $(document).on('click', '#selectReceiptBtn', function () {

        var stmtid   = $('.stmtid').val();
        var stmtdate = $('.stmtdate').val();
        var stmtval  = parseFloat($('.stmtval').val());

        var total = 0;
        var receipt_date = '';
        var cellvalues = [];

        receiptTable.rows('.selected').every(function () {
            var row = this.data();
            total += parseFloat(row.receipt_amount);
            receipt_date = row.receipt_date;
            cellvalues.push(row.receipt_id);
        });

        console.log(stmtval);
        console.log(total);
        console.log(stmtdate);
        console.log(receipt_date);

        if (cellvalues.length > 0) {

            if (stmtval == total && stmtdate == receipt_date) {

                $.get(
                    'receiptupdate',
                    {
                        payment: cellvalues,
                        stmtid: stmtid,
                        date: stmtdate
                    },
                    function (data) {
                        data = $.trim(data);
                        if (data == 1) {
                            showCustomAlert("Bank Date Updated Successfully", 'success');
                            receiptTable.ajax.reload();
                            $('#receiptmodal').modal('hide');
                            $('#receiptmodal').one('hidden.bs.modal', function () {

                                loadStatementTable();   // ✅ RELOAD stmtdetails
                            });
                        }
                    }
                );

            } else {
                $('#savereceiptdetailsModal').modal('show');
            }

        } else {
            showCustomAlert("Please select atleast one row", 'error');
        }
    });

});

$(document).on('click', '.modal-close-btn', function () {
    $(this).closest('.modal').modal('hide');
});

$(document).on('click change','.paymentdetailspopup',function(){
   var Row=$(this).attr('data-id');
   var Row_val=$(this).attr('data-value');
   var Row_date=$(this).attr('data-date');
   var Row_narr=$(this).attr('data-narr');
   var Row_chq_no=$(this).attr('data-chq_no');
     var Row_status=$(this).attr('data-status');
    if(Row_status=="1")
    {
        notyMsg('error',"Statement Already Mapped");
    }
    else
    {
   var row_id = $('.row_id'+Row).attr('id');
   $(".stmtid").val(row_id);
   $(".stmtval").val(Row_val);
   $(".stmttdate").val(Row_date);
   $(".stmtdate").val(Row_date);
    $(".amttrpt").val(Row_val);
   $(".chq_notrpt").val(Row_chq_no);
   $(".narrtrpt").val(Row_narr);
   $('#paymentdetails').modal('show');
   $('#paymentdetails').width("100%");
    }
});

$(document).ready(function () {

    var customeropt = "{{$customeropt}}";

    /* ---------------------------------
       DataTable Initialization
    --------------------------------- */
    var paymentTable = $('#getpaymentdetailsData').DataTable({
    processing: true,
    serverSide: true,          
    destroy: true,             // ✅ prevents double init
    lengthChange: true,
    pageLength: 10,
    lengthMenu: [[10, 20, 50, 100, 1000], [10, 20, 50, 100, 1000]],
    scrollX: true,
    ordering: false,

    ajax: {
        url: 'getpaymentdetailsDataforpayment',
        type: 'GET'
    },

    columns: [
        {
            data: null,
            orderable: false,
            searchable: false,
            render: () => '<input type="checkbox" class="row-select">'
        },
        { data: "payment_number" },
        { data: "payment_date" },
        { data: "payment_type_id" },
        { data: "supplier_name" },
        { data: "customer_name" },
        { data: "first_name" },
        { data: "bill_number" },
        { data: "invoice" },
        { data: "cheque_no" },
        { data: "payment_amount" },
        { data: "bank_name" }
    ]
});


    /* ---------------------------------
       Row Select (jqGrid style)
    --------------------------------- */
    $('#getpaymentdetailsData tbody').on('click', 'tr', function (e) {
        if (!$(e.target).is('input')) {
            var checkbox = $(this).find('.row-select');
            checkbox.prop('checked', !checkbox.prop('checked'));
        }
        $(this).toggleClass('selected');
    });

    /* ---------------------------------
       Add Button (jqGrid navButtonAdd)
    --------------------------------- */
    $('<button type="button" class="btn btn-primary mb-2" id="selectPaymentBtn">' +
        '<i class="bi bi-plus"></i> Select PURCHASE PAYMENT</button>')
        .insertBefore('#getpaymentdetailsData');

    /* ---------------------------------
       Button Click Logic (UNCHANGED)
    --------------------------------- */
    $(document).on('click', '#selectPaymentBtn', function () {

        var stmtid   = $('.stmtid').val();
        var stmtdate = $('.stmtdate').val();
        var stmtval  = parseFloat($('.stmtval').val());

        var total = 0;
        var payment_date = '';
        var cellvalues = [];

        paymentTable.rows('.selected').every(function () {
            var row = this.data();
            total += parseFloat(row.payment_amount);
            payment_date = row.payment_date;
            cellvalues.push(row.payment_id);
        });

        console.log(stmtval);
        console.log(total);
        console.log(stmtdate);
        console.log(payment_date);

        if (cellvalues.length > 0) {

            if (stmtval == total && stmtdate == payment_date) {

                $.get(
                    'statementupdate',
                    {
                        payment: cellvalues,
                        stmtid: stmtid,
                        date: stmtdate
                    },
                    function (data) {
                        data = $.trim(data);
                        if (data == 1) {
                            showCustomAlert("Bank Date Updated Successfully", 'success');
                            paymentTable.ajax.reload();
                            $('#paymentdetails').modal('hide');
                            $('#paymentdetails').one('hidden.bs.modal', function () {

                                loadStatementTable();   // ✅ RELOAD stmtdetails
                            });
                        }
                    }
                );

            } else {
                $('#savedetailsModal').modal('show');
            }

        } else {
            showCustomAlert("Please select atleast one row", 'error');
        }
    });

});
  
$(document).on('click','#canceldata',function()
    {
    $('#savedetailsModal').modal('hide');
    $('#paymentdetails').modal('hide');
});  

$(document).on('click', '.savedata', function () {

    var stmtid   = $('.stmtid').val();
    var stmtdate = $('.stmtdate').val();
    var stmtval  = parseFloat($('.stmtval').val());

    var total = 0;
    var payment_date = '';
    var cellvalues = [];

    // 🔁 DataTable instance
    var paymentTable = $('#getpaymentdetailsData').DataTable();

    // 🔁 Loop selected rows (jqGrid → DataTables)
    paymentTable.rows('.selected').every(function () {

        var row = this.data();

        var v = row.payment_id;
        total += parseFloat(row.payment_amount);
        payment_date = row.payment_date;

        if (v !== false && v !== undefined) {
            cellvalues.push(v);
        }
    });

    console.log(stmtval);
    console.log(total);
    console.log(stmtdate);
    console.log(payment_date);

    // 🔁 SAME backend call (unchanged)
    $.get(
        'statementupdate',
        {
            payment: cellvalues,
            stmtid: stmtid,
            date: stmtdate
        },
        function (data) {
            data = $.trim(data);
            if (data == 1) {

                $(".searchdata").trigger('click');
                $("#clearsearch").trigger('click');

                $('#paymentdetails').modal('hide');
                $('#savedetailsModal').modal('hide');

                notyMsg('success', "Bank Date Updated Successfully");
            }
        }
    );
});


$(document).on('click','#receiptcanceldata',function()
    {
    $('#savereceiptdetailsModal').modal('hide');
    $('#receiptmodal').modal('hide');
});  

$(document).on('click', '.receiptsavedata', function () {

    var stmtid   = $('.stmtid').val();
    var stmtdate = $('.stmtdate').val();
    var stmtval  = parseFloat($('.stmtval').val());

    var total = 0;
    var receipt_date = '';
    var cellvalues = [];

    // 🔁 DataTable instance
    var receiptTable = $('#receiptjqgrid').DataTable();

    // 🔁 Loop selected rows (jqGrid selarrrow → DataTables rows('.selected'))
    receiptTable.rows('.selected').every(function () {

        var row = this.data();

        var v = row.receipt_id;
        total += parseFloat(row.receipt_amount);
        receipt_date = row.receipt_date;

        if (v !== false && v !== undefined) {
            cellvalues.push(v);
        }
    });

    console.log(stmtval);
    console.log(total);
    console.log(stmtdate);
    console.log(receipt_date);

    // 🔁 SAME AJAX CALL (unchanged)
    $.get(
        'receiptupdate',
        {
            payment: cellvalues,
            stmtid: stmtid,
            date: stmtdate
        },
        function (data) {
            data = $.trim(data);
            if (data == 1) {
                notyMsg('success', "Bank Date Updated Successfully");
                $(".searchdata").trigger('click');
                $("#clearsearch1").trigger('click');
                $('#receiptmodal').modal('hide');
                $('#savereceiptdetailsModal').modal('hide');
            }
        }
    );
});


$('#paymentdetails').on('shown.bs.modal', function () {
    let table = $('#getpaymentdetailsData').DataTable();

    table.columns.adjust();      // fix width
    table.ajax.reload(null, false); // 🔥 force data load on first open
});

$('#receiptmodal').on('shown.bs.modal', function () {
    let table = $('#receiptjqgrid').DataTable();

    table.columns.adjust();
    table.ajax.reload(null, false); // 🔥 reload data
});


    /* ================= DATE PICKER ================= */
    let dateFmt = "{{ session('j_date_format','yy-mm-dd') }}";

    $('.start_date').datepicker({
        dateFormat: dateFmt,
        changeMonth: true,
        changeYear: true,
        maxDate: 0,
        onSelect: function (date) {
            $('.end_date').prop('disabled', false)
                          .datepicker('option', 'minDate', date);
        }
    });

    $('.end_date').datepicker({
        dateFormat: dateFmt,
        changeMonth: true,
        changeYear: true,
        maxDate: 0
    });

    $(document).on('click', '.moveStmt', function(e){
        e.preventDefault();

        let id = $(this).data('id');
        $('#move_stmt_id').val(id);

        $('#moveConfirmModal').modal('show');
        });

        $('#confirmMoveBtn').click(function(){

        let id = $('#move_stmt_id').val();

        $.ajax({
            url: "{{ url('move-statement') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(res){

                if(res.status){
                    $('#moveConfirmModal').modal('hide');

                    showCustomAlert("Record moved successfully","success");

                    loadStatementTable();   // 🔥 reload grid
                }else{
                    showCustomAlert("Update failed","error");
                }
            }
        });

    });


</script>
@endpush