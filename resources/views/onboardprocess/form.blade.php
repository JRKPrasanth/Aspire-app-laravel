@extends('layouts.header')
@section('content')

<h2 class="heads">Job Description</h2>




<div class="card">


    <div class="card-body card-block">

        <form  action="" id="jobdescription" data-parsley-validate >
        <input type="hidden" name="edit_id" value="{{$row->description_id}}" id="edit_id" />
            {{ csrf_field()}}
        <div class="row">
            <div class="col-md-6">
                
                <div class="form-group row">
                    <label for="start_date" class="form-control-label col-md-5"><span >*</span>Team Lead Name </label>
                    <div class="col-md-7" >
                      <select name='team_leads_id' rows='5'  id="team_leads_id" class='select2 gender' data-show-subtext="true" data-live-search="true">
                              {!! $row->employee !!}
                      </select>
                    </div>
                </div>
                
                <div class=" form-group row">
                    <label for="fob_point_name" class="form-control-label col-md-5"><span >*</span>Description Name</label>
                    <div class="col-md-5">
                        <input type="text" id="description_name" name="description_name" class="form-control description_name" value="" required>
                        <span class="btn btn-danger dup_name" style="display:none;"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="start_date" class="form-control-label col-md-5"><span >*</span>Required Skills</label>
                    <div class="col-md-7">
                        <textarea name='reqired_skills' id="reqired_skills" class='form-control reqired_skills' >
                        </textarea>
                    </div>
                </div>

                <div class=" form-group row">
                    <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Month & Year</label>
                    <div class="col-md-6" style="margin-left:-13px;">
                    <div class="col-md-6">
                       
                            <select  id="month" name="month" class="select2 month" value="" required>
                                <option value="">--- Please Select --</option>
                                <option value="1">Jan</option>
                                <option value="2">Feb</option>
                                <option value="3">March</option>
                            </select>
                        
                    </div>
                    &nbsp;
                    <div class="col-md-6">
                            <select  id="year" name="year" class="select2  year" value="" required>
                                <option value="">--- Please Select --</option>
                                <option value="29">2018</option>
                                <option value="28">2017</option>
                            </select>
                       
                    </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="organization_id" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Department</label>
                    <div class="col-md-7">
                            <select name='department' rows='5' id="department" class='select2 department' data-show-subtext="true" data-live-search="true" required>
                               {!! $row->department !!}
                            </select>
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="active" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Job title</label>
                        <div class="col-md-7">
                              <div class="row">
                              </div>

                            <select name='job_title' rows='5' id="job_title" class='select2 marital_status' data-show-subtext="true" data-live-search="true" required>
                               {!! $row->jobtitle !!}
                              </select>
                        </div>
                    </div>
            </div>

            <div class="col-md-6">
                    
                
                    <div class="form-group row">
                        <label for="active" class="form-control-label col-md-5 "><span style="font-style:20px;color:red;">*</span>Min Salary</label>
                        <div class="col-md-4">
                            <input class="form-control  " id="min_salary" name="min_salary" size="16" type="text" value="" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Max Salary</label>
                        <div class="col-md-4">
                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" id="max_salary" name="max_salary" size="16" type="text" value="" required>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Interview Process</label>
                        <div class="col-md-4">
                            <div class="col-md-12">
                                <select name="int_pro" id="int_pro" class="select2 form-control" required multiple>
                                     {!! $row->interview_process!!}
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Min Experience in years</label>
                        <div class="col-md-4">
                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control  " id="min_experience" name="min_experience" size="16" type="text" value="" required>
                            </div>
                        </div>
                    </div>
                
                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Max Experience in years</label>
                        <div class="col-md-4">
                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control  " id="max_experience" name="max_experience" size="16" type="text" value="" required>
                            </div>
                        </div>
                    </div>
                
                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>No of Employees Needed</label>
                        <div class="col-md-4">
                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control  " id="no_of_persons" name="no_of_persons" size="16" type="text" value="" required>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
                
                

        <div class="row text-center">
            <button type="button"  class="btn save save_form">Save</button> &nbsp;&nbsp;&nbsp;
            <button type='button'  class='btn del clear' id="delete">Clear</button>
        </div>
    </form>

    <div class="panel-title ">
            <div class="row">
            <div class="col-md-12">
            <button class="btn sec edit"> Edit</button>
<!--            <a class="btn vie view" > View</a>-->
            <button type='button' href='' class='btn del delete'>Delete</button>
    </div>
    </div>
    </div>

<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>
</div>


