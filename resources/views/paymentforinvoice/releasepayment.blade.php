@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Release Payment</h3>
  @include('layouts.breadcrumb')

  <?php if ($pageMethod == "releasepaymentfremp") {?>
  <button type='button' class='btn btn-success px-4 released_selected approved mt-2'>Release Selected</button>
  <?php } else { ?>
  <button type='button' class='btn  btn-success px-4 approved mt-2'>Approve</button>
  <?php } ?>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th><input type="checkbox" id="select-all"></th>
            <th>Employee No</th>
            <th>Employee Name</th>
            <th>Status</th>
            <th>Month</th>
            <th>Year</th>
            <th>Date</th>
            <th>Gross Salary</th>
            <th>Net Salary</th>
            <th>Balance Salary</th>
            <th>Department</th>
            <th>Bank Name</th>
          </tr>

          <tr class="table-danger">
            <th></th> <!-- Empty for checkbox -->
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
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>



@endsection
@push('scripts')

  <script>


    $(document).ready(function () {
      var pagemode = "{{$pageMethod}}";
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "employeepayrolforpaygrid?pagemode=" + pagemode,
        columns: [
          {
            data: 'id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },
          { data: 'employee_number', name: 'employee_number' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'approved_status',
            className: 'text-center',
            render: function (data) {
              let color = '';
              if (data === 'Hold') color = 'red';
              else color = 'green';
              return `<span style="color:${color}; font-weight:bold;">${data}</span>`;
            }
          },
          { data: 'month', name: 'month' },
          { data: 'year', name: 'year' },
          { data: 'date', name: 'date' },
          { data: 'gross_salary', name: 'gross_salary' },
          { data: 'net_salary', name: 'net_salary' },
          { data: 'balance', name: 'balance' },
          { data: 'department', name: 'department' },
          { data: 'bank_name', name: 'bank_name' }

        ],


        initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        }
      });




      $('#select-all').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
      });


      $('#AccTbl tbody').on('change', '.row-checkbox', function () {
        if (!this.checked) {
          var el = $('#select-all').get(0);
          if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
          }
        }
      });
    });


    // ✅ Release Selected
    $(document).on('click', '.released_selected', function () {
      var selectedIds = [];
      $('.row-checkbox:checked').each(function () {
        selectedIds.push($(this).val());
      });

      if (selectedIds.length !== 0) {
        var url = "{{URL('releasedpayment')}}/?row_id=" + selectedIds.join(',');
        $.get(url, function (data) {
          if (data == 1) {
            showCustomAlert('Payment Released Successfully', 'success');
            window.location.reload();
          }
        });
      } else {
        showCustomAlert('Please select a row', 'info');
      }
    });

    // ✅ Approve Selected
    $(document).on('click', '.approved', function () {
      var selectedIds = [];
      $('.row-checkbox:checked').each(function () {
        selectedIds.push($(this).val());
      });

      if (selectedIds.length !== 0) {
        var url = "{{URL('approvepaymentfremp')}}/?row_id=" + selectedIds.join(',');
        $.get(url, function (data) {
          if (data == 1) {
            showCustomAlert('Payment Approved Successfully', 'success');
            window.location.reload();
          }
        });
      } else {
        showCustomAlert('Please select a row', 'info');
      }
    });


  </script>

@endpush