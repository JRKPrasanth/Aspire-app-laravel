@extends('layouts.header')
@section('content')

<style type="text/css">
@media screen and (min-width: 1500px){
  .bulk_line_no{width:155px;}
.bulk_activity_name{width:330px;}
.bulk_prd_category_id{width:250px;}
.bulk_outlet_id{width:350px;}
.bulk_description{width:205px;}
.bulk_prd_id{
    width:190px !important;
}
}
.bulk_line_no{width:55px;}
.bulk_activity_name{width:230px;}
.bulk_prd_category_id{width:150px;}
.bulk_outlet_id{width:250px;}
.bulk_description{width:105px;}
.bulk_prd_id{
    width:190px;
}
.bulk_activity_name{
   background-color: #fff;
    border: 1px solid #375a80;
    width: 100%;
    border-radius: 5px;
    box-shadow: none;
    color: #000;
    height: 30px;
    padding: 7px 12px;
    transition: all 300ms linear 0s;
}
</style>

<?php error_reporting(0);?>
<span class="ui_close_btn"></span>

<h2 class="heads">Document Internal Transfer</h2>

      <div class="card">
       <div class="card-body card-block">
       <form  action=""  id="beatmapping_form" >
                    <input type="hidden" name="doc_hdr_id" value="{{ $row->doc_hdr_id }}" id="doc_hdr_id" />
                    <input type="hidden" name="beatmappinglines_id" value="" id="beatmappinglines_id" />
                          {{ csrf_field()}}
               
                    <div class="row">
                    
                  <div class="col-md-6">
                    <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Employee Name</label>
                            <div class="col-md-7">
                                <select name='employee_id' id="employee_id" rows='5' class='employee_id select2' required="required">
                                    {!! $employee_id!!}
                                </select>
                            </div>
                            <span class="btn btn-danger dup_name" style="display:none;"></span>
                    </div>
                    </div>
               
                  <div class="col-md-6">      
                
                        <div class="form-group row" >
                            <label for="inputIsValid" class="form-control-label col-md-5">Remarks</label>
                            <div class="col-md-7">
                                <input type="text" id="remarks" name="remarks" class="form-control remarks" value="{{ $row->remarks }}">
                            </div>
                        </div>
                      </div> 
                            </div>

 <!-------------------------Linedata -------------------------------->
              <div class="row">
<div class=" col-md-12">
    
<a href="javascript:void(0);" class="add_row additem refbtnhide" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>

<div id="preview-area" class="chandru">
    <table class="overflow-y preview beatmap_table">


<thead>
<tr>
<th >Line No</th>
<th >Activity Name</th>
<th >Start DateTime</th>
<th >End DateTime</th>
<th >Duration</th>
<th>&nbsp;</th>
</tr>
</thead>

<tbody class="beatmapping_form_table">
<?php  if(count($linedata)<=0) { ?>
    <tr class="rcopy clone clonedInput">
        <td><input type="hidden" name="bulk_job_activity_line_id[]" class="form-control  bulk_job_activity_line_id" value=""></td>
        <td><input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no"  readonly="readonly" ></td>
        <td><select id="bulk_activity_name" name="bulk_activity_name[]" class=" bulk_activity_name" value="" required>{!! $activity_name !!}</select></td>
        <td><input type='text' name='bulk_start_datetime[]' class=' bulk_start_datetime form-control start_datetime' value='' required="required"></td>
        <td><input type='text' name='bulk_end_datetime[]' class='bulk_end_datetime  form-control end_datetime' value='' required="required"></td>
        <td> <input type="text" name="bulk_duration[]" class="form-control  bulk_duration"  readonly="readonly" ></td>
        <td style="width: 33px;">
              <a class="remove"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
              <input type="hidden" name="counter[]">
        </td>
    </tr>
    <?php } else { foreach($linedata as $key=>$value){?>
<tr class="rcopy clone clonedInput">
        <td><input type="hidden" name="bulk_job_activity_line_id[]" class="form-control  bulk_job_activity_line_id" value="{{$value->job_activity_line_id}}"></td>
        <td><input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no"  readonly="readonly" value="{{$value->line_no}}" ></td>
        <td><select id="bulk_activity_name" name="bulk_activity_name[]" class=" bulk_activity_name" value="" required>{!! $value->activity_name !!}</select></td>
        <td><input type='text' name='bulk_start_datetime[]' class="bulk_start_datetime form-control start_datetime" value="{{$value->start_datetime}}" required="required"></td>
        <td><input type='text' name='bulk_end_datetime[]' class='bulk_end_datetime  form-control end_datetime' value="{{$value->end_datetime}}" required="required"></td>
        <td> <input type="text" name="bulk_duration[]" class="form-control  bulk_duration"   value="{{$value->duration}}" readonly="readonly"></td>
        <td >
              <a class="remove"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
              <input type="hidden" name="counter[]">
        </td>
    </tr>
    <?php } } ?>
    </tbody>
    </table>
      <div class="row">
       <div class="col-lg-12 col-md-12">
          <div class="form-group text-center">
             <button name="submit" type="button" class="btn save saveform" value="SAVE">SAVE</button>
              <a class='btn cancel' onclick='location.href="{{ url('documentinterntransfer') }}"'>Cancel</a>
          </div>
       </div>
      </div>
   </form>
   </div>      
   </div>
   <input type="hidden" class="so" value='0'>

