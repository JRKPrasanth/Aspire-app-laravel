@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Distributor Mapping</h3>

  <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
    <div class="card-body card-block">
      <form action="" id="beatmapping_form">
        <input type="hidden" name="distributormapping_id" value="{{ $row->distributormapping_id }}"
          id="distributormapping_id" />
        <input type="hidden" name="beatmappinglines_id" value="" id="beatmappinglines_id" />
        {{ csrf_field()}}

        <div class="row">

          <div class="col-md-6">
            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Employee
                Name</label>
              <div class="col-md-7">
                <select name='employee_id' id="employee_id" rows='5' class='employee_id select2' required="">
                  {!! $employee_id!!}
                </select>
              </div>
              <span class="btn btn-danger dup_name" style="display:none;"></span>
            </div>
          </div>

          <div class="col-md-6">

            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-5">Description</label>
              <div class="col-md-7">
                <input type="text" id="description" name="description" class="form-control description"
                  value="{{ $row->description }}">
              </div>
            </div>
          </div>
        </div>

        <!-------------------------Linedata -------------------------------->
        <div class="row mt-4">
          <div class="col-12 linetable">
            <div class="table-responsive">
              <table class="table table-bordered company_table">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th>State Name</th>
                    <th>Town Name</th>
                    <th>Distributor Name</th>
                    <th></th>
                    <th>Description</th>
                    <th>Active</th>
                    <th></th>

                  </tr>
                </thead>
                <tbody class="company_lines_body">
                  @if(count($linedata) > 0)
                    @foreach($linedata as $key => $value)
                      <tr class="line-row" data-index="{{ $key }}">

                        <td>
                          <input type="hidden" name="bulk_distributormappinglines_id[]"
                            value="{{ $value->distributormappinglines_id }}">
                          <input type="text" name="bulk_line_no[]" class="form-control bulk_line_no" readonly
                            value="{{ $value->line_no }}">
                        </td>

                        <td>
                          <select name="bulk_state_id[]" class="select2 bulk_state_id">
                            {!! $value->state_id !!}
                          </select>
                        </td>

                        <td>
                          <select name="bulk_town_id[]" class="select2 bulk_town_id">
                            {!! $value->town_id !!}
                          </select>
                        </td>

                        <td>
                          <!-- DYNAMIC INDEX CLASS -->
                          <input type="hidden" name="bulk_disti_id[]" class="bulk_disti_id bulk_disti_id{{ $key }}"
                            value="{{ $value->disti_id }}">
                          <input type="text" class="form-control distributor distributor{{ $key }}"
                            value="{{ $value->distributor }}" readonly required>
                        </td>

                        <td>
                          <i class="fa fa-search distributorsearch"></i>
                        </td>

                        <td>
                          <input type="text" name="bulk_description[]" class="form-control bulk_description"
                            value="{{ $value->description }}" required>
                        </td>

                        <td>
                          <select name="bulk_active[]" class="select2 bulk_active" readonly>
                            <option value="Yes" {{ $value->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $value->active == 'No' ? 'selected' : '' }}>No</option>
                          </select>
                        </td>

                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-minus-circle"></i>
                          </button>
                        </td>

                      </tr>
                    @endforeach

                  @else
                    <!-- FIRST EMPTY ROW WITH INDEX 0 -->
                    <tr class="line-row" data-index="0">

                      <td>
                        <input type="hidden" name="bulk_distributormappinglines_id[]" value="">
                        <input type="text" name="bulk_line_no[]" class="form-control bulk_line_no" readonly>
                      </td>

                      <td>
                        <select name="bulk_state_id[]" class="select2 bulk_state_id">{!! $state_id !!}</select>
                      </td>

                      <td>
                        <select name="bulk_town_id[]" class="select2 bulk_town_id">{!! $city_id !!}</select>
                      </td>

                      <td>
                        <!-- FOR FIRST ROW ADD INDEX 0 -->
                        <input type="hidden" name="bulk_disti_id[]" class="bulk_disti_id bulk_disti_id0">
                        <input type="text" class="form-control distributor distributor0" readonly required>
                      </td>

                      <td>
                        <i class="fa fa-search distributorsearch"></i>
                      </td>

                      <td>
                        <input type="text" name="bulk_description[]" class="form-control bulk_description" required>
                      </td>

                      <td>
                        <select name="bulk_active[]" class="select2 bulk_active">
                          <option value="Yes">Yes</option>
                          <option value="No">No</option>
                        </select>
                      </td>

                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                          <i class="fas fa-minus-circle"></i>
                        </button>
                      </td>

                    </tr>
                  @endif
                </tbody>

              </table>

              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row">
                  <i class="fas fa-plus-circle"></i> Add Row
                </button>
              </div>
            </div>

          </div>
        </div>
        <!-- END -->

        <div class="text-center mt-4">
          <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
          <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4 me-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <input type="hidden" class="so" value='0'>
  <!-- Distributor Modal -->
  <div class="modal fade" id="distriModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Select Distributor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <input type="hidden" class="pdtindex" value="" />
        <input type="hidden" id="selected_row_index">

        <div class="modal-body">
          <table class="table table-bordered table-striped" id="distriTable" style="width:100%">
            <thead class="table-light">
              <tr>
                <th>Distributor Code</th>
                <th>Distributor Name</th>
                <th>Select</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </div>
  </div>



