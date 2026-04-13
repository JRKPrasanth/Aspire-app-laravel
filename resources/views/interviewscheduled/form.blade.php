@extends('layouts.header')
@section('content')
<h3 class="text-danger">Scheduled Interview</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">

        <form action="" id="save" class="p-2">
            <?php $data = \Session::get('data');
            if (isset($data[$pageMethod]['save'])) { ?>

            <input type="hidden" name="edit_id" value="" id="edit_id" />
            {{ csrf_field() }}

            <div class="row g-4">

                <!-- Candidate Name -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <span class="text-danger">*</span> Candidate Name
                    </label>
                    <div class="input-group">
                        <select name="name_of_candidate" id="name_of_candidate"
                                class="form-control select2 name_of_candidate" required></select>
                        <span class="input-group-text bg-light cursor-pointer">
                            <i class="fa fa-refresh jcr_name_of_candidate"></i>
                        </span>
                    </div>
                </div>

                <!-- From Date -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">From Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control start_date" id="from_date" name="from_date">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <!-- To Date -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">To Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control end-date" id="to_date" name="to_date">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

            </div>

            <!-- Buttons -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-primary px-4 search_interview">Search</button>
            </div>

            <div class="text-start mt-3">
                <button type="button" id="change_interviewdate" 
                        class="btn btn-outline-primary change_interviewdate">
                    Change Interview Date
                </button>
            </div>
            <?php } ?>
        </form>


        <!-- Table Header -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover align-middle">
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th class="bg-primary text-white text-center" style="width:10%;">SL.No</th>
                        <th class="bg-primary text-white text-center" style="width:20%;">Candidate Name</th>
                        <th class="bg-primary text-white text-center" style="width:20%;">Interview Date</th>
                        <th class="bg-primary text-white text-center" style="width:10%;">Date</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- Table Body -->
        <div class="table-responsive" style="height:300px; overflow-y:auto;">
            <table class="table table-bordered table-hover">
                <tbody class="blkqty_body text-center">
                    <tr><td colspan="5">No Data Found</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="changeDate" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Change Interview Date</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Interview Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control interview_date" id="interview_date">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-success px-4 changeintdate">Change</button>
                        </div>

                    </div>

                    <input type="hidden" class="serclear_sat" value="0" />

                </div>
            </div>
        </div>

    </div>
</div>


@endsection
@push('scripts')


<script>


	$(document).ready(function () {




		var today = new Date();
		today.setDate(today.getDate() - 1);
		var todaytime = new Date();
		todaytime.setHours(0);
		todaytime.setDate(today.getDate() + 1);

		var endtime = new Date();
		endtime.setHours(23, 59, 59, 999);
		$('.interview_date').datetimepicker({ format: 'dd-mm-yyyy hh:ii:ss', autoClose: true, startDate: todaytime });
		$('.interview_date').datetimepicker({ format: 'dd-mm-yyyy hh:ii:ss', autoClose: true, }).on('changeDate', function (ev) {
			$(this).datetimepicker('hide');
		});
		/** interview date change date Start **/

		$(document).on('click', '.changeintdate', function () {
			var id = $(this).attr('data-val');
			var date = $('.interview_date').val();
			if (date != '') {
				$('.ajaxLoading').show().delay(2000);
				var url = "{{ URL::to('changeinterviewdate') }}/" + id + "/" + date;
				$.get(url, function (data) {
					if (data == 0) {
						showCustomAlert('Interview Date Changed Successfully','success');
						window.location.reload();
					}
					$('.search_interview').trigger('click');

				});
			}


		});

		$(document).on('click', '.interview_id', function () {
			var inverview_id = $(this).val();
			var inverview_date = $(this).attr('data-date');
			$('.change_interviewdate').attr('data-val', inverview_id);
			$('.change_interviewdate').attr('data-date', inverview_date);
		});
		/** interview date change date End **/


		$(document).on('click', '.change_interviewdate', function () {
			var val = $(this).attr('data-val');
			var date_val = $(this).attr('data-date');
			$('.interview_date').val(date_val);
			$('.changeintdate').attr('data-val', val);
			if ((val != "") & (date_val != "")) {
				$('#changeDate').modal('show');
			}
			else {
				showCustomAlert('info', 'Please Select a Row');
			}

		});

		/** Search interview date change date Start **/
		$(document).on('click', '.search_interview', function () {

			var employee_id = $('.name_of_candidate').select2('val');
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();

			var form = $('#save');
			form.parsley().validate();
			var form = $('#save');
			form.parsley().validate();
			$('.change_interviewdate').attr('data-val', '');
			$('.change_interviewdate').attr('data-date', '');
			if (form.parsley().isValid()) {
				var url = "{{URL::to('searchscheduledinterview')}}/?employee_id=" + employee_id + "&start_date=" + from_date + "&end_date=" + to_date;
				$.get(url, function (data) {
					//var data =$.parseJSON(data);
					$('.blkqty_body').html("");
					$('.blkqty_body').append(data);
				});

			}
		});

	});


</script>

@endpush