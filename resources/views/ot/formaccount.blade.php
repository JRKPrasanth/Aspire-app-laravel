@extends('layouts.header')
@section('content')
<h3 class="text-danger">HRMS Account Settings</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body card-block">
        <div class="row">
            <div class="col-md-12">

                <form method="post" action="" id="setting_form" data-parsley-validate enctype="multipart/form-data">
                    <?php $data = \Session::get('data');
                    if (isset($data[$pageMethod]['save'])) { ?>

                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4"><span
                                        class="req">*</span>Department</label>
                                <div class="col-md-8">
                                    <input type="hidden" id="account_id" name="account_id"
                                        class="form-control account_id" value="">
                                    <select name='department_id' id="department_id"
                                        class='form-control department_id select2 employee_change' required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Salary Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='salary_account_id' id="salary_account_id"
                                        class='form-control salary_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Basic Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='basic_account_id' id="basic_account_id"
                                        class='form-control basic_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">HRA Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='hra_account_id' id="hra_account_id"
                                        class='form-control hra_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">DA Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='da_account_id' id="da_account_id"
                                        class='form-control da_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Professional Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='professional_account_id' id="professional_account_id"
                                        class='form-control professional_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">ESI Employee Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='esi_employee_account_id' id="esi_employee_account_id"
                                        class='form-control esi_employee_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">ESI Company Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='esi_company_account_id' id="esi_company_account_id"
                                        class='form-control esi_company_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">PF Employee Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='pf_employee_account_id' id="pf_employee_account_id"
                                        class='form-control pf_employee_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Volunter PF Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='volunter_pf_account_id' id="volunter_pf_account_id"
                                        class='form-control volunter_pf_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">PF Company Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='pf_company_account_id' id="pf_company_account_id"
                                        class='form-control pf_company_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">PF Company1 Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='pf_company1_account_id' id="pf_company1_account_id"
                                        class='form-control pf_company1_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Income Tax Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='income_tax_account_id' id="income_tax_account_id"
                                        class='form-control income_tax_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">OT Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='ot_account_id' id="ot_account_id"
                                        class='form-control ot_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Advance Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='advance_account_id' id="advance_account_id"
                                        class='form-control advance_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Travel Claim Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='travel_claim_account_id' id="travel_claim_account_id"
                                        class='form-control travel_claim_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Annual Allowance Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='annual_allowance_account_id' id="annual_allowance_account_id"
                                        class='form-control annual_allowance_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-4">Gratuity Account
                                    Structure</label>
                                <div class="col-md-8">
                                    <select name='gratuity_account_id' id="gratuity_account_id"
                                        class='form-control gratuity_account_id select2 employee_change'>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-success px-4 saveform" value="SAVE">Save</button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </form>
            </div>

        </div>
    </div>

</div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="AccountsTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Department Name</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
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

    // Save function
    $(document).on('click', '.saveform', function () {
        var form = $("#setting_form");
        form.parsley().validate();

        if (form.parsley().isValid()) {
            $.ajax({
                url: "{{ URL::to('hrmsaccountsettingssave') }}",
                type: "POST",
                data: form.serialize(),
                success: function (data) {
                    showCustomAlert(data.message, 'success');
                    form[0].reset();
                    $('.select2').val('').trigger('change');
                    $('#accountclassTable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    showCustomAlert('Save failed. Try again.', 'error');
                }
            });
        }
    });	
	
	
	
	
	
    // data table funcrion	
    $(document).ready(function () {
      var table = $('#AccountsTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('hrmsaccountsettingsdata') }}",
        columns: [

            { data: 'sub_department_name', name: 'sub_department_name' },

          {
            data: 'account_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.account_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.account_id}">
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

    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('hrmsaccountsettingsdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#AccountsTbl').DataTable().ajax.reload();
            }
            if (data == '2') {
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
	
	
	
$(document).ready(function() {

    // Generic init function using AJAX to fill <select>
    function initDropdown(triggerClass, elementSelector, url, selectedValue = "", placeholder = "-- Select --") {
        function loadData() {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    if (typeof data === "string") {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {
                            console.error("Invalid JSON response:", data);
                            return;
                        }
                    }

                    var $el = $(elementSelector);
                    $el.html('<option value="">' + placeholder + '</option>');

                    $.each(data, function (i, item) {
                        var sel = (item.val == selectedValue) ? ' selected' : '';
                        $el.append('<option value="' + item.val + '"' + sel + '>' + item.option_name + '</option>');
                    });

                    $el.trigger('change.select2');
                },
                error: function(xhr, status, err) {
                    console.error("Dropdown load error:", status, err);
                }
            });
        }

        // load on click (like your original) and also initial load
        $(document).on('click', triggerClass, function () {
            loadData();
        });

        // initial load
        loadData();
    }

    // Department dropdown (special parent condition)
    var condition2 = "parent_class_id=0";
    var deptUrl = "{{ URL::to('jcomboform?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name') }}"
                + "&order_by=department_line_id asc&parent=" + condition2;

  
    initDropdown(".jcr_department_id", "#department_id", deptUrl, @json($row->department_id ?? ''), "-- Select Department --");


    // Common Account Structure URL
    var accountUrl = "{{ URL::to('jcomboform?table=f_account_structure_t:f_account_structure_id:concatenated_segments') }}";

    // Map of triggerClass -> { id: selector, selected: server-side value }
    var accountMap = {
        ".jcr_salary_account_id":      { id: "#salary_account_id",      selected: @json($row->salary_account_id ?? '') },
        ".jcr_basic_account_id":       { id: "#basic_account_id",       selected: @json($row->basic_account_id ?? '') },
        ".jcr_hra_account_id":         { id: "#hra_account_id",         selected: @json($row->hra_account_id ?? '') },
        ".jcr_da_account_id":          { id: "#da_account_id",          selected: @json($row->da_account_id ?? '') },
        ".jcr_professional_account_id":{ id: "#professional_account_id",selected: @json($row->professional_account_id ?? '') },
        ".jcr_esi_employee_account_id":{ id: "#esi_employee_account_id",selected: @json($row->esi_employee_account_id ?? '') },
        ".jcr_esi_company_account_id": { id: "#esi_company_account_id", selected: @json($row->esi_company_account_id ?? '') },
        ".jcr_pf_employee_account_id": { id: "#pf_employee_account_id", selected: @json($row->pf_employee_account_id ?? '') },
        ".jcr_volunter_pf_account_id": { id: "#volunter_pf_account_id", selected: @json($row->volunter_pf_account_id ?? '') },
        ".jcr_pf_company_account_id":  { id: "#pf_company_account_id",  selected: @json($row->pf_company_account_id ?? '') },
        ".jcr_pf_company1_account_id": { id: "#pf_company1_account_id", selected: @json($row->pf_company1_account_id ?? '') },
        ".jcr_income_tax_account_id":  { id: "#income_tax_account_id",  selected: @json($row->income_tax_account_id ?? '') },
        ".jcr_advance_account_id":     { id: "#advance_account_id",     selected: @json($row->advance_account_id ?? '') },
        ".jcr_travel_claim_account_id":{ id: "#travel_claim_account_id",selected: @json($row->travel_claim_account_id ?? '') },
        ".jcr_annual_allowance_account_id": { id: "#annual_allowance_account_id", selected: @json($row->annual_allowance_account_id ?? '') },
        ".jcr_gratuity_account_id":    { id: "#gratuity_account_id",    selected: @json($row->gratuity_account_id ?? '') },
        ".jcr_ot_account_id":          { id: "#ot_account_id",          selected: @json($row->ot_account_id ?? '') }
    };

    // Initialize all mapped account dropdowns
    $.each(accountMap, function (triggerClass, obj) {
        initDropdown(triggerClass, obj.id, accountUrl, obj.selected, "-- Select Account --");
    });

});

	
	
    $("#editdata").click(function()
            {
            var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            var account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'account_id');
            var department_id = jQuery("#grid1").jqGrid ('getCell', gr, 'department_id');
            var salary_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'salary_account_id');
            var basic_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'basic_account_id');
            var hra_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'hra_account_id');
            var da_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'da_account_id');
            var professional_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'professional_account_id');
            var esi_employee_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'esi_employee_account_id');
            var esi_company_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'esi_company_account_id');
            var pf_employee_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'pf_employee_account_id');
            var volunter_pf_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'volunter_pf_account_id');
            var pf_company_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'pf_company_account_id');
            var pf_company1_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'pf_company1_account_id');
            var income_tax_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'income_tax_account_id');
            var ot_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'ot_account_id');
            var advance_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'advance_account_id');
            var travel_claim_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'travel_claim_account_id');
            var annual_allowance_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'annual_allowance_account_id');
            var gratuity_account_id = jQuery("#grid1").jqGrid ('getCell', gr, 'gratuity_account_id');
          
            if(gr)
            {

         $('#account_id').val(account_id);
         $('#department_id').select2('val',[department_id]);
         $('#basic_account_id').select2('val',[basic_account_id]);
         $('#salary_account_id').select2('val',[salary_account_id]);
         $('#hra_account_id').select2('val',[hra_account_id]);
         $('#da_account_id').select2('val',[da_account_id]);
         $('#professional_account_id').select2('val',[professional_account_id]);
         $('#esi_employee_account_id').select2('val',[esi_employee_account_id]);
         $('#esi_company_account_id').select2('val',[esi_company_account_id]);
         $('#pf_employee_account_id').select2('val',[pf_employee_account_id]);
         $('#volunter_pf_account_id').select2('val',[volunter_pf_account_id]);
         $('#pf_company_account_id').select2('val',[pf_company_account_id]);
         $('#pf_company1_account_id').select2('val',[pf_company1_account_id]);
         $('#income_tax_account_id').select2('val',[income_tax_account_id]);
         $('#ot_account_id').select2('val',[ot_account_id]);
         $('#advance_account_id').select2('val',[advance_account_id]);
         $('#travel_claim_account_id').select2('val',[travel_claim_account_id]);
         $('#annual_allowance_account_id').select2('val',[annual_allowance_account_id]);
         $('#gratuity_account_id').select2('val',[gratuity_account_id]);
        
    
     }
            else
            {
            notyMsg("info","Please Select a Row");
            }
            });



</script>


@endpush