<input type="hidden" class="pdtindex" value="" />
<script type="text/javascript">
$(document).ready(function()
{

/************************save******************************/
         $(document).on('click','.saveform',function()
{
  
    var form = $('#beatmapping_form');
                validationrule('beatmapping_form');
                form.parsley().validate();
               if (form.parsley().isValid())
    {
    $('.ajaxLoading').show();
      change_date();
    var form_data = new FormData(document.getElementById('beatmapping_form'));
                $.ajax({
                  
                  url: "{{URL::to('documentinterntransfersave')}}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                    
                                }, true);
                        }
          return xhr;

                }
                }).done(function(data)
    {
    var status  = data.status;
    var msg     = data.message;
                 var url  ="{{URL::to('documentinterntransfer')}}"; 
                 
      
    notyMsg(status,msg);
    setTimeout(function(){
        $('.ajaxLoading').hide();
                    window.location.href=url;
    }, 1500);
    
    });
    
    }
  
});











  $('.add_row').click(function()
    {
        var form = $('#beatmapping_form');
        form.parsley().destroy();
  
  });
  var index = $('.clone').closest('tr').index();
        changeclassfields1();
  /*$(".add_row").relCopy(data);
        changeClassfields();
        $('.add_row').click(function()
  {    
    
      changeClassfields();
    });*/
    
     $(document).on('click','.add_row', function() {
            var cloned = $('.beatmap_table').find('tr:eq(1)').clone();
            cloned.appendTo('.beatmapping_form_table');
            cloned.find('input').val('');
            cloned.find('select').val('');
             changeclassfields1();
             
        }); 
               
             $(document).on('click','.rem',function()
{
  var index = $(this).closest('tr').index();
  var rowCount = $('.beatmap_table tbody tr').length;
        
  if(rowCount > 1)
  {
    $($(this).closest("tr")).remove();
    removeclassfields();
  }
  else
  {
    notyMsg('info',"You Can't Delete Atleast One row should be there");
  }
});


     
                 function changeClassfields()
    {
        changeClassName('bulk_job_activity_line_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_activity_name');
        changeClassName('bulk_start_datetime');
        changeClassName('bulk_end_datetime');
        changeClassName('bulk_duration');
       //var edit=$('#job_activity_id').val();
 
    }
    /*deepika purpose:to add class for activity details*/ 
    function changeclassfields1() {
        changeClassName1('bulk_job_activity_line_id');
        changeClassName1('bulk_line_no');
        changeClassName1('bulk_activity_name');
        changeClassName1('bulk_start_datetime');
        changeClassName1('bulk_end_datetime');
        changeClassName1('bulk_duration');
     }
/*end*/
function removeclassfields(){


        removeClass('bulk_job_activity_line_id');
        removeClass('bulk_line_no');
        removeClass('bulk_activity_name');
        removeClass('bulk_start_datetime');
        removeClass('bulk_end_datetime');
        removeClass('bulk_duration');
        
}

function changeClassName(className)
{
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});

}

function changeClassName1(className)
{
$('.' + className).each(function (index)
{

if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

function removeClass(className)
{
  var rowCount = $('.beatmap_table tbody tr').length;
  for(var i=0;i<=rowCount;i++)
  {
  $('.beatmap_table tbody tr').find('.'+className).removeClass(className+i);
  }
  $('.' + className).each(function (index)
  {
    if (className == "bulk_line_no")
    {
    $(this).val(index + 1).attr("readonly", 1);
    }
    $(this).addClass(className + index);
  });
}

//var jobdate = "2022-04-01";
var d = new Date();
var jobdate = d.setDate(d.getDate() - 5);
$('#preview-area').on('mousemove', function() {
$('.start_datetime').datetimepicker({
        startDate : new Date(jobdate),
    endDate : new Date(),
        format: 'yyyy-mm-dd hh:ii',
        useCurrent: false,
        autoclose: true,
        keepOpen: false,
    });
  $('.end_datetime').datetimepicker({
       startDate : new Date(jobdate),
    endDate : new Date(),
        format: 'yyyy-mm-dd hh:ii',
        useCurrent: false,
        autoclose: true,
        keepOpen: false,
    });        
 });
 $(document).on("change",".bulk_start_datetime,.bulk_end_datetime",function(){
      var index=$(this).closest('tr').index();
        var start = $('.bulk_start_datetime'+index).val();
        var end = $('.bulk_end_datetime'+index).val();
        var hrs = $('.bulk_duration').val();
    if(index==0)
    {
         $('.bulk_start_datetime').each(function(i){
       
       if($('.bulk_start_datetime'+i).val()=='')
       {
              $('.bulk_start_datetime'+i).val(start);
       }
        if(end!='')
        {
          if( $('.bulk_end_datetime'+i).val()=='')
          {
              $('.bulk_end_datetime'+i).val(end);
        hourscal(i);
          }
        }
              
         });
    }
    if(end!='')
       hourscal(index);
    
    });    
    
/*deepika purpose: to calculate time based on start & endtime*/ 
function hourscal(index)
{
     
    var end_actual_time=new Date(' '+$('.bulk_end_datetime'+index).val()+"-00");
    var start_actual_time=new Date(' '+$('.bulk_start_datetime'+index).val()+"-00");
    var date=end_actual_time.getFullYear()+"-"+end_actual_time.getMonth()+1+"-"+end_actual_time.getDate()+" "+end_actual_time.getHours()+":"+end_actual_time.getMinutes()+":"+end_actual_time.getSeconds();
      var diff = end_actual_time - start_actual_time;

    var diffSeconds = diff / 1000;
    var HH = Math.floor(diffSeconds / 3600);
    var MM = Math.floor(diffSeconds % 3600) / 60;
    var totlalhr=HH+'.'+MM;
     console.log(totlalhr);
$('.bulk_duration'+index).val(totlalhr);    

}
    

  });
    </script>
@include('layouts.php_js_validation')
@endsection