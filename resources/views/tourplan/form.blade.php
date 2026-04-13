@extends('layouts.header')
@section('content')

<style type="text/css">
     table tbody tr:nth-child(1) {
    background: none;
}
/*.flipped{
  transform:rotateY(-180deg);
  height:400px;
  width:200px;
  left:calc(50% - 100px);
  top:calc(50vh - 200px);
}*/
th{
  background: #9675ce;
  color: #fff;
}

.fc-first .fc-day.fc-sun.fc-past,.fc-first .fc-day.fc-mon,.fc-first .fc-day.fc-tue,.fc-first .fc-day.fc-wed,.fc-first .fc-day.fc-thu,.fc-first .fc-day.fc-fri{
  /*background: #6E3882;*/
}
.fc-last .fc-mon.fc-future,.fc-last .fc-tue.fc-future,.fc-last .fc-wed.fc-future,.fc-last .fc-thu.fc-future,.fc-last .fc-fri.fc-future,.fc-last .fc-sat.fc-future{
  /*background: #6E3882;*/
}

.select2-container{
  height: auto !important ;
}

.select2-selection__rendered {
 
  font-size: 11px !important;
}




.select2-container--default .select2-selection--multiple{
  border:none !important ;
}
.select2-container .select2-selection--multiple {
    box-sizing: border-box !important ;
    cursor: pointer !important ;
    display: block !important ;
    min-height: 27px !important ;
    user-select: none !important;
    -webkit-user-select: none !important ;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
   border: none !important;

}
select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
  background: #eee;
  box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
  display: none;
}

/*.fc-event-container .fc-event.fc-event-hori.fc-event-start.fc-event-end{
  
  height: 70px;
  overflow-y: auto;
}
*/
.card ::-webkit-scrollbar {
display: none;
}
.fc-event{
  height: 60px;
  /*overflow-y:auto;*/
}

/*.fc-day.fc-widget-content .fc-day-content{
  padding-top:9px;
}*/

</style>




<?php  error_reporting(0); ?>
<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/fullcalendar.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/fullcalendar.css') }}">


<?php if($urlname == "tourplancreate" ) { ?>
    <h2 class="heads"> Tour Plan
        <span class="ui_close_btn">
            <a href="{{URL::to('tourplan')}}" class="collapse-close pull-right btn-danger"></a>
        </span> 
    </h2> 
<?php }elseif($urlname == "tourapproval" ) { ?>
    <h2 class="heads"> Tour Plan Approval
        <span class="ui_close_btn">
            <a href="{{URL::to($return_url)}}" class="collapse-close pull-right btn-danger"></a>
        </span> 
    </h2> 
<?php } ?>

 
<form action="" method="post" id="priceform" data-parsley-validate>
	
{{ csrf_field() }}
  <div class="modal fade" id="tour">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <!--Moda Header-->
      <div class="modal-header">

          <h4 align="center" class="modal-title"> Tour Plan Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <!-- Modal Body -->
      <div class="modal-body">
          <!--form method="post" action="" id="newaddress" class="newaddress" data-parsley-validate-->
          <div class="form-group row tourplan_data ">
          <input type="hidden" name="index" class="index">
              <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Tour Date </label>
                        <div class="col-md-7">
                            <td><input type="text" name="tour_date" class="tour_date datepicker form-control" id="tour_date" value="{{ $row->tour_date }}" data-link-format="yyyy-mm-dd"  style="width: 100%;" readonly></td>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Tour Type <span style="color: red;" >*</span> </label>
                        <div class="col-md-7">
                            <input class="form-control employee_id" id="employee_id" name="employee_id" type="hidden" value="{{ $employee_id }}" >
                            <input class="form-control tourprogram_id" id="tourprogram_id" name="tourprogram_id" size="16" type="hidden" value="{{ $row->tourprogram_id }}" >
                            <input class="form-control tour_status" id="tour_status" name="tour_status" size="16" type="hidden" value="" >
                             <input class="form-control save_status" id="save_status" name="save_status" size="16" type="hidden" value="" >
                            <select name="tour_details" class=" form-control tour_type select2 " id="tour_type"  required style="width: 100%;">
                                {!! $tour_type !!}
                            </select>
                        </div>
                    </div>

             </div>
                  <div class="col-md-6">
                   <div class="form-group row area_div">
                        <label for="inputIsValid" class="form-control-label col-md-5">Tour Area</label>
                        <div class="col-md-7">
                            <select name="tour_area[]" id="tour_area" class="form-control tour_area select2" multiple style="width: 100%;">
                                {!! $tour_area !!}
                            </select> 
                        </div>
                    </div>
                    
                   
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Remarks </label>
                        <div class="col-md-7">
                            <input type="text" name="remarks" class="remarks form-control" id="remarks" value="{{ $row->remarks }}" >                   
                        </div>
                    </div>
                 </div>
              </div>

             <div class="col-md-12" style="width:100%;margin:auto;text-align:center">
                 <button type="button" class="btn ok tour_save" value="Ok">Save</button>         
                 <?php if($urlname == "tourapproval" ) { ?>
                    <button type="button" class="btn approve save" value="APPROVED">approve</button>
                    <button type="button" class="btn approve save" value="REJECTED">reject</button>
                <?php } ?>        
        </div>
      </div>

          <!--form-->
         <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
    </div>


