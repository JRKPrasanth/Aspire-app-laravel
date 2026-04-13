@extends('layouts.header') @section('content')

                 <h3 class="heads">Budgets
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url('budgets') }}"'></a></span>
</h3>

<form method="post" action="" id="budget_form" data-parsley-validate>
{{ csrf_field() }}

<style type="text/css">

.bulk_budget_line_id {width: 100px;}
 .bulk_line_no {width: 100px;}
 .bulk_line_account1 {width: 100px;}
 .bulk_line_account2 {width: 100px;}
 .bulk_line_account3 {width: 100px;}
 .bulk_line_account4 {width: 100px;}
 .bulk_budget_amount {width: 100px;}
 .bulk_overallmonthamt {width: 100px;}
 .bulk_overaccperiod {width: 100px;}

 .bulk_budget_amount_id {width: 100px;}
 .bulk_account_period {width: 100px;}
 .bulk_monthly_amount {width: 100px;}
 .bulk_total_budgetamt {width: 100px;}


</style>
          
<div class="card">

                <div class="card-body card-block">
                <!------------------------------- toggle content start ---------------------------->
                <div class="row">
                <div class="col-md-12">
            <div>
                        <div class="row">
                         <div class="col-md-4">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-size:20px;color:red">*</span>Budget Name</label>
                        <div class="col-md-7">
                            <input class="form-control budget_hdr_id" id="budget_hdr_id" name="budget_hdr_id" size="16" type="hidden" value="" readonly>
                            <input type="text" id="budget_name" name="budget_name" class="form-control budget_name" value="" required />
                            <input type="hidden" value="{{$row->line_id}}" name="line_id" class="line_id">
                        </div>
                        
                    </div>
                               <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Budget Date</label>
                                    <div class="col-md-7">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker budget_date" id="budget_date" name="budget_date" size="16" type="text" value="{{$row->budget_date}}" readonly>
                                            <!-- <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span> -->
                                        </div>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                 <div class="form-group row">
                         <label for="inputIsValid" class="form-control-label col-md-5">Budget Status</label>
                         <div class="col-md-7">
                             <select type="text" name="budget_status" id="budget_status" class="form-control budget_status" readonly>
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->budget_status=="DRAFT" ) echo "selected"; ?> value="DRAFT">DRAFT</option>
                             <option <?php if($row->budget_status=="INITIATED" ) echo "selected"; ?> value="INITIATED">INITIATED</option>
                             </select>
                         </div>
                         
                     </div>
                             
                    <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5">Parent Budget Name</label>
                                <div class="col-md-7">
                                    <select name='parent_budget_id' rows='5' id='parent_budget_id' class='form-control parent_budget_id'>
                                        {!! $parent_budget_id  !!}
                                    </select>
                                </div>
                               

                    </div>
                     <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5">Original Budget</label>
                                <div class="col-md-7 supplier_div">
                                    <select name='original_budget_id' rows='5' id='original_budget_id' class='form-control original_budget_id'>
                                         {!! $original_budget_id !!}
                                    </select>
                                </div>
                               
                            </div>        
                   
                   


			</div>
                        <div class="col-md-4">
                           <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-size:20px;color:red">*</span>Budget Year</label>
                                <div class="col-md-7">
                                    <select name='budget_year' rows='5' id='budget_year' class='form-control budget_year' readonly required>
                                        {!! $budget_year !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline">
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-size:20px;color:red">*</span>Budget From Period</label>
                                <div class="col-md-7">
                                    <select name='budget_from_period_id' rows='5' id='budget_from_period_id' class='form-control budget_from_period_id' required>
                                        {!! $budget_from_period_id !!}
                                    </select>
                                </div>
                                
                            </div>
                             <div class="form-group row">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-size:20px;color:red">*</span>Budget To Period</label>
                                <div class="col-md-7">
                                   <select name='budget_to_period_id' rows='5' id='budget_to_period_id' class='form-control budget_to_period_id' required >
                                        {!! $budget_to_period_id !!}
                                    </select>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Budget From Date</label>
                                    <div class="col-md-7">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker budget_from_date" id="budget_from_date" name="budget_from_date" size="16" type="text" value="" readonly>
                                           <!--  <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span> -->
                                        </div>
                                    </div>
                                   
                                </div>
                            <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Budget To Date</label>
                                    <div class="col-md-7">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker budget_to_date" id="budget_to_date" name="budget_to_date" size="16" type="text" value="" readonly>
                                            <!-- <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span> -->
                                        </div>
                                    </div>
                                   
                                </div>
                            
                        </div>

                            <div class="col-md-4">
                               <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-size:20px;color:red">*</span>Budget Check Level</label>
                                    <div class="col-md-7">
                                        <select name='budget_check_level' rows='5' class='budget_check_level select2' required>
                                            <option value="">--Please Select--</option>
                                            <option value="OBSOLUTE">OBSOLUTE</option>
                                            <option  value="ADISORY">ADISORY</option>
                                        </select>
                                    </div>
                                   
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Budget Currency</label>
                                    <div class="col-md-7">
                                        <select name='budget_currency_id' rows='5' id='budget_currency_id' class='select2 budget_currency_id'>
                                            {!! $budget_currency_id  !!}
                                        </select>
                                    </div>
                                   
                                </div>
                                <div class="form-group row">
                                  <label for="inputIsValid" class="form-control-label col-md-5">Budget Line Total</label>
                                  <div class="col-md-7">
                                      <input type="text" id="budget_line_total" name="budget_line_total" class="form-control budget_line_total" value="{{ $row->budget_line_total }}" readonly />
                                  </div>
                                  
                              </div>
                                 <div class="form-group row" style="display:none;">
                          <label for="inputIsValid" class="form-control-label col-md-5">Actual Line Total</label>
                          <div class="col-md-7">
                              <input type="text" id="actual_line_total" name="actual_line_total" class="form-control actual_line_total" value="" readonly />
                          </div>
                          
                      </div>
                                 <div class="form-group row" style="display:none;">
                          <label for="inputIsValid" class="form-control-label col-md-5">Variance Total</label>
                          <div class="col-md-7">
                              <input type="text" id="variance_total" name="variance_total" class="form-control variance_total" value="" readonly />
                          </div>
                          <div class="col-md-2">
                          </div>
                      </div>
                                <div class="form-group row" >
                                    <label for="inputIsValid" class="form-control-label col-md-5">Active</label>
                                    <div class="col-md-7">
                                        <select name='active' rows='5' class='form-control active' >
                                            <option  value="YES">YES</option>
                                            <option  value="NO">NO</option>
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Company </label>
                                    <div class="col-md-7">
                                        <select name='company_id' rows='5' id='company_id' class='form-control company_id' readonly>
                                            {!! $company_id  !!}
                                        </select>
                                    </div>
                                   
                                </div>
                      

                            </div>
                            <div class="col-md-4">
                               
                               
                                <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Created By </label>
                                    <div class="col-md-7">
                                        <select name='created_by' rows='5' id='created_by' class='form-control created_by'>
                                             {!! $created_by  !!}
                                        </select>
                                    </div>
                                    
                                </div>

                            </div>
                       </div>
                   </div>
                </div>
            </div>

<!------------------------- clone row End-------------------------------->
<div class="row">
   <div class="col-md-4">
        <div class="list-group list-group-tree well">
            <?php echo $tree; ?>
       </div>
   </div>
    <div class="col-md-8">

<a href="javascript:void(0);" class="add_row additem newitem" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>
<div id="preview-area" class="chandru">
               <table class="overflow-y preview budget_table">
                <thead>
                    <tr>
                        
                        <th>Line No</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Department</th>
                        <th>Account Code</th>
                        <th>Budget Amount</th>
                        <th>&nbsp;</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="pmt_lines_body">
                    <?php if(count($linedata)>=1) { ?> @foreach($linedata as $key=>$value)
                       
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_budget_line_id[]" class="form-control  bulk_budget_line_id" value="{{  $value->budget_line_id }}">
                        </td>
                         <td style="display:none;">
                            <input type="hidden" name="bulk_budget_hdr_id[]" class="form-control  bulk_budget_hdr_id" value="{{  $value->budget_hdr_id }}">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="{!! ($key+1)  !!}" readonly="readonly" >
                        </td>
                        <td>
                            <select name="bulk_line_account1[]" id="bulk_line_account1" class="select2 bulk_line_account1" required="required" >{!! $value->line_account1 !!}</select>
                        </td>
                        <td>
                            <select name="bulk_line_account2[]" id="bulk_line_account2" class="select2 bulk_line_account2" required="required">{!! $value->line_account2 !!}</select>
                        </td>
                        <td>
                            <select name="bulk_line_account3[]" id="bulk_line_account3" class="select2 bulk_line_account3" required="required" >{!! $value->line_account3 !!}</select>
                        </td>
                        <td>
                            <select name="bulk_line_account4[]" id="bulk_line_account4" class="select2 bulk_line_account4 select2 parsley-validated" >{!! $value->line_account4 !!}</select>
                        </td>
                        <td>
                            <input type="text"  name="bulk_budget_amount[]" class="form-control  bulk_budget_amount" required="required" value=""> 
                        </td>
                        <td>
                            <button type="button" class="btn flash budgetamt bulk_budgetamt" data-value="{{$value->budget_line_id}}" data-row="0" data-toggle="modal"  >Split Amount </button>
                        </td>
                        <input type="hidden" class="bulk_overaccperiod" name="bulk_overaccperiod[]" value="">
                        <input type="hidden" class="bulk_overallmonthamt" name="bulk_overallmonthamt[]" value="">
                        <td>
                            <a class="remove remove0">
                                <i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i>
                            </a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    @endforeach
                    <?php } if(count($linedata) < 1 ) { ?>
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_budget_line_id[]" class="form-control  bulk_budget_line_id" value="">
                        </td>
                        <td style="display:none;">
                            <input type="hidden" name="bulk_budget_hdr_id[]" class="form-control  bulk_budget_hdr_id" value="">
                        </td>
                        <td>
                            <input type="text"  name="bulk_line_no[]" class="form-control  bulk_line_no" value="" readonly="readonly" >
                        </td>
                       <td>
                            <select name="bulk_line_account1[]" id="bulk_line_account1" class="select2 bulk_line_account1"  required="required" readonly>{!! $line_account1 !!}</select>
                        </td>
                        <td>
                            
                            <select name="bulk_line_account2[]" id="bulk_line_account2" class="select2 bulk_line_account2"  required="required" readonly>{!! $line_account2 !!}</select>
                        </td>
                        <td>
                            <select name="bulk_line_account3[]" id="bulk_line_account3" class="select2 bulk_line_account3" required="required" >{!! $line_account3 !!}</select>
                        </td>
                        <td>
                            <select name="bulk_line_account4[]" id="bulk_line_account4" class="select2 bulk_line_account4" required="required">{!! $line_account4 !!}</select>
                        </td>
                        <td>
                            <input type="text"  name="bulk_budget_amount[]" class="form-control  bulk_budget_amount"  required="required" value="" >
                        </td>
                        <td>
                         <button type="button" class="btn flash budgetamt bulk_budgetamt" data-value="" data-row="0" data-toggle="modal"  >Split Amount </button>
                        </td>
                        <input type="hidden" class="bulk_overaccperiod" name="bulk_overaccperiod[]" value="">
                       <input type="hidden" class="bulk_overallmonthamt" name="bulk_overallmonthamt[]" value="">
                        <td>
                            <a class="remove remove0">
                                <i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i>
                            </a>
                            <input type="hidden" name="counter[]">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <!--<button type="button" class="btn applychanges saveform" value="APPLYCHANGES">Apply Changes</button>-->
            <button type="button" class="btn save saveform" value="DRAFT">Draft</button>
            <!--<button type="button" class="btn save saveform" value="SAVENEW">Save and New</button>-->
            <button type="button" class="btn save saveform" value="SAVE">Save</button>
            <a href="{{ url('budgets') }}" class='btn cancel'>Cancel</a>
        </div>
    </div>
</div>

<input type="hidden" class="pdtindex" value="" />
</div>
</div>



<!--/*Karthigaa Purpose for Budget Amount Based in Periods*/-->
<div class="modal fade" id="budgetmodal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
     <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <!--<h4 class="modal-title">Budget Amount</h4>-->
          <h4 class="modal-title">Budget Amount Total -</h4>
          <input type="text" name="bulk_total_budgetamt[]" class="bulk_total_budgetamt" value="" readonly>
        </div>
        <div class="modal-body form-horizontal" id="modal-body">
            <table class="table table-striped budgtamt_table"  >
                <thead>
                <th></th>
                <th>Account Period</th>
                <th>Amount</th>
            </thead>
                <tbody class="budgtamt_lines_body ">
                    <tr>
                        <td class="account_period">
                            <input type="hidden" name="bulk_budget_amount_id[]" class="form-control  bulk_budget_amount_id" value="">
                            <input type="hidden" name="bulk_account_periods[]" class="form-control  bulk_account_period" value="">
                        </td>  
                        <td class="monthly_amount">
                            <input type="hidden" name="bulk_monthly_amount[]" class="form-control  bulk_monthly_amount" value="">
                        </td>  
                    </tr>
                </tbody>
            </table>
            <div class="wrapper">
                <button type="button" class="btn btn-success budamtsave" data-index="" value="">OK</button></div>
      </div>

    </div>
  </div>
    
    </div>
</form>
<style type="text/css">
#tree-table {

  width: 100%;
  overflow: hidden;
  overflow-x: scroll;
}
 .treegrid-indent {
        width: 0px;
        height: 16px;
        display: inline-block;
        position: relative;
    }

    .treegrid-expander {
        width: 0px;
        height: 16px;
            color: #07234e;
        display: inline-block;
        position: relative;
        left:-17px;
        cursor: pointer;
    }
    #tree-table th {
    background: #07234e;
    border: transparent;
    color: #fff;
  }
