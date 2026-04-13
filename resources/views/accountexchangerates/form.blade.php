@extends('layouts.header')
@section('content')
<h3 class="text-danger">Exchange Rates</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form id="acc_exchange_save" action="" data-parsley-validate>
            <?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>

            {{ csrf_field() }}
            <input type="hidden" name="savestatus" id="savestatus" value="" />
            <input type="hidden" name="edit_id" id="edit_id" value="{{ $row->account_exchangerate_id }}" class="account_exchangerate_id" />

            <div class="row g-4">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> From Currency
                        </label>
                        <select name="from_currency_id" class="form-select from_currency_id select2" required tabindex="1">
                            {!! $from_currency_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">From Date</label>
                        <input type="text" id="from_date" name="from_date" class="form-control start_date" value="" tabindex="2">
                    </div>

                    <div class="mb-3 none">
                        <label class="form-label">Created By</label>
                        <select name="created_by" class="form-select created_by select2" tabindex="3">
                            {!! $created_by !!}
                        </select>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> To Currency
                        </label>
                        <select name="to_currency_id" class="form-select to_currency_id select2" required tabindex="4">
                            {!! $to_currency_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">To Date</label>
                        <input type="text" id="to_date" name="to_date" class="form-control last_date" value="" tabindex="5">
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Conversion Rate</label>
                        <input type="text" id="conversion_rate" name="conversion_rate" class="form-control conversion_rate" value="{{ $row->conversion_rate }}" tabindex="6">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Active</label>
                        <select name="active" class="form-select active select2">
                            <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-success px-4 saveform">Save</button>
                </div>
            </div>

            <?php } ?>
        </form>
    </div>
</div>



<!-- DataTable Card -->
<div class="card shadow-lg rounded-4 border-0 mt-4">
  <div class="container mt-4">
    <table id="AccountsTbl" class="table table-bordered table-striped w-100">
      <thead>
        <tr class="table-warning">
			<th></th>
			<th></th>
          <th>From Currency</th>
          <th>To Currency</th>
          <th>From Date</th>
          <th>To Date</th>
          <th>Conversion Rate</th>
          <th>Active</th>
          <th>Created By</th>
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

  // Init DataTable
  var table = $('#AccountsTbl').DataTable({
    processing: true,
    serverSide: true,
    order: [[4, 'desc']],
    ajax: "{{ URL::to('getAccountexchangeratesData') }}",
    columns: [
	  { data: 'from_currency_id', name: 'from_currency_id', visible:false },
	  { data: 'to_currency_id', name: 'to_currency_id', visible:false },
      { data: 'from_currency_code', name: 'from_currency_code' },
      { data: 'to_currency_code', name: 'to_currency_code' },
      { data: 'from_date', name: 'from_date' },
      { data: 'to_date', name: 'to_date' },
      { data: 'conversion_rate', name: 'conversion_rate' },
      { data: 'active', name: 'active' },
      { data: 'first_name', name: 'tb_users.first_name' },
      {
        data: 'account_exchangerate_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        className: 'text-center',
        width: '140px',
        render: function (data, type, row) {
          let buttons = '';
          if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
            buttons += `
        <button class="btn btn-sm btn-primary edit-btn" 
        data-id="${row.account_exchangerate_id}" 
        data-fcur="${row.from_currency_id}" 
		data-tocur="${row.to_currency_id}" 
		data-fdate="${row.from_date}" 
		data-tdate="${row.to_date}" 
		data-rate="${row.conversion_rate}" 
        data-active="${row.active}">
        <i class="bi bi-pencil"></i>
        </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
            buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.account_exchangerate_id}">
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
	
    

    // Save function
    $(document).on('click', '.saveform', function () {
        var form = $("#acc_exchange_save");
        form.parsley().validate();

        if (form.parsley().isValid()) {
            $.ajax({
                url: "{{ URL::to('accountexchangeratessave') }}",
                type: "POST",
                data: form.serialize(),
                success: function (data) {
                    showCustomAlert(data.message, 'success');
                    window.location.reload();
                },
                error: function (xhr) {
                    showCustomAlert('Save failed. Try again.', 'error');
                }
            });
        }
    });
	
	
    // Edit button
    $(document).on('click', '.edit-btn', function () {
        const btn = $(this);
        $('#edit_id').val(btn.data('id'));
        $('#from_date').val(btn.data('fdate'));
        $('#to_date').val(btn.data('tdate'));
        $('#conversion_rate').val(btn.data('rate'));

        $('select[name="from_currency_id"]').val(btn.data('fcur')).trigger('change');
        $('select[name="to_currency_id"]').val(btn.data('tocur')).trigger('change');
        $('select[name="active"]').val(btn.data('active')).trigger('change');
    });	
	
	
	
	
    // Delete button
    let deleteId = null;
    $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
        $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
        if (deleteId) {
            $.ajax({
                url: "{{ url('accountexchangeratesdelete') }}/" + deleteId,
                type: "GET",
                success: function (response) {
                    $('#globalDeleteModal').modal('hide');
                    showCustomAlert('Deleted successfully!', 'success');
                    $('#AccountsTbl').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    $('#globalDeleteModal').modal('hide');
                    const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                    showCustomAlert(errorMsg, 'error');
                }
            });
        }
    });


      $(document).on("focus", ".last_date", function () {
        $(this).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: "yy-mm-dd",
          minDate: new Date(2024, 3, 1),
          showAnim: "slideDown",
          yearRange: "-25:+0",
        });
      });

	
</script>

@endpush