<script>
	$(document).ready(function()
        {

 // numbers only validation
 $(document).on('keypress', '.amount', function(ev)
                {    
                    var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) 
                    {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
                });
            /** Jqgrid Employee Document Load Data Start **/
            $("#grid1").jqGrid(
            {
                url: "jobdescriptionformgriddata",
                datatype: "json",
                mtype: "GET",
                colModel: [
                    { name: "description_id", label: "description_id", width: 250, hidden: true },
                    { name: "description_name", label: "Description Name.", width: 250},
                    { name: "department", label: "Department", width: 250,hidden: true},
                    { name: "job_title", label: "Job Title", width: 250,hidden: true},
                    { name: "department_name", label: "Department", width: 250},
                    { name: "job_title_name", label: "Job Title", width: 250},
                    { name: "reqired_skills", label: "Required Skills",width: 250},
                    { name: "month", label: "month", width: 250, hidden: true },
                    { name: "year", label: "year", width: 250, hidden: true },
                    { name: "min_salary", label: "min_salary", width: 250, hidden: true },
                    { name: "max_salary", label: "max_salary", width: 250, hidden: true },
                    { name: "interview_process", label: "interview_process", width: 250, hidden: true },
                    { name: "min_experience", label: "min_experience", width: 250, hidden: true },
                    { name: "max_experience", label: "max_experience", width: 250, hidden: true },
                    { name: "no_of_persons", label: "no_of_persons", width: 250, hidden: true },
                    { name: "team_leads_id", label: "team_leads_id", width: 250, hidden: true },
                ],

                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,50,100,200,250,500,1000,2000],
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                caption: "Job Description",
                pager: "#grid1",
                multiselect:false,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
            /** Jqgrid Employee Document Load Data End **/
           
            
            /** Jqgrid Employee Document Edit Data Start **/
            $(".edit").click(function()
            {
                    var index = $("#grid1").jqGrid('getGridParam','selrow');
                    var description_id = $("#grid1").jqGrid ('getCell', index, 'description_id');
                    var description_name = $("#grid1").jqGrid ('getCell', index, 'description_name');
                    var reqired_skills = $("#grid1").jqGrid ('getCell', index, 'reqired_skills');
                    var month = $("#grid1").jqGrid ('getCell', index, 'month');
                    var year = $("#grid1").jqGrid ('getCell', index, 'year');
                    var department = $("#grid1").jqGrid ('getCell', index, 'department');
                    var job_title = $("#grid1").jqGrid ('getCell', index, 'job_title');
                    var min_salary = $("#grid1").jqGrid ('getCell', index, 'min_salary');
                    var max_salary = $("#grid1").jqGrid ('getCell', index, 'max_salary');
                    var int_pro = $("#grid1").jqGrid ('getCell', index, 'interview_process');
                    var min_experience = $("#grid1").jqGrid ('getCell', index, 'min_experience');
                    var max_experience = $("#grid1").jqGrid ('getCell', index, 'max_experience');
                    var no_of_persons = $("#grid1").jqGrid ('getCell', index, 'no_of_persons');
                    var team_leads_id = $("#grid1").jqGrid ('getCell', index, 'team_leads_id');
                    var selRows= $('#grid1 tbody .ui-state-highlight').length;
                    
                    
                    if(description_id != false)
                    {
                        $('#team_leads_id').val(team_leads_id);
                        $('#description_name').val(description_name);
                        $('#reqired_skills').val(reqired_skills);
                        $('#month').select2('val',[month]);
                        $('#year').select2('val',[year]);
                        $('#department').select2('val',department);
                        $('#job_title').select2('val',job_title);
                        $('#min_salary').val(min_salary);
                        $('#max_salary').val(max_salary);
                        $('#max_salary').val(max_salary);
                        $('#int_pro').select2('val',[int_pro]);
                        $('#min_experience').val(min_experience);
                        $('#max_experience').val(max_experience);
                        $('#no_of_persons').val(no_of_persons);
                        $('#edit_id').val(description_id);
                    }
                    else
                    {
                        alert("Please Select Row");
                    }

            });
            /** Jqgrid Employee Document Edit Data End **/


                $('.experience').hide();

                /** Employee Document Experience Start **/
                $(document).on('change','#exp_level',function()
                {
                    var exp = $('#exp_level').select2('val');
                    if(exp == 2)
                    {
                        $('.experience').css("display", "block");
                        $('#current_company_exp').attr('required');
                    }
                    else
                       $('.experience').css("display", "none");
                        $('#current_company_exp,#current_salary').removeAttr('required');
                });
                /** Employee Document Experience End **/
                
                /** Employee Document Cancel **/
                 $(document).on('click','.cancel',function()
                {
                   window.location.href="resumecollection";
                });
                /** Employee Document Cancel **/
                
               
                /** Employee Document Notice Period Start **/
                $(document).on('change','.notice_period',function(){
                    var id = $('.notice_period').select2('val');
                    
                    if(id == 6)
                    {
                       $('.custom').css("display", "block");
                    }
                    else
                    {
                         $('.custom').css("display", "none");
                    }
                });
                /** Employee Document Notice Period End **/
                
                
                /** Employee Document Cancel **/
                $(document).on('click','.cancel',function(){
                    
                    window.location.href="jobdescription";
                });
                /** Employee Document Cancel **/
                

                jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
                
                /** Employee Document Save Start **/
                $(document).on('click','.save_form',function()
                {
                    var url	="{{url('jobdescriptionsave')}}";
                    var data	= $('#jobdescription').serialize();
                    var form = $('#jobdescription');
                    form.parsley().validate();
                    var form = $('#jobdescription');
                    form.parsley().validate();
                    if (form.parsley().isValid())
                    {
                        $.post(url,data,function(data1)
                        {
                            if(data1 == 1)
                            {
                                notyMsg('info','Job Description Saved Successfully');
                                $("#grid1")[0].triggerToolbar();
                                $('#jobdescription')[0].reset();
                            }
                            else
                            {
                               notyMsg('info','Job Description Updated Successfully'); 
                               $("#grid1")[0].triggerToolbar();
                               $('#jobdescription')[0].reset();  
                            }

                        });
                    }
		});
                /** Employee Document Save End **/
                });

	
	</script>
@include('layouts.php_js_validation')
@endsection