</style>
<script type="text/javascript">
 $(document).ready(function() {
// delegated handler
$(".list-group-tree").on('click', "[data-toggle=collapse]", function(){
  $(this).toggleClass('in')
	$(this).next(".list-group.collapse").collapse('toggle');
  
  // next up, when you click, dynamically load contents with ajax - THEN toggle
  return false;
})

});

</script>
<script>
    $(document).ready(function() {
$('.budget_status,.company_id,.parent_budget_id,.original_budget_id,.budget_year,.bulk_line_account1,.bulk_line_account2').css("pointer-events","none");
 $('.bulk_budget_amount').attr("required",true); 
 
  /*Validation*/
	$(document).on('keypress','.bulk_budget_amount', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*End*/
        
 /* Karthigaa code for lower to uppercase */
         $('.budget_name').keyup(function(){
            this.value = this.value.toUpperCase();
         });
         /* end */

/*Karthigaa Purpose For Default company*/
var company = '<?php echo Session::get('companyid'); ?>' ;
$('.company_id').val(company).change();

        var data = "{{\Session::get('j_date_format')}}";
        $(".add_row").relCopy(data);
        
        $('.add_row').click(function() {
            changeclassfields();
//            var a=$(".line_id").val();
//            if(a!="")
//            {
             var rowCount = $('.budget_table tbody tr').length;
 
             var index = Number(rowCount) - Number(1);
              var line1=$('.bulk_line_account10').val();
                var line2=$('.bulk_line_account20').val();
               
                $('.bulk_line_account1'+index).val(line1).change();
                $('.bulk_line_account2'+index).val(line2).change();
//            }
        });
       
        changeclassfields();

        var index = $('.clone').closest('tr').index();
        changeclassfields();

$(document).on('change','.budget_year',function(){
                        var year=$(this).val();
                        if(year !='')
                        {
                        var budgetyear = $(this).val();
                        var year ='year='+budgetyear;
                        
                        $(".budget_from_period_id").jCombo("{{ URL::to('jcomboforminv?table=f_account_periods_t:account_period_id:month') }}&order_by=MONTH(`from_date`)"+'&group_by=month'+'&parent='+year,
                        {selected_value:""});
                        
                        $(".budget_to_period_id").jCombo("{{ URL::to('jcomboforminv?table=f_account_periods_t:account_period_id:month') }}&order_by=MONTH(`from_date`)"+'&group_by=month'+'&parent='+year,
                        {selected_value:""});
                        }
                        else
                        {
                            $(".budget_from_period_id").val('').change();
                            $(".budget_to_period_id").val('').change();
                            notyMsg('error','Please Enter Budget Year !!!');
                        }

                });


                function select_from_date(year,month) 
                {
                    var now = new Date(year,month,1);
                    var first_day = new Date(now.getFullYear(), now.getMonth(), 1);
                    var last_day  = new Date(now.getFullYear(), now.getMonth(), 0);
                    var result = first_day.getDate()  + '-' +  first_day.getMonth()+'-'+first_day.getFullYear();
                    return result;
                }
                function select_to_date(year,id) 
                {
                    var now = new Date(year,id,1);
                    var first_day = new Date(now.getFullYear(), now.getMonth(), 1);
                    var last_day  = new Date(now.getFullYear(), now.getMonth(), 0);

                    var result = last_day.getDate()+'-'+first_day.getMonth()+'-'+first_day.getFullYear();
                    return result;
                }
                
                $('.budget_to_period_id').change(function()
                {
                     var year=$('.budget_year').val();
                    var months={JANUARY:1,FEBRUARY:2,MARCH:3,APRIL:4,MAY:5,JUNE:6,JULY:7,AUGUST:8,SEPTEMBER:9,OCTOBER:10,NOVEMBER:11,DECEMBER:12};
                    var toperiod=$(this).find('option:selected').html();
                    var tomonth=months[toperiod];
                    
                    var month=$('.budget_from_period_id').find('option:selected').html();
                    var monthno=months[month];

                    select_from_date(year,tomonth);
                    select_to_date(year,monthno);
        
                    if(tomonth != '' && monthno != '')
                    {
                        $('.budget_from_date').val(select_from_date(year,monthno));
                        $('.budget_to_date').val(select_to_date(year,tomonth));
                        
                    }
                    else{
                        notyMsg('error',"Please Choose Budget Period");
                    }
                });
                
                
$(document).on('click','.budamtsave',function(){
    var index = $(this).attr('data-index');
var monthamt= [];
var month= [];
$('.bulk_monthly_amount').each(function(){
                         monthamt.push($(this).val());

                       });
$('.bulk_account_period').each(function(){
                         month.push($(this).val());

                       });                       
                        $('.bulk_overallmonthamt'+index).val(monthamt);
                        $('.bulk_overaccperiod'+index).val(month);
                 // console.log(month);
                  
                    });
            
/*Karthigaa purpose:to get month wise Budget amount*/
$(document).on('click change','.budgetamt',function(){
    var index = $(this).closest('tr').index();
    var budyear = $('.budget_year').val();
    var budfrom = $('.budget_from_period_id').val();
    var budto = $('.budget_to_period_id').val();
      var amt = $('.bulk_budget_amount'+index).val();
      var budlineid= $(this).data('value');
    var from_date = $(".budget_from_date").val();
    var to_date   = $(".budget_to_date").val();
   if(budyear != ''){
       if(budto != ''){
        if(amt != ''){
//       if(budlineid !='')
//        {

            $('#budgetmodal').modal('show');
            $('#budgetmodal').width("100%");
          
                $.get("{{ URL::to('monthwisebudget') }}/"+from_date+"/"+to_date,function(data)
			{
				$('.budgtamt_table tbody').html('');
                                $('.budgtamt_lines_body').append(data);
				changeclassfields();
			});
         var index = $(this).closest('tr').index();
             $('.budamtsave').attr('data-index',index);
             
setTimeout(function(){
    
      var lineamount = $('.bulk_overallmonthamt'+index).val();
      var accperiod = $('.bulk_overaccperiod'+index).val();
      var amt = $('.bulk_budget_amount'+index).val();
       $('.bulk_total_budgetamt').val(amt);
          
      result = lineamount.split(',');
      result1 = accperiod.split(',');
       $('.bulk_monthly_amount').each(function(ind){
          $('.bulk_monthly_amount'+ind).val(result[ind]);
          });
          }, 800);
      }
        else{
           notyMsg('error',"Please Enter Budget Amount");
 }
     }
     else{
           notyMsg('error',"Please Choose Budget Periods");
 }
   }
     else{
               notyMsg('error',"Please Choose Budget Year");
            }
  
});  

$(document).on('keyup','.bulk_monthly_amount',function(){
    var index = $(this).closest('tr').index();
    var amt = $('.bulk_total_budgetamt').val();
    var month = 0;
                $('.bulk_monthly_amount').each(function(){
                        month +=Number(isNaN($(this).val())?0:$(this).val());
                       // sum += parseFloat($(this).val());
                 });
                if(month > amt){
                     notyMsg('error',"Please Enter Within Budget Amount");
                      $('.bulk_monthly_amount'+index).val('');
                }
    });

  /* Code for set Budget linetotal values into header level field*/
                $(document).on('keyup','.bulk_budget_amount',function(){
                        var index = $(this).closest('tr').index();
                        var amt = $('.budget_line_total').val();
                        var month = 0;
                $('.bulk_budget_amount').each(function(){
                        month +=Number(isNaN($(this).val())?0:$(this).val());
                       // sum += parseFloat($(this).val());
                 });
                if(month > amt){
                     notyMsg('error',"Please Enter Within Budget Amount");
                      $('.bulk_budget_amount'+index).val('');
                }
        });
                /*End*/  
                   
 $(document).on('click','.budgetamt',function(){
        var index = $(this).closest('tr').index();
      //  alert(index);
      var lineamount = $('.bulk_overallmonthamt'+index).val();
        var accperiod = $('.bulk_overaccperiod'+index).val();
      result = lineamount.split(',');
        result1 = accperiod.split(',');
       $('.bulk_monthly_amount').each(function(ind){
           
          $('.bulk_monthly_amount'+ind).attr('value',result[ind]);

          });
        $('.bulk_account_period').each(function(ind){
          $('.bulk_account_period'+ind).val(result1[ind]);
          });
         
    });
         
/*Purpose for Budget Amount Save*/
$(document).on('click', '.budamtsave', function() {
    $('#budgetmodal').modal('hide');
    });
/*End*/   

$(document).on('click', '.detail_btn', function() {
    var index = $(this).closest('tr').index();
//    var id=$('.parent_budget_id').val();
    var id=$(this).data("id");
    var lineid=$(this).data("value");
    addfunction(id,lineid);
    });




/****  Karthigaa purpose remove function    ***/
        $(document).on('click', '.remove', function() {
            var index = $(this).closest('tr').index();
            var rowCount = $('.budget_table tbody tr').length;
            if (rowCount > 1) {
                $($(this).closest("tr")).remove();
                removeclassfields();
            } else {
                notyMsg('error',"You Can't Delete Atleast One row should be there");
            }
            var sum = 0;
            $('.bulk_budget_amount').each(function(){
                    sum += parseFloat($(this).val());
            });
            $('#budget_line_total').val(sum);
        });
   /*End*/
$(document).on('click', '.saveform', function() {
            var btnval = $(this).val();
            if(btnval == 'APPLYCHANGES'){
			$("#budget_status").val('DRAFT');
		}
            else if(btnval == 'DRAFT'){
			$("#budget_status").val('DRAFT');
	    }else{
			$("#budget_status").val('INITIATED');
		}
            $('#savestatus').val(btnval);
            var url = "{{ url('budgetssave') }}";
            var red_url = "{{ url('budgets') }}";
            var create_url = "{{ url('budgetscreate') }}";

            validationrule('budget_form');
            var form = $('#budget_form');
            if (btnval != 'APPLYCHANGES') {
                //   form.parsley().validate();
                var form = $('#budget_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    change_date();
                    var formdata = $('#budget_form').serialize();
                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg     = '<span style="color:#090065"></span>  '+data.message;
                        var id = data.id;
                        var edit_url = "{{ url('budgetscreate') }}/" + id;

                        if (btnval != 'SAVE' && btnval != 'DRAFT') {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = create_url;
                            }, 1500);
                        } else {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = red_url;
                            }, 1500);
                        }
                    });
                }
            } else {
                change_date();
                var formdata = $('#budget_form').serialize();
                $.post(url, formdata, function(data) {

                    var status = data.status;
		var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
		var id     = data.id;
                    var edit_url = "{{ url('budgetscreate') }}/" + id;
                    notyMsg(status, msg);
                    setTimeout(function() {
                        window.location.href = edit_url;
                    }, 1500);

                });
            }
        });

    });


