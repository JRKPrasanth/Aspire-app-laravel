@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Product Mapping</h3>

  <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
    <div class="card-body card-block">
      <form action="" id="beatmapping_form">
        <input type="hidden" name="productmapping_id" value="{{ $row->productmapping_id }}" id="productmapping_id" />
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
                    <th>Product Group</th>
                    <th>Product Category</th>
                    <th>Product Name</th>
                    <th></th>
                    <th>Description</th>
                    <th>Active</th>
                    <th></th>

                  </tr>
                </thead>
                <tbody class="company_lines_body">
                  @if(count($linedata) > 0)
                    @foreach($linedata as $key => $value)
                                  <tr class="line-row">

                                    <td><input type="hidden" name="bulk_productmappinglines_id[]"
                                        class="form-control  bulk_productmappinglines_id" value="{{$value->productmappinglines_id}}">
                                      <input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no" readonly="readonly"
                                        value="{{$value->line_no}}">
                                    </td>
                                    <td> <select id="bulk_prd_group_id" name="bulk_prd_group_id[]" class="select2 bulk_prd_group_id"
                                        value="" required>{!! $value->prd_group_id !!}</select></td>
                                    <td> <select id="bulk_prd_category_id" name="bulk_prd_category_id[]"
                                        class="select2 bulk_prd_category_id" value="" required>{!!  $value->prd_category_id !!}</select>
                                    </td>
                                    <td> <input type="hidden" id="bulk_prd_id" name="bulk_prd_id[]" class=" bulk_prd_id"
                                        value="{{$value->prd_id}}">
                                      <input type="text" class="form-control product" value="{{$value->product}}" required readonly>
                                    </td>
                                    <td>
                                      <i class="fa fa-search productsearch"></i>
                                    </td>
                                    <td> <input type="text" name="bulk_description[]" class="form-control  bulk_description"
                                        value="{{$value->description}}"></td>
                                    <td>
                                      <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active" readonly>
                                        <option value="Yes" <?php    if ($value->active == 'Yes') {
                        echo "selected";
                      }?>>Yes</option>
                                        <option value="No" <?php    if ($value->active == 'No') {
                        echo "selected";
                      }?>>No</option>
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
                    <tr class="line-row">

                      <td><input type="hidden" name="bulk_productmappinglines_id[]"
                          class="form-control  bulk_productmappinglines_id" value="">
                        <input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no" readonly="readonly">
                      </td>
                      <td> <select id="bulk_prd_group_id" name="bulk_prd_group_id[]" class="select2 bulk_prd_group_id"
                          value="" required>{!! $prd_group_id !!}</select></td>
                      <td> <select id="bulk_prd_category_id" name="bulk_prd_category_id[]"
                          class="select2 bulk_prd_category_id" value="" required>{!! $prd_category_id !!}</select></td>
                      <td> <input type="hidden" id="bulk_prd_id" name="bulk_prd_id[]" class=" bulk_prd_id bulk_prd_id0"
                          value="">
                        <input type="text" class="form-control product product0" value="" required readonly>
                      </td>
                      <td>
                        <i class="fa fa-search productsearch"></i>
                      </td>
                      <td> <input type="text" name="bulk_description[]" class="form-control  bulk_description">
                      </td>
                      <td>
                        <select name="bulk_active[]" class="select2 bulk_active" id="bulk_active" readonly>
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
  <!-- Deepika purpose product search jqgrid model-->
  <div class="modal fade" id="distriModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Select Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <input type="hidden" class="pdtindex" value="" />
        <input type="hidden" id="selected_row_index">

        <div class="modal-body">
          <table class="table table-bordered table-striped" id="productTable" width="100%">
            <thead class="table-light">
              <tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Select</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </div>
  </div>

  <!--end-->


