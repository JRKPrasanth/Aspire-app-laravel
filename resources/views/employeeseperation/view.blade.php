@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Report</h3>
@include('layouts.breadcrumb')
<style>
.select2-container--open { z-index: 200000 !important; }
</style>	

<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body mt-2">
    <div class="row g-3 mb-4">
      <!-- Search Box -->
      <div class="col-md-3">
        <input type="text" name="first_keyword" id="first_keyword" placeholder="Search..." class="form-control">
      </div>
      <!-- Department Dropdown -->
      <div class="col-md-3">
        <select name="third_third" id="third_third" class="form-select select2">
          {!! $dept !!}
        </select>
      </div>
      <!-- Search Button -->
      <div class="col-md-3">
        <button type="button" name="search" class="btn btn-primary searched" id="search">
          <i class="bi bi-search"></i> Search
        </button>
      </div>
    </div>

    <!-- Employee List -->
    <?php if(count($list)>0){ ?>
      @foreach($list as $key=>$value)
        @php  
          $department = json_decode($value->department); 
          $image = $value->photo != "" ? $value->photo : "profile_none.jpg";
          $disabled = $value->releive_status == 1 ? "disabled" : "";
        @endphp

        <div class="card shadow-lg rounded-4 border-0">
          <div class="card-body">
            <div class="row g-3">
              <!-- Employee Photo -->
              <div class="col-md-3 text-center">
                <img src="images/profile_images/{{$image}}" 
                     alt="Employee Photo" 
                     class="img-fluid rounded-circle border border-3 border-secondary mb-2" 
                     style="width: 150px; height: 150px; object-fit: cover;">
                <span class="badge bg-success px-3 py-2">{{$value->employee_number}}</span>
              </div>

              <!-- Employee Info -->
              <div class="col-md-9">
                <h4 class="fw-bold text-primary">{{$value->first_name." ".$value->last_name}}</h4>
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Email:</strong> {{$value->email}}</p>
                    <p><strong>Mobile:</strong> {{$value->work_telephone_number}}</p>
                    <p><strong>Employment Status:</strong> {{$value->work_telephone_number}}</p>
                    <p><strong>Date of Birth:</strong> {{$value->date_of_birth}}</p>
                    <p><strong>Company:</strong> {{$value->company_id}}</p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Date of Joining:</strong> {{$value->date_of_joining}}</p>
                    <p><strong>Designation:</strong></p>
                    <p><strong>Department:</strong> {{$value->department}}</p>
                    <p><strong>Experience:</strong> {{$value->years_of_experience}}</p>
                    <p><strong>Biometric Empno:</strong> {{$value->biometric_empno}}</p>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-3">
                  <button type="button" data-action="releive" {{$disabled}} 
                          class="btn btn-danger me-2 action" id="{{$value->employee_id}}">
                    Relieve
                  </button>
                  <button type="button" data-action="cance" 
                          class="btn btn-primary action" id="{{$value->employee_id}}">
                    View
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    <?php } ?>
  </div>
</div>

<!-- Modal -->
<form id="save_data">
  <div class="modal fade" id="changeDate" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Change Relieve Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="employee_id" id="employee_id" class="employee_id">

          <!-- Notice Period -->
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"><span class="text-danger">*</span> Notice Period</label>
            <div class="col-sm-8">
              <select name="notice_period" id="notice_period" class="form-select select2 notice_period" required></select>
            </div>
          </div>

          <!-- Relieve Date (After) -->
          <div class="mb-3 row reliveafter">
            <label class="col-sm-4 col-form-label"><span class="text-danger">*</span> Relieve Date</label>
            <div class="col-sm-8">
              <input class="form-control relivedate releive_date" id="releive_date" name="releive_date" type="text" readonly>
            </div>
          </div>

          <!-- Relieve Date (Before) -->
          <div class="mb-3 row relievebefore">
            <label class="col-sm-4 col-form-label"><span class="text-danger">*</span> Relieve Date</label>
            <div class="col-sm-8">
              <input class="form-control relivedate releive_date_before" id="releive_date_before" name="releive_date1" type="text">
            </div>
          </div>

          <!-- Relieve Reason -->
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"><span class="text-danger">*</span> Relieve Reason</label>
            <div class="col-sm-8">
              <input type="text" name="releive_reason" id="releive_reason" class="form-control" required>
            </div>
          </div>

          <!-- Actual Relieve Date -->
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label">Actual Relieve Date</label>
            <div class="col-sm-8">
              <input class="form-control relivedate" id="releive_date_actual" name="releive_date_actual" type="text">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="save" class="btn btn-success save_form">Save</button>
          <button type="button" class="btn btn-secondary cancel_form" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</form>



