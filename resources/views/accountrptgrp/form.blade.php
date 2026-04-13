@extends('layouts.header')
@section('content')

<style>
.card{
    margin-bottom: 60px;
}
.ui-jqgrid .ui-jqgrid-bdiv {
    position: relative;
    margin: 0;
    padding: 0;
    height: auto !important;
    min-width: 100%;
    overflow: unset !important;
    overflow-x: unset;
    text-align: left;
}
</style>
<h2 class="heads">Account Reporting Group</h2>

    <div class="card">
      

       <div class="card-body card-block">
      <form id="acc_rpt_grp_save" action="" data-parsley-validate>
	   <?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>

        <input type="hidden" value="" name="savestatus" id="savestatus" />

          <input type="hidden" name="edit_id" value="{{$row->account_class_id}}" id="edit_id" class="account_class_id" /> {{ csrf_field()}}
          <!------------------------------------- Body content start here ---------------------------->
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                    <div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">
                                    <span class="req">*</span> Account Group</label>
                                    <div class="col-md-7">
                                        <input type="text" name="account_group" id="account_group" value="{{$row->rpt_grp}}" class="form-control account_group" required tabindex="1">
                                        <span class="btn btn-danger dup_name" style="display:none;"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="created_by" class="form-control-label col-md-5">Created By</label>
                                    <div class="col-md-7">
                                        <select name='created_by' rows='5' class='form-control created_by' required>
                                            {!! $created_by !!}
                                        </select>
                                    </div>
                                </div>                        
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">
                                    <span class="req">*</span>Account Type</label>
                                    <div class="col-md-7">
                                        <select id="account_type" name="account_type" class="form-control select2 account_type"  tabindex="4">
                                            <option value="">--Please Select--</option>
                                            <option value="Balance Sheet">Balance Sheet</option>
                                            <option value="PandL">PandL</option>
                                        </select>                                        
                                    </div>
                                </div>                    
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Active</label>
                                    <div class="col-md-7">
                                        <select name="active" class="form-control active select2" tabindex="4">
                                            <option value="Yes" <?php if ($row->active == 'Yes') {echo "selected";} ?>>Yes</option>
                                            <option value="No" <?php if ($row->active == 'No') {echo "selected";} ?>>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Account Seq. No</label>
                                    <div class="col-md-7">
                                        <input type="text" id="account_seqno" name="account_seqno" class="form-control  account_seqno" required value="{{$row->rpt_seq}}" tabindex="2">
                                    </div>
                                </div>
                            </div>                            
                        </div>
                  <div class="row">
                    <div class="col-md-12 text-center">
                       <button type="submit"  class="btn success save">Save</button> 
                      <?php include('toolbar.php');?>
                    </div>
                  </div>
                </div>
                    </div>
                    </div>
                </div>
            </div>
		<?php } else { ?>
	   <div class="row text-center">
          
       
		
        <?php  include('toolbar.php'); ?>
   
    </div>
	<?php } ?>
	
      <!------------------------------------------------------------------------------------------>

    </form>
  <div class="row">

    <div class="col-md-12">
      <table id="accountrptgrpgrid"></table>
    </div>
  </div>
  </div>
</div>




<script>
/*Karthigaa purpose:To check Duplicate entry*/
var dup_chk = true;
function duplicate_validate()
{
    var account_rpt_grp = $(".account_group").val();
    var edit_id = $("#edit_id").val();
    $.ajax({
        cache: false,
        url: "{{ URL::to('accountrptgrpcheckname/') }}",
        type: 'GET',
        dataType: 'json',
        async: false,
        data: {account_rpt_grp: account_rpt_grp, edit_id: edit_id},
        success: function (response){
            console.log(response);
            if (response == 1)
            {
                $('.dup_name').html('Class:' + account_rpt_grp + ' Already Exists');
                $('.dup_name').show();
                $(".account_group").val('');
                dup_chk = false;

            } else if (response == 0)
            {
                var html = "";
                $('.dup_name').hide();
                dup_chk = true;
            }
        },
        error: function (xhr, resp, text)
        {
            console.log(xhr, resp, text);
        }
    });
}
/*end*/

