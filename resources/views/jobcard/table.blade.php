@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Jobcard Status</h3>
  @include('layouts.breadcrumb')

<style>
  .select2-container--open {
    z-index: 200000 !important;
  }

  /* Bootstrap datetimepicker (most versions) */
  .datetimepicker,
  .bootstrap-datetimepicker-widget,
  .datepicker,
  .ui-datepicker {
    z-index: 200000 !important;
  }

/* Allow full product name */
table.dataTable td.product-column,
table.dataTable th.product-column {

    overflow: visible !important;
    text-overflow: unset !important;
    max-width: 400px !important;   /* adjust if needed */
    word-break: break-word;
}


</style>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="JobTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">

              <th>Actions</th>
              <th class="freeze">Job No</th>
              <th>Plan No</th>
              <th>Job Date</th>
              <th>Job status</th>
              <th>Job Completion Date</th>
              <th>Product</th>
              <th>Batch Number</th>
              <th>Job Qty</th>
               <th>Job Completion Qty</th>
              <th>Job Balance Qty</th>
              <th>Job Adjusted Qty</th>
              <th>Uom Code</th>
              <th>Process Name</th>
              <th>Job Created by</th>
              <th>Product Code</th>
              <th>Remarks</th>

            </tr>

            <tr class="table-info">

              <th class="freeze"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            </tr>
          </thead>
          <tbody>
            {{-- DataTable will populate via AJAX --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!--popups-->

  <!--store move-->

  <div class="modal fade" id="qtyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-3" style="height:550px;">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Store Move Details</h5>
          <input type="text" class="form-control form-control-sm ms-3 bg-light border-0 pro_name" readonly
            style="flex:1;">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="overflow-y:auto;">
          <form id="jobmovetostore" method="post" data-parsley-validate>

            <input type="hidden" class="macid">
            <input type="hidden" class="productid">
            <input type="hidden" class="jobid">
            <input type="hidden" class="job_date">
            <input type="hidden" class="qualitycheck">
            <input type="hidden" class="planhdrid">
            <input type="hidden" class="jobassigned_to">
            <input type="hidden" class="type">
            <input type="hidden" class="activity_name">
            <input type="hidden" class="processlevel1">
            <input type="hidden" class="processname">
            <input type="hidden" class="actualhrs">
            <input type="hidden" class="starttime">
            <input type="hidden" class="endtime">
            <input type="hidden" class="workinghrs">
            <input type="hidden" class="empstatus" value="0">
            <input type="hidden" class="employee_qty">
            <input type="hidden" name="prev_job_process_name" class="prev_job_process_name">
            <input type="hidden" name="prev_process_level" class="prev_process_level">

            <div class="row g-4">

              <div class="col-md-6">

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Process Level</label>
                  <div class="col-sm-7">
                    <select class="form-select select2 process_level" name="process_level" id="process_level" required>

                    </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Subinventory</label>
                  <div class="col-sm-7">
                    <select class="form-select select2 subinventory_id" id="subinventory_id" name="subinventory_id"
                      required>
                      {!! $subinventory_id !!}
                    </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Sublocator</label>
                  <div class="col-sm-7">
                    <select class="form-select select2 sublocator_id" name="sublocator_id" id="sublocator_id" required>
                      {!! $locator_id !!}
                    </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label">Date</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control trx_date" name="trx_date" id="trx_date"
                      value="<?php echo date("d-m-Y H:i:s"); ?>" readonly>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Machine Name</label>
                  <div class="col-sm-7">
                    <select class="form-select select2 machine_id" name="machine_id" id="machine_id" required></select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Machine Time</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control machine_time" name="machine_time" id="machine_time" readonly
                      required>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label">Calibration Checked By</label>
                  <div class="col-sm-7">
                    <select class="form-select select2 calibration_checked_by" name="calibration_checked_by"
                      id="calibration_checked_by"></select>
                  </div>
                </div>

              </div>

              <!-- Right Column -->
              <div class="col-md-6">

                <div class="mb-3 row none">
                  <label class="col-sm-5 col-form-label required">Process Name</label>
                  <div class="col-sm-7">
                    <select class="form-select select2 process_name" name="process_name" id="process_name"
                      required></select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label">Job Qty</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control jobqty" readonly>
                    <input type="hidden" class="batchno" name="batch_no">
                    <input type="hidden" class="productid" name="productid">
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Qty</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control qty" name="qty" required>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label">Previous Process Moved Qty</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control pmoved_qty" name="pmoved_qty" readonly>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label">Moved Qty</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control moved_qty" name="moved_qty" readonly>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Process Start Time</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control process_start_date" name="process_start_date"
                      id="process_start_date" required>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-sm-5 col-form-label required">Process End Time</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control process_end_date" name="process_end_date" id="process_end_date"
                      required>
                  </div>
                </div>

              </div>
            </div>

            <!-- Dynamic Employee Section -->
            <div class="mt-3 emppopup"></div>
            <div class="text-end">
              <button type="button" class="btn btn-success btn-sm add-row">
                <i class="fas fa-plus-circle"></i> Add Row
              </button>
            </div>

          </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-success movetostoredata">Move To Store</button>
          <button type="button" class="btn btn-secondary View-btnsm">Preview</button>
        </div>

      </div>
    </div>
  </div>


  <!--store move preview-->

  <!-- Store Move Preview Modal (Bootstrap 5) -->
  <div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

        <!-- Modal Header -->
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title fw-bold">Store Move Details Preview</h5>
          <input class="form-control ms-3 pro_name bg-light border-0 fw-semibold" readonly>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body configdetail">
          <div class="row g-4">

            <!-- Left Column -->
            <div class="col-md-6">
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Process Level</label>
                <div class="col-md-7">
                  <span class="form-control process_level1 prestyle">Selected Process Level</span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Subinventory</label>
                <div class="col-md-7">
                  <span class="form-control subinventory prestyle">Selected Subinventory</span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Sublocator</label>
                <div class="col-md-7">
                  <span class="form-control sublocator prestyle">Selected Sublocator</span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Date</label>
                <div class="col-md-6">
                  <span class="form-control trx_date1 prestyle"></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Machine Name</label>
                <div class="col-md-7">
                  <span class="form-control machine_name prestyle">Selected Machine Name</span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Machine Time</label>
                <div class="col-md-6">
                  <span class="form-control machine_time1 prestyle"></span>
                </div>
              </div>
              <div class="mb-3 row calibration_checked">
                <label class="col-md-5 col-form-label fw-semibold">Calibration Checked By</label>
                <div class="col-md-7">
                  <span class="form-control calibration_checked_by1 prestyle">Checked By Name</span>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Process Name</label>
                <div class="col-md-7">
                  <span class="form-control process_name1 prestyle">Selected Process Name</span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Job Qty</label>
                <div class="col-md-6">
                  <span class="form-control jobqty1 prestyle"></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Qty</label>
                <div class="col-md-6">
                  <span class="form-control qty1 prestyle"></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Previous Process Moved Qty</label>
                <div class="col-md-6">
                  <span class="form-control pmoved_qty1 prestyle"></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Moved Qty</label>
                <div class="col-md-6">
                  <span class="form-control moved_qty1 prestyle"></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Process Start Time</label>
                <div class="col-md-6">
                  <span class="form-control process_start_date1 prestyle"></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-5 col-form-label fw-semibold">Process End Time</label>
                <div class="col-md-6">
                  <span class="form-control process_end_date1 prestyle"></span>
                </div>
              </div>
            </div>

            <!-- Employee Table -->
            <div class="col-12">
              <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" id="employeeTable" style="width:100%">
                  <thead class="table-light">
                    <tr>
                      <th>Employee Name</th>
                      <th>Type</th>
                      <th>Activity Name</th>
                      <th>Actual Hours</th>
                      <th>Start Time</th>
                      <th>End Time</th>
                      <th>Working Hours</th>
                      <th>Qty</th>
                    </tr>
                  </thead>
                  <tbody id="tableBody">
                    <!-- Dynamic Rows -->
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
          </button>
        </div>

      </div>
    </div>
  </div>


  <!--segregation-->

  <div class="modal fade" id="segregationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title w-100 text-center">Segregation Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <form method="post" id="segregation" data-parsley-validate>
            <input type="hidden" class="jobqty" name="qty_seg">
            <input type="hidden" class="jobid">

            <!-- Product / Batch / Qty -->
            <div class="row g-3 align-items-center bg-light p-3 mb-4 rounded border">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Product</label>
                <input type="text" class="form-control pro_name text-center" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Batch</label>
                <input type="text" class="form-control batchno text-center" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Qty</label>
                <input type="text" class="form-control jobqty text-center" readonly>
              </div>
            </div>

            <!-- Form Fields -->
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">Machine Name <span class="text-danger">*</span></label>
                <select name="machine_name1" class="form-select select2 machine_name1" id="machine_name1" required>
                  {!! $machine_seg !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Received in Kg <span class="text-danger">*</span></label>
                <input type="text" name="seg_kg" class="form-control seg_kg" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Subinventory <span class="text-danger">*</span></label>
                <select name="subinventory_id_seg" class="form-select select2 subinventory_id_seg"
                  id="subinventory_id_seg" required>
                  {!! $subinventory_id !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Sublocator <span class="text-danger">*</span></label>
                <select name="sublocator_id_seg" class="form-select select2 sublocator_id_seg" id="sublocator_id_seg"
                  required>
                  {!! $locator_id !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Duration <span class="text-danger">*</span></label>
                <input type="text" name="duration_seg" class="form-control duration_seg" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                <select name="employee_seg[]" class="form-select select2 closed_by" id="employee_seg" multiple required>
                  {!! $emp_seg !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Output in Kg</label>
                <input type="text" name="output_seg" class="form-control output_seg">
              </div>

              <div class="col-md-6">
                <label class="form-label">Total Hrs</label>
                <input type="text" name="total_seg" class="form-control total_seg" readonly>
              </div>

              <div class="col-md-6">
                <label class="form-label">Emp Start Time <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-clock"></i></span>
                  <input type="text" name="emp_start_date" id="emp_start_date" class="form-control emp_start_date"
                    required>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Emp End Time <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                  <input type="text" name="emp_end_date" id="emp_end_date" class="form-control emp_end_date" required>
                </div>
              </div>

            </div>
          </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success seg_save px-4">
            <i class="bi bi-check2-circle me-1"></i> Submit
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
        </div>

      </div>
    </div>
  </div>

  <!--traydryer-->

  <div class="modal fade" id="traydryerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title w-100 text-center">Tray Dryer Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <form method="post" id="traydryer" data-parsley-validate>
            <input type="hidden" class="jobqty" name="qty_tray">
            <input type="hidden" class="jobid">

            <!-- Product / Batch / Qty -->
            <div class="row g-3 align-items-center bg-light p-3 mb-4 rounded border">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Product</label>
                <input type="text" class="form-control pro_name text-center" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Batch</label>
                <input type="text" class="form-control batchno text-center" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Qty</label>
                <input type="text" class="form-control jobqty text-center" readonly>
              </div>
            </div>

            <!-- Form Fields -->
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">Machine Name <span class="text-danger">*</span></label>
                <select name="machine_name2" class="form-select select2 machine_name2" id="machine_name2" required>
                  {!! $machine_tray !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Received in Kg <span class="text-danger">*</span></label>
                <input type="text" name="tray_kg" class="form-control tray_kg" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Subinventory <span class="text-danger">*</span></label>
                <select name="subinventory_id_tray" class="form-select select2 subinventory_id_tray"
                  id="subinventory_id_tray" required>
                  {!! $subinventory_id !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Sublocator <span class="text-danger">*</span></label>
                <select name="sublocator_id_tray" class="form-select select2 sublocator_id_tray" id="sublocator_id_tray"
                  required>
                  {!! $locator_id !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Duration <span class="text-danger">*</span></label>
                <input type="text" name="duration_tray" class="form-control duration_tray" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                <select name="employee_tray[]" class="form-select select2 closed_by" id="employee_tray" multiple required>
                  {!! $emp_tray !!}
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Output in Kg</label>
                <input type="text" name="output_tray" class="form-control output_tray">
              </div>

              <div class="col-md-6">
                <label class="form-label">Total Hrs</label>
                <input type="text" name="total_tray" class="form-control total_tray" readonly>
              </div>

              <div class="col-md-6">
                <label class="form-label">Emp Start Time <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-clock"></i></span>
                  <input type="text" name="emp_start_date_tray" id="emp_start_date_tray" class="form-control emp_start_date_tray"
                    required>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Emp End Time <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                  <input type="text" name="emp_end_date_tray" id="emp_end_date_tray" class="form-control emp_end_date_tray" required>
                </div>
              </div>

            </div>
          </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success tray_save px-4">
            <i class="bi bi-check2-circle me-1"></i> Submit
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
        </div>

      </div>
    </div>
  </div>

  <!--job close-->
  <!-- Jobcard Close Modal (Bootstrap 5) -->
  <div class="modal fade" id="jobcloseModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold">Update Jobcard Close</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">

          <!-- Jobcard Info -->
          <div class="d-flex justify-content-between mb-3">
            <div><strong>JOBCARD NUMBER:</strong> <span class="intjob_no"></span></div>
            <div><strong>JOBCARD DATE:</strong> <span class="intjob_date"></span></div>
          </div>

          <!-- Reason Section -->
          <div class="row mb-4">
            <label class="col-md-4 col-form-label fw-semibold">Reason for Jobcard Close:</label>
            <div class="col-md-8">
              <textarea class="form-control remarks" rows="4" required></textarea>
              <input type="hidden" class="form-control intjob_id">
            </div>
          </div>

          <!-- Closed By Section -->
          <div class="row mb-3">
            <label class="col-md-4 col-form-label fw-semibold">Jobcard Closed By:</label>
            <div class="col-md-8 closed_read_by">
              <select name="closed_by" id="closed_by" class="form-select select2 closed_by"></select>
              <input type="hidden" class="form-control intprd_id">
            </div>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success px-4 jobclose_save" id="updateClose">
            <i class="bi bi-check-circle me-1"></i> Jobcard Close
          </button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
        </div>

      </div>
    </div>
  </div>


  <!--end-->




@endsection
@push('scripts')

  <script>


    // table data

    $(document).ready(function () {

      var status = '<?php echo $status; ?>';
      var type = '<?php echo $type; ?>';
      var jobtype = '<?php echo $jobtype; ?>';

      var table = $('#JobTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[3, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getjobcardData?status=" + status + "&type=" + type,

        columns: [

          {

            data: 'w_jobs_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-primary edit-btn me-1"
                                    data-id="${row.w_jobs_hdr_id}"
                    data-id1="${row.quality_spec_trx_hdr_id}"
                    data-id2="${row.i_qoh_detail_id}"
                    data-status="${row.job_status}">
                                    <i class="bi bi-pencil"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-warning view-btn me-1"
                                    data-id="${row.w_jobs_hdr_id}">
                                    <i class="bi bi-eye"></i>
                                </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'rework')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-primary rework-btn me-1"
                                    data-id="${row.quality_spec_trx_hdr_id}"
                    data-source="${row.reworksource}"
                    data-qaid="${row.qa_submitstage_trx_hdr_id}"
                    data-qohid="${row.qoh_detail_id}">
                                   Rework
                                </button>`;
              }

              buttons += `
                                <button type="button" class="btn btn-sm btn-danger close-btn me-1"
                                    data-id="${row.w_jobs_hdr_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Job Card Close">
                                    <i class="bi bi-x"></i>
                                </button>`;

              <?php if ($pageMethod == "packingjobcardstatus") { ?>
              buttons += `
                                <button type="button" class="btn btn-sm btn-success process-btn me-1"
                                    data-id="${row.w_jobs_hdr_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Store Move">
                                    <i class="bi bi-arrows-move"></i>
                                </button>`;
              <?php } ?>

              buttons += `
                                <button type="button" class="btn btn-sm btn-info segregation-btn me-1"
                                    data-id="${row.w_jobs_hdr_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Segregation">
                                   <i class="bi bi-funnel"></i>
                                </button>`;

              buttons += `
                                <button type="button" class="btn btn-sm btn-dark traydryer-btn me-1"
                                    data-id="${row.w_jobs_hdr_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Tray Dryer">
                                   <i class="bi bi-stack"></i>
                                </button>`;                  

              return buttons;
            },

          },

          { data: 'job_no', name: 'job_no', class:'freeze' },
          { data: 'plan_no', name: 'plan_no' },
          { data: 'job_date', name: 'job_date' },
          { data: 'job_status', name: 'job_status' },
          { data: 'job_completion_date', name: 'job_completion_date' },
          { data: 'concatenated_product', name: 'concatenated_product', className: 'product-column'},
          { data: 'batch_no', name: 'batch_no' },
          { data: 'job_qty', name: 'job_qty' },
          { data: 'production_qty', name: 'production_qty' },
          { data: 'balancejob_qty', name: 'balancejob_qty' },
          { data: 'job_adjusted_qty', name: 'job_adjusted_qty' },
          { data: 'uom_code', name: 'uom_code' },
          { data: 'job_process', name: 'job_process' },
          { data: 'first_name', name: 'first_name' },
          { data: 'product_code', name: 'product_code' },
          { data: 'remarks', name: 'remarks' },
          { data: 'reworksource', name: 'reworksource', visible: false },
          { data: 'segregation', name: 'segregation', visible: false },
          { data: 'traydryer', name: 'traydryer', visible: false },
          { data: 'product_id', name: 'product_id', visible: false },
          { data: 'qoh_detail_id', name: 'qoh_detail_id', visible: false },
          { data: 'quality_spec_trx_hdr_id', name: 'quality_spec_trx_hdr_id', visible: false },
          { data: 'qa_submitstage_trx_hdr_id', name: 'qa_submitstage_trx_hdr_id', visible: false },
          { data: 'reference_source_id', name: 'reference_source_id', visible: false },
          { data: 'segregation_status', name: 'segregation_status', visible: false },
          { data: 'traydryer_status', name: 'traydryer_status', visible: false },
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

    });


    // view 

    /* purpose: function :redirect to show the created records*/
    $(document).on('click', '.view-btn', function () {

      const id = $(this).data('id');

      var pageurl = "<?php echo $pageurl; ?>";

      var url = "{{URL::to('jobcardview')}}/" + id + "?pageurl=" + pageurl;
      window.location.replace(url);

    });

    // rework

    $(document).on('click', '.rework-btn', function () {

      let value = $(this).data('id');
      const qaid = $(this).data('qaid');
      const qohid = $(this).data('qohid');
      const reworksource = $(this).data('source');

      let source;

      if (value != 0) {
        source = "{{ $source }}";
      } else {
        value = qohid;
        if (reworksource == 'salesrework') {
          source = "Returnrework";
        } else {
          source = "Rework";
        }
      }

      const url = "{{ URL::to($editurl) }}";
      const editUrl = url + '/' + value + '?source=' + source + '&qaid=' + qaid + '&reworksrc=' + reworksource;

      window.location.replace(editUrl);
    });


    // edit 
    $(document).on('click', '.edit-btn', function () {


      const id = $(this).data('id');
      const id1 = $(this).data('id1');
      const id2 = $(this).data('id2');
      const job_status = $(this).data('status');
      var jobtype = '<?php echo $jobtype; ?>';

      var source = "{{ $source }}";
      if (source == "JOBCARD") {
        id = id;
      }

      else if (source == "REWORK") {

        id = id1;

      }

      if (job_status == "OPEN") {
        var url = "{{ URL::to($editurl) }}";
        if (source !== "") {
          var editUrl = url + '/' + id + '?source=' + source;
          window.location.replace(editUrl);
        }


        else {
          var editUrl = url + '/' + id + '?jobtype=' + jobtype;
          window.location.replace(editUrl);
        }
      } else {
        showCustomAlert("Open Job Card Should be Allow to Edit", 'warning');
      }

    });



    $(document).ready(function () {

      var cleared_by = "{{ \Session::get('emp_id') }}";
      var url = "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name&parent=active='Yes'&order_by=employee_id asc";

      $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
          // If response is JSON string, parse it
          if (typeof data === "string") {
            try {
              data = JSON.parse(data);
            } catch (e) {
              console.error("Invalid JSON response:", data);
              return;
            }
          }

          $('.closed_by').html('<option value="">-- Select Employee --</option>');

          $.each(data, function (i, item) {
            let selected = (item.val == cleared_by) ? 'selected' : '';
            $('.closed_by').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
          });

          // If using select2
          $('.closed_by').trigger('change.select2');
        }
      });


      /* purpose: move qty to store*/
      $(document).on('click', '.process-btn', function () {

        // Get the row data for the clicked button
        var row = $('#JobTbl').DataTable().row($(this).closest('tr')).data();

        var jobstatus = row.job_status;
        var jobid = row.w_jobs_hdr_id;
        var jobdate = row.job_date;
        var cellValue = row.machine_id;
        var Proname = row.concatenated_product;
        var productid = row.product_id;
        var jobqty = row.job_adjusted_qty;
        var batchno = row.batch_no;
        var qccheck = row.quality_check;
        var planhdrid = row.reference_source_id;

        if (jobstatus === "MATERIAL RECEIVED") {

          // Get process levels
          $.get("{{ URL::to('prsdetails') }}/" + productid, function (data) {
            if (data != 0) {
              var prc = data['prcslvl'].split(",");
              var html = "<option value>-- Please Select --</option>";
              $.each(prc, function (i, v) {
                html += "<option value='" + v + "'>" + v + "</option>";
              });
              $(".process_level").html(html);
            }
          });

          // Get employee work details
          $.get("{{ URL::to('employeeworkdetails') }}/" + jobid, function (data) {
            $('.emppopup').html(data);
          });

          // Show modal
          $('#qtyModal').modal('show');

          // Reset modal fields
          $('.process_name').val('').trigger('change'); // for select2
          $('.machine_time').val('');
          $('.qty').val('');
          $('.process_start_date').val('');
          $('.process_end_date').val('');
          $('.macid').val(cellValue);
          $('.productid').val(productid);
          $('.jobid').val(jobid);
          $('.job_date').val(jobdate);
          $('.pro_name').val(Proname);
          $('.jobqty').val(jobqty);
          $('.planhdrid').val(planhdrid);
          $('.batchno').val(batchno);
          $('.qualitycheck').val(qccheck);

          // Handle subinventory change
          $('.subinventory_id').off('change').on('change', function () {
            var subinv = $(this).val();
            var cond = "subinventory_id=" + subinv + " and sublocator_id in(191,192)";
            $('.sublocator_id').prop('disabled', true);

            if (subinv != '') {
              $('.sublocator_id').prop('disabled', false);
              var url = "{{ URL::to('jcomboform') }}?table=m_sublocators_t:sublocator_id:locator_code&parent=" + cond + "&order_by=locator_code asc";

              $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                  // Parse if response is JSON string
                  if (typeof data === "string") {
                    try {
                      data = JSON.parse(data);
                    } catch (e) {
                      console.error("Invalid JSON response:", data);
                      return;
                    }
                  }

                  $('.sublocator_id').html('<option value="">-- Select Sublocator --</option>');

                  $.each(data, function (i, item) {
                    let selected = item.val == "{{ $row->sublocator_id ?? '' }}" ? 'selected' : '';
                    $('.sublocator_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                  });

                  // If you are using select2
                  $('.sublocator_id').trigger('change.select2');
                }
              });
            }
          });


    $(document).ready(function () {


          const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
          const timeFormat = "HH:mm";
          var jobdate = $('.job_date').val();


      $(document).on("focus", ".process_start_date", function () {
        $(this).datetimepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          timeFormat: timeFormat,
          controlType: 'select',
          oneLine: true,
          minDate: new Date(jobdate),
          maxDate: new Date(),
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDateTime) {
            if (selectedDateTime) {
              const startDate = $(this).datetimepicker("getDate");

                  let endOfDay = new Date(startDate);
                  endOfDay.setHours(23, 59, 59, 999);
              // Reinitialize process_end_date with updated minDate
              $(".process_end_date").datetimepicker("destroy").datetimepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                timeFormat: timeFormat,
                controlType: 'select',
                oneLine: true,
                minDate: startDate, 
                maxDate: endOfDay,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });

          // When modal opens, set up datetime pickers



          // machine time calculation based on start and end time - vignesh m
          $(document).on("change", ".process_end_date", function () {


            var end_actual_time = new Date(' ' + $('.process_end_date').val() + "-00");
            var start_actual_time = new Date(' ' + $('.process_start_date').val() + "-00");

            var diff = end_actual_time - start_actual_time;
            var diffSeconds = diff / 1000;
            var HH = Math.floor(diffSeconds / 3600);
            var MM = Math.floor(diffSeconds % 3600) / 60;
            var totlalhr = HH + '.' + MM;

            $('.machine_time').val(totlalhr);


          });

        } else {
          showCustomAlert("Received Jobcard should be allow to move", 'info');

        }
      });

      // end 	



      $('.process_level').change(function () {

        var level = $(this).val();
        var $selected = $(this).find(':selected');
        var plevel = $selected.prev().text();
        if (plevel != '-- Please Select --') {
          plevel = plevel;
        } else {
          plevel = 0;
        }
        var jobid = $('.jobid').val();
        var productid = $('.productid').val();
        var selectedValue = '';



        $.get("{{URL::to('getprdtype')}}/" + productid, function (data) {
          var cond = "machine_hdr_id in(" + data + ")";

          var url = "{{ URL::to('jcomboformcomp') }}?table=w_machine_hdr_t:machine_hdr_id:machine_name&parent=" + cond + "&order_by=machine_name asc";

          $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
              // Parse JSON string if needed
              if (typeof data === "string") {
                try {
                  data = JSON.parse(data);
                } catch (e) {
                  console.error("Invalid JSON response:", data);
                  return;
                }
              }

              $('.machine_id').html('<option value="">-- Select Machine --</option>');

              $.each(data, function (i, item) {
                let selected = item.val == selectedValue ? 'selected' : '';
                $('.machine_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
              });

              // If using select2
              $('.machine_id').trigger('change.select2');
            }
          });
        });


        $('.calibration_checked').hide();
        if (level == 'PROCESS-1') {
          $('.calibration_checked').show();
          $('.calibration_checked_by').attr('required', true);
          var calib_cond = "  employee_id in (21,122,52,265,483,590) AND active ='Yes'";
          var url = "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name&parent=" + calib_cond + "&order_by=first_name asc";

          $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
              // Parse JSON if response is a string
              if (typeof data === "string") {
                try {
                  data = JSON.parse(data);
                } catch (e) {
                  console.error("Invalid JSON response:", data);
                  return;
                }
              }

              $('.calibration_checked_by').html('<option value="">-- Select Employee --</option>');

              $.each(data, function (i, item) {
                let selected = item.val == selectedValue ? 'selected' : '';
                $('.calibration_checked_by').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
              });

              // If using select2
              $('.calibration_checked_by').trigger('change.select2');
            }
          });
        } else {
          $('.calibration_checked_by').removeAttr('required');
          $('.calibration_checked_by').hide();
        }
        console.log("A");
        if (jobid != "" && level != "") {
          console.log("B");
          $.get("{{URL::to('prsdetails')}}/" + productid + "?level=" + level + "&jobid=" + jobid + "&plevel=" + plevel, function (data) {

            $('.prev_process_level').val(plevel);

            if (level != 'PROCESS-1') {
              if (data['pstatus'] == '1') {
                var cond = '  lookup_type="PROCESS_NAME"';
                var selectedValue = data['pname'] ?? '';
                console.log("D");
                var url = "{{ URL::to('jcomboform') }}?table=a_lookuplines_t:lookup_code:lookup_code&parent=" + cond + "&order_by=lookup_code asc";

                $.ajax({
                  url: url,
                  type: 'GET',
                  success: function (data) {
                    // Parse JSON string if needed
                    if (typeof data === "string") {
                      try {
                        data = JSON.parse(data);
                      } catch (e) {
                        console.error("Invalid JSON response:", data);
                        return;
                      }
                    }

                    $('.process_name').html('<option value="">-- Select Process --</option>');
                    console.log("viki", selectedValue);
                    $.each(data, function (i, item) {
                      let selected = item.val == selectedValue ? 'selected' : '';
                      $('.process_name').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                    });

                    // Trigger change if using select2
                    $('.process_name').trigger('change.select2');
                  }
                });

                $('.prev_job_process_name').val(data['p_prcsname']);
                $('.moved_qty').val(data['movedqty']);
                $('.pmoved_qty').val(data['pmovedqty']);
              } else {
                $('.process_level').select2('val', ['']);
                $('.moved_qty').val(0);
                $('.pmoved_qty').val(0);

                if (plevel != 0) {
                  showCustomAlert('Stock Not Moved for this Process ' + plevel, 'info');
                  $('.process_level').select2('val', ['']);
                  $('.moved_qty').val(0);
                  $('.pmoved_qty').val(0);

                }
              }
            } else {
              var selectedValue = data['pname'] ?? '';
              var cond = '  lookup_type="PROCESS_NAME"';
              var url = "{{ URL::to('jcomboform') }}?table=a_lookuplines_t:lookup_code:lookup_code&parent=" + cond + "&order_by=lookup_code asc";

              $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                  // Parse JSON string if needed
                  if (typeof data === "string") {
                    try {
                      data = JSON.parse(data);
                    } catch (e) {
                      console.error("Invalid JSON response:", data);
                      return;
                    }
                  }

                  $('.process_name').html('<option value="">-- Select Process --</option>');

                  $.each(data, function (i, item) {
                    let selected = item.val == selectedValue ? 'selected' : '';
                    $('.process_name').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                  });

                  // Trigger change if using select2
                  $('.process_name').trigger('change.select2');
                }
              });
              $('.prev_job_process_name').val(data['p_prcsname']);
              $('.moved_qty').val(data['movedqty']);
              $('.pmoved_qty').val(data['pmovedqty']);

            }
          });
        }
      });

      $(document).on('keypress', '.qty,.machine_time', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });

      $('.qty').change(function () {
        var qty = parseFloat($(this).val());
        var jobqty = parseFloat($('.jobqty').val());
        var pmqty = parseFloat($('.pmoved_qty').val());
        var mqty = parseFloat($('.moved_qty').val());
        var plevel = $('.process_level').select2('val');
        if (plevel != 'PROCESS-1') {
          var qty1 = qty + mqty;
          if (qty1 > pmqty) {
            showCustomAlert('Qty Should not exceed previous Process Qty', 'info');
            $('.qty').val('');
          } else if (qty > jobqty) {
            showCustomAlert('Qty Should not exceed Job Qty', 'info');
            $('.qty').val('');
          } else {

          }
        } else {
          var jqty = (jobqty * 0.5) / 100;
          var ejqty = parseFloat(jqty) + parseFloat(jobqty);
          console.log(ejqty);
          if (mqty == 0) {
            if (qty > ejqty) {
              showCustomAlert('Qty Should not exceed Job Qty', 'info');
              $('.qty').val('');
            }
          } else {
            var bjqty = ejqty - mqty;
            if (qty > bjqty) {
              showCustomAlert('Qty Should not exceed Job Qty', 'warning');
              $('.qty').val('');
            }

          }
        }

      });


      $('.movetostoredata').click(function () {
        var product = $('.productid').val();
        var subid = $('.subinventory_id').val();
        var sublocid = $('.sublocator_id').val();
        var prslvl = $('.process_level').val(); console.log(prslvl);
        var jobid = $('.jobid').val();
        var qty = $('.qty').val();
        var planid = $('.planhdrid').val();
        var qc = $('.qualitycheck').val();
        var mvqty = $('.moved_qty').val();
        var hrs = 0;
        var a = 0;
        var b = 0;
        var jobassign = [];
        var types = [];
        var activities = [];
        var prclevel = [];
        var prcname = [];
        var acthrs = [];
        var starttime = [];
        var endtime = [];
        var workhrs = [];
        var empqty = [];
        var myArray = [];
        var qty = 0;
        $('.working_hrs').each(function (i) {
          var rl = ($(this).val() ? $(this).val() : 0);
          var time = String(rl).split(".");
          a += parseInt(time[0]);
          b += parseInt(time[1]);

          hrs += rl;
          var jobass = parseFloat($('.job_assigned_to' + i).val() ? $('.job_assigned_to' + i).val() : 0);
          var type = parseFloat($('.type' + i).val() ? $('.type' + i).val() : 0);
          var activity = parseFloat($('.activity_name' + i).val() ? $('.activity_name' + i).val() : 0);
          var prlvl = $('.process_level')
          var plevel = $('.processlevel' + i).val();
          var pname = $('.processname' + i).val();
          var acthr = $('.actual_hrs' + i).val();
          var st = $('.start_time' + i).val();
          var et = $('.end_time' + i).val();
          var wh = $('.working_hrs' + i).val();
          var eqty = parseFloat($('.emp_qty' + i).val() ? $('.emp_qty' + i).val() : 0);
          jobassign.push(jobass);
          types.push(type);
          activities.push(activity);
          prclevel.push(plevel);
          prcname.push(pname);
          acthrs.push(acthr);
          starttime.push(st);
          endtime.push(et);
          if (wh != "") {
            workhrs.push(wh);
          }
          empqty.push(eqty);
          qty += eqty;
        });
        let h = Math.floor(b / 60);
        let m = parseInt(b % 60);
        var totalhr = parseInt(a) + parseInt(h);
        var totwrkhrs = totalhr + "." + m;
        var popstatus = "";
        var popstatus1 = "";
        $('.total_working_hrs').val(totwrkhrs);
        $('.jobassigned_to').val(jobassign);

        if (workhrs != "") {
          $('.actualhrs').val(acthrs);
          $('.starttime').val(starttime);
          $('.endtime').val(endtime);
          $('.workinghrs').val(workhrs);
          popstatus = 0;
        } else {
          showCustomAlert("Please enter employee details", 'error');
          popstatus = 1;
        }


        $('.processlevel1').val(prclevel);
        $('.processname').val(prcname);
        $('.employee_qty').val(empqty);
        $('.empstatus').val(1);

        if (mvqty == "") {
          mvqty = 0;
        }
        var date = $('.trx_date').val();
        var url = "{{URL::to('movetostore')}}/" + jobid;
        validationrule('jobmovetostore');
        var form = $('#jobmovetostore');
        form.parsley().validate();
        if (form.parsley().isValid()) {
          var formdata = $('#jobmovetostore').serialize();
          $.post(url, formdata, function (data) {
            if (data == 1) {
              showCustomAlert('Stock Moved To Production Location Successfully', 'success');
              $('#grid1').trigger("reloadGrid");
              $('#qtyModal').modal('hide');
            }

          });
        }
      });



      $('.datetimepicker1').datetimepicker({
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        format: "dd-mm-yyyy hh:ii:ss",
      });


      /*deepika purpose:redirect to create function*/
      $(document).on('click', '.create', function () {

        var url = "{{ URL::to('jobcardcreate') }}/0";
        var red_url = "{{ URL::to('jobcard') }}";
        window.location.replace(url);
      });


      /* purpose: function :redirect to delete the created records*/
      $("#delete").click(function () {
        var gr = jQuery("#grid1").jqGrid('getGridParam', 'selrow');
        var id = jQuery("#grid1").jqGrid('getCell', gr, 'w_jobs_hdr_id');
        if (gr) {
          swal({
            title: "Are you sure?",
            text: "You want to delete!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: !1,
            //timer: 2e3,
            closeOnCancel: !1
          }, function (e) {

            if (e == true) {
              var url = "{{ URL::to('workorderdelete') }}/" + id;
              $.get(url, function (data) {
                var data = $.trim(data);
                var red_url = "{{ URL::to('workorder') }}";
                var data = $.trim(data);
                if (data == '0') {
                  notyMsg('success', 'Deleted Successfully', red_url);
                  setTimeout(function () {
                    window.location.href = red_url;
                  }, 1500);
                }
                if (data == '2') {
                  notyMsg('error', "You Can't delete , Enquiry Used in SomeWhere", red_url);
                }

              });
            }
            else {
              $('.apply').css('display', 'none');
              swal("Cancelled");
            }

          })
          $('.apply').css('display', 'none');
        }
        else {
          notyMsg("info", "Please Select Row");
        }
      });


      /*deepika purpose:qty validation*/
      $(document).on('keypress', '.emp_qty', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });

      $(document).on("change", ".emp_qty", function () {
        var pqty = $('.qty').val();
        var eq = 0;
        if (pqty != "") {
          $('.emp_qty').each(function (i) {
            var empq = $('.emp_qty' + i).val();
            eq = eq + parseFloat(empq);
            console.log(eq);
            if (eq > pqty) {
              showCustomAlert('Employee Qty Should not greater than Production qty', 'warning');
              $('.emp_qty' + i).val('');
            }

          });
        } else {
          showCustomAlert('Please Enter Production qty', 'info');
          $('.emp_qty').val('');
        }
      });

$(document).on("change", ".start_time, .end_time", function () {

    var $row = $(this).closest('tr');

    var start = $row.find('.start_time').val();
    var end   = $row.find('.end_time').val();

    // If first row → copy values to empty rows
    if ($row.is(':first-child')) {

        $('.clone_lines_body tr').each(function () {

            var $r = $(this);

            if ($r.find('.start_time').val() === '') {
                $r.find('.start_time').val(start);
            }

            if (end !== '' && $r.find('.end_time').val() === '') {
                $r.find('.end_time').val(end);
                hourscal($r);
            }
        });
    }

    if (end !== '') {
        hourscal($row);
    }
});

function hourscal(row) {

    var startTime = row.find('.start_time').datetimepicker('getDate');
    var endTime   = row.find('.end_time').datetimepicker('getDate');

    if (!startTime || !endTime) return;

    if (endTime <= startTime) {
        row.find('.working_hrs').val('0.00');
        return;
    }

    var diffMs = endTime - startTime;

    var totalMinutes = Math.floor(diffMs / 60000);

    var hours   = Math.floor(totalMinutes / 60);
    var minutes = totalMinutes % 60;

    // HH.MM format
    var result = hours + (minutes / 100);

    row.find('.working_hrs').val(result.toFixed(2));
}




      var st = $('.starttime').val();
      var et = $('.endtime').val();
      var wh = $('.workinghrs').val();
      var st1 = st.split(",");
      var et1 = et.split(",");
      var wh1 = wh.split(",");
      $('.working_hrs').each(function (i) {
        $('.start_time' + i).val(st1[i]);
        $('.end_time' + i).val(et1[i]);
        $('.working_hrs' + i).val(1);

      });

      // close jobcard

      $(document).on('click', '.close-btn', function () {

        // Get the selected row (you may need to track selection in DataTables)
        var row = $('#JobTbl').DataTable().row($(this).closest('tr')).data();

        var w_jobs_hdr_id = row.w_jobs_hdr_id;
        var job_status = row.job_status;
        var job_no = row.job_no;
        var concatenated_product = row.concatenated_product;
        var job_date = row.job_date;
        var remarks = row.remarks;

        if (job_status === 'OPEN') {

          $(".intjob_no").html(job_no);
          $(".intjob_date").html(job_date);
          $(".intjob_id").val(w_jobs_hdr_id);
          $(".intprd_id").val(concatenated_product);

          $("#jobcloseModal").modal('show');

          var url = "{{ URL::to('jobcardcloedit') }}?id=" + w_jobs_hdr_id;
          $.get(url, function (data) {
            $('.remarks').val(data.remarks);

            if (data.update !== "create") {
              $('.closed_read_by').css("pointer-events", "none");
              $('.remarks').attr("required", true);
            } else {
              $('.closed_read_by').css("pointer-events", "none");
              $('.remarks').attr("required", true);
            }
          });

        } else {
          showCustomAlert("Please Select OPEN Jobcards Only!", 'warning');
        }

      });



      $(document).on('click', '.jobclose_save', function () {

        var id = $(".intjob_id").val();
        var intprd_id = $(".intprd_id").val();
        var closed_by = $(".closed_by").val();
        var remarks = $(".remarks").val();
        if (!remarks) {
          alert('Reason for Jobcard Close is mandatory');
          return;
        } else {
          $.get("jobcloseupdate?id=" + id + "&closed_by=" + closed_by + "&remarks=" + remarks + "&intprd_id=" + intprd_id, function (data) {

            if ($.trim(data) == '1') {

              showCustomAlert("Jobcard Closed Successfully", 'success');
              $("#grid1")[0].triggerToolbar();
              $('#JobTbl').DataTable().ajax.reload();
            } else {

              showCustomAlert('Please Try Again', 'error');
              $("#grid1")[0].triggerToolbar();
            }
          });

          $("#jobcloseModal").modal('hide');
        }

      });





      /*end*/

    });

    // preview script - VIGNESH M
    $(document).ready(function () {
      // When Preview button is clicked
      $(".View-btnsm").click(function () {

        let processLevel = $("#process_level").find("option:selected").text();
        let subinventory = $("#subinventory_id").find("option:selected").text();
        let sublocator = $("#sublocator_id").find("option:selected").text();
        let date = $("#trx_date").val();
        let machineName = $("#machine_id").find("option:selected").text();
        let machineTime = $("#machine_time").val();
        let calibrationChecked = $("#calibration_checked_by").find("option:selected").text();
        let processName = $("#process_name").find("option:selected").text();
        let jobQty = $(".jobqty").val();
        let qty = $(".qty").val();
        let prevMovedQty = $(".pmoved_qty").val();
        let movedQty = $(".moved_qty").val();
        let startTime = $("#process_start_date").val();
        let endTime = $("#process_end_date").val();
        //  let employee = $(".job_assigned_to").val();
        let employee = $(".job_assigned_to").find("option:selected").text();
        let type = $(".type").find("option:selected").text();
        let activity = $(".activity_name").find("option:selected").text();
        let empstartTime = $(".start_time").val();
        let empendTime = $(".end_time").val();
        let acthrs = $(".actual_hrs").val();
        let wrkhrs = $(".working_hrs").val();
        let empqty = $(".emp_qty").val();
        //       let line = $(".line_no").val();

        $("#previewModal .pro_name").text("Product Name Here"); // Change as needed
        $("#previewModal .configdetail").find(".process_level1").text(processLevel);
        $("#previewModal .configdetail").find(".subinventory").text(subinventory);
        $("#previewModal .configdetail").find(".sublocator").text(sublocator);
        $("#previewModal .configdetail").find(".trx_date1").text(date);
        $("#previewModal .configdetail").find(".machine_name").text(machineName);
        $("#previewModal .configdetail").find(".machine_time1").text(machineTime);
        $("#previewModal .configdetail").find(".calibration_checked_by1").text(calibrationChecked);
        $("#previewModal .configdetail").find(".process_name1").text(processName);
        $("#previewModal .configdetail").find(".jobqty1").text(jobQty);
        $("#previewModal .configdetail").find(".qty1").text(qty);
        $("#previewModal .configdetail").find(".pmoved_qty1").text(prevMovedQty);
        $("#previewModal .configdetail").find(".moved_qty1").text(movedQty);
        $("#previewModal .configdetail").find(".process_start_date1").text(startTime);
        $("#previewModal .configdetail").find(".process_end_date1").text(endTime);
        $("#previewModal .configdetail").find(".employee").text(employee);
        $("#previewModal .configdetail").find(".type").text(type);
        $("#previewModal .configdetail").find(".activity").text(activity);
        $("#previewModal .configdetail").find(".empstartTime").text(empstartTime);
        $("#previewModal .configdetail").find(".empendTime").text(empendTime);
        $("#previewModal .configdetail").find(".acthrs").text(acthrs);
        $("#previewModal .configdetail").find(".wrkhrs").text(wrkhrs);
        $("#previewModal .configdetail").find(".empqty").text(empqty);
        //  $("#previewModal .configdetail").find(".line").text(line);
        // Retrieve values dynamically from the form

        let employeeNames = $(".job_assigned_to").map(function () {
          return $(this).find("option:selected").text();
        }).get().join(", ");
        // Remove empty or falsy values
        let types = $(".type").map(function () {
          return $(this).find("option:selected").text().trim();
        })
          .get()
          .filter(text => text)
          .join(", ");
        console.log(types);

        let activities = $(".activity_name").map(function () {
          return $(this).find("option:selected").text().trim();
        })
          .get()
          .filter(text => text)
          .join(", ");
        console.log(activities);
        let actualHours = $(".actual_hrs").map(function () {
          return $(this).val();
        }).get().join(", ");
        let startTimes = $(".start_time").map(function () {
          return $(this).val();
        }).get().join(", ");
        let endTimes = $(".end_time").map(function () {
          return $(this).val();
        }).get().join(", ");
        let workingHours = $(".working_hrs").map(function () {
          return $(this).val();
        }).get().join(", ");
        let quantities = $(".emp_qty").map(function () {
          return $(this).val();
        }).get().join(", ");

        // Prepare the data
        const data = [
          {
            employeeName: employeeNames,
            type: types,
            activityName: activities,
            actualHours: actualHours,
            startTime: startTimes,
            endTime: endTimes,
            workingHours: workingHours,
            qty: quantities,
          },
        ];
        // Populate the table
        populateTable(data);

        // Show the preview modal
        $("#previewModal").modal("show");
      });

      // Function to split values and populate the table
      function populateTable(data) {
        const tableBody = document.getElementById("tableBody");
        tableBody.innerHTML = ""; // Clear existing rows

        data.forEach((row) => {
          const {
            employeeName,
            type,
            activityName,
            actualHours,
            startTime,
            endTime,
            workingHours,
            qty,
          } = row;

          // Split values by comma
          const names = employeeName.split(", ");
          const types = type.split(", ");
          const activities = activityName.split(", ");
          const hours = actualHours.split(", ");
          const startTimes = startTime.split(", ");
          const endTimes = endTime.split(", ");
          const workHours = workingHours.split(", ");
          const quantities = qty.split(", ");

          // Create a row for each line
          names.forEach((_, index) => {
            const newRow = document.createElement("tr");

            newRow.innerHTML = `
                <td>${names[index] || ""}</td>
                <td>${types[index] || ""}</td>
                <td>${activities[index] || ""}</td>
                <td>${hours[index] || ""}</td>
                <td>${startTimes[index] || ""}</td>
                <td>${endTimes[index] || ""}</td>
                <td>${workHours[index] || ""}</td>
                <td>${quantities[index] || ""}</td>
              `;

            tableBody.appendChild(newRow);
          });
        });
      }
    });


    // segregation purpose - vignesh M
    // Segregation action (DataTables version)
    $('#segregationModal').on('shown.bs.modal', function () {
    $(this).find('.select2').select2({
        dropdownParent: $('#segregationModal'),
        width: '100%'
    });
});

    $(document).on('click', '.segregation-btn', function () {
      const dt = $('#JobTbl').DataTable();

      // Get the row data for the clicked button
      const $tr = $(this).closest('tr');
      const row = dt.row($tr).data();
      if (!row) {
        showCustomAlert('Unable to read row data.', 'error');
        return;
      }

      const jobstatus = row.job_status;
      const seg_status = row.segregation_status;
      const jobid = row.w_jobs_hdr_id;
      const Proname = row.concatenated_product;
      const productid = row.product_id;
      const jobqty = row.job_adjusted_qty;
      const batchno = row.batch_no;
      const segregation = row.segregation;
      const jobdate = row.job_date;
      const jobprocess = row.job_process;

      if ((jobstatus == 'CLOSED' || jobstatus == 'COMPLETED') && segregation == '1') {
        if (seg_status == null) {

          $('#segregationModal').modal('show');
          $('.productid').val(productid);
          $('.jobid').val(jobid);
          $('.job_date').val(jobdate);
          $('.pro_name').val(Proname);
          $('.jobqty').val(jobqty);
          $('.batchno').val(batchno);

          try {
            $('.emp_start_date').datetimepicker('remove');
          } catch (e) { }
          try {
            $('.emp_end_date').datetimepicker('remove');
          } catch (e) { }

                const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
                const timeFormat = "HH:mm";


      $(document).on("focus", ".emp_start_date", function () {
        $(this).datetimepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          timeFormat: timeFormat,
          controlType: 'select',
          oneLine: true,
          showSecond: true,
          minDate: new Date(2024, 3, 1),
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDateTime) {
            if (selectedDateTime) {
              const startDate = $(this).datetimepicker("getDate");

              // Reinitialize end_date_time with updated minDate
              $(".emp_end_date").datetimepicker("destroy").datetimepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                timeFormat: timeFormat,
                controlType: 'select',
                oneLine: true,
                showSecond: true,
                minDate: startDate, // Disallow dates before start time
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });

          // Duration calculation (HH.MM) whenever start/end change
          $(document)
            .off('change.seg') // prevent duplicate handlers on repeated opens
            .on('change.seg', '.emp_start_date, .emp_end_date', function () {
              const s = $('.emp_start_date').val();
              const e = $('.emp_end_date').val();
              if (!s || !e) return;

              // Parse safely: "YYYY-MM-DD HH:MM"
              const parse = (str) => {
                const m = str.match(/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})$/);
                if (!m) return null;
                const [_, Y, M, D, H, I] = m.map(Number);
                return new Date(Y, M - 1, D, H, I, 0, 0);
              };

              const start = parse(s);
              const end = parse(e);
              if (!start || !end || end < start) {
                $('.total_seg').val('');
                return;
              }

              const diffMs = end - start;
              const diffSec = Math.floor(diffMs / 1000);
              const hours = Math.floor(diffSec / 3600);
              const mins = Math.floor((diffSec % 3600) / 60);

              // HH.MM format with zero-padded minutes
              const total = hours + '.' + String(mins).padStart(2, '0');
              $('.total_seg').val(total);
            });

        } else {
          showCustomAlert('jobcard Already Segregatated', 'info');
        }
      } else {
        showCustomAlert('Segregation only allowed for closed and completed jobcard and tablet Compression', 'error');
      }
    });



    $('.seg_save').click(function () {

      var jobid = $('.jobid').val();
      var $btn = $(this);
      $btn.prop('disabled', true);

      var url = "{{URL::to('segregationsave')}}/" + jobid;
      validationrule('segregation');
      var form = $('#segregation');
      form.parsley().validate();
      if (form.parsley().isValid()) {
        var formdata = $('#segregation').serialize();
        $.post(url, formdata, function (data) {
          if (data == 1) {
            showCustomAlert('Segregation Saved Successfully', 'success');
            $('#JobTbl').DataTable().ajax.reload();
            $('#segregationModal').modal('hide');
          }

        });
      }
    });


    // traydryer purpose
    // Traydryer action (DataTables version)
    $(document).on('click', '.traydryer-btn', function () {
      const dt = $('#JobTbl').DataTable();

      // Get the row data for the clicked button
      const $tr = $(this).closest('tr');
      const row = dt.row($tr).data();
      if (!row) {
        showCustomAlert('Unable to read row data.', 'error');
        return;
      }

      const jobstatus = row.job_status;
      const tray_status = row.traydryer_status;
      const jobid = row.w_jobs_hdr_id;
      const Proname = row.concatenated_product;
      const productid = row.product_id;
      const jobqty = row.job_adjusted_qty;
      const batchno = row.batch_no;
      const traydryer = row.traydryer;
      const jobdate = row.job_date;
      const jobprocess = row.job_process;

      if ((jobstatus == 'CLOSED' || jobstatus == 'COMPLETED') && traydryer == '1') {
        if (tray_status == null) {

          $('#traydryerModal').modal('show');
          $('.productid').val(productid);
          $('.jobid').val(jobid);
          $('.job_date').val(jobdate);
          $('.pro_name').val(Proname);
          $('.jobqty').val(jobqty);
          $('.batchno').val(batchno);

          try {
            $('.emp_start_date_tray').datetimepicker('remove');
          } catch (e) { }
          try {
            $('.emp_end_date_tray').datetimepicker('remove');
          } catch (e) { }

                const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
                const timeFormat = "HH:mm";


      $(document).on("focus", ".emp_start_date_tray", function () {
        $(this).datetimepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          timeFormat: timeFormat,
          controlType: 'select',
          oneLine: true,
          showSecond: true,
          minDate: new Date(2024, 3, 1),
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDateTime) {
            if (selectedDateTime) {
              const startDate = $(this).datetimepicker("getDate");

              // Reinitialize end_date_time with updated minDate
              $(".emp_end_date_tray").datetimepicker("destroy").datetimepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                timeFormat: timeFormat,
                controlType: 'select',
                oneLine: true,
                showSecond: true,
                minDate: startDate, // Disallow dates before start time
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });

          // Duration calculation (HH.MM) whenever start/end change
          $(document)
            .off('change.tray') // prevent duplicate handlers on repeated opens
            .on('change.tray', '.emp_start_date_tray, .emp_end_date_tray', function () {
              const s = $('.emp_start_date_tray').val();
              const e = $('.emp_end_date_tray').val();
              if (!s || !e) return;

              // Parse safely: "YYYY-MM-DD HH:MM"
              const parse = (str) => {
                const m = str.match(/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})$/);
                if (!m) return null;
                const [_, Y, M, D, H, I] = m.map(Number);
                return new Date(Y, M - 1, D, H, I, 0, 0);
              };

              const start = parse(s);
              const end = parse(e);
              if (!start || !end || end < start) {
                $('.total_tray').val('');
                return;
              }

              const diffMs = end - start;
              const diffSec = Math.floor(diffMs / 1000);
              const hours = Math.floor(diffSec / 3600);
              const mins = Math.floor((diffSec % 3600) / 60);

              // HH.MM format with zero-padded minutes
              const total = hours + '.' + String(mins).padStart(2, '0');
              $('.total_tray').val(total);
            });

        } else {
          showCustomAlert('jobcard Already added Dray Trayer', 'info');
        }
      } else {
        showCustomAlert('Tray Dryer only allowed for closed and completed jobcard and tablet Compression', 'error');
      }
    });



    $('.tray_save').click(function () {

      var jobid = $('.jobid').val();
      var $btn = $(this);
      $btn.prop('disabled', true);

      var url = "{{URL::to('traydryersave')}}/" + jobid;
      validationrule('traydryer');
      var form = $('#traydryer');
      form.parsley().validate();
      if (form.parsley().isValid()) {
        var formdata = $('#traydryer').serialize();
        $.post(url, formdata, function (data) {
          if (data == 1) {
            showCustomAlert('Tray Dryer Saved Successfully', 'success');
            $('#JobTbl').DataTable().ajax.reload();
            $('#traydryerModal').modal('hide');
          }

        });
      }
    });



    $('#qtyModal').on('shown.bs.modal', function () {

    // Disable line inputs initially
    $('.start_time, .end_time')
        .prop('disabled', true)
        .css('pointer-events', 'none');

});


$(document).on('change', '.type', function () {


    const type = $(this).val();
      console.log(type);
    if(type === 'SUB'){
        processStart = $('.job_date').val();
    }else{
        processStart = $('.process_start_date').datetimepicker('getDate');

    }
    // Enable start_time
    $('.start_time')
        .prop('disabled', false)
        .css('pointer-events', 'auto');

    // Initialize start_time picker
    $('.start_time').each(function () {

        try { $(this).datetimepicker('destroy'); } catch (e) {}

        $(this).datetimepicker({
            dateFormat: "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}",
            timeFormat: "HH:mm",
            controlType: 'select',
            oneLine: true,
            minDate: processStart,
            maxDate: new Date(),
            appendTo: '#qtyModal'
        });

    });

});

$(document).on('change', '.start_time', function () {

    const startDateTime = $(this).datetimepicker('getDate');
    if (!startDateTime) return;

    const $row = $(this).closest('tr');
    const $end = $row.find('.end_time');

    // Enable end_time
    $end.prop('disabled', false)
        .css('pointer-events', 'auto');

    // Calculate end of same day (24:00)
    let endOfDay = new Date(startDateTime);
    endOfDay.setHours(23, 59, 59, 999);

    try { $end.datetimepicker('destroy'); } catch (e) {}

    $end.datetimepicker({
        dateFormat: "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}",
        timeFormat: "HH:mm",
        controlType: 'select',
        oneLine: true,
        minDate: startDateTime,   // 2026-02-26 10:30
        maxDate: endOfDay,        // 2026-02-26 23:59
        appendTo: '#qtyModal'
    });

});


function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
        $(this).find('.line_no').val(index + 1);
    });
}


  // Function to initialize select2
function initSelect2($row) {
    $row.find('select.select2').each(function () {
        $(this).select2({
            dropdownParent: $('#qtyModal'), // IMPORTANT for modal
            width: '100%'
        });
    });
}


$(document).on('click', '.add-row', function () {

    const $tbody   = $('.clone_lines_body');
    const $lastRow = $tbody.find('tr:last');
    const $newRow  = $lastRow.clone(false, false);

    /* =======================================================
       1️⃣ CLEAR INPUT VALUES
    ======================================================= */
    $newRow.find('input').not('.bulk_line_no').val('');

    /* =======================================================
       2️⃣ DESTROY & CLEAN SELECT2
    ======================================================= */
    $newRow.find('select.select2').each(function () {

        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }

        $(this).val('');
    });

    $newRow.find('span.select2').remove();

    /* =======================================================
       3️⃣ REMOVE DATEPICKER STATE COMPLETELY
    ======================================================= */
    $newRow.find('.start_time, .end_time').each(function () {

        try { $(this).datetimepicker('destroy'); } catch (e) {}

        $(this)
            .removeClass('hasDatepicker')
            .removeAttr('id')
            .val('')
            .prop('disabled', true)
            .css('pointer-events', 'none');
    });

    /* =======================================================
       4️⃣ REMOVE DUPLICATE IDS
    ======================================================= */
    $newRow.find('[id]').each(function () {
        this.id = this.id + '_' + Date.now();
    });

    /* =======================================================
       5️⃣ APPEND NEW ROW
    ======================================================= */
    $tbody.append($newRow);

    /* =======================================================
       6️⃣ RE-INITIALIZE SELECT2
    ======================================================= */
    initSelect2($newRow);

    /* =======================================================
       7️⃣ RE-APPLY PROCESS START LOGIC
    ======================================================= */
    const processStart = $('.process_start_date').datetimepicker('getDate');

    if (processStart) {

        $newRow.find('.start_time')
            .prop('disabled', false)
            .css('pointer-events', 'auto')
            .datetimepicker({
                dateFormat: "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}",
                timeFormat: "HH:mm",
                controlType: 'select',
                oneLine: true,
                minDate: processStart,
                maxDate: new Date(),
                appendTo: '#qtyModal'
            });

    }

    /* =======================================================
       8️⃣ UPDATE LINE NUMBERS
    ======================================================= */
    updateLineNumbers();

});



    // Remove button
    $(document).on('click', '.remove-row', function () {
      const rowCount = $('.clone_lines_body tr').length;
      if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
      } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
      }
    });


    $(document).ready(function () {

      $('#qtyModal').on('shown.bs.modal', function () {

        $('.select2').select2({
          dropdownParent: $('#qtyModal'),
          width: '100%',

        });

      });

    });

  </script>


@endpush