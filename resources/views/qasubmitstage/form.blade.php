@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Job Card Completion</h3>
    @include('layouts.breadcrumb')
    <style>
        .veri,
        .qa_status,
        .qatrx_date,
        .jobcard_qty,
        .job_date,
        .organization_id,
        .job_no,
        .pdtdiv,
        .uomdiv,
        .bulk_scrap_qty,
        .bulk_qty,
        .product_id,
        .batch_no,
        .uom_code_id {
            pointer-events: none;
        }

        <?php if ($pageMethod == "packingqasubmitstage") {
                ?>
        .qa {
            display: none;
        }

        .pqar {
            pointer-events: none;
        }

        <?php } ?>
        <?php if ($pageMethod == "qasubmitstage") {
                ?>
        .pqa {
            display: none;
        }

        <?php } ?>

        .select2-container--open {
            z-index: 200000 !important;
        }
    </style>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold"></div>
        <div class="card-body card-block">


            <form method="post" action="" id="qasubmitstage" data-parsley-validate>
                <input type="hidden" value="" name="save_status" id="save_status" />
                {{ csrf_field()}}

                <div class="row">

                    <div class="col-md-4">
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Reference No</label>

                            <div class="col-md-7">
                                <input class="form-control qa_submitstage_trx_hdr_id" id="qa_submitstage_trx_hdr_id"
                                    name="qa_submitstage_trx_hdr_id" size="16" type="hidden" value="" readonly>
                                <input type="text" id="reference_no" name="reference_no" class="form-control reference_no"
                                    value="{{$row->reference_no}}" row="5" readonly style="width:100%;">
                            </div>

                        </div>

                        <div class="row mb-3 none">
                            <label for="inputIsValid" class="col-form-label col-md-5">Job No</label>

                            <div class="col-md-7">
                                <input type="hidden" class="form-control w_jobs_hdr_id" id="w_jobs_hdr_id"
                                    value="{{$row->w_jobs_hdr_id}}" />
                                <input type="hidden" name="pagemode" class="form-control " id="" value="{{$pageMethod}}" />
                                <select name='job_no' rows='5' class='form-control job_no select2' id="job_no"
                                    data-show-subtext="true" data-live-search="true">
                                    {!! $job_no !!}
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3 none">
                            <label for="Product" class="col-form-label col-md-5">Product</label>
                            <div class="col-md-7">

                                <select name="product_id" id="product_id" class="form-control product_id select2"
                                    required="required">
                                    {!! $product_id !!}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 none">
                            <label for="active" class="col-form-label col-md-5">Batch No</label>
                            <div class="col-md-7">
                                <input type="text" name='batch_no' rows='5' class='form-control batch_no' id="batch_no"
                                    data-show-subtext="true" data-live-search="true" value="{!! $batch_no !!}">


                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="active" class="col-form-label col-md-5"><span style="color:red;">*</span>Quality
                                Check</label>
                            <div class="col-md-7">
                                <select name='quality_check' rows='5' class='select2 quality_check select2'
                                    id="quality_check" data-show-subtext="true" data-live-search="true" required>
                                    <option value="">--Please Select--</option>
                                    <option value="Yes" <?php if ($row->quality_check == 'Yes') {
        echo "selected";
    } ?>>Yes
                                    </option>
                                    <option value="No" <?php if ($row->quality_check == 'No') {
        echo "selected";
    } ?>>No
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span style="color:red;">*</span>EMP
                                Working Hrs</label>

                            <div class="col-md-5">

                                <input type="text" id="total_working_hrs" name="total_working_hrs"
                                    class="form-control total_working_hrs" value="" row="5" readonly style="width:100%;"
                                    required>

                            </div>
                            <div class="col-md-2 showinline">


                                <span class="showspan employeedetails" title="Employee Details"> <i class="fa fa-plus"></i>
                                </span>
                            </div>

                        </div>
                        <?php // if($pageurl=="qasubmitstage"){ ?>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Manufacturer Date</label>
                            <div class="col-md-7">
                                <input type="text" id="manufacturer_date" name="manufacturer_date"
                                    class="form-control manufacturer_date" value="{{$row->manufacturer_date}}" row="5"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Product Expire Date</label>
                            <div class="col-md-7">
                                <input type="text" id="product_expire_date" name="product_expire_date"
                                    class="form-control product_expire_date datepicker"
                                    value="{{$row->product_expire_date}}" row="5">
                            </div>
                        </div>
                        <?php // } ?>
                    </div>


                    <div class="col-md-4">
                        <div class="row mb-3">
                            <label for="qatrx_date" class="col-form-label col-md-5">Qa Trx Date</label>
                            <div class="col-md-7">

                                <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                                    data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width: 100%;">
                                    <input class="form-control qatrx_date datepicker" id="qatrx_date" name="qatrx_date"
                                        size="16" type="text" value="{{$row->qatrx_date}}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="start_date" class="col-form-label col-md-5">Job Date</label>
                            <div class="col-md-7">
                                <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                                    data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control job_date datepicker" id="job_date" name="job_date" size="16"
                                        type="text" value="{{ $row->job_date}}">
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3 none">
                            <label for="Uom" class="col-form-label col-md-5">Uom Code</label>
                            <div class="col-md-7">
                                <select name="uom_code_id" id="uom_code_id" class=" form-control uom_code_id select2">
                                    {!! $uom_code_id !!}
                                </select>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="active" class="col-form-label col-md-5"><span style="color:red;">*</span>Activity
                                in-charge</label>
                            <div class="col-md-7 veri">

                                <select name='verifier' rows='5' class='form-control select2 verifier'
                                    data-show-subtext="true" data-live-search="true" required readonly>
                                    {!! $verifier !!}

                                </select>
                            </div>
                        </div>
                        <?php if ($pageurl == "qasubmitstage") { ?>
                        <div class="row mb-3">
                            <label for="active" class="col-form-label col-md-5"><span style="color:red;">*</span>Store
                                Move</label>
                            <div class="col-md-7">
                                <select name='store_move' rows='5' class='select2 store_move select2' id="store_move"
                                    data-show-subtext="true" data-live-search="true" required="true">
                                    <option value="">--Please Select--</option>
                                    <option value="Yes" <?php    if ($row->store_move == 'Yes') {
            echo "selected";
        } ?>>Yes
                                    </option>
                                    <option value="No" <?php    if ($row->store_move == 'No') {
            echo "selected";
        } ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Start Time</label>
                            <div class="col-md-7">
                                <input type="text" id="from_time" name="from_time" class="form-control from_time"
                                    value="{{$row->from_time}}" row="5">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">End Time</label>
                            <div class="col-md-7">
                                <input type="text" id="to_time" name="to_time" class="form-control to_time"
                                    value="{{$row->to_time}}" row="5">
                            </div>
                        </div>

                        <!--Vj - machine name add-->
                        <?php if ($pageurl == "qasubmitstage") { ?>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Machine Duration</label>

                            <div class="col-md-5">

                                <input type="text" id="total_machine_working_hrs" name="total_machine_working_hrs"
                                    class="form-control total_machine_working_hrs" value="" row="5" readonly
                                    style="width:100%;" required>

                            </div>
                            <div class="col-md-2 showinline">


                                <span class="showspan machinedetails" title="Machine Details"> <i class="fa fa-plus"></i>
                                </span>
                            </div>

                        </div>
                        <?php } ?>

                    </div>

                    <div class="col-md-4">
                        <div class="row mb-3 none">
                            <label for="inputIsValid" class="col-form-label col-md-5">Qa Status</label>

                            <div class="col-md-7">
                                <select name='qa_status' rows='5' class='form-control qa_status select2' id="qa_status"
                                    data-show-subtext="true" data-live-search="true">
                                    {!! $qa_status !!}
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Jobcard Qty</label>
                            <div class="col-md-7">
                                <input type="text" id="jobcard_qty" name="jobcard_qty" class="form-control jobcard_qty"
                                    value="{{$row->jobcard_qty}}" row="5">
                                <input type="hidden" id="machineid" name="machineid" class="form-control machineid"
                                    value="{{$row->machine_id}}" row="5">
                            </div>
                        </div>
                        <?php if ($pageurl == "packingqasubmitstage") {
        foreach ($prcs as $k => $v) { ?>

                        <div class="row mb-3">
                            <label for="Required Qty" class="col-form-label col-md-5"> <?php        echo $v->job_process; ?>
                            </label>
                            <div class="col-md-7">
                                <input type="text" name=""
                                    class="form-control input-sm moveqty moveqty<?php        echo $v->job_process; ?> input_qty_width"
                                    value="{{$v->qoh}}" readonly>
                            </div>

                        </div>
                        <?php    }
    } ?>
                        <div class="row mb-3">
                            <label for="Required Qty" class="col-form-label col-md-5"><span style="color:red;">*</span>
                                Production Qty</label>
                            <div class="col-md-7">
                                <input type="text" name="production_qty"
                                    class="form-control input-sm production_qty input_qty_width"
                                    value="{{$row->production_qty}}" required="required">
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Remarks</label>
                            <div class="col-md-7">
                                <input type="text" id="remarks" name="remarks" class="form-control remarks"
                                    value="{{$row->remarks}}" style="width: 100%;">
                            </div>
                        </div>
                        <?php if ($pageMethod != "packingqasubmitstage") {
                                ?>
                        <div class="row mb-3">
                            <label for="moveto_subinventory" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Move to Shopfloor</label>
                            <div class="col-md-7">
                                <select name='moveto_subinventory' rows='5' class='select2 moveto_subinventory'
                                    id="moveto_subinventory" data-show-subtext="true" data-live-search="true"
                                    required="required">
                                    <option value="">--Please Select--</option>
                                    <option value="Yes" <?php    if ($row->moveto_subinventory == 'Yes') {
            echo "selected";
        } ?>>
                                        Yes
                                    </option>
                                    <option value="No" <?php    if ($row->moveto_subinventory == 'No') {
            echo "selected";
        } ?>>No
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 sub">
                            <label for="subinventory_id" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Subinventory</label>
                            <div class="col-md-7">
                                <select name='subinventory_id' rows='5' class='select2 subinventory_id' id="subinventory_id"
                                    data-show-subtext="true" data-live-search="true">
                                    {!! $subinventory_id !!}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 subloc">
                            <label for="sublocator_id" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Sublocator</label>
                            <div class="col-md-7">
                                <select name='sublocator_id' rows='5' class='select2 sublocator_id' id="sublocator_id"
                                    data-show-subtext="true" data-live-search="true">
                                    {!! $sublocator_id !!}
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <br><br><br><br>
                    </div>

                </div>


                <div class="row mt-4">
                    <div class="col-md-12">
                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table" style="width: 130%;">

                                <thead class="table-light">

                                    <tr>

                                        <th>Line No</th>
                                        <th>Product </th>
                                        <th>Uom Code</th>
                                        <?php if ($pageMethod == "packingqasubmitstage") {
                                                ?>
                                        <th>Process</th>
                                        <?php } ?>
                                        <th>Material Issued Qty</th>
                                        <th>Production Qty</th>
                                        <th class="pqa">Add Return Qty</th>
                                        <th>Return Qty</th>
                                        <th>Exceed Qty</th>
                                        <th>Scrap Qty</th>
                                        <th class="qa">Batch Number
                                        <th>
                                        <th class="qa">Subinventory</th>
                                        <th class="qa">Locator</th>
                                        <th>Comments</th>


                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">


                                    <?php if (count($linedata) >= 1) {
                                            ?>
                                    @foreach($linedata as $key => $value)
                                        <tr class="clone ">
                                            <td>
                                                <input type="hidden" name="bulk_qa_submitstage_trx_line_id[]"
                                                    class="form-control input-sm bulk_qa_submitstage_trx_line_id" value="">
                                                <input type="hidden" class="form-control input-sm bulk_compqty"
                                                    value="{{ $value->compqty}}">
                                                <input type="text" name="bulk_line_no[]"
                                                    class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                                                    readonly="readonly">
                                            </td>
                                            <td class="pdtdiv">
                                                <select name="bulk_product_id[]" id="bulk_product_id"
                                                    class="bulk_product_id select2" required="required">{!! $value->product_id
                                                                !!}</select>
                                            </td>

                                            <td class="uomdiv">
                                                <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                    class="select2 bulk_uom_code_id" data-show-subtext="true"
                                                    data-live-search="true" readonly>
                                                    {!! $value->uom_code_id !!}

                                                </select>
                                            </td>
                                            <?php        if ($pageMethod == "packingqasubmitstage") {
                                                            ?>
                                            <td>
                                                <input type="text" name="bulk_process_level[]"
                                                    class="form-control input-sm bulk_process_level process_level_width"
                                                    value="{{ $value->process_level}}" readonly>
                                            </td>
                                            <?php        } ?>
                                            <td>
                                                <input type="text" name="bulk_qty[]"
                                                    class="form-control input-sm bulk_qty input_qty_width"
                                                    value="{{ $value->qty }}">
                                            </td>
                                            <?php        if ($pageMethod == "packingqasubmitstage") {
                                                            ?>
                                            <td>
                                                <input type="text" name="bulk_production_qty[]"
                                                    class="form-control input-sm bulk_production_qty input_qty_width"
                                                    value="{{ $value->production_qty }}" required="required">
                                            </td>
                                            <?php        } else { ?>
                                            <td>
                                                <input type="text" name="bulk_production_qty[]"
                                                    class="form-control input-sm bulk_production_qty input_qty_width" value=""
                                                    required="required">
                                            </td>
                                            <?php        } ?>

                                            <td class="pqa"><a href="#" class="subinvdetails" title="Add Return Qty"> <i
                                                        class="fa fa-plus"></i></a>
                                                <input type="hidden" class="bulk_subinventoryid" name="bulk_subinventoryid[]"
                                                    value="">
                                                <input type="hidden" class="bulk_batchnum" name="bulk_batchnum[]" value="">
                                                <input type="hidden" class="bulk_locatorid" name="bulk_locatorid[]" value="">
                                                <input type="hidden" class="bulk_returnqty" name="bulk_returnqty[]" value="">
                                                <input type="hidden" class="bulk_issuedqty" name="bulk_issuedqty[]" value="">
                                            </td>
                                            <td class="pqar">
                                                <input type="text" name="bulk_return_qty[]"
                                                    class="form-control input-sm bulk_return_qty input_qty_width"
                                                    value="{{$value->return_qty}}" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_exceed_qty[]"
                                                    class="form-control input-sm bulk_exceed_qty input_qty_width"
                                                    value="{{$value->exceed_qty}}" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_scrap_qty[]"
                                                    class="form-control input-sm bulk_scrap_qty input_qty_width" value="">
                                            </td>
                                            <td class="qa">
                                                <select name="bulk_batchno[]" id="bulk_batchno" class="select2  bulk_batchno">
                                                    {!! $value->batchno !!}

                                                </select>
                                            </td>
                                            <td></td>
                                            <td class="qa">
                                                <select name="bulk_subinventory_id[]" id="bulk_subinventory_id"
                                                    class="select2  bulk_subinventory_id">
                                                    {!! $value->subinventory_id !!}

                                                </select>
                                            </td>
                                            <td class="qa">
                                                <select name="bulk_sublocator_id[]" id="bulk_sublocator_id"
                                                    class="select2 bulk_sublocator_id">
                                                    {!! $value->locator_id !!}
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_comments[]"
                                                    class="form-control input-sm bulk_comments" value="{{ $value->comments }}">
                                            </td>

                                        </tr>
                                    @endforeach
                                    <?php } ?>

                                </tbody>
                            </table>
                            <input type="hidden" name="enable-masterdetail" value="true">
                        </div>
                        <!--*******************-Linedata End*******************************-->
                    </div>
                </div>


                <div class="row mt-4 mb-3">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">
                            <button id="btn" type="button" class="btn btn-success saveform px-4 me-2"
                                value="SAVE">Submit</button>
                            <a href="{{ URL::to($pageurl) }}" class='btn btn-danger px-4'>Cancel</a>
                        </div>
                    </div>
                </div>


                <!-- Employee Details Modal -->
                <div class="modal fade" id="employeedetailsModal" tabindex="-1" aria-labelledby="employeedetailsLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content rounded-3 shadow-lg border-0">

                            <!-- Modal Header -->
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="employeedetailsLabel">
                                    <i class="fa fa-users me-2"></i> Employee Details
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <input type="hidden" class="soindex" value="">
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body sodetail">
                                <div class="row">
                                    <div class="col-12">
                                        <!-- Placeholder for dynamic content -->
                                    </div>
                                </div>
                            </div>

                            <!-- Optional New Item Button -->
                            <?php if ($pageurl != "packingqasubmitstage") { ?>
                            <div class="text-left">
                                <button type="button" class="btn btn-primary btn-sm addrow_empdetails">
                                    <i class="fas fa-plus-circle"></i> Add Row
                                </button>
                            </div>
                            <?php } ?>

                            <!-- Action Button -->
                            <div class="text-center mb-3">
                                <button type="button" class="btn btn-success px-4 py-2 employeee" id="employee">
                                    <i class="fa fa-clock me-2"></i> Add Employee Working Hrs
                                </button>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fa fa-times me-1"></i> Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden" class="form-control input-sm jobassigned_to" value="">
                <input type="hidden" class="form-control input-sm proceslevel" value="">
                <input type="hidden" class="form-control input-sm processname" value="">
                <input type="hidden" class="form-control input-sm actualhrs" value="">
                <input type="hidden" class="form-control input-sm starttime" value="">
                <input type="hidden" class="form-control input-sm endtime" value="">
                <input type="hidden" class="form-control input-sm workinghrs" value="">
                <input type="hidden" class="form-control input-sm empstatus" value="0">
                <input type="hidden" class="form-control input-sm employee_qty" value="">


    <!-- Machine Details Modal -->
    <div class="modal fade" id="machinedetailsModal" tabindex="-1" aria-labelledby="machineLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- 95% ~ modal-xl -->
            <div class="modal-content rounded-3 shadow-lg border-0">

                <!-- Header -->
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="machineLabel">
                        <i class="fa fa-cogs me-2"></i> Machine Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <input type="hidden" class="somacindex" value="">
                </div>

                <!-- Body -->
                <div class="modal-body somacdetail">
                    <div class="row">
                        <div class="col-12"><!-- Dynamic content --></div>
                    </div>
                </div>

                <!-- New Item Button -->
                <?php if ($pageurl != "packingqasubmitstage") { ?>
                <div class="text-left">
                    <button type="button" class="btn btn-secondary btn-sm addrow_macdetails">
                        <i class="fas fa-plus-circle"></i> Add Row
                    </button>
                </div>
                <?php } ?>

                <!-- Action Button -->
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-success px-4 py-2 machine" id="machine">
                        <i class="fa fa-clock me-2"></i> Add Machine Working Hrs
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Machine Modal -->


            </form>
        </div>
    </div>

    <!-- Save Confirmation Modal -->
    <div class="modal fade" id="savedetailsModal" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
        <div class="modal-dialog modal-md"> <!-- 40% ~ modal-md -->
            <div class="modal-content rounded-3 shadow-lg border-0">

                <!-- Modal Header -->
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="saveLabel">
                        <i class="fa fa-exclamation-triangle me-2"></i> Save Confirmation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" class="soindex" value="">
                </div>

                <!-- Modal Body -->
                <div class="modal-body text-center">
                    <h5 class="fw-normal">
                        After submit, you cannot change job completion details.
                        <br><br>
                        Do you want to save?
                    </h5>
                </div>

                <!-- Actions -->
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-success me-2 savedata" id="savedata">
                        <i class="fa fa-check me-1"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="canceldata">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Save Modal -->


    <!-- Subinventory Details Modal -->
    <div class="modal fade" id="subinvdetailsModal" tabindex="-1" aria-labelledby="subinvLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- 80% ~ modal-xl -->
            <div class="modal-content rounded-3 shadow-lg border-0">

                <!-- Header -->
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="subinvLabel">
                        <i class="fa fa-boxes me-2"></i> Batch Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <input type="hidden" class="btindex" value="">
                </div>

                <!-- Body -->
                <div class="modal-body btdetail text-center">
                    <!-- Dynamic content -->
                </div>

                <!-- Extra buttons -->
                <div class="px-4 pb-3">
                    <a href="javascript:void(0);" class="btn btn-primary add_row2 additem newitem" rel=".clone2">
                        <i class="fa fa-plus me-1"></i> New Item
                    </a>
                </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-success mtlisqty" id="mtlisqty">
                        <i class="fa fa-plus-circle me-2"></i> Add Return Qty
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Subinventory Modal -->


@endsection
@push('scripts')

    <script>

        // Add Row
        $(document).on('click', '.add-row', function () {
            const $lastRow = $('.clone_lines_body tr:last');
            const $newRow = $lastRow.clone(false, false); // clone without events or data

            // Clear all input and select values in the cloned row
            $newRow.find('input').val('');
            $newRow.find('select').val('').trigger('change');

            // Remove any Select2 artifacts before reinitializing
            $newRow.find('select.select2').each(function () {
                if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2('destroy');
                }
                $(this).removeAttr('data-select2-id');
                $(this).next('.select2').remove(); // remove the select2 container
            });

            // Append the cleaned-up cloned row
            $('.clone_lines_body').append($newRow);

            // Reinitialize select2
            $newRow.find('select.select2').select2({ width: '100%' });

            // Update line numbers
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

        // Renumber Line Nos
        function updateLineNumbers() {
            $('.clone_lines_body tr').each(function (index) {
                $(this).find('.bulk_line_no').val(index + 1);
            });
        }

        function updateLineNumbers1() {
            $('.emp_lines_body tr').each(function (index) {
                $(this).find('.line_no').val(index + 1);
            });
        }

        $(document).ready(function () {
            const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
            const timeFormat = "HH:mm:ss";


            $(document).on("focus", ".from_time", function () {
                $(this).datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: dateFormat,
                    timeFormat: timeFormat,
                    controlType: 'select',
                    oneLine: true,
                    showSecond: true,
                    showAnim: "slideDown",
                    yearRange: "-25:+0",
                    onClose: function (selectedDateTime) {
                        if (selectedDateTime) {
                            const startDate = $(this).datetimepicker("getDate");

                            // Reinitialize end_date_time with updated minDate
                            $(".to_time").datetimepicker("destroy").datetimepicker({
                                changeMonth: true,
                                changeYear: true,
                                dateFormat: dateFormat,
                                timeFormat: timeFormat,
                                controlType: 'select',
                                oneLine: true,
                                showSecond: true,
                                minDate: startDate,
                                showAnim: "slideDown",
                                yearRange: "-25:+0"
                            });
                        }
                    }
                });
            });
        });


        $(document).on("focus", ".product_expire_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });
        /***************To check employee Already Selected (QASubmit)***********/
        function qaempcheck(job_assigned_to, index) {
            var empcount = 0;
            $('.clone').each(function (ind, v) {
                var val = $(".job_assigned_to" + ind).val();
                if (index != ind) {
                    if (val == job_assigned_to) {
                        empcount++;
                    }
                }
            });
            return empcount;
        }


        $(document).ready(function () {




            $(".hove").hover(function () {
                var data = $(".product_id option:selected").text();
                $(this).css('cursor', 'pointer').attr('title', data);
            });
            <?php if ($pageMethod == "packingqasubmitstage") { ?>
            $('.production_qty').attr('readonly', true);
            <?php } ?>


            $('.subinvdetails').click(function () {

                $('#subinvdetailsModal').modal('show');
                var index = $(this).closest('tr').index();

                $('.btindex').val(index);

            });



            $('#subinvdetailsModal').on('shown.bs.modal', function () {

                var index = $('.btindex').val();
                var $row = $('.clone_lines_body tr').eq(index);

                var product = $row.find('.bulk_product_id').val();
                var jobid = $('.w_jobs_hdr_id').val();

                $.get(
                    "{{ URL::to('prdmtlsubinventorydetails') }}",
                    { index: index, product: product, jobid: jobid },
                    function (data) {

                        $('.btdetail').html(data);

                        var subinv = $row.find('.bulk_subinventoryid').val();
                        var batchno = $row.find('.bulk_batchnum').val();
                        var loc = $row.find('.bulk_locatorid').val();
                        var issqty = $row.find('.bulk_returnqty').val();
                        var mtlqty = $row.find('.bulk_issuedqty').val();

                        if (subinv) {

                            setTimeout(function () {

                                var subinventory = subinv.split(',');
                                var batch = batchno.split(',');
                                var locator = loc.split(',');
                                var returnqty = issqty.split(',');
                                var issuedqty = mtlqty.split(',');

                                $.each(subinventory, function (i, val) {

                                    if (i !== 0) {
                                        $('.add_row2').trigger('click');
                                    }

                                    $('.subinventoryid').eq(i).val(val);
                                    $('.batchnumber').eq(i).val(batch[i]);
                                    $('.locatorid').eq(i).val(locator[i]);
                                    $('.mtlqty').eq(i).val(returnqty[i]);
                                    $('.mtlissueqty').eq(i).val(issuedqty[i]);

                                });

                            }, 300);
                        }
                    }
                );
            });


            $(document).off('click', '.mtlisqty').on('click', '.mtlisqty', function () {

                var qty = 0;
                var returnQtyArr = [];
                var subinvArr = [];
                var locatorArr = [];
                var batchArr = [];
                var issuedQtyArr = [];
                var checkQty = 0;
                var checkIss = 0;

                $('.mtlqty').each(function (i) {

                    var rl = parseFloat($(this).val()) || 0;
                    var iss = parseFloat($('.mtlissueqty').eq(i).val()) || 0;

                    var sub = $('.subinventoryid').eq(i).val();
                    var loc = $('.locatorid').eq(i).val();
                    var bat = $('.batchnumber').eq(i).val();

                    // qty validation
                    if (rl <= 0) {
                        checkQty = 1;
                    }

                    // issued qty validation
                    if (iss <= 0) {
                        checkIss = 1;
                    }

                    if (rl > 0) {
                        qty += rl;
                        returnQtyArr.push(rl);
                        subinvArr.push(sub);
                        locatorArr.push(loc);
                        batchArr.push(bat);
                        issuedQtyArr.push(iss);
                    }
                });


                if (checkQty === 1 || checkIss === 1) {
                    showCustomAlert("Please enter Return Qty and Issued Qty", 'error');
                    return false;
                }

                var index = $('.btindex').val();
                var $row = $('.clone_lines_body tr').eq(index);

                $row.find('.bulk_return_qty').val(qty).trigger('change');
                $row.find('.bulk_subinventoryid').val(subinvArr.join(','));
                $row.find('.bulk_locatorid').val(locatorArr.join(','));
                $row.find('.bulk_batchnum').val(batchArr.join(','));
                $row.find('.bulk_returnqty').val(returnQtyArr.join(','));
                $row.find('.bulk_issuedqty').val(issuedQtyArr.join(','));


                $('#subinvdetailsModal').modal('hide');
            });




            /* purpose:to fetch material issue data based on batch number*/
            $(document).on('change', '.batchnumber', function () {

                var $row = $(this).closest('tr');
                var index = $row.index();
                var batch = $(this).val();
                var lineIx = $('.index1').val();

                if (!batch) return;
                console.log(batch, index);


                var productid = $('.clone_lines_body tr').eq(lineIx).find('.bulk_product_id').val();
                var jobid = $('.job_no').val();

                var url = "{{ URL::to('mtlbatchdetails') }}/" + productid +
                    "?jobid=" + jobid + "&batch=" + batch;

                $.get(url, function (data) {
                    $row.find('.mtlissueqty').val($.trim(data));
                });
            });
            $(document).on('change', '.subinventoryid', function () {

                var $row = $(this).closest('tr');
                var index = $row.index();

                var subid = $(this).val();
                var batch = $row.find('.batchnumber').val();
                var lineIx = $('.index1').val();
                var product = $('.clone_lines_body tr').eq(lineIx).find('.bulk_product_id').val();
                var jobid = $('.w_jobs_hdr_id').val();

                if (!subid) return;

                var condition = "subinventory_id=" + subid;

                if (subid == "5") {
                    condition += " and sublocator_id in(191,192)";
                }

                var url = "{{ URL::to('jcomboform') }}" +
                    "?table=m_sublocators_t:sublocator_id:locator_code" +
                    "&parent=" + condition +
                    "&order_by=locator_code asc";

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {

                        if (typeof data === "string") {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON:", data);
                                return;
                            }
                        }

                        var $locator = $row.find('.locatorid');

                        $locator.empty().append('<option value="">-- Select Locator --</option>');

                        $.each(data, function (_, item) {
                            $locator.append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        // Refresh Select2 safely
                        if ($locator.hasClass('select2-hidden-accessible')) {
                            $locator.trigger('change.select2');
                        }
                    }
                });
            });



            /* purpose:hide show fields based on movetosubinventory(yes/No)*/
            var moveto_subinventory = $('.moveto_subinventory').val();
            if (moveto_subinventory == 'Yes') {
                $('.sub,.subloc').show();
                $('.subinventory_id,.sublocator_id').attr('required', 'required');
            } else {
                $('.sub,.subloc').hide();
                $('.subinventory_id,.sublocator_id').prop('required', false);
            }

            $(document).on('change', '.moveto_subinventory', function () {
                // kaviya pupose quality and store move disable 
                var moveto_subinventory = $(this).val();
                var quality = $(".quality_check").val();
                var storemove = $(".store_move").val();
                if (moveto_subinventory == 'Yes') {
                    $('.sub,.subloc').show();
                    $('.subinventory_id,.sublocator_id').attr('required', 'required');
                    $(".store_move option[value='Yes']").prop("disabled", "disabled");
                } else if (moveto_subinventory == 'No' && (storemove == "")) {

                    $('.sub,.subloc').hide();
                    $('.subinventory_id,.sublocator_id').prop('required', false);
                    $('.store_move option[value="Yes"]').remove();
                    $('.store_move option:first').after('<option value="Yes">Yes</option>');
                } else if (moveto_subinventory == 'No') {

                    $('.sub,.subloc').hide();
                    $('.subinventory_id,.sublocator_id').prop('required', false);
                    $('.moveto_subinventory option[value="Yes"]').remove();
                    $('.moveto_subinventory option:first').after('<option value="Yes">Yes</option>');
                }
            });

            //  pupose quality and move to subinventory disable 
            $('.store_move').change(function (data) {
                var qc = $(this).val();
                var msub = $(".moveto_subinventory").val();
                if (qc == 'Yes') {
                    $(".moveto_subinventory option[value='Yes']").attr("disabled", true);
                } else if ((qc == 'No') && (msub == "")) {

                    $('.subinventory_id,.sublocator_id').prop('required', false);
                    $('.moveto_subinventory option[value="Yes"]').remove();
                    $('.moveto_subinventory option:first').after('<option value="Yes">Yes</option>');
                    $('.sub,.subloc').hide();
                    $('.subinventory_id,.sublocator_id').prop('required', false);
                } else if ((qc == 'No') && (msub == "No")) {

                    $('.subinventory_id,.sublocator_id').prop('required', false);
                    $('.moveto_subinventory option[value="Yes"]').remove();
                    $('.moveto_subinventory option:first').after('<option value="Yes">Yes</option>');
                    $('.sub,.subloc').hide();
                    $('.subinventory_id,.sublocator_id').prop('required', false);
                }
            });

            // JS
            $(document).on('change', '.subinventory_id', function () {
                const subinv = $(this).val();
                const $subloc = $('.sublocator_id');

                // reset & disable while loading
                $subloc.prop('disabled', true)
                    .html('<option value="">-- Select Sublocator --</option>');

                if (!subinv) return;

                // Build the "parent" condition the same way your PHP does
                // (Blade will render this server-side once)
                <?php if ($pageMethod === "packingqasubmitstage") { ?>
                var cond = "subinventory_id=" + subinv + " and sublocator_id in(191,192)";
                <?php } else { ?>
                var cond = "subinventory_id=" + subinv + " and sublocator_id in(189,194)";
                <?php } ?>

                // Compose URL (same params you passed to jCombo)
                // NOTE: encode the parent condition to be safe in the querystring.
                const url = "{{ URL::to('jcomboform') }}" +
                    "?table=m_sublocators_t:sublocator_id:locator_code" +
                    "&parent=" + encodeURIComponent(cond) +
                    "&order_by=" + encodeURIComponent("locator_code asc");

                $.ajax({
                    url: url,
                    type: 'GET',
                    // if your endpoint already returns JSON, keep this:
                    dataType: 'json',
                    success: function (data) {
                        // If the endpoint sometimes returns a string, make it robust:
                        if (typeof data === 'string') {
                            try { data = JSON.parse(data); }
                            catch (e) {
                                console.error('Invalid JSON:', data);
                                return;
                            }
                        }

                        // Expected shape: [{ val: "123", option_name: "LOC-001" }, ...]
                        // (matches how you handled .machine_id)
                        $subloc.html('<option value="">-- Select Sublocator --</option>');

                        $.each(data, function (i, item) {
                            // if you have a saved value to preselect, swap it in here:
                            let selected = item.val == "{{ $row->sublocator_id ?? '' }}" ? 'selected' : '';
                            $subloc.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                        });

                        $subloc.prop('disabled', false).trigger('change.select2'); // if using Select2
                    },
                    error: function (xhr) {
                        console.error('Load sublocators failed:', xhr.responseText || xhr.statusText);
                        // keep disabled, but you could also show a toast here
                    }
                });
            });


            /* purpose:qty validation*/

            $(document).on('keypress', '.production_qty,.bulk_production_qty,.emp_qty,.bulk_exceed_qty,.bulk_return_qty,.actual_hrs,.working_hrs', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });

            /* purpose:based on qty,scrap,exceed,return qty calculation */

$(document).on('change', '.production_qty', function () {
    var qty = parseFloat($(this).val()) || 0;
    var decimalpoint = parseInt("<?php echo \Session::get('decimal') ?>") || 2;
    var qtyvalid = "<?php echo $qtyvalid; ?>";

    $('.bulk_compqty').each(function (i) {

        var compqty = parseFloat($(this).val()) || 0;
        var prodqty = (qty * compqty).toFixed(decimalpoint);

        var issuedqty = parseFloat($('.bulk_qty').eq(i).val()) || 0;

        if (qtyvalid == "") {
            if (issuedqty >= parseFloat(prodqty)) {
                $('.bulk_production_qty').eq(i).val(prodqty);
            } else {
                showCustomAlert('Production qty should not exceed Material issued Qty...', 'warning');
                $('.bulk_production_qty').eq(i).val('');
                return; // stop further calc for this row
            }
        }

        if (parseFloat(prodqty) == issuedqty) {
            $('.bulk_exceed_qty').eq(i).val('0');
            $('.bulk_return_qty').eq(i).val('0');
            $('.bulk_scrap_qty').eq(i).val('0').prop('readonly', true);
        } else {
            var scrap = issuedqty - parseFloat(prodqty);
            var scrap1 = scrap > 0 ? scrap.toFixed(decimalpoint) : 0;

            $('.bulk_exceed_qty').eq(i).val('0');
            $('.bulk_return_qty').eq(i).val('0');
            $('.bulk_scrap_qty').eq(i).val(scrap1).prop('readonly', false);
        }
    });
});


            /* purpose:based on all qty,scrap,exceed,return qty calculation */
            $(document).on('change', '.bulk_exceed_qty, .bulk_return_qty, .bulk_production_qty', function () {

                var $row = $(this).closest('tr');

                var materialissue = parseFloat($row.find('.bulk_qty').val()) || 0;
                var exceed_qty = parseFloat($row.find('.bulk_exceed_qty').val()) || 0;
                var production_qty = parseFloat($row.find('.bulk_production_qty').val()) || 0;
                var returnqty = parseFloat($row.find('.bulk_return_qty').val()) || 0;

                var decimalpoint = "{{ Session::get('decimal') }}";

                /* ❌ Production > Issued */
                if (production_qty > materialissue) {
                    showCustomAlert('Production Qty should not be more than Material Issued Qty', 'info');
                    $row.find('.bulk_production_qty, .bulk_return_qty, .bulk_exceed_qty, .bulk_scrap_qty').val(0);
                    return;
                }

                /* ❌ Exceed > Issued */
                if (exceed_qty > materialissue) {
                    showCustomAlert('Exceed Qty is more than Issue Qty', 'info');
                    $row.find('.bulk_exceed_qty').val(0);
                    return;
                }

                /* 🔢 Calculate Scrap */
                var scrap = materialissue - (returnqty + production_qty + exceed_qty);
                var scrapFixed = scrap.toFixed(decimalpoint);

                if (scrapFixed < 0) {
                    showCustomAlert('Exceed Qty is more than sum of other Qty', 'info');

                    $row.find('.bulk_exceed_qty').val(0);
                    $row.find('.bulk_return_qty').val(0);
                    $row.find('.bulk_scrap_qty').val(0);

                    $row.find('.bulk_returnqty, .bulk_batchnum, .bulk_subinventoryid, .bulk_locatorid').val('');
                    return;
                }

                /* ✅ Set Scrap */
                if (scrapFixed > 0) {
                    $row.find('.bulk_scrap_qty').val(scrapFixed);
                } else {
                    $row.find('.bulk_scrap_qty').val(0);
                }

            });


            $('.bulk_production_qty').trigger('change');

            // emp details popup - fetching details

            const DATE_FORMAT = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
            const TIME_FORMAT = "HH:mm:ss";

            function parseToMinutes(str) {
                if (!str) return NaN;
                const s = String(str).trim();

                // Pull the last time-looking token (works for "YYYY-MM-DD HH:mm[:ss]" and also "HH:mm[:ss] AM/PM")
                // Examples that match: "14:05", "14:05:30", "2:05 PM", "2025-10-04 02:05:30 PM"
                const m = /(\d{1,2}):(\d{2})(?::(\d{2}))?\s*([AP]M)?$/i.exec(s);
                if (!m) return NaN;

                let h = +m[1];
                const min = +m[2];
                const sec = +((m[3] || '0'));
                const ampm = (m[4] || '').toUpperCase();

                if (ampm) {
                    // Convert 12h → 24h
                    if (h === 12 && ampm === 'AM') h = 0;
                    else if (h !== 12 && ampm === 'PM') h += 12;
                }
                if (h < 0 || h > 23 || min < 0 || min > 59) return NaN;

                return h * 60 + min + Math.floor(sec / 60);
            }

            // Minutes → "H.MM" (minutes zero-padded)
            function minutesToHdotMM(total) {
                const H = Math.floor(total / 60);
                const M = total % 60;
                return H + '.' + String(M).padStart(2, '0');
            }
            // Safe (re)initialize a datetimepicker on an input
            function safeInitDateTime($input, opts) {
                try { $input.datetimepicker('destroy'); } catch (_) { }
                $input.removeClass('hasDatepicker');           // cloned rows can inherit this
                $input.datetimepicker(opts);
            }
            // Compute a single row’s working hrs from start/end
            function calcRowHours($row) {
                const s = parseToMinutes($row.find('.start_time').val());
                const e = parseToMinutes($row.find('.end_time').val());
                if (isNaN(s) || isNaN(e)) { $row.find('.working_hrs').val(''); return; }
                let diff = e - s;
                if (diff < 0) diff += 24 * 60;                 // overnight shift support
                $row.find('.working_hrs').val(minutesToHdotMM(diff));
            }
            // Sum all rows’ working_hrs → .total_working_hrs
            function sumTotalWorkingHours($scope) {
                let total = 0;
                $scope.find('.working_hrs').each(function () {
                    const val = String($(this).val() || '');
                    if (!val) return;
                    const [H, M] = val.split('.');
                    const h = parseInt(H || '0', 10);
                    const m = parseInt(M || '0', 10);
                    if (!isNaN(h) && !isNaN(m)) total += (h * 60 + m);
                });
                $('.total_working_hrs').val(minutesToHdotMM(total));
            }
            // Initialize calendars for ONE employee row
            function initEmpRowCalendars($row) {
                const jobdate = $('.job_date').val() || null;
                const $start = $row.find('.start_time');
                const $end = $row.find('.end_time');

                safeInitDateTime($start, {
                    changeMonth: true, changeYear: true,
                    dateFormat: DATE_FORMAT, timeFormat: TIME_FORMAT,
                    controlType: 'select', oneLine: true, showSecond: true,
                    minDate: jobdate, maxDate: 0, showAnim: 'slideDown', yearRange: '-25:+0',
                    beforeShow: function () { setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0); },
                    onClose: function () {
                        const startDt = $start.datetimepicker('getDate');
                        safeInitDateTime($end, {
                            changeMonth: true, changeYear: true,
                            dateFormat: DATE_FORMAT, timeFormat: TIME_FORMAT,
                            controlType: 'select', oneLine: true, showSecond: true,
                            minDate: startDt || jobdate, maxDate: 0, showAnim: 'slideDown', yearRange: '-25:+0',
                            beforeShow: function () { setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0); },
                            onClose: function () { calcRowHours($row); sumTotalWorkingHours($('#employeedetailsModal')); }
                        });
                        calcRowHours($row);
                        sumTotalWorkingHours($('#employeedetailsModal'));
                    }
                });

                safeInitDateTime($end, {
                    changeMonth: true, changeYear: true,
                    dateFormat: DATE_FORMAT, timeFormat: TIME_FORMAT,
                    controlType: 'select', oneLine: true, showSecond: true,
                    minDate: jobdate, maxDate: 0, showAnim: 'slideDown', yearRange: '-25:+0',
                    beforeShow: function () { setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0); },
                    onClose: function () { calcRowHours($row); sumTotalWorkingHours($('#employeedetailsModal')); }
                });
            }



            $(document).on('click', '.employeedetails', function () {
                const $srcRow = $(this).closest('tr');
                const index = $srcRow.index();

                $('#employeedetailsModal')
                    .data({ index, $srcRow })
                    .modal('show')
                    .css('margin', 'auto');
            });

            // When modal is shown → load HTML + init row widgets
            $('#employeedetailsModal').on('shown.bs.modal', function () {
                const $modal = $(this);
                const { index } = $modal.data();
                const jobid = $('.w_jobs_hdr_id').val();
                const empstatus = $('.empstatus').val();
                const pgurl = "<?php echo $pageurl; ?>";

                if (empstatus == 1) return;

                $.get("{{ URL::to('employeedetails') }}/" + jobid, { index, pgurl }, function (html) {
                    $modal.find('.sodetail').html(html);

                    // Initialize calendars for all existing rows
                    $modal.find('.emp_lines_body tr').each(function () { initEmpRowCalendars($(this)); });

                    // Optional: prefill from hidden page-level comma fields
                    const starts = String($('.starttime').val() || '').split(',').filter(Boolean);
                    const ends = String($('.endtime').val() || '').split(',').filter(Boolean);
                    const works = String($('.workinghrs').val() || '').split(',').filter(Boolean);
                    const need = Math.max(starts.length, ends.length, works.length);

                    for (let i = 0; i < need; i++) {
                        let $r = $modal.find('.emp_lines_body tr').eq(i);
                        if (!$r.length) {
                            // If your modal has an "add row" button with .addrow_empdetails, click it
                            $modal.find('.addrow_empdetails').trigger('click');
                            $r = $modal.find('.emp_lines_body tr').eq(i);
                        }
                        if (starts[i]) $r.find('.start_time').val(starts[i]);
                        if (ends[i]) $r.find('.end_time').val(ends[i]);
                        if (works[i]) $r.find('.working_hrs').val(works[i]);
                    }

                    sumTotalWorkingHours($modal);
                });
            });




            $('#employeedetailsModal').on('change', '.start_time, .end_time', function () {
                const $row = $(this).closest('tr');
                const $tbody = $row.closest('tbody');
                const first = ($row.index() === 0);
                const start = $row.find('.start_time').val();
                const end = $row.find('.end_time').val();

                // Recalc current row
                if (start && end) calcRowHours($row);

                // If first row: copy to blanks in other rows and recalc
                if (first) {
                    $tbody.find('tr').each(function () {
                        const $r = $(this);
                        if ($r.is($row)) return;
                        if (start && !$r.find('.start_time').val()) $r.find('.start_time').val(start);
                        if (end && !$r.find('.end_time').val()) $r.find('.end_time').val(end);
                        if ($r.find('.start_time').val() && $r.find('.end_time').val()) calcRowHours($r);
                    });
                }

                sumTotalWorkingHours($('#employeedetailsModal'));
            });


            $('#employeedetailsModal').off('click', '.employee').on('click', '.employeee', function () {
                const $modal = $('#employeedetailsModal');
                const $tbody = $modal.find('tbody');
                const pgurl = "<?php echo $pageurl; ?>";

                let ok = true, totalMin = 0;
                const jobassign = [], prclevel = [], prcname = [], acthrs = [], starts = [], ends = [], works = [], empqty = [];

                $tbody.find('tr').each(function () {
                    const $r = $(this);
                    const jobass = $r.find('.job_assigned_to').val() || '';
                    const plevel = $r.find('.processlevel').val() || '';
                    const pname = $r.find('.process_name').val() || '';
                    const ahrs = $r.find('.actual_hrs').val() || '';
                    const st = $r.find('.start_time').val() || '';
                    const et = $r.find('.end_time').val() || '';
                    const wh = $r.find('.working_hrs').val() || '';
                    const eqty = parseFloat($r.find('.emp_qty').val() || '0');

                    const any = jobass || plevel || pname || ahrs || st || et || wh || eqty;
                    if (!any) return;

                    if (!st || !et || !wh) ok = false;

                    jobassign.push(jobass);
                    prclevel.push(plevel);
                    prcname.push(pname);
                    acthrs.push(ahrs);
                    starts.push(st);
                    ends.push(et);
                    works.push(wh);
                    empqty.push(isNaN(eqty) ? 0 : eqty);

                    const [H, M] = (wh + '').split('.');
                    totalMin += (parseInt(H || '0', 10) * 60) + (parseInt(M || '0', 10));
                });

                if (!ok) { (window.showCustomAlert || alert)('Please enter employee details', 'error'); return; }
                console.log(totalMin)
                // Write to your hidden/page-level fields
                $('.total_working_hrs').val(minutesToHdotMM(totalMin));
                $('.jobassigned_to').val(jobassign.join(','));
                $('.actualhrs').val(acthrs.join(','));
                $('.starttime').val(starts.join(','));
                $('.endtime').val(ends.join(','));
                $('.workinghrs').val(works.join(','));
                $('.employee_qty').val(empqty.join(','));
                if (pgurl === 'packingqasubmitstage') {
                    $('.proceslevel').val(prclevel.join(','));
                    $('.processname').val(prcname.join(','));
                }

                $('.empstatus').val(1);
                $modal.modal('hide');
            });


            // Add a new employee detail row (clone last, clean state, re-init widgets)
            $(document).on('click', '.addrow_empdetails', function () {
                const $modal = $('#employeedetailsModal');
                const $tbody = $modal.find('.emp_lines_body');
                const $last = $tbody.find('tr:last');
                const $newRow = $last.clone(false, false); // clone without events/data

                // 1) Clear values
                $newRow.find('input').val('');
                $newRow.find('select').val('').trigger('change');

                // 2) Clean Select2 artifacts on the clone (if you use Select2)
                $newRow.find('select.select2').each(function () {
                    if ($.fn.select2 && $(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                    $(this).removeAttr('data-select2-id');
                    $(this).next('.select2').remove();
                });

                // 3) Fully purge any jQuery UI (date|time)picker state from the clone
                $newRow.find('.start_time, .end_time').each(function () {
                    try { $(this).datetimepicker('destroy'); } catch (_) { }
                    try { $(this).datepicker('destroy'); } catch (_) { }
                    $(this)
                        .removeClass('hasDatepicker')
                        .removeAttr('id aria-describedby autocomplete') // IDs must be unique
                        .removeData('datetimepicker')
                        .removeData('datepicker')
                        .removeData('timepicker');
                });

                // 4) Append the clean clone
                $tbody.append($newRow);

                // 5) Re-init Select2 on the new row (if used)
                    if ($.fn.select2) {
                        $newRow.find('select.select2').select2({
                            dropdownParent: $('#employeedetailsModal'),
                            width: '100%'
                        });
                    }

                // 6) Init calendars for the new row (uses your helper)
                initEmpRowCalendars($newRow);

                // 7) Optional: re-number S.No cells/inputs
                if (typeof updateLineNumbers === 'function') {
                    updateLineNumbers1();
                } else {
                    // Basic inline renumber (if you don't have a helper)
                    $tbody.find('tr').each(function (i) {
                        $(this).find('.line_no').val(i + 1);
                    });
                }
            });

            // (Optional) also recalc on manual typing (paste) in time fields
            $('#employeedetailsModal').on('input', '.start_time, .end_time', function () {
                const $row = $(this).closest('tr');
                calcRowHours($row);
                sumTotalWorkingHours($('#employeedetailsModal'));
            });

            // remove row - emp popup
            $(document).on('click', '.removerow_empdel', function () {
                const rowCount = $('.emp_lines_body tr').length;
                if (rowCount > 1) {
                    $(this).closest('tr').remove();
                    updateLineNumbers();
                } else {
                    showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
                }
            });



            // Machine Popup Details

            (function ($) {
                const DATE_FORMAT = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
                const TIME_FORMAT = "HH:mm:ss";

                // Parse "HH:mm[:ss]" or "... YYYY-MM-DD HH:mm[:ss] ..." (12h with AM/PM also ok)
                function parseToMinutes(str) {
                    if (!str) return NaN;
                    const s = String(str).trim();
                    const m = /(\d{1,2}):(\d{2})(?::(\d{2}))?\s*([AP]M)?$/i.exec(s);
                    if (!m) return NaN;

                    let h = +m[1];
                    const min = +m[2];
                    const sec = +((m[3] || '0'));
                    const ampm = (m[4] || '').toUpperCase();

                    if (ampm) {
                        if (h === 12 && ampm === 'AM') h = 0;
                        else if (h !== 12 && ampm === 'PM') h += 12;
                    }
                    if (h < 0 || h > 23 || min < 0 || min > 59) return NaN;
                    return h * 60 + min + Math.floor(sec / 60);
                }

                // Minutes → "H.MM"
                function minutesToHdotMM(total) {
                    const H = Math.floor(total / 60);
                    const M = total % 60;
                    return H + '.' + String(M).padStart(2, '0');
                }

                // Safe (re)initialize jQuery-UI datetimepicker on an input
                function safeInitDateTime($input, opts) {
                    try { $input.datetimepicker('destroy'); } catch (_) { }
                    $input.removeClass('hasDatepicker'); // cloned rows can inherit this
                    $input.removeAttr('id aria-describedby autocomplete');
                    $input.datetimepicker(opts);
                }

                // Compute a single row’s working hrs from start/end
                function calcRowHours($row) {
                    const s = parseToMinutes($row.find('.machine_start_time').val());
                    const e = parseToMinutes($row.find('.machine_end_time').val());
                    if (isNaN(s) || isNaN(e)) { $row.find('.machine_working_hrs').val(''); return; }
                    let diff = e - s;
                    if (diff < 0) diff += 24 * 60; // overnight shift support
                    $row.find('.machine_working_hrs').val(minutesToHdotMM(diff));
                }

                // Sum all rows’ .machine_working_hrs → .total_machine_working_hrs
                function sumMachineTotals($scope) {
                    let total = 0;
                    $scope.find('.machine_working_hrs').each(function () {
                        const val = String($(this).val() || '');
                        if (!val) return;
                        const [H, M] = val.split('.');
                        const h = parseInt(H || '0', 10);
                        const m = parseInt(M || '0', 10);
                        if (!isNaN(h) && !isNaN(m)) total += (h * 60 + m);
                    });
                    $('.total_machine_working_hrs').val(minutesToHdotMM(total));
                }

                // Initialize calendars for ONE machine row (jQuery-UI timepicker addon)
                function initMachineRowCalendars($row) {
                    const jobdate = $('.job_date').val() || null;
                    const $start = $row.find('.machine_start_time');
                    const $end = $row.find('.machine_end_time');

                    safeInitDateTime($start, {
                        changeMonth: true, changeYear: true,
                        dateFormat: DATE_FORMAT, timeFormat: TIME_FORMAT,
                        controlType: 'select', oneLine: true, showSecond: true,
                        minDate: jobdate, maxDate: 0, showAnim: 'slideDown', yearRange: '-25:+0',
                        // appendTo helps inside modals (supported by jQuery-UI datepicker)
                        appendTo: 'body',
                        beforeShow: function () { setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0); },
                        onClose: function () {
                            const startDt = $start.datetimepicker('getDate');
                            safeInitDateTime($end, {
                                changeMonth: true, changeYear: true,
                                dateFormat: DATE_FORMAT, timeFormat: TIME_FORMAT,
                                controlType: 'select', oneLine: true, showSecond: true,
                                minDate: startDt || jobdate, maxDate: 0, showAnim: 'slideDown', yearRange: '-25:+0',
                                appendTo: 'body',
                                beforeShow: function () { setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0); },
                                onClose: function () { calcRowHours($row); sumMachineTotals($('#machinedetailsModal')); }
                            });
                            calcRowHours($row);
                            sumMachineTotals($('#machinedetailsModal'));
                        }
                    });

                    safeInitDateTime($end, {
                        changeMonth: true, changeYear: true,
                        dateFormat: DATE_FORMAT, timeFormat: TIME_FORMAT,
                        controlType: 'select', oneLine: true, showSecond: true,
                        minDate: jobdate, maxDate: 0, showAnim: 'slideDown', yearRange: '-25:+0',
                        appendTo: 'body',
                        beforeShow: function () { setTimeout(() => $('#ui-datepicker-div').css('z-index', 99999), 0); },
                        onClose: function () { calcRowHours($row); sumMachineTotals($('#machinedetailsModal')); }
                    });
                }

                // Open trigger (row button/link has class .machinedetails)
                $(document).on('click', '.machinedetails', function () {
                    const pgurl = "<?php echo $pageurl; ?>";
                    if (pgurl === "packingqasubmitstage") return;

                    const index = $(this).closest('tr').index();
                    $('.somacindex').val(index);
                    $('#machinedetailsModal').modal('show').css('margin', 'auto');
                });

                // When modal becomes visible, load HTML and init rows
                $('#machinedetailsModal').on('shown.bs.modal', function () {
                    const $modal = $(this);
                    const index = $('.somacindex').val();
                    const jobid = $('.w_jobs_hdr_id').val();
                    const machine_status = $('.machine_status').val();
                    const pgurl = "<?php echo $pageurl; ?>";

                    if (machine_status == 1) return;

                    $.get("{{URL::to('getallmachinedetails') }}/" + jobid, { index, pgurl }, function (html) {
                        $('.somacdetail').html(html);

                        // init all current rows in the modal
                        $modal.find('.mac_class_body tr').each(function () {
                            $(this).find('.machine_start_time, .machine_end_time')
                                .removeClass('hasDatepicker')
                                .removeAttr('id aria-describedby autocomplete');
                            initMachineRowCalendars($(this));
                        });

                        // optional prefill from hidden page fields
                        const sts = String($('.machine_starttime').val() || '').split(',').filter(Boolean);
                        const ets = String($('.machine_endtime').val() || '').split(',').filter(Boolean);
                        const whs = String($('.machine_workinghrs').val() || '').split(',').filter(Boolean);
                        for (let i = 0; i < Math.max(sts.length, ets.length, whs.length); i++) {
                            const $r = $modal.find('.mac_class_body tr').eq(i);
                            if (!$r.length) break;
                            if (sts[i]) $r.find('.machine_start_time').val(sts[i]).trigger('change');
                            if (ets[i]) $r.find('.machine_end_time').val(ets[i]).trigger('change');
                            if (whs[i]) $r.find('.machine_working_hrs').val(whs[i]);
                        }
                        sumMachineTotals($modal);
                    });
                });

                // FIRST ROW PROPAGATION
                $('#machinedetailsModal').on('change', '.machine_start_time, .machine_end_time', function () {
                    const $row = $(this).closest('tr');
                    const $tbody = $row.closest('tbody');
                    if ($row.index() !== 0) return;

                    const start = $row.find('.machine_start_time').val();
                    const end = $row.find('.machine_end_time').val();

                    $tbody.find('tr').each(function () {
                        const $r = $(this);
                        if ($r.is($row)) return;
                        if (start && !$r.find('.machine_start_time').val()) $r.find('.machine_start_time').val(start).trigger('change');
                        if (end && !$r.find('.machine_end_time').val()) $r.find('.machine_end_time').val(end).trigger('change');
                    });
                });

                // ADD / REMOVE ROWS
                $(document).on('click', '.addrow_macdetails', function () {
                    const $modal = $('#machinedetailsModal');
                    const $tbody = $modal.find('.mac_class_body');
                    const $lastRow = $tbody.find('tr:last');

                    const $newRow = $lastRow.clone(false, false);

                    // Clear values
                    $newRow.find('input').val('');
                    $newRow.find('select').val('').trigger('change');

                    // Clean Select2 artifacts & reinit later
                    $newRow.find('select.select2').each(function () {
                        if ($.fn.select2 && $(this).hasClass('select2-hidden-accessible')) {
                            $(this).select2('destroy');
                        }
                        $(this).removeAttr('data-select2-id');
                        $(this).next('.select2').remove();
                    });

                    // Purge any jQuery-UI datetimepicker state from the clone
                    $newRow.find('.machine_start_time, .machine_end_time').each(function () {
                        try { $(this).datetimepicker('destroy'); } catch (_) { }
                        $(this)
                            .removeClass('hasDatepicker')
                            .removeAttr('id aria-describedby autocomplete');
                    });
                    // Append clean clone
                    $tbody.append($newRow);

                    // Re-init Select2 if used
                    if ($.fn.select2) {
                        $newRow.find('select.select2').select2({
                            dropdownParent: $('#machinedetailsModal'),
                            width: '100%'
                        });
                    }

                    // Init calendars for this row
                    initMachineRowCalendars($newRow);

                    // Optional: copy first row's times into the new row
                    const $first = $tbody.find('tr').first();
                    const firstStart = $first.find('.machine_start_time').val();
                    const firstEnd = $first.find('.machine_end_time').val();
                    if (firstStart && !$newRow.find('.machine_start_time').val()) {
                        $newRow.find('.machine_start_time').val(firstStart).trigger('change');
                    }
                    if (firstEnd && !$newRow.find('.machine_end_time').val()) {
                        $newRow.find('.machine_end_time').val(firstEnd).trigger('change');
                    }

                    if (typeof updateLineNumbers === 'function') updateLineNumberss();
                    sumMachineTotals($modal);
                });

                $(document).on('click', '.removerow_macdet', function () {
                    const $modal = $('#machinedetailsModal');
                    const $tbody = $modal.find('.mac_class_body');
                    const rowCount = $tbody.find('tr').length;

                    if (rowCount > 1) {
                        $(this).closest('tr').remove();
                        if (typeof updateLineNumbers === 'function') updateLineNumberss();
                        sumMachineTotals($modal);
                    } else {
                        (window.showCustomAlert || alert)("You Can't Delete. At least one row should be there");
                    }
                });

                // LIVE RECALC ON TYPING/PASTE
                $('#machinedetailsModal').on('input', '.machine_start_time, .machine_end_time', function () {
                    const $row = $(this).closest('tr');
                    calcRowHours($row);
                    sumMachineTotals($('#machinedetailsModal'));
                });

                // SAVE BUTTON
                $(document).on('click', '.machine', function () {
                    const $modal = $('#machinedetailsModal');
                    const $tbody = $modal.find('.mac_class_body');

                    let ok = true, totalMin = 0;
                    const machine_hdr = [], machine_acthrs = [], machine_starttime = [], machine_endtime = [], machine_workhrs = [], machine_qty = [];

                    $tbody.find('tr').each(function () {
                        const $r = $(this);
                        const hdr = $r.find('.machine_assigned_to').val() || '';
                        const ahrs = $r.find('.machine_actual_hrs').val() || '';
                        const st = $r.find('.machine_start_time').val() || '';
                        const et = $r.find('.machine_end_time').val() || '';
                        const wh = $r.find('.machine_working_hrs').val() || '';
                        const qty = parseFloat($r.find('.machine_qty').val() || '0');

                        const any = hdr || ahrs || st || et || wh || qty;
                        if (!any) return;

                        if (!st || !et || !wh) ok = false;

                        machine_hdr.push(hdr);
                        machine_acthrs.push(ahrs);
                        machine_starttime.push(st);
                        machine_endtime.push(et);
                        machine_workhrs.push(wh);
                        machine_qty.push(isNaN(qty) ? 0 : qty);

                        const [H, M] = (wh + '').split('.');
                        totalMin += (parseInt(H || '0', 10) * 60) + (parseInt(M || '0', 10));
                    });

                    if (!ok) { (window.showCustomAlert || alert)("Please enter Machine details"); return; }

                    $('.total_machine_working_hrs').val(minutesToHdotMM(totalMin));
                    $('.machineassigned_to').val(machine_hdr.join(','));
                    $('.machine_actualhrs').val(machine_acthrs.join(','));
                    $('.machine_starttime').val(machine_starttime.join(','));
                    $('.machine_endtime').val(machine_endtime.join(','));
                    $('.machine_workinghrs').val(machine_workhrs.join(','));
                    if ($('.machine_qty_hidden').length) $('.machine_qty_hidden').val(machine_qty.join(','));

                    $('.machine_status').val(1);
                    $modal.modal('hide');
                });

            })(jQuery);


            $(document).on("change", '.processlevel', function () {
                var level = $(this).val();
                var index = $(this).closest('tr').index();
                var productid = $('.product_id').val();
                $.get("{{URL::to('prsdetails')}}/" + productid + "?level=" + level, function (data) {
                    var cond = '  lookup_type="PROCESS_NAME"';
                    $(".process_name" + index).jCombo("{{ URL::to('jcomboform?table=a_lookuplines_t:lookup_code:lookup_code') }}&parent=" + cond + "&order_by=lookup_code asc",
                        { 'selected_value': data['pname'] });
                });
            });


            /* purpose:based on subinventory,locator should be load*/
            // When a subinventory changes, load locators for that *row* only
            $(document).on('change', '.bulk_subinventory_id', function () {

                const $row = $(this).closest('tr');
                const subinv = $(this).val();
                const $subloc = $row.find('.bulk_sublocator_id');

                // Reset & disable while loading
                $subloc
                    .prop('disabled', true)
                    .html('<option value="">-- Select Locator --</option>');

                if (!subinv) return;

                // If you *don’t* need the special filter, just use subinventory_id=...
                var parentCond = "subinventory_id=" + subinv;

                const url =
                    "{{ URL::to('jcomboform') }}" +
                    "?table=" + encodeURIComponent("m_sublocators_t:sublocator_id:locator_code") +
                    "&parent=" + encodeURIComponent(parentCond) +
                    "&order_by=" + encodeURIComponent("locator_code asc");

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json', // expect JSON; we’ll still guard if it returns a string
                    success: function (data) {
                        if (typeof data === 'string') {
                            try { data = JSON.parse(data); }
                            catch (e) {
                                console.error('Invalid JSON response:', data);
                                return;
                            }
                        }

                        // Expected: [{ val: "123", option_name: "LOC-001" }, ...]
                        $subloc.html('<option value="">-- Select Locator --</option>');

                        $.each(data, function (i, item) {
                            $subloc.append(`<option value="${item.val}">${item.option_name}</option>`);
                        });

                        $subloc.prop('disabled', false).trigger('change.select2');
                    },
                    error: function (xhr) {
                        console.error('Failed to load locators:', xhr.responseText || xhr.statusText);

                    }
                });
            });


            /* purpose:to save qasubmit*/
            var pgurl = "<?php echo $pageurl; ?>";
            if (pgurl == "packingqasubmitstage") {
                $(document).on('click', '.saveform', function (event) {

                    var btnval = $(this).val();
                    var url = "{{ URL::to('qasubmitstagesave') }}";
                    var red_url = "{{ URL::to($pageurl) }}";
                    var moveto_subinventory = $('.moveto_subinventory').val();
                    var store_move = $('.store_move').val();

                    var form = $('#qasubmitstage');
                    form.parsley().validate();
                    if (form.parsley().isValid()) {

                        $('#savedetailsModal').modal('show');
                        $(document).on('click', '.savedata', function () {

                            var formdata = $('#qasubmitstage').serialize();
                            $.post(url, formdata, function (data) {
                                var $btn = $(this);
                                $btn.prop('disabled', true);
                                var status = data.status;
                                var msg = data.message;
                                var id = data.id;
                                var edit_url = "{{ URL::to('qasubmitstagecreate') }}/" + id;
                                showCustomAlert(msg,status);
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);

                            }).fail(function () {
                            });
                        });
                    }

                });
            } else {

                $(document).on('click', '.saveform', function (event) {
                    // one time only click purpose
                    event.preventDefault();
                    var btnval = $(this).val();
                    var url = "{{ URL::to('qasubmitstagesave') }}";
                    var red_url = "{{ URL::to($pageurl) }}";
                    var moveto_subinventory = $('.moveto_subinventory').val();
                    var store_move = $('.store_move').val();

                    if ((store_move != 'No' && moveto_subinventory == 'No') || (store_move == 'No' && moveto_subinventory != 'No')) {
                        var form = $('#qasubmitstage');
                        form.parsley().validate();
                        if (form.parsley().isValid()) {

                            var formdata = $('#qasubmitstage').serialize();
                            $.post(url, formdata, function (data) {
                                var $btn = $(this);
                                $btn.prop('disabled', true);
                                var status = data.status;
                                var msg = data.message;
                                var id = data.id;
                                var edit_url = "{{ URL::to('qasubmitstagecreate') }}/" + id;
                                showCustomAlert(msg,'success');
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);

                            }).fail(function () {
                            });

                        }

                    } else {
                        showCustomAlert("Please Choose Yes for store move/move to shopfloor", 'warning');
                    }

                });



            }
            var pgurl = "<?php echo $pageurl; ?>";
            if (pgurl == "packingqasubmitstage") {

                $('.employeedetails').each(function (index) {
                    var index = $('.soindex').val();
                    var jobid = $('.w_jobs_hdr_id').val();
                    var empstatus = $('.empstatus').val();

                    var pgurl = "<?php echo $pageurl; ?>";
                    if (empstatus != 1) {
                        var url = "{{URL::to('employeedetails') }}/" + jobid + '?index=' + index + "&pgurl=" + pgurl

                        var result = $.ajax({
                            url: url,
                            type: 'GET',
                            async: false,
                            data: data
                        });
                        var data = result.responseText;
                        $('.sodetail').html(data);
                        $('.employee').trigger('click');
                    }
                });
            }
            /* purpose: when cancel in save popup model hide*/
            $(document).on('click', '#canceldata', function () {
                $('#savedetailsModal').modal('hide');
            });


        });


        // Renumber Line Nos
        function updateLineNumberss() {
            $('.mac_class_body tr').each(function (index) {
                $(this).find('.mac_line_no').val(index + 1);
            });
        }

    </script>



@endpush