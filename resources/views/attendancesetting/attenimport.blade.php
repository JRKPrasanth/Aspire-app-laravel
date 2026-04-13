@extends('layouts.header')
@section('content')
<style>
	input{
		border:1px solid #ccc;
	}
</style>
<span class="ui_close_btn"></span>

<h3 class="heads">Attendance import and approval
</h3>


<div class="card">
<div class="card-body card-block">
 

     	
        <div class="row">
            <div class="col-md-6">
               <form method="post" action="" id="attendanceimport" class="attendanceimport" data-parsley-validate enctype="multipart/form-data">
                <fieldset><legend> File Upload</legend>

                    <div class="form-group row hidethis " > 
                        <label for="Month" class=" control-label col-md-4 text-left"> 
                            Month
                        </label>
                        <div class="col-md-4">
                            <select name="month" id="month"  class="select2" readonly="true" required></select>
                        </div> 
                        <div class="col-md-2">

                        </div>
                    </div> 	
                    <div class="form-group row hidethis " > 
                        <label for="Month" class=" control-label col-md-4 text-left"> 
                            Year
                        </label>
                        <div class="col-md-4">
                            <select name="year" id="year"  class="select2" readonly="true" required></select>
                        </div> 
                        <div class="col-md-2">

                        </div>
                    </div> 

                    <div class="form-group row hidethis " > 
                        <label for="Month" class=" control-label col-md-4 text-left"> 
                            File
                        </label>
                        <div class="col-md-4">
                            <input type="file" class="atten_file"   name="atten_file" class="form-control" required readonly="true">
                        </div> 
                        <div class="col-md-2">

                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-4 text-right">&nbsp;</label>
                        <div class="col-sm-8">	
                            <button type="button"  class="btn upload file_upload">Upload</button>
                            <a href="{{ URL::to('/download/emp_attendance.csv')}}"  class="btn download" download>Download Template</a>
                        </div>	
                    </div> 
                </fieldset>
                   
            </div> 
            
            <div class="col-md-6">
                <fieldset><legend> Approval</legend>

                    <div class="form-group row " > 
                        <label for="Month1" class=" control-label  col-md-3 text-left"> 
                            Month
                        </label>
                        <div class="col-md-4">
                            <select name="month1" id="month1" class="select2 "></select>
                        </div> 
                        <div class="col-md-2">

                        </div>
                    </div> 	
                    <div class="form-group  row" > 
                        <label for="year1" class=" control-label col-md-3 text-left "> 
                            Year
                        </label>
                        <div class="col-md-4">
                            <select name="year1" id="year1" class="select2"></select>
                        </div> 
                        <div class="col-md-2">

                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-4 text-right">&nbsp;</label>
                        <div class="col-sm-8">	
                            <button type="button" class="btn approve month_approved"> Approved </button>

                        </div>			
                    </div>
                </fieldset>                    
            </div>
        </div> 
     
    

</div>
</div>
         

    
<style>
.table-responsive thead tr th p {
margin: 0;
width: auto;
padding: 4px 1px;
text-align: left;
}
</style>
<script>
$(document).ready(function()
{
        
        var condition1 ='1=1';
		$("#month,#month1").jCombo("{{ URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id asc"+'&parent='+condition1,
                {selected_value:'<?php echo date("m"); ?>'});
		
  var condition1 ='1=1';
		
               var min = 1900,
					max = new Date().getFullYear(),
					select = document.getElementById('year');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}
                                  var min = 1900,
					max = new Date().getFullYear(),
					select = document.getElementById('year1');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}
                                	$(document).on('click','.file_upload',function()
		{	
			var atten_file =  $('.atten_file').val();
			var month 		 =  $('#month').select2('val');
			var year         =  $('#year').select2('val');
			
                        if(atten_file != '' && year != '' && month != '' ){
		
		var form_data = new FormData(document.getElementById('attendanceimport'));              
                $.ajax({
                  url: "{{URL::to('attendanceuploadexcel')}}",
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
                                        //update progressbar

                                }, true);
                        }
          return xhr;

                }
                }).done(function(data)
		{
	
			notyMsg(data['status'],data['message']);
                          setTimeout(function(){
                            location.reload();
                           }, 200);
		
		});
		
                        }
                        
                        else{
                            notyMsg('Info',"Please Choose All the Fields!!!");
                        }
		});
                
                  $(".month_approved").click(function(){
       var month=$("#month1").select2('val'); 
        var year=$("#year1").select2('val'); 
        if(month == "" && year == "")
        {
            notyMsg('Info',"Please Choose All the Fields!!!");
        }
        else
        {
            var url='attendenceimport/monthapproval/'+month+'/'+year;
            $.get(url,function(data){
                
                  notyMsg(data['status'],data['message']);
                  setTimeout(function(){
                            location.reload();
                           }, 200);
             
            });
        }
    });
     });
</script>

@include('layouts.php_js_validation')
@endsection
