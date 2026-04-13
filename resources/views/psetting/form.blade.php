@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Week & Month Off Details</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <form action="" id="settingsave" data-parsley-validate>
      <input type="hidden" name="edit_id" value="" id="edit_id" />
      <div class="card-body">
        <div class="row">
          <!-- WEEK OFF TABLE -->
          <div class="col-6 linetable">
            <div class="table-responsive">
              <table class="table table-bordered clone_table">
                <thead class="table-primary">
                  <tr>
                    <th>Day</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  @if(isset($week_off) && count($week_off) > 0)
                    @foreach($week_off as $day)
                      <tr class="line-row">
                        <td>
                          <select name="week_off[]" class="form-control week_off select2" required>
                            <option value="">Please Select</option>
                            @foreach(['1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday'] as $key => $val)
                              <option value="{{ $key }}" {{ $day == $key ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-danger remove-row"><i
                              class="fas fa-minus-circle"></i></button>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr class="line-row">
                      <td>
                        <select name="week_off[]" class="form-control week_off select2" required>
                          <option value="">Please Select</option>
                          <option value="1">Monday</option>
                          <option value="2">Tuesday</option>
                          <option value="3">Wednesday</option>
                          <option value="4">Thursday</option>
                          <option value="5">Friday</option>
                          <option value="6">Saturday</option>
                          <option value="7">Sunday</option>
                        </select>
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row"><i
                            class="fas fa-minus-circle"></i></button>
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row"><i class="fas fa-plus-circle"></i> Add
                  Row</button>
              </div>
            </div>
          </div>

          <!-- MONTH OFF TABLE -->
          <div class="col-6 linetable1">
            <div class="table-responsive">
              <table class="table table-bordered clone_table1">
                <thead class="table-warning">
                  <tr>
                    <th>Day</th>
                    <th>Week</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body1">
                  @if(isset($month_off) && count($month_off) > 0)
                    @foreach($month_off as $i => $day)
                      <tr class="line-row">
                        <td>
                          <select name="month_off[]" class="form-control month_off select2" required>
                            <option value="">Please Select</option>
                            @foreach(['1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday'] as $key => $val)
                              <option value="{{ $key }}" {{ $day == $key ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td>
                          <select name="week_period[]" class="form-control week_period select2" required>
                            <option value="">Please Select</option>
                            @foreach(['first' => 'Week 1', 'second' => 'Week 2', 'third' => 'Week 3', 'fourth' => 'Week 4', 'fifth' => 'Week 5'] as $key => $label)
                              <option value="{{ $key }}" {{ isset($week_period[$i]) && $week_period[$i] == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-danger remove-row1"><i
                              class="fas fa-minus-circle"></i></button>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr class="line-row">
                      <td>
                        <select name="month_off[]" class="form-control month_off select2" required>
                          <option value="">Please Select</option>
                          <option value="1">Monday</option>
                          <option value="2">Tuesday</option>
                          <option value="3">Wednesday</option>
                          <option value="4">Thursday</option>
                          <option value="5">Friday</option>
                          <option value="6">Saturday</option>
                          <option value="7">Sunday</option>
                        </select>
                      </td>
                      <td>
                        <select name="week_period[]" class="form-control week_period select2" required>
                          <option value="">Please Select</option>
                          <option value="first">Week 1</option>
                          <option value="second">Week 2</option>
                          <option value="third">Week 3</option>
                          <option value="fourth">Week 4</option>
                          <option value="fifth">Week 5</option>
                        </select>
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row1"><i
                            class="fas fa-minus-circle"></i></button>
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row1"><i class="fas fa-plus-circle"></i> Add
                  Row</button>
              </div>
            </div>
          </div>


        </div>
      </div>
  </div>

  <div class="row">
    <div class="col-md-12 text-center">
      <button type="button" id="save" class="btn btn-success saveform px-4 mt-4">Save</button>
    </div>
  </div>


  </div>
  </form>
  </div>






  <?php

  $result = $week_off;
  $result1 = $month_off;
  $result2 = $week_period;

  ?>

@endsection
@push('scripts')

  <script>

    $(document).ready(function () {
      // Add Row - weekoff table
      $(document).on('click', '.add-row', function () {
        const $lastRow = $('.clone_lines_body tr:last');
        const $newRow = $lastRow.clone(false, false); // clone without events or data

        $newRow.find('input').val('');
        $newRow.find('select').val('').trigger('change');

        // Reset and destroy old Select2
        $newRow.find('select.select2').each(function () {
          if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
          }
          $(this).removeAttr('data-select2-id');
          $(this).next('.select2').remove(); // remove select2 container
        });

        $('.clone_lines_body').append($newRow);

        // Reinitialize Select2
        $newRow.find('select.select2').select2({ width: '100%' });

        updateLineNumbers();
      });

      // Remove weekoff row
      $(document).on('click', '.remove-row', function () {
        const rowCount = $('.clone_lines_body tr').length;
        if (rowCount > 1) {
          $(this).closest('tr').remove();
          updateLineNumbers();
        } else {
          showCustomAlert("You Can't Delete. At least one row should be there.", "warning");
        }
      });

      // Add Row - month off table
      $(document).on('click', '.add-row1', function () {
        const $lastRow = $('.clone_lines_body1 tr:last');
        const $newRow = $lastRow.clone(false, false); // clone without events or data

        $newRow.find('input').val('');
        $newRow.find('select').val('').trigger('change');

        $newRow.find('select.select2').each(function () {
          if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
          }
          $(this).removeAttr('data-select2-id');
          $(this).next('.select2').remove(); // remove select2 container
        });

        $('.clone_lines_body1').append($newRow);

        $newRow.find('select.select2').select2({ width: '100%' });

        updateLineNumbers();
      });

      // Remove month off row
      $(document).on('click', '.remove-row1', function () {
        const rowCount = $('.clone_lines_body1 tr').length;
        if (rowCount > 1) {
          $(this).closest('tr').remove();
          updateLineNumbers();
        } else {
          showCustomAlert("You Can't Delete. At least one row should be there.", "info");
        }
      });

      // Update line numbers and assign dynamic class names
      function updateLineNumbers() {
        $('.clone_lines_body tr').each(function (index) {
          $(this).find('.week_off')
            .removeClass((i, cls) => (cls.match(/week_off\d+/g) || []).join(' '))
            .addClass('week_off' + index);
        });

        $('.clone_lines_body1 tr').each(function (index) {
          $(this).find('.month_off')
            .removeClass((i, cls) => (cls.match(/month_off\d+/g) || []).join(' '))
            .addClass('month_off' + index);

          $(this).find('.week_period')
            .removeClass((i, cls) => (cls.match(/week_period\d+/g) || []).join(' '))
            .addClass('week_period' + index);
        });
      }

      // Initialize select2 for all existing selects
      $('select.select2').select2({ width: '100%' });

      // === AUTO ADD & SET VALUES FROM PHP ===
      <?php if (!empty($result)): ?>
      <?php  foreach ($result as $key => $value): ?>
      setTimeout(function () {
        <?php    if ($key != 0): ?>
        $('.add-row').trigger('click');
        <?php    endif; ?>
        $('.week_off<?= $key ?>').val('<?= $value ?>').trigger('change');
      }, <?= $key * 150 ?>);
      <?php  endforeach; ?>
      <?php endif; ?>

      <?php if (!empty($result1)): ?>
      <?php  foreach ($result1 as $key1 => $value1): ?>
      setTimeout(function () {
        <?php    if ($key1 != 0): ?>
        $('.add-row1').trigger('click');
        <?php    endif; ?>
        $('.month_off<?= $key1 ?>').val('<?= $value1 ?>').trigger('change');
        $('.week_period<?= $key1 ?>').val('<?= $result2[$key1] ?>').trigger('change');
      }, <?= $key1 * 150 ?>);
      <?php  endforeach; ?>
      <?php endif; ?>
    });

    // save
    $(document).on('click', '.saveform', function (e) {

      e.preventDefault();
      var data;
      data = $("#settingsave").serialize();
      var url = "{{URL::to('setting')}}";
      var url1 = "{{URL::to('psettingsave')}}";
      var form = $('#settingsave');
      form.parsley().validate();
      if (form.parsley().isValid()) {
      var $btn = $(this);            
			$btn.prop('disabled', true);
        $.post(url1, data, function (data) {
          if (data == 1) {
            showCustomAlert('Saved Successfully', 'success');

            setTimeout(function () {
              location.reload();
            }, 2000);
          }
          else if (data == 2) {
            showCustomAlert('Deleted Successfully', 'success');
            location.reload();
          }
        });


      }
    });







  </script>

@endpush