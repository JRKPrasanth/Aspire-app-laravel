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
<h2 class="heads">Report Date Config</h2>

    <div class="card">
      

       <div class="card-body card-block">
      <form id="acc_class_save" action="" data-parsley-validate>
	 

        <input type="hidden" value="" name="savestatus" id="savestatus" />

          <input type="hidden" name="id" value="" id="edit_id" class="id" /> 
          {{ csrf_field()}}
          <!------------------------------------- Body content start here ---------------------------->
          <div class="row">
            <div class="col-md-12">
              <div class="">
                
                <div>
                  <div class="row">
                   <div class="col-md-4">
                <div class="form-group row" >
                        <label for="inputIsValid" class="form-control-label col-md-5">Date</label>
                        <div class="col-md-7">
                                <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                                        <input class="form-control date datepicker" id="date" name="date"   type="text" value="" >
                                </div>
                        </div>
                </div>
              </div>
                <div class="col-md-4">
                  <div class="form-group row" >
                    <label for="comments" class="form-control-label col-md-5">Comment</label>
                    <div class="col-md-7">
                      <input type="text" id="comments" name="comments" class="form-control comments" value="">
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
	
      <!------------------------------------------------------------------------------------------>

    </form>
  <div class="row">

    <div class="col-md-12">
      <table id="accountclassgrid"></table>
    </div>
  </div>
  </div>
</div>




<script>
/*Karthigaa purpose:To check Duplicate entry*/
var dup_chk = true;
function duplicate_validate()
{
    var account_class_name = $(".account_class_name").val();
    var edit_id = $("#edit_id").val();
    $.ajax({
        cache: false,
        url: "{{ URL::to('accountclasscheckname/') }}",
        type: 'GET',
        dataType: 'json',
        async: false,
        data: {account_class_name: account_class_name, edit_id: edit_id},
        success: function (response){
            console.log(response);
            if (response == 1)
            {
                $('.dup_name').html('Class:' + account_class_name + ' Already Exists');
                $('.dup_name').show();
                $(".account_class_name").val('');
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
    var form=$("#acc_class_save");
    form.parsley();
    form.submit(function(){
      $('input[name=_token]').val("{{csrf_token()}}");
         var data;
        data = form.serialize();
        var url="{{ URL::to('datemastersave') }}";
        form.parsley().validate();
    if (form.parsley().isValid())
    {
    //   duplicate_validate();
         if(dup_chk==true)
    {
        $.post(url, data, function(data1)
        {

         var status = data1.status;
         var msg    = data1.message;
         notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+"");
 window.location.reload();
            $("#accountclassgrid")[0].triggerToolbar();
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
    $("#accountclassgrid").jqGrid("exportToExcel",{
                                            includeLabels : true,
                                            includeGroupHeader : true,
                                            includeFooter: true,
                                            fileName : "Report Date Master.xlsx"
                                    })		 
});  
/*Karthigaa Purpose for Pdf Download*/
    $(document).on('click',".exportpdf",function() {
               $("#accountclassgrid").jqGrid('exportToPdf', {
         title: null,
         orientation: 'portrait',
         pageSize: 'A4',
         description: null,
         onBeforeExport: null,
         download: 'download',
         includeLabels : true,
         includeGroupHeader : true,
         includeFooter: true,
         fileName : "Report Date Master.pdf",
         mimetype : "application/pdf"  
       });
       });

    $(".select2").select2();
    $(".select2").css('width','100%');
/*Karthigaa Purpose For Displaying data in JQgrid*/
    $("#accountclassgrid").jqGrid({
        url: "{{URL::to('getreportdatemasterData')}}",
        mtype: 'GET',
        datatype: 'json',
        colModel: [
            {name: "id", align: "center", hidden: true},
            {name: "date", align: "center", label: "Date"},
             {name: "comments", align: "center", label: "Comments"},
            {name: "username", align: "center", label: "Created By"},
        ],
        rowNum: 10,
        rowList: [10, 20,50,100,250,500,1000],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers: true,
        caption: "",
        pager: "#accountclassgrid",
        autowidth: true,
        viewrecords: true,
                searching: {
                    defaultSearch: "cn"
                }
    });
    jQuery("#accountclassgrid").jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});
    $("#accountclassgrid").jqGrid("setLabel", "rn", "S.No");
    showcolumn('accountclassgrid');
/*Karthigaa Purpose for Edit Function*/
    $("#editdata").click(function (){
      var form=$("#acc_class_save");
      form.parsley().destroy();
        var gr = $("#accountclassgrid").jqGrid('getGridParam', 'selrow');
        if (gr)
        {
        var id = $("#accountclassgrid").jqGrid('getCell', gr, 'id');
        var date = $("#accountclassgrid").jqGrid('getCell', gr, 'date');
            $('#comments').val(comments);
            $('#edit_id').val(id);
        } else
        {
            notyMsg("info", "Please Select Row");
        }
    });
/*End*/

/*Karthigaa Purpose for Clear Search*/
    $('.reset').click(function () {
      var form=$("#acc_class_save");
   form.parsley().destroy();
        $(':input', '#save')
                .not(':button, :submit, :reset')
                .val('')
                .prop('checked', false);
        $('.id').val('');
        $('.comments').val('');
        $('.date').val('');
    });
    /*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#accountclassgrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
              $('input[id*="gs_"]').val("");
    });
/*end*/
/*Karthigaa Purpose for Delete*/
    $(".delete").click(function () {
        var gr = jQuery("#accountclassgrid").jqGrid('getGridParam', 'selrow');
        var id = jQuery("#accountclassgrid").jqGrid('getCell', gr, 'account_class_id');
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


  // $('#start_date').datepicker().datepicker('setDate','today');
    var dateToday = new Date();
    var dates=$('#sdate').datepicker({defaultDate: "today",dateFormat: "dd/mm/yy",
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