</form>

<div class="card">
    <div class="tour_data">
      
    </div>
    <!--********************- Body content start here ********************-->
    <div class="card-body card-block">
        <div class="row">
            <div class="col-md-12">
            <?php if($urlname == "tourapproval" ) { ?>
                <input type="checkbox" name="" class="selall" id="selall" value="1" >Select all
                <button type="button" class="btn save" value="APPROVED">approve</button>
                <button type="button" class="btn save" value="REJECTED">reject</button>
            <?php } ?>
                <!-- <a href="{{ url('tourplan') }}" class='btn cancel'>Cancel</a> -->

                <div id='calendar'> </div>

            </div>
        </div>
    </div>
</div>

<script>

   $('#calendar').fullCalendar({
        selectable: true,
         contentHeight:375,
        header: {
                    left: 'prev today next',
                    center: 'title',
                    right: '',

                  },eventMouseover: function(calEvent, jsEvent) {
                    var tooltip = '<div class="tooltipevent" style="font-weight:bold;font-size:10px;color:#fff;width:310px;padding:5px;height:100px;background:rgb(24, 40, 116);position:absolute;z-index:10001;">' + calEvent.title + '</div>';
                    var $tooltip = $(tooltip).appendTo('body');

              $(this).mouseover(function(e) {
                  $(this).css('z-index', 10000);
                  $tooltip.fadeIn('500');
                  $tooltip.fadeTo('10', 1.9);
              }).mousemove(function(e) {
                  $tooltip.css('top', e.pageY + 10);
                  $tooltip.css('left', e.pageX + 20);
              });
          },

            eventMouseout: function(calEvent, jsEvent) {
                $(this).css('z-index', 8);
                $('.tooltipevent').remove();
            },

            dayClick: function(date) {
             // alert('clicked ' + date.format());
             var class1="tp_date";
              date_event(date.format());
            },
            select: function(startDate, endDate) {
             // alert('selected ' + startDate.format() + ' to ' + endDate.format());
            }
      });


    var array = [];
    var bankNamesList = [];
     var item = {};
   function date_event(test){
      

      var pageurl = '<?php echo $urlname; ?>';

      var dateAr = test.split('-');
      var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];

      $('.tour_date').val(newDate);
      var employee_id="{{$employee_id}}";  

            var url = "{{ URL::to('touraeditdata')}}/"+newDate+"?emp_id="+employee_id;
            $.get(url,function(data){
                dlen = data.length;

                if(dlen >= 1){
                  if(newDate == data[0]['tour_date']){
                   
                      var myArray = JSON.parse(data[0]['tour_area']);

                      if(myArray != null){
                          var area = data[0]['tour_area'].split(',');                    
                          $('.tour_area').select2('val',[myArray]);
                          $('.tour_area').change();
                      }else{
                        $('.tour_area').select2('val',data[0]['tour_area'])
                        $('.tour_area').change();
                      }

                      $('.remarks').val(data[0]['remarks']);
                      $('.tourprogram_id').val(data[0]['tourprogram_id']);
                      $('.tour_type').select2('val',[data[0]['tour_details']]);

                      var status = data[0]['status'];
                      if(status == "APPROVED"){
                          $('.remarks,.tour_type').attr('readonly',true);
                          $('.tour_save').prop('disabled',true);
                          $('.tour_type,.tour_area').css('pointer-events','0');
                      }else{
                        $('.remarks,.tour_type').attr('readonly',false);
                        $('.tour_save').prop('disabled',false);
                        $('.tour_type,.tour_area').css('pointer-events','');
                      }
                      $('.index').val(length);
                      
                  }
                }else{
                 
                    $('.index').val(length);                    
                    $('.remarks,.tour_type').attr('readonly',false);
                    $('.tour_type,.tour_area').css('pointer-events','');

                    $('.remarks').val('');
                    $('.tour_save').prop('disabled',false);
                    $('.tourprogram_id').val('');
                    $('.tour_type').select2('val',['']);
                }
            });
             
          $('.tour_approvedata').hide();
          $('.tourplan_data').show();
     
        $('#tour').modal('show');
        $('#tour').width("100%");
  	}

