<!DOCTYPE html>
<?php
error_reporting(0);
?>
<html>
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="boot.css">
<link href="https://fonts.googleapis.com/css?family=Courgette|Open+Sans:300,300i,600,600i,700,800" rel="stylesheet">

  
<style>
	strong{
            font-size:15px;
        
    }
    th{
      width: 20%;
    }
	 .image_style{
    
    position:  absolute;
    top:  12px;
    left: 82px;
	}
    table.minimalistBlack {
  border: 3px solid #000000;
  width: 100%;
  text-align: left;
  border-collapse: collapse;
}
table.minimalistBlack td, table.minimalistBlack th {
  border: 1px solid #000000;
  padding: 5px 4px;
}
table.minimalistBlack tbody td {
  font-size: 13px;
}
table.minimalistBlack thead {
  background: #CFCFCF;
  background: -moz-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
  background: -webkit-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
  background: linear-gradient(to bottom, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
  border-bottom: 3px solid #000000;
}
table.minimalistBlack thead th {
  font-size: 15px;
  font-weight: bold;
  color: #000000;
  text-align: left;
}
table.minimalistBlack tfoot {
  font-size: 14px;
  font-weight: bold;
  color: #000000;
  border-top: 3px solid #000000;
}
table.minimalistBlack tfoot td {
  font-size: 14px;
	}
        .main_div{
            font-size: 14px;
  font-weight: bold;  
        }
        table td,table th{
          text-align: left;
        }
</style>  
  
  
</head>
<body>

              <br>
         
                       
                        <div class="main_div">
                              <label  class="control-label">Dear Mr/Ms {{$emp_name}} ({{$emp_code}}),</b> </b>
                              <br>
                              <br>
                              
                              This notification is with regards to Attendance Regularization/Leave Application in the Leave Management System (LMS).
                              <br>
                              <br>
                              This is to inform you to update your attendance regularization/ Leaves on LMS portal for the days as mentioned below from 01-01-2020 To {{$date}} , failing to do so will result in Loss of Pay (LOP). If you have already applied, it will get updated after your managers approval
                              <br>
                              <br>
                              Note:- Please ignore the mail if you have already applied ARC/ Leave and ensure that approval would be taken by your manager for the below days :
    </label>
                            <table>
                                <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Date</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Worked Hrs</th>
                                <th>Present Status</th>
                                </tr>        
                                </thead>
                                
                                   <?php foreach($overall_data as $index=>$value) { ?>
                                   
                                   <tr>
                                       <td>{{$index+1}}</td>
                                       <td>{{$value->atten_date}}</td>
                                       <td>{{$value->check_in}}</td>
                                       <td>{{$value->check_out}}</td>
                                       <td>{{$value->working_hours}}</td>
                                       <td>{{$value->ststus}}</td>
                                      
                                   </tr>
                                   <?php } ?>
                                   
                            </table>
                            

                       You can also take appropriate action by logging to  Web Portal http://modine.ifive.in.      
                            
                        </div>
 
		
           
                
</body>
</html>