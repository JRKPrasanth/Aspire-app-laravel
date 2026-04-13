@extends('layouts.header')
@section('content')
	<h3 class="text-danger">

		<?php if ($return_url == 'schemesapprovalcreate') { ?>
		Schemes Approval - L1
		<?php } else if ($return_url == 'schemeslevel2approvalcreate') { ?>
		Schemes Approval - L2
		<?php } else { ?>
		Schemes
		<?php } ?>

	</h3>
	@include('layouts.breadcrumb')


	<div class="card shadow-lg rounded-4 border-0">
		<div class="card-body">
			<form id="schemes_form" action="">
				{{ csrf_field() }}
				<input type="hidden" name="savestatus" id="savestatus" value="">
				<input type="hidden" name="approvestatus" id="approvestatus" value="">
				<input type="hidden" name="route_name" value="{{ \Request::route()->getName() }}">
				<input type="hidden" id="schemes_hdr_id" name="schemes_hdr_id" value="{{ $row->schemes_hdr_id }}">
				<input type="hidden" class="inedx_pop">

				<div class="row g-4">
					<!-- Scheme Name -->
					<div class="col-md-4">
						<label for="schemes_name" class="form-label">
							<span class="text-danger">*</span> Schemes
						</label>
						<input type="text" id="schemes_name" name="schemes_name" class="form-control schemes_name"
							value="{{ $row->schemes_name }}" required>
						<span class="btn btn-danger dup_name mt-1" style="display:none;"></span>
					</div>

					<!-- Scheme Type -->
					<div class="col-md-4">
						<label for="scheme_type" class="form-label">Scheme Type</label>
						<select id="scheme_type" name="scheme_type" class="form-select select2" required>
							<option value="">--Please Select--</option>
							<option value="product_based" {{ $row->scheme_type == 'product_based' ? 'selected' : '' }}>Product
								Based</option>
							<option value="order_based" {{ $row->scheme_type == 'order_based' ? 'selected' : '' }}>Order Based
							</option>
						</select>
					</div>

					<!-- Start Date -->
					<div class="col-md-4">
						<label for="start_date" class="form-label">Start Date</label>
						<input type="text" id="start_date" name="start_date" class="form-control schemes_date"
							value="{{ $row->start_date }}">
					</div>

					<!-- End Date -->
					<div class="col-md-4">
						<label for="end_date" class="form-label">End Date</label>
						<input type="text" id="end_date" name="end_date" class="form-control schemes_date"
							value="{{ $row->end_date }}">
					</div>

					<!-- Created By -->
					<div class="col-md-4 none">
						<label for="created_by" class="form-label">Created By</label>
						<select id="created_by" name="created_by" class="form-select select2 created_by">
							{!! $created_by !!}
						</select>
					</div>

					<!-- Location -->
					<div class="col-md-4">
						<label for="location" class="form-label">Location</label>
						<select id="location" name="location" class="form-select select2 location">
							{!! $location !!}
						</select>
					</div>

					<!-- Active -->
					<div class="col-md-4">
						<label for="active" class="form-label">Active</label>
						<select id="active" name="active" class="form-select select2">
							<option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
							<option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
						</select>
					</div>
				</div>



				<div class="row mt-4">
					<div class="col-md-12">

						<div id="preview-area" class="table-responsive">
							<table class="table table-bordered clone_table" style="width: 140%;">
								<thead class="table-light">
									<tr>
										<th>Line No</th>
										<th class="pdtdiv">Product </th>
										<th class="scheme_base">Scheme Base</th>
										<th>Schemes Type</th>
										<th class="thgift">Gift Product</th>
										<th> Scheme Base Value From</th>
										<th> Scheme Base Value To</th>
										<th>Schemes Type Value</th>
										<th>Comments</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="clone_lines_body">
									<?php if (count($linedata) >= 1) { ?>
									@foreach($linedata as $key => $value)
																<tr class="rcopy clone">
																	<td>

																		<input type="hidden" name="bulk_schemes_lines_id[]"
																			class="form-control input-sm bulk_schemes_lines_id"
																			value="{{$value->schemes_lines_id}}">

																		<input type="text" name="bulk_line_no[]"
																			class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
																			readonly="readonly">
																	</td>
																	<td class="pdtdiv">
																		<select name="bulk_product_id[]" class="select2 bulk_product_id">
																			{!! $value->product_id !!}
																		</select>
																	</td>
																	<td class="scheme_base">
																		<select name="bulk_scheme_base[]"
																			class="select2 bulk_scheme_base  parsley-validated">
																			<option value="">--Please Select </option>
																			<option value="Quantity Based" <?php if ($value->scheme_base == 'Quantity Based') {
											echo "selected";
										} ?>>Quantity Based</option>
																			<option value="Price Based" <?php if ($value->scheme_base == 'Price Based') {
											echo "selected";
										} ?>>Price Based</option>
																		</select>
																	</td>
																	<td>
																		<select name="bulk_schemes_type[]" id="bulk_schemes_type"
																			class="select2 bulk_schemes_type" required>
																			<option value="">--Please Select</option>
																			<option value="Amount Based" <?php if ($value->schemes_type == 'Amount Based') {
											echo "selected";
										} ?>>Amount Based</option>
																			<option value="Gift" <?php if ($value->schemes_type == 'Gift') {
											echo "selected";
										} ?>>Gift</option>
																			<option value="Points" <?php if ($value->schemes_type == 'Points') {
											echo 'selected';
										} ?>>Points</option>
																			<option value="Discounts" <?php if ($value->schemes_type == 'Discounts') {
											echo 'selected';
										} ?>>Discounts</option>
																		</select>
																	</td>

																	<td><select name="bulk_line_pdt_id[]" class="select2 bulk_line_pdt_id ">
																			{!! $value->line_product_id !!}
																		</select></td>
																	<td>
																		<input type="text" name="bulk_scheme_base_value_from[]"
																			class="form-control input-sm bulk_scheme_base_value_from input_qty_width"
																			value="{{ $value->scheme_base_value_from }}" required="required">
																	</td>
																	<td>
																		<input type="text" name="bulk_scheme_base_value_to[]"
																			class="form-control bulk_scheme_base_value_to input_qty_width"
																			value="{{ $value->scheme_base_value_to }}" required="required">
																	</td>

																	<td>
																		<input type="text" name="bulk_schemes_type_value[]"
																			class="form-control input-sm bulk_schemes_type_value input_qty_width"
																			value="{{ $value->schemes_type_value }}" required="required">
																	</td>
																	<td>
																		<textarea name="bulk_comments[]" class="form-control input-sm bulk_comments"
																			value="{{$value->comments}}" rows="1"> {!! $value->comments !!}</textarea>
																	</td>
																	<td class="text-center">
																		<button type="button" class="btn btn-sm btn-danger remove-row">
																			<i class="fas fa-minus-circle"></i>
																		</button>
																		<input type="hidden" name="counter[]">
																	</td>

																</tr>
									@endforeach
									<?php }
	if (count($linedata) < 1) { ?>
									<tr class="rcopy clone">
										<td>
											<input type="hidden" name="bulk_schemes_lines_id[]"
												class="form-control input-sm bulk_schemes_lines_id" value="">
											<input type="text" name="bulk_line_no[]"
												class="form-control input-sm bulk_line_no " value="1" readonly="readonly">
										</td>
										<td class="pdtdiv">
											<select name="bulk_product_id[]" class="select2 bulk_product_id">
												{!! $product_id !!}
											</select>
										</td>
										<td class="scheme_base">
											<select name="bulk_scheme_base[]" class="select2 bulk_scheme_base">
												<option value="">--Please Select </option>
												<option value="Quantity Based">Quantity Based </option>
												<option value="Price Based"> Price Based </option>
											</select>
										</td>
										<td>
											<select name="bulk_schemes_type[]" id="bulk_schemes_type"
												class="select2 bulk_schemes_type" required>
												<option value="">--Please Select</option>
												<option value="Amount Based">Amount Based</option>
												<option value="Gift">Gift</option>
												<option value="Points">Points</option>
												<option value="Discounts">Discounts</option>
											</select>
										</td>

										<td><select name="bulk_line_pdt_id[]" class="select2 bulk_line_pdt_id ">
												{!! $product_id !!}
											</select></td>
										<td>
											<input type="text" name="bulk_scheme_base_value_from[]"
												class="form-control input-sm bulk_scheme_base_value_from input_qty_width"
												value="" required="required">

										</td>
										<td>
											<input type="text" name="bulk_scheme_base_value_to[]"
												class="form-control bulk_scheme_base_value_to input_qty_width" value=""
												required="required">
										</td>

										<td>
											<input type="text" name="bulk_schemes_type_value[]"
												class="form-control input-sm bulk_schemes_type_value input_qty_width"
												value="" minlength="1" maxlength="4" required="required">
										</td>
										<td>
											<textarea name="bulk_comments[]" class="form-control input-sm bulk_comments"
												rows="1"></textarea>
										</td>
										<td class="text-center">
											<button type="button" class="btn btn-sm btn-danger remove-row">
												<i class="fas fa-minus-circle"></i>
											</button>
											<input type="hidden" name="counter[]">
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							<div class="text-end">
								<button type="button" class="btn btn-success btn-sm add-row">
									<i class="fas fa-plus-circle"></i> Add Row
								</button>
							</div>

							<input type="hidden" name="enable-masterdetail" value="true">
						</div>
					</div>
				</div>


				<div class="row mt-4 mb-3">
					<div class="col-lg-12 col-md-12">
						<div class="form-group text-center">
							<input type="hidden" name="submit_type" class="submit_type" id="submit_type">
							<?php if ($return_url != 'schemesapprovalcreate' && $return_url != 'schemeslevel2approvalcreate') { ?>
							<button name="submit" type="button" class="btn btn-success px-4 me-2 saveform"
								value="SAVE">Save</button>
							<a href="{{ url('schemes') }}" class='btn btn-danger px-4 me-2'>Cancel</a>
							<?php } else if ($return_url == 'schemeslevel2approvalcreate') { ?>
							<button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
								value="LEVEL2APPROVED">Approve</button>
							<button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform"
								value="LEVEL2REJECT">Reject</button>
							<a class='btn btn-outline-danger px-4 me-2'
								onclick="location.href = '{{URL::to('schemeslevel2approval')}}'">Cancel</a>
							<?php  } else { ?>
							<button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
								value="APPROVED">Approve</button>
							<button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform"
								value="REJECT">Reject</button>
							<a class='btn btn-outline-danger px-4 me-2'
								onclick="location.href = '{{URL::to('schemesapproval')}}'">Cancel</a>
							<?php }?>
						</div>
					</div>
				</div>


				<!-- purpose Product Details model-->
				<div class="modal fade" id="giftPdtModal" tabindex="-1" aria-labelledby="giftPdtModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-xl"> <!-- modal-xl gives ~80% width -->
						<div class="modal-content">

							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title" id="giftPdtModalLabel">Product Details</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								<input type="hidden" class="soindex" value="">
							</div>

							<!-- Modal Body -->
							<div class="modal-body sodetail">

								<a href="javascript:void(0);" class="add_row1 additempdt newitem mb-3 d-inline-block"
									rel=".rpdtcopy">
									<i class="fa fa-plus"></i> New Item
								</a>

								<div id="preview-area" class="chandru">
									<table class="table table-bordered preview pdt_table">
										<thead class="table-light">
											<tr>
												<th style="width:10%;">Line No</th>
												<th style="width:40%;">Product</th>
												<th style="width:20%;">Qty</th>
												<th style="width:10%;"></th>
											</tr>
										</thead>
										<tbody class="pdt_body">
											<!-- Dynamic rows get appended here -->
										</tbody>

										<!-- Hidden Template Row -->
										<tr class="rpdtcopy clone d-none">
											<td>
												<input type="text" name="bulk_pdt_line_no[]"
													class="form-control form-control-sm bulk_pdt_line_no" value="1">
											</td>
											<td>
												<select name="bulk_line_product_id[]"
													class="form-select form-select-sm bulk_line_product_id select2">
													{!! $product_id !!}
												</select>
											</td>
											<td>
												<input type="text" name="bulk_pdt_qty[]"
													class="form-control form-control-sm bulk_pdt_qty" value="">
											</td>
											<td>
												<a class="lineremove">
													<i class="fa fa-minus-circle text-danger fa-2x"></i>
												</a>
											</td>
										</tr>
									</table>
								</div>

								<div class="text-center mt-3">
									<button type="button" class="btn btn-primary savePdt" id="savePdt"
										data-index="">OK</button>
								</div>

							</div>

							<!-- Modal Footer -->
							<div class="modal-footer"></div>

						</div>
					</div>
				</div>




				<!--  purpose Product search jqgrid model-->
				<div class="modal fade" id="productModal">
					<div class="modal-dialog" style="width:80%;">
						<div class="modal-content">
							<!--Moda Header-->
							<div class="modal-header">
								<h4 class="modal-title"> Product Details </h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<!-- Modal Body -->
							<div class="modal-body">
								<table id="productgrid"></table>
							</div>
							<!-- Modal footer -->
							<div class="modal-footer">
							</div>
						</div>
					</div>
				</div>
				<!--end-->


				<input type="hidden" class="pdtindex" value="" />
			</form>
		</div>
	</div>


@endsection
@push('scripts')

	<script>

		/* --  Start Duplicate data validate Function --*/
		var dup_chk = true;
		function duplicate_validate() {
			var salesperson_name = $(".schemes_name").val();
			var edit_id = $("#edit_id").val();

			$.ajax({
				cache: false,
				url: 'schemes/checkname', //this is your uri
				type: 'GET',
				dataType: 'json',
				async: false,
				data: { schemes_name: schemes_name, edit_id: edit_id },
				success: function (response) {
					if (response == 1) {
						$('.dup_name').html('schemes name:' + schemes_name + ' Already Exists');
						$('.dup_name').show();
						$(".schemes name").val('');
						dup_chk = false;
					}
					else if (response == 0) {
						var html = "";
						$('.dup_name').hide();
						dup_chk = true;
					}

				},
				error: function (xhr, resp, text) {
					console.log(xhr, resp, text);
				}
			});
		}


		$(document).ready(function () {
			$('.addPdt').css('pointer-events', 'none');


			var index = $(this).closest('tr').index();
			var rowCount = $('.po_inq_table tbody tr').length;


			$(document).on('click', '.addPdt', function () {
				var index = $(this).closest('tr').index();
				var lineqty = $('.bulk_line_pdt_qty' + index).val();
				$('.inedx_pop').val(index);
				$('.bulk_line_product_id').select2('val', ['']);
				$('.bulk_pdt_qty').val('');
				var rowCount = $('.pdt_table tbody tr').length;
				$('.pdt_table tbody tr').each(function (k) {
					if (k != 0) {
						$($(this).closest("tr")).remove();
						removeclassfields1();
					}
				});
				if (lineqty != '') {
					$('.pdt_table  tr:gt(1)').remove();
					var arr = lineqty.split(',');
					var count = arr.length;
					for (var i = 1; i < (count); i++) {
						$('.add_row1').trigger('click');
					}

					$.each(arr, function (ind, value) {
						$('.bulk_pdt_qty' + ind).val(value);
					});

				}
				else {
					var count = 0;
				}


				var linepdtid = $('.bulk_line_pdt_id' + index).val();
				if (linepdtid != '') {
					//$('.pdt_table  tr:gt(1)').remove();
					var arr1 = linepdtid.split(',');
					var count = arr1.length;
					$.each(arr1, function (ind1, value) {
						$('.bulk_line_product_id' + ind1).val(value).change();
					});

				}
				else {
					var count = 0;
				}

				$('.savepdt').attr('data-index', index);
				$('#giftPdtModal').modal('show');

			});
			$(document).on('click', '.savePdt', function () {
				var check = 0;
				$('.bulk_pdt_qty').each(function () {
					var valid = $(this).val();
					if (valid == '') {
						check++;
					}
				});
				$('.bulk_line_product_id').each(function () {
					var vali = $(this).val();
					if (vali == '') {
						check++;
					}
				});
				if (check == 0) {
					var index = $('.inedx_pop').val();
					var giftqtys = [];
					var giftpdts = [];
					$('.bulk_pdt_qty').each(function () {
						giftqtys.push($(this).val() ? $(this).val() : 0);
					});
					var giftqty = giftqtys.toString();

					$('.bulk_line_product_id').each(function () {
						if ($(this).val() != "") {
							giftpdts.push($(this).val());
						}
						else {
							giftpdts.push('0');
						}
					});
					var giftpdt = giftpdts.toString();
					//$('.bulk_gift_product_id'+index).select2('val',(giftpdt).split(','));
					$('.bulk_line_pdt_id' + index).val(giftpdt);
					$('.bulk_line_pdt_qty' + index).val(giftqtys);
					$('.pdt_body tr:gt(0)').remove();
					$('#giftPdtModal').modal('hide');
				} else {
					notyMsg("warning", "Please fill Product Details");
				}
			});


			$(document).on('change', '#scheme_type', function () {
				var type = $(this).val();
				console.log(type);

				if (type == "order_based") {

					$('.scheme_base').hide();
					$('.bulk_product_id').attr('data-parsley-required', false);
					$('.pdtdiv').hide();
					$('.bulk_scheme_base').attr('data-parsley-required', false);


				} else {


					$('.scheme_base').show();
					$('.bulk_product_id').attr('data-parsley-required', true);
					$('.pdtdiv').show();
					$('.bulk_scheme_base').attr('data-parsley-required', true);

				}


			});


			$('#scheme_type').trigger('change');

			/* -- Start Chart Upper Case --*/
			$('.schemes_name').on('keyup', function () {
				this.value = this.value.toUpperCase();
			});
			/* -- End Chart Upper Case --*/

			/* -- Start Duplicate validate function --*/
			var dup_check = true;
			function validate() {
				$('.bulk_schemes_type').each(function () {
					var scheme = $(this).val();
					var scheme_type = $('#scheme_type').val();
					if (scheme == "Gift" && scheme_type == "order_based") {
						var bulk_line_product_id = $('.bulk_line_product_id').select2('val');
						var bulk_pdt_qty = $('.bulk_pdt_qty').val();
						if (bulk_line_product_id != '' && bulk_line_product_id != '') {

						} else {
							dup_check = false;
							showCustomAlert("Please fill gift product", "warning");
						}
					}
				});
			}

			// save
			$(document).on('click', '.saveform', function () {
				var btnval = $(this).val();

				if (btnval == 'APPLYCHANGES') {
					var savestatus = 'APPLY CHANGES';
				}

				else if (btnval == 'DRAFT') {
					var savestatus = 'DRAFT';
				}


				else if (btnval == 'REJECT') {
					var savestatus = 'REJECTED';
					var approvestatus = 'REJECTED';

				}
				else if (btnval == 'APPROVED') {
					var savestatus = 'APPROVED';
					var approvestatus = 'INITIATED';

				}
				else if (btnval == 'LEVEL2REJECT') {
					var savestatus = 'REJECTED';
					var approvestatus = 'REJECTED';

				}
				else if (btnval == 'LEVEL2APPROVED') {
					var savestatus = 'APPROVED';
					var approvestatus = 'APPROVED';

				}
				else {
					var savestatus = 'INITIATED';
					var approvestatus = 'INITIATED';
				}

				$('#savestatus').val(savestatus);
				$('#approvestatus').val(approvestatus);

				var url = "{{ url('schemessave') }}";
				var red_url = "{{ url('schemes') }}";
				var create_url = "{{ url('schemescreate') }}/0";
				validate();
				var form = $('#schemes_form');

				form.parsley().validate();
				var form = $('#schemes_form');
				form.parsley().validate();

				if (form.parsley().isValid()) {
					var $btn = $(this);
					$btn.prop('disabled', true);
					var formdata = $('#schemes_form').serialize();
					$.post(url, formdata, function (data) {
						var status = data.status;
						var msg = data.message;
						var id = data.id;
						if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != "APPROVED" && btnval != "REJECT" && btnval != "LEVEL2APPROVED" && btnval != "LEVEL2REJECT") {
							showCustomAlert(msg, status);
							setTimeout(function () {
								window.location.href = create_url;
							}, 1500);
						}
						else if (btnval == "APPROVED" || btnval == "REJECT") {
							showCustomAlert(msg, status);
							setTimeout(function () {
								window.location.href = "{{URL::to('schemesapproval')}}";
							}, 1500);

						} else if (btnval == "LEVEL2APPROVED" || btnval == "LEVEL2REJECT") {
							showCustomAlert(msg, status);
							setTimeout(function () {
								window.location.href = "{{URL::to('schemeslevel2approval')}}";
							}, 1500);

						}
						else {
							showCustomAlert(msg, status);
							setTimeout(function () {
								window.location.href = red_url;
							}, 1500);
						}
					});
				}
			});

		});


		$(document).ready(function () {
			$(".select2").select2({
				allowClear: true
			})

		});



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


		$(document).on("focus", ".schemes_date", function () {

			$(this).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "yy-mm-dd",
				minDate: 0,
				showAnim: "slideDown",
				yearRange: "-25:+0",

			});
		});

	</script>

@endpush