@extends('layouts.header')
@section('content')
<h3 class="text-danger">Bank Charges</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <form id="acc_bankcharge_save" method="post" action="" data-parsley-validate>
      {{ csrf_field() }}
      <input type="hidden" name="savestatus" id="savestatus" value="">
      <input type="hidden" name="edit_id" id="edit_id" class="account_bankcharge_id"
             value="{{ $row->account_bankcharge_id }}">

      <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">
              <span class="text-danger">*</span> Bank Name
            </label>
            <select name="bank_id" class="form-select select2 bank_id" required tabindex="1">
              {!! $bank_id !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">
              <span class="text-danger">*</span> From Value
            </label>
            <input type="text" name="from_value" id="from_value"
                   class="form-control from_value" required tabindex="2">
          </div>

          <div class="mb-3 none">
            <label class="form-label">Created By</label>
            <select name="created_by" class="form-select created_by select2">
              {!! $created_by !!}
            </select>
          </div>
        </div>

        <!-- Middle Column -->
        <div class="col-md-4">
          <div class="mb-3 none">
            <label class="form-label">
              <span class="text-danger">*</span> Payment Method
            </label>
            <select name="payment_type_id" class="form-select select2 payment_type_id" required tabindex="4">
              {!! $payment_type_id !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">
              <span class="text-danger">*</span> To Value
            </label>
            <input type="text" name="to_value" id="to_value"
                   class="form-control to_value" required tabindex="5">
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">
              <span class="text-danger">*</span> Bank Charges
            </label>
            <input type="text" name="bank_charges" id="bank_charges"
                   class="form-control bank_charges" 
                   value="{{ $row->bank_charges }}" required tabindex="6">
          </div>

          <div class="mb-3">
            <label class="form-label">Active</label>
            <select name="active" class="form-select select2">
              <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
              <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-4 d-flex justify-content-center gap-2">
        <button type="button" class="btn btn-success px-4 save_form">Save</button>
      </div>
    </form>
  </div>
</div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="AccountsTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Bank Name</th>
            <th>Payment Method</th>
            <th>From Value</th>
            <th>To Value</th>
            <th>Bank Charges</th>
            <th>Active</th>
            <th>Created By</th>
            <th>Created Date</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

@endsection
@push('scripts')

<script>
	
    // data table funcrion	
    $(document).ready(function () {
      var table = $('#AccountsTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getAccountbankchargesData') }}",
        columns: [

          { data: 'bank_name', name: 'bank_name' },
          { data: 'payment_type_id', name: 'payment_type_id' },
          { data: 'from_value', name: 'from_value' },
          { data: 'to_value', name: 'to_value' },
          { data: 'bank_charges', name: 'bank_charges' },
          { data: 'active', name: 'active' },
          { data: 'username', name: 'username' },
          { data: 'created_at', name: 'created_at' },



          {
            data: 'account_bankcharge_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.account_bankcharge_id}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
          <button class="btn btn-sm btn-danger delete-btn" data-id="${row.account_bankcharge_id}">
            <i class="bi bi-trash"></i>
          </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccountsTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	

		$(document).ready(function () {
    
      /*Karthigaa Purpose For Save Function*/
         $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('accountbankchargessave')}}";
                    var form = $('#acc_bankcharge_save');
                    form.parsley().validate();

                    
                    var data	= $('#acc_bankcharge_save').serialize();
		    if (form.parsley().isValid())
		{
                    $.post(url, data, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        showCustomAlert(msg,status);
                        setTimeout(function(){
                            $("#accountbankchargegrid")[0].triggerToolbar();
                            }, 150);
                        
                        $(".reset").trigger('click');
                        $(".clearsearch").trigger('click');
                    });
                }

                });
               
 

    $("#editdata").click(function (){
      var form=$("#acc_bankcharge_save");
   form.parsley().destroy();
        var gr = $("#accountbankchargegrid").jqGrid('getGridParam', 'selrow');
        var id = $("#accountbankchargegrid").jqGrid('getCell', gr, 'account_bankcharge_id');
        var bank_id = $("#accountbankchargegrid").jqGrid('getCell', gr, 'bank_id');
        var payment_type_id = $("#accountbankchargegrid").jqGrid('getCell', gr, 'payment_type_id');
        var from_value = $("#accountbankchargegrid").jqGrid('getCell', gr, 'from_value');
        var to_value = $("#accountbankchargegrid").jqGrid('getCell', gr, 'to_value');
        var bank_charges = $("#accountbankchargegrid").jqGrid('getCell', gr, 'bank_charges');
        var active = $("#accountbankchargegrid").jqGrid('getCell', gr, 'active');
//alert(active);
        if (gr)
        {
            $('.bank_id').val(bank_id).change();
            $('.payment_type_id').val(payment_type_id).change();
             $('#from_value').val(from_value);
             $('#to_value').val(to_value);
            $('#bank_charges').val(bank_charges);
            $('.active').val(active).change();
            $('#edit_id').val(id);
        } else
        {
            notyMsg("info", "Please Select Row");
        }
    });


 // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('accountbankchargesdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#AccountsTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Subinventory Used in SomeWhere.", 'error');
              $('#AccountsTbl').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });

			

});
	
</script>

@endpush