$('.tour_data').hide();
  
$('#tour').on('hidden.bs.modal', function () {
  $('.index').val('');
  $('.tour_date').val('');
  $('.tour_type').select2('val',['']);
  $('.tour_area').select2('val',['']);
  $('.tourprogram_id').val('');
  $('.remarks').val('');
});
$(document).ready(function(){

    var employee_id="{{$employee_id}}";  

    var url_val     ="{{ url('disdata') }}?employee_id="+employee_id;
    $.getJSON(url_val,function(data){       
        showcalender(data);       
    });

    function showcalender(data)
    {

    	var source = 'calendar/jsondata?filter=';

    	$('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: '',
            defaultDate: '{{ date("Y-m-d")}}',
            selectable: true,
            selectHelper: true,
    		    eventRender: function (event, element)
            {
                element.attr('href', '#');
            }
            
        });

        $('#calendar').fullCalendar('addEventSource', data['calendar']);
    }

    $(document).on('click','.selall',function(){
        var sel = $('.selall:checked').val();
        var status = [];
        if(sel == 1){
            $('.checkme').each(function () { 
                $(this).prop('checked', true); 
                status.push($(this).val());
            });
        }else{
            $('.checkme').each(function () { 
                $(this).prop('checked', false); 
            });
        }
        $('.tour_status').val(status);
        console.log(status);
    });

$(document).on('click','.tour_save',function(){
      
      var formdata = $('#priceform').serialize();
      console.log(formdata);
      var url = "{{URL::to('tourplansave')}}";
        $('#calendar').fullCalendar('refetchEvents');
        $('#calendar').fullCalendar('renderEvents');
        $('#calendar').fullCalendar('addEventSource', data['calendar']);
          $.ajax({
              cache: false,
              url: url, 
              type: 'POST',
              dataType: '',
              async : false,
              data:formdata,
              success: function(response){
                console.log(response);
                   if(response == 1){
                        notyMsg('success','Updated Successfully');
                        location.reload();
                   }
                   else if(response == 2){
                       notyMsg('success','Saved  Successfully');
                       location.reload();
                   }
                   $('#tour').modal('hide');
              },
              error: function(xhr, resp, text)
              {
                  console.log(xhr, resp, text);
              }
          });
     
});
  
   $(document).on('click','.save',function(){
          var url     ="{{ url('tourplansave') }}";
          var red_url   ="{{ url('tourplan') }}";
          var status = $(this).val();

          $('.save_status').val(status);
         validationrule('priceform');
        var form = $('#priceform');
        form.parsley().validate();
        if(form.parsley().isValid()){
          change_date();
          var formdata  = $('#priceform').serialize();
      
          $.post(url,formdata,function(data)
          {
              var status = data.status;
              var msg    = data.message;
              notyMsg(status,msg);
              window.location.href=red_url;
          });
        }
    });


});

 $('.card').click(function(){
  $('.card').toggleClass('flipped');
 });


</script>

<script type="text/javascript">
$(document).ready(function(){
  

// $(".fc-view>.fc-event-container>.fc-event").hover(function(){
//   alert();
//     $(this).css('cursor','pointer').attr('title', 'This is a hover text.');
//     }, function(){
//     $(this).css('cursor','auto');
// });


// $("#calendar>.fc-content>.fc-view>.fc-event-container>.fc-event").on({
//     mouseenter: function (event) {
//         alert();
//     }

$(".fc-event-time .fc-event-title").mouseenter(function(){
  alert();
    //$(this).css('cursor','pointer').attr('title', 'This is a hover text.');
});


});

$('.tour_area option')
    .filter(function() {
        console.log($(this.value).length == 0);
        return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
    })
   .remove();


</script>
<style>
    #script-warning {
        display: none;
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: red;
    }
    #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
    }

</style>


@include('layouts.php_js_validation') @endsection