$(document).ready(function () {
$('.created_by').css("pointer-events","none");
/*Karthigaa Purpose For Save Function*/
    var form=$("#acc_rpt_grp_save");
    form.parsley();
    form.submit(function(){
    $('input[name=_token]').val("{{csrf_token()}}");
    var data;
    data = form.serialize();
    var url="{{ URL::to('accountrptgrpsave') }}";
    form.parsley().validate();
    if (form.parsley().isValid())
    {
        duplicate_validate();
        if(dup_chk==true)
        {
            $.post(url, data, function(data1)
            {
    
             var status = data1.status;
             var msg    = data1.message;
             notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+"");
    
                $("#accountrptgrpgrid")[0].triggerToolbar();
                $(".reset").trigger('click');
            });
        }
        return false;
    }
    return false;
    });
/*  ---End Save Function--- */
/*Karthigaa Purpose for Excel Download*/
$(document).on('click',".exportexcel",function() {
    $("#accountrptgrpgrid").jqGrid("exportToExcel",{
                                            includeLabels : true,
                                            includeGroupHeader : true,
                                            includeFooter: true,
                                            fileName : "Account Class.xlsx"
                                    })		 
});  
/*Karthigaa Purpose for Pdf Download*/
    $(document).on('click',".exportpdf",function() {
               $("#accountrptgrpgrid").jqGrid('exportToPdf', {
         title: null,
         orientation: 'portrait',
         pageSize: 'A4',
         description: null,
         onBeforeExport: null,
         download: 'download',
         includeLabels : true,
         includeGroupHeader : true,
         includeFooter: true,
         fileName : "Account Reporting Group.pdf",
         mimetype : "application/pdf"  
       });
       });
    /*Karthigaa Purpose for Upper Case */
    $('.account_class_name,.main_account_code').on('keyup', function () {
        this.value = this.value.toUpperCase();
    });

    $(".select2").select2();
    $(".select2").css('width','100%');
/*Karthigaa Purpose For Displaying data in JQgrid*/
    $("#accountrptgrpgrid").jqGrid({
        url: "{{URL::to('getAccountrptgrpData')}}",
        mtype: 'GET',
        datatype: 'json',
        colModel: [
            {name: "acc_rpt_grp_id", align: "center", hidden: true},
            {name: "rpt_grp", align: "center", label: "Report Group"},
             {name: "rpt_type", align: "center", label: "Report Type"},
            {name: "rpt_seq", align: "center", label: "Report Seq. NO"},
            {name: "active", align: "center", label: "Active"},
            {name: "username", align: "center", label: "Created By"},
        ],
        rowNum: 10,
        rowList: [10, 20,50,100,250,500,1000],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers: true,
        caption: "",
        pager: "#accountrptgrpgrid",
        autowidth: true,
        viewrecords: true,
                searching: {
                    defaultSearch: "cn"
                }
    });
    jQuery("#accountrptgrpgrid").jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});
    $("#accountrptgrpgrid").jqGrid("setLabel", "rn", "S.No");
    showcolumn('accountrptgrpgrid');
/*Karthigaa Purpose for Edit Function*/
    $("#editdata").click(function (){
      var form=$("#acc_rpt_grp_save");
      form.parsley().destroy();
        var gr = $("#accountrptgrpgrid").jqGrid('getGridParam', 'selrow');
        if (gr)
        {
            var id = $("#accountrptgrpgrid").jqGrid('getCell', gr, 'acc_rpt_grp_id');
            var rpt_grp = $("#accountrptgrpgrid").jqGrid('getCell', gr, 'rpt_grp');
            var rpt_type = $("#accountrptgrpgrid").jqGrid('getCell', gr, 'rpt_type');
            var rpt_seq = $("#accountrptgrpgrid").jqGrid('getCell', gr, 'rpt_seq');
            var active = $("#accountrptgrpgrid").jqGrid('getCell', gr, 'active');
            
            $('#account_group').val(rpt_grp);
            $('#account_type').select2('val',[rpt_type]);
            $('#account_seqno').val(rpt_seq);
            $('.active').val(active);
            $('#edit_id').val(id);
        } else
        {
            notyMsg("info", "Please Select Row");
        }
    });
/*End*/

 /*Validation*/
	$(document).on('keypress','.main_account_code', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*End*/
  /*copy past validation*/
     $('.main_account_code').bind("cut copy paste", function(e) { 
        e.preventDefault();
            });
  /*copy past validation*/
/*Karthigaa Purpose for Clear Search*/
    $('.reset').click(function () {
        var form=$("#acc_rpt_grp_save");
        form.parsley().destroy();
        $(':input', '#save')
                .not(':button, :submit, :reset')
                .val('')
                .prop('checked', false);
        $('.account_group').val('');
        $('.account_type').select2('val',['']);
        $('.account_seqno').val(''); 
        $('.active').val('Yes');
        $('#edit_id').val('');
    });
    /*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#accountrptgrpgrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
              $('input[id*="gs_"]').val("");
    });
/*end*/
/*Karthigaa Purpose for Delete*/
    $(".delete").click(function () {
        var gr = jQuery("#accountrptgrpgrid").jqGrid('getGridParam', 'selrow');
        var id = jQuery("#accountrptgrpgrid").jqGrid('getCell', gr, 'account_class_id');
        if (gr)
        {
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
            }, function (e)
            {
                if (e == true)
                {
                    var url = "{{ url('accountclassdelete') }}/" + id;
                    $.get(url, function (data)
                    {
                        var data = $.trim(data);
                        var red_url = "{{ url('accountclass') }}";
                        var data = $.trim(data);
                        if (data == '0')
                        {
                            notyMsg('success', 'Deleted Successfully!!!', red_url);
                            setTimeout(function () {
                                window.location.href = red_url;
                            }, 1500);
                        }
                        if (data == '1')
                        {
                            notyMsg('error', "You Can't delete , Account class Used in SomeWhere!!!", red_url);
                        }
                    });
                } else
                {
                    $('.apply').css('display', 'none');
                    swal("Cancelled");
                }
            })
            $('.apply').css('display', 'none');
        } else
        {
            notyMsg("info", "Please Select Row");
        }

    });
    /*End*/

    /* start date and end date validation
    var dateToday = new Date();
var dates = $("#start_date, #start_date").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    numberOfMonths: 1,
    minDate: dateToday,
    onSelect: function(selectedDate) {
        var option = this.id == "from" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),
            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
        dates.not(this).datepicker("option", option, date);
    }
});
     end */



  // $('#start_date').datepicker().datepicker('setDate','today');
    var dateToday = new Date();
    var dates=$('#start_date,#end_date').datepicker({defaultDate: "today",dateFormat: "dd/mm/yy",
    changeMonth: true,
    numberOfMonths: 1,
    minDate: dateToday,
    onSelect: function(selectedDate) {
        var option = this.id == "start_date" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),

  date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
  dates.not(this).datepicker("option", option, date);
    }
    });




});
</script>
@include('layouts.php_js_validation')
@endsection