@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      $(document).on('change', '.employee_id', function () {

        var employeeid = $(this).val();
        var url = "{{URL::to('sodistributordetails')}}/" + employeeid;

        $.get(url, function (data) {

          if (data != 0) {

            $(".so").val('1');

            $.each(data, function (i, val) {

              // Add row except for first record
              if (i != 0) {
                $(".add-row").trigger("click");
              }

              var $row = $(".company_lines_body tr").eq(i);

              // STATE
              $row.find(".bulk_state_id").val(val.state_id).trigger("change");

              var stateCond = "&parent=" + encodeURIComponent("state_id=" + val.state_id);
              $row.find(".bulk_state_id").jCombo(
                "{{ URL::to('jcomboformlogin?table=m_states_t:state_id:state_name') }}" +
                "&order_by=state_id asc" + stateCond,
                { selected_value: val.state_id }
              );

              // TOWN
              $row.find(".bulk_town_id").val(val.town_id);

              var townCond = encodeURIComponent("city_id=" + val.town_id + " and state_id=" + val.state_id);
              $row.find(".bulk_town_id").jCombo(
                "{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}" +
                "&parent=" + townCond + "&order_by=city_name asc",
                { selected_value: val.town_id }
              );

              // DISTI
              var awdCond = encodeURIComponent("awd_id=" + val.disti_id);
              $row.find(".bulk_disti_id").jCombo(
                "{{ URL::to('jcomboform?table=awd_tbl:awd_id:bp_code|bp_name') }}" +
                "&order_by=awd_id asc&parent=" + awdCond,
                { selected_value: val.disti_id }
              );

              // Distributor name
              $.get("{{URL::to('getdistributorname')}}/" + val.disti_id, function (d) {
                if (d.length > 0) {
                  var name = d[0].bp_code + "-" + d[0].bp_name;
                  $row.find(".distributor").val(name);
                }
              });

            });

          }

        });

      });

      $(document).on('change', '.bulk_state_id', function () {

        var so = $('.so').val();
        if (so != '0') return;

        var $row = $(this).closest('tr');
        var state_id = $(this).val();
        var $town = $row.find('.bulk_town_id');

        if (state_id == "") {
          $town.html('<option value="">-- Select Town --</option>');
          return;
        }

        var condition = encodeURIComponent("state_id=" + state_id);

        var url = "{{ URL::to('jcomboformlogin') }}" +
          "?table=m_cities_t:city_id:city_name" +
          "&parent=" + condition +
          "&order_by=city_name";

        $.ajax({
          url: url,
          type: "GET",
          success: function (response) {

            let data = typeof response === "string" ? JSON.parse(response) : response;

            $town.empty().append('<option value="">-- Select Town --</option>');

            $.each(data, function (i, item) {
              $town.append(`<option value="${item.val}">${item.option_name}</option>`);
            });

            $town.trigger('change.select2');
          }
        });

      });

      $(document).on('change', '.bulk_town_id', function () {

        var so = $('.so').val();
        if (so != '0') return;

        var $row = $(this).closest("tr");
        var bulk_town_id = $(this).val();
        var state = $row.find(".bulk_state_id").val();

        // Reset distributor fields if town empty
        if (bulk_town_id === "") {
          $row.find(".bulk_disti_id").val("");
          $row.find(".distributor").val("");
          return;
        }

        // Store values for Distributor Search Modal reloading
        $('#distriModal').data('town', bulk_town_id);
        $('#distriModal').data('state', state);

        // Reload DataTable dynamically
        if (typeof distriTable !== 'undefined') {
          distriTable.ajax.reload();
        }
      });





      $(document).on('click', '.distributorsearch', function () {

        var index = $(this).closest('tr').index();
        var town = $('.bulk_town_id' + index).val();
        var state = $('.bulk_state_id' + index).val();

        if (town === "") {
          showCustomAlert("Please select town", "info");
          return;
        }

        $(".pdtindex").val(index);
        $("#selected_row_index").val(index);
        // pass town & state to DataTable Ajax
        $('#distriModal').data('town', town);
        $('#distriModal').data('state', state);

        distriTable.ajax.reload();   // reload table with filters
        $('#distriModal').modal('show');
      });


      var distriTable = $('#distriTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
        ajax: {
          url: "{{ URL::to('getdistrigridData') }}",
          data: function (d) {
            d.town = $('.modal').data('town');
            d.state = $('.modal').data('state');
          }
        },

        columns: [
          { data: 'customer_number', name: 'customer_number' },
          { data: 'customer_name', name: 'customer_name' },

          {
            data: 'customer_id',
            orderable: false,
            searchable: false,
            render: function (id) {
              return `
                        <button type="button"
                            class="btn btn-success btn-sm select-distributor"
                            data-id="${id}">
                            Select
                        </button>`;
            }
          }
        ]
      });

      $(document).on('click', '.select-distributor', function () {

        var distributor_id = $(this).data('id');
        var index = $("#selected_row_index").val();

        $('.bulk_disti_id' + index).val(distributor_id);

        $.get("{{ URL::to('getdistributorname') }}/" + distributor_id, function (data) {
          $('.distributor' + index).val(data[0].customer_number + "-" + data[0].customer_name);
        });

        $('#distriModal').modal('hide');
      });




    });




    // Add Row
    $(document).on('click', '.add-row', function () {

      const $lastRow = $('.company_lines_body tr:last');
      const $newRow = $lastRow.clone(false, false);

      // Clear values
      $newRow.find('input').val('');
      $newRow.find('select').val('').trigger('change');

      // Remove select2 wrapper
      $newRow.find('select.select2').each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
          $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id');
        $(this).next('.select2').remove();
      });

      // Append new row
      $('.company_lines_body').append($newRow);

      // Reinitialize select2
      $newRow.find('select.select2').select2({ width: '100%' });

      // Assign unique index
      let index = $('.company_lines_body tr').length - 1;
      $newRow.attr("data-index", index);

      $newRow.find(".bulk_disti_id")
        .removeClass(function (i, cls) { return (cls.match(/bulk_disti_id\d+/g) || []).join(' ') })
        .addClass("bulk_disti_id" + index);

      $newRow.find(".distributor")
        .removeClass(function (i, cls) { return (cls.match(/distributor\d+/g) || []).join(' ') })
        .addClass("distributor" + index);

      updateLineNumbers();
    });




    // Remove button
    $(document).on('click', '.remove-row', function () {
      const rowCount = $('.company_lines_body tr').length;
      if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
      } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
      }
    });

    // Renumber Line Nos
    function updateLineNumbers() {
      $('.company_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
      });
    }

    // Save Form

    $(document).on('click', '.saveform', function () {
      const form = $("#beatmapping_form");
      let dup_chk = true; // Declare duplicate check variable

      form.parsley().validate(); // Validate form using Parsley

      if (form.parsley().isValid() && dup_chk === true) {

      var $btn = $(this);            
			$btn.prop('disabled', true);

        const formData = form.serialize(); // Serialize form data

        $.ajax({
          url: "{{ url('distributormappingsave') }}",
          type: "POST",
          data: formData,
          success: function (response) {
            if (response.status === "success") {
              showCustomAlert(response.message || 'Saved successfully!', 'success');
              setTimeout(() => {
                window.location.href = "{{ url('distributorbeatmapping') }}";
              }, 1500);
            } else {
              showCustomAlert(response.message || 'Save failed. Please check your input.', 'error');
            }
          },
          error: function (xhr) {
            let errorMsg = 'Unexpected error occurred.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMsg = xhr.responseJSON.message;
            }
            showCustomAlert(errorMsg, 'error');
          }
        });
      } else {
        showCustomAlert("Please fill out all required fields correctly.", 'warning');
      }
    });


  </script>

@endpush