@endsection
@push('scripts')

  <script>

    $(document).on('change', '#employee_id', function () {

      let employeeid = $(this).val();
      let url = "{{ URL::to('soproductdetails') }}/" + employeeid;

      $.ajax({
        url: url,
        type: "GET",
        success: function (data) {

          // CLEAR ALL ROWS EXCEPT FIRST
          $(".company_lines_body .line-row").not(":first").remove();

          // If no data → exit
          if (data == 0) return;

          $.each(data, function (i, val) {

            // ADD ROW EXCEPT FIRST
            if (i != 0) $(".add-row").trigger("click");

            // Get last row index
            let row = $(".company_lines_body .line-row").last();
            let index = row.index();

            // Set Product Group
            $(".bulk_prd_group_id", row).val(val.prd_group_id).trigger("change");

            // Load Product Category
            $(".bulk_prd_category_id", row).jCombo(
              "{{ URL::to('jcomboformlogin?table=m_product_category_t:product_category_id:category_name') }}&parent=prd_group_id=" + val.prd_group_id,
              { selected_value: val.prd_category_id }
            );

            // Load Product Name
            $(".bulk_prd_id", row).val(val.prd_id);

            // Fetch Product Name Text
            $.ajax({
              url: "{{ URL::to('getproductname') }}/" + val.prd_id,
              type: "GET",
              success: function (p) {
                let dname = p[0].bp_code + "-" + p[0].bp_name;
                $(".product", row).val(dname);
              }
            });
          });
        }
      });
    });


    $(document).on('change', '.bulk_prd_group_id', function () {
      var so = $('.so').val();
      if (so != '0') return; // keep original behavior

      let $row = $(this).closest("tr");
      let prd_group_id = $(this).val();
      // find the category select inside this same row (no index-suffixed class)
      let $category = $row.find("select.bulk_prd_category_id");

      // helper to reset dropdown
      function resetCategory() {
        $category.empty().append('<option value="">-- Select Category --</option>');
        // if using Select2, tell it to update
        if ($category.hasClass('select2-hidden-accessible')) {
          $category.val('').trigger('change.select2');
        } else {
          $category.val('');
          $category.trigger('change');
        }
      }

      if (!prd_group_id) {
        // no value: reset & return
        resetCategory();
        return;
      }

      let condition = encodeURIComponent("product_group_id=" + prd_group_id);
      let url = "{{ URL::to('jcomboformlogin') }}" +
        "?table=m_product_category_t:product_category_id:category_name" +
        "&parent=" + condition +
        "&order_by=category_name";

      $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
          // normalize response to array of { val, option_name } if JSON else try parse HTML
          let data = response;
          if (typeof response === "string") {
            try {
              data = JSON.parse(response);
            } catch (e) {
              // if server returned HTML <option> list, place it directly
              // but first reset then append returned html
              $category.empty().append('<option value="">-- Select Category --</option>');
              $category.append(response);
              if ($category.hasClass('select2-hidden-accessible')) {
                $category.trigger('change.select2');
              } else {
                $category.trigger('change');
              }
              return;
            }
          }

          // now response is parsed JSON array
          $category.empty().append('<option value="">-- Select Category --</option>');
          $.each(data, function (i, item) {
            // adapt to your API keys (val / option_name)
            $category.append(`<option value="${item.val}">${item.option_name}</option>`);
          });

          if ($category.hasClass('select2-hidden-accessible')) {
            $category.trigger('change.select2');
          } else {
            $category.trigger('change');
          }
        },

        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
        }
      });
    });


    $(document).on('change', '.bulk_prd_category_id', function () {

      let row = $(this).closest("tr");
      let category_id = $(this).val();
      let group_id = row.find(".bulk_prd_group_id").val();

      if (category_id == "") {
        row.find(".bulk_prd_id").empty();
        return;
      }

      let url = "{{ URL::to('getselectproductgridData') }}?prd_category_id=" + category_id + "&prd_group_id=" + group_id;

      // Reload DataTable dynamically
      if (typeof distriTable !== 'undefined') {
        distriTable.ajax.reload();
      }
    });


    $(document).on('click', '.productsearch', function () {

      var index = $(this).closest('tr').index();
      var cat = $('.bulk_prd_category_id' + index).val();

      if (cat !== '') {
        $('.pdtindex').val(index);
        $("#selected_row_index").val(index);
        $('#distriModal').modal('show');
      } else {
        showCustomAlert("Please select category", "info");
      }
    });


    var productTable = $('#productTable').DataTable({
      processing: true,
      serverSide: true,
      searching: true,
      lengthMenu: [[10, 20, 50, 100, 250, 500], [10, 20, 50, 100, 250, 500]],

      ajax: {
        url: "{{ URL::to('getselectproductgridData') }}",
        data: function (d) {
          var index = $('.pdtindex').val();
        }
      },

      columns: [
        { data: 'product_code', name: 'product_code' },
        { data: 'concatenated_product', name: 'concatenated_product' },

        {
          data: 'product_id',
          name: 'product_id',
          orderable: false,
          searchable: false,
          render: function (id) {
            return `
                        <button type="button"
                            class="btn btn-success btn-sm select-product"
                            data-id="${id}">
                            Select
                        </button>
                    `;
          }
        }
      ]
    });


    $(document).on('click', '.select-product', function () {

      var product_id = $(this).data('id');
      var index = $("#selected_row_index").val();

      $('.bulk_prd_id' + index).val(product_id);

      $.get("{{ URL::to('getproductname') }}/" + product_id, function (data) {
        var pname = data[0].product_code + "-" + data[0].concatenated_product;
        $('.product' + index).val(pname);
      });

      $('#distriModal').modal('hide');
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

      $newRow.find(".bulk_prd_id")
        .removeClass(function (i, cls) { return (cls.match(/bulk_prd_id\d+/g) || []).join(' ') })
        .addClass("bulk_prd_id" + index);

      $newRow.find(".product")
        .removeClass(function (i, cls) { return (cls.match(/product\d+/g) || []).join(' ') })
        .addClass("product" + index);

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
          url: "{{ url('productmappingsave') }}",
          type: "POST",
          data: formData,
          success: function (response) {
            if (response.status === "success") {
              showCustomAlert(response.message || 'Saved successfully!', 'success');
              setTimeout(() => {
                window.location.href = "{{ url('productmapping') }}";
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