function addfunction(id,lineid){
            if(id !=""){
        $.get("{{ URL::to('budgetdetails') }}/"+id+"/"+lineid,function(data)
            {
             $('.budget_name').val("");
             $('.budget_hdr_id').val("");
             $('.current_budget_level').val(data['query'][0].current_budget_level);
             $('.parent_budget_id').val(data['query'][0].budget_hdr_id);
             $('.original_budget_id').val(data['query'][0].budget_hdr_id);
             $('.line_id').val(data['sub'][0].budget_line_id);
             $('.budget_year').val(data['query'][0].budget_year);
             $('.budget_from_period_id').html(data['html']);
             $('.budget_to_period_id').html(data['html']);
             $('.budget_from_date').val("");
             $('.budget_to_date').val("");
             $('.budget_check_level').val(data['query'][0].budget_check_level).change();
             $('.budget_currency_id').val(data['query'][0].budget_currency_id).change();
             $('.budget_line_total').val(data['query'][0].budget_line_total);
             $('.company_id').val(data['query'][0].company_id);
             
             var sub=data.sub;
           //console.log(sub);
                $.each(sub,function(k,value){
//               if(k != "0")
//                    {
//                        $('.add_row').trigger('click');
//                    }
                    $('.bulk_line_account1'+k).val(value.line_account1).change();
                    $('.bulk_line_account2'+k).val(value.line_account2).change();
                })
            });
}
else{
            $.get("{{ URL::to('budgetlines') }}/"+lineid,function(data)
            {
            $('.budget_name').val("");
             $('.budget_hdr_id').val("");
             $('.current_budget_level').val(data['query'][0].current_budget_level);
             $('.parent_budget_id').val(data['query'][0].budget_hdr_id);
             $('.original_budget_id').val(data['query'][0].budget_hdr_id);
             $('.line_id').val(data['sub'][0].budget_line_id);
             $('.budget_year').val(data['query'][0].budget_year);
             $('.budget_from_period_id').html(data['html']);
             $('.budget_to_period_id').html(data['html']);
             $('.budget_from_date').val("");
             $('.budget_to_date').val("");
             $('.budget_check_level').val(data['query'][0].budget_check_level).change();
             $('.budget_currency_id').val(data['query'][0].budget_currency_id).change();
             $('.budget_line_total').val(data['query'][0].budget_line_total);
             $('.company_id').val(data['query'][0].company_id);
             
             var sub=data.sub;
           //console.log(sub);
                $.each(sub,function(k,value){
//               if(k != "0")
//                    {
//                        $('.add_row').trigger('click');
//                    }
                    $('.bulk_line_account1'+k).val(value.line_account1).change();
                    $('.bulk_line_account2'+k).val(value.line_account2).change();
                })
            });
            
    }
}

    function changeclassfields() {
        changeClassName('bulk_budget_line_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_line_account1');
        changeClassName('bulk_line_account2');
        changeClassName('bulk_line_account3');
        changeClassName('bulk_line_account4');
        changeClassName('bulk_budget_amount');
        changeClassName('bulk_overallmonthamt');
        changeClassName('bulk_overaccperiod');
        changeClassName('bulk_budgetamt');

        changeClassName('bulk_budget_amount_id');
        changeClassName('bulk_account_period');
        changeClassName('bulk_monthly_amount');
        changeClassName('bulk_total_budgetamt');
       }

    function removeclassfields() {
        removeClass('bulk_budget_line_id');
        removeClass('bulk_line_no');
        removeClass('bulk_line_account1');
        removeClass('bulk_line_account2');
        removeClass('bulk_line_account3');
        removeClass('bulk_line_account4');
        removeClass('bulk_budget_amount');
         removeClass('bulk_overallmonthamt');
         removeClass('bulk_overaccperiod');
         removeClass('bulk_budgetamt');
        
        removeClass('bulk_budget_amount_id');
        removeClass('bulk_account_period');
        removeClass('bulk_monthly_amount');
         removeClass('bulk_total_budgetamt');
      }
    /************ Karthigaa purpose to remove row action ********************/
    function removeClass(className)
	{
        var rowCount = $('.budget_table tbody tr').length;
        for (var i = 0; i <= rowCount; i++) {
            $('.budget_table tbody tr').find('.' + className).removeClass(className + i);
        }
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", 1);
            }
            $(this).addClass(className + index);
        });
    }

    function changeClassName(className)
	{
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", 1);
            }

            $(this).removeClass(className + '0');
            $(this).addClass(className + index);
        });
    }
</script>



@include('layouts.php_js_validation') @endsection