@endsection
@push('scripts')

<script>

$( document ).ready(function()
{
    $('.relievebefore').hide();
     /************  check employee before relieve to reassign those employee start ***********/
            var dup_chk_1 = true;
            function check_reassign(emp_id)
            {
                var emp_id=emp_id;              
                $.ajax({
                    cache: false,
                    url: "{{URL::to('relievecheckreassign')}}", //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {emp_id : emp_id},
                    success: function(response)
                    {
                        if(response == 1)
                        {
                             showCustomAlert('Reassign the Employee Reporting and then Request','warning');
                            $("#employee_id").select2('val',['']);
                            dup_chk_1 = false;
                        }
                        else if(response == 0)
                        {
                            var html ="";
                         
                            dup_chk_1 = true;
                        }
                    },
                    error: function(xhr, resp, text)
                    {
                        console.log(xhr, resp, text);
                    }
                });
            }
  /************  check employee before relieve to reassign those employee end ***********/

/*  ssearch data */

 $(document).on('click','.searched',function()
     {
        var firstkeyword = $('#first_keyword').val();
        var secondkeyword = $('#second_keyword').val();
        var thirdthird = $('#third_third').select2('val');

     
        if(thirdthird == '')
            thirdthird = 0;
        else
            thirdthird =thirdthird;

        var url = "{{url('employeesepsearch')}}?searchdata="+firstkeyword+"&dept="+thirdthird;
       
        window.location.href = url;

     });


/* End  */
var condition1 = ' and lookup_type="noticeperiod"';
var url = "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(condition1) + "&order_by=lookuplines_id asc";

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

        $('#notice_period').html('<option value="">-- Select Notice Period --</option>');

        $.each(data, function (i, item) {
            let selected = item.val == "{{ $row->notice_period ?? '' }}" ? 'selected' : '';
            $('#notice_period').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });

        $('#notice_period').trigger('change.select2');

    }
});

	
// relive date 
  var dateToday = new Date();
  $(".relivedate").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd",
      showAnim: "slideDown",
      yearRange: "-25:+0"
  });


         $(document).on('change','#notice_period',function(){
            $('.relievebefore').hide();
            $('.reliveafter').show();
            $('#releive_date').attr('required','true');
            $('#releive_date_before').removeAttr('required','true');
            var notice_period=$("#notice_period").select2("val");
            var today = new Date();
            if(notice_period == '167')
            {
                var date_today=notice_date(today);
                $("#releive_date").val(date_today);
        }
        else if(notice_period == '168')
        {
           today.setDate(today.getDate() + 15);
           var date_today=notice_date(today);
           $("#releive_date").val(date_today);
        }
        else if(notice_period == '169')
        {
                var next= new Date(today.getFullYear(), today.getMonth()+1, 0).getDate();
                today.setDate(today.getDate() + next);
                var date_today=notice_date(today);
                $("#releive_date").val(date_today);
            }
            else if(notice_period=='175')
        {
                var next= new Date(today.getFullYear(), today.getMonth()+1, 0).getDate();
                var next_n=next +15;
                today.setDate(today.getDate() + next_n);
                var date_today=notice_date(today);
                $("#releive_date").val(date_today);
        }
            else if(notice_period=='171')
        {
                var next= new Date(today.getFullYear(), today.getMonth()+1, 0).getDate();
                var next1= new Date(today.getFullYear(), today.getMonth()+2, 0).getDate();
                var next_n=next + next1;
                today.setDate(today.getDate() + next_n);
                var date_today=notice_date(today);
                $("#releive_date").val(date_today);
        }
            else if(notice_period=='172')
        {
                var next= new Date(today.getFullYear(), today.getMonth()+1, 0).getDate();
                var next1= new Date(today.getFullYear(), today.getMonth()+2, 0).getDate();
                var next2= new Date(today.getFullYear(), today.getMonth()+3, 0).getDate();
                var next_n=next + next1 + next2;
                today.setDate(today.getDate() + next_n);
                var date_today=notice_date(today);
                $("#releive_date").val(date_today);
            }
            else if(notice_period=='')
        {
                $("#releive_date").val('');
            }
            else if(notice_period=='267')
        {
              $('#releive_date_before').attr('required','true');
            $('#releive_date').removeAttr('required','true');
          $('.relievebefore').show();
            $('.reliveafter').hide();
            }
        });
            /** notice date automatic change date format funcation **/
        function notice_date(today){
        var dd = today.getDate(); 
            var mm = today.getMonth()+1; 
            var yyyy = today.getFullYear();
            var date_today =yyyy+"-"+mm+"-"+dd;
            return date_today;
    }
	
	
        /** save relieve **/
        $(document).on('click','.save_form',function(){
            
            var data = $('#save_data').serialize();
            var url = "{{URL::to('save/releive')}}";
            var form=$('#save_data');
            form.parsley().validate();
              if (form.parsley().isValid()) {
              var notice_period=$("#notice_period").select2("val");

            $.post(url,data,function(data)
            {
                if(data == 1)
                {
                    showCustomAlert('Relieve Process Saved Successfully','success');
                    location.reload();
                }
            });
                    }
        });


        $(document).on('click','.cancel_form',function(){
            $('#changeDate').modal('hide');
        });
    /** relive popup open function **/
    $(document).on('click','.action',function()
    {
        var action = $(this).attr('data-action');
        if(action == "releive")
        {
            var user_id = "{{Session::get('emp_id')}}";
                var emp_id = $(this).attr('id');

            if(user_id!="1"){
            var result = duplicate_validate(user_id,emp_id);
            }else{
                var result=[];
               result['count']=2; 
            }
           
            if(result['count'] == 1)
            {
                var html='';
                result['list'].forEach(function(item) 
                {
                    
                    html +=item;
                });
                console.log(html);
                showCustomAlert("You can't relieve bcz,these user are only Assigned to You( "+html+" )",'warning');
                 $('#employee_id').val('');
            }
            else if(result['count'] == 2)
            {

                   check_reassign(emp_id);
             if(dup_chk_1){
            $('#employee_id').val(emp_id);
                $('#changeDate').modal('show');
            }
                
            }
              else if(result['count'] == 3)
            {
                $('#employee_id').val('');
                showCustomAlert("You can't relieve  bcz ur not assigned to user",'warning');
            }
          
        }
        else
        {
           
            var id  = $(this).attr('id');
       var url = "{{URL::to('viewseperation')}}/"+id;
       window.location.href= url;
            
        }
    });
	            function duplicate_validate(user_id,emp_id)
            {
                var user_id = user_id;
                var emp_id = emp_id;
                var res;
                $.ajax({
                    cache: false,
                    url: 'employee/checkreport', //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {user_id : user_id,emp_id: emp_id},
                    success: function(response)
                    {
                      res = response;
                    },
                    error: function(xhr, resp, text)
                    {
                        console.log(xhr, resp, text);
                    }
                });
              
                return res;
                
            }
	
	 });
</script>

@endpush
