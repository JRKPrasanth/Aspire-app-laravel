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
                            <?php if($status!='approve') {?>
                              <label  class="control-label"><b>Dear Sir/Madam,</b> </label>
                              <?php } else { ?>
                              <label  class="control-label"><b>Dear <?php echo $leave_data[0]->first_name; ?>,</b> </label>
                              <?php } ?>
                            <table>
                                <tr>
                                    <th>Employee Name </th>   : <td><?php  echo $leave_data[0]->employee_number."-".$leave_data[0]->first_name ; ?></td>
                                    <th>Leave Type  </th>        :<td> <?php  echo $leave_data[0]->lookup_meaning ; ?></td>
                                    <?php if($leave_data[0]->lookup_meaning=='PERMISSION'){ ?>
                                    <th>Permission For </th>         :<td> <?php  echo $leave_data[0]->leave_mode ; ?> </td>
                                    <?php } else if($leave_data[0]->lookup_meaning=='ON-DUTY'){ ?>
                                    <th>On Duty For </th>         :<td> <?php  echo $leave_data[0]->leave_mode ; ?> </td>
                                    <?php }else { ?>
                                    <th>Leave Mode </th>         :<td> <?php  echo $leave_data[0]->leave_mode ; ?> </td>
                                    <?php } ?>
                                </tr>
                                <?php if($leave_data[0]->lookup_meaning=='PERMISSION'){ ?>
                                <tr>
                                    <th>Start Date Time  </th>       :<td> <?php  echo $leave_data[0]->start_date_time ; ?></td>
                                    <th>End Date Time</th>           :<td> <?php  echo $leave_data[0]->end_date_time ; ?></td>
                                    <th>Requested Permission  </th>   : <td><?php  echo $leave_data[0]->no_of_hrs ; ?> </td>
                               </tr>
                               <?php } else if($leave_data[0]->lookup_meaning=='ON-DUTY' && $leave_data[0]->leave_mode == 'HALF DAY'){ ?>
                                <tr>
                                    <th>OD Start Date  </th>       :<td> <?php  echo $leave_data[0]->od_start_date ; ?></td>
                                    <th>OD End Date</th>           :<td> <?php  echo $leave_data[0]->od_end_date ; ?></td>
                                    <th>Requested OD Hrs  </th>   : <td><?php  echo $leave_data[0]->od_no_of_days ; ?> </td>
                               </tr>
                                <?php }else { ?>
                                <tr>
                                    <th>Start Date   </th>       :<td> <?php  echo $leave_data[0]->start_date ; ?></td>
                                    <th>End Date </th>           :<td> <?php  echo $leave_data[0]->end_date ; ?></td>
                                    <th>No. of Days  </th>   : <td><?php  echo $leave_data[0]->no_of_days ; ?> </td>
                               </tr>
                               <?php } ?>
                                <?php if($leave_data[0]->lookup_meaning=='PERMISSION'){ ?>
                                <tr>
                                      <th>Permission Reason   </th>      :<td> <?php  echo $leave_data[0]->leave_reason; ?> </td>
                                    
                                    <th>Status   </th>      :<td> <?php  echo $leave_data[0]->leave_status ; ?></td>
                                </tr>
                                <?php } else if($leave_data[0]->lookup_meaning=='ON-DUTY'){ ?>
                                <tr>
                                      <th>OD Reason   </th>      :<td> <?php  echo $leave_data[0]->leave_reason; ?> </td>
                                    
                                    <th>Status   </th>      :<td> <?php  echo $leave_data[0]->leave_status ; ?></td>
                                </tr>
                                <?php }else { ?>
                                 <tr>
                                      <th>Leave Reason   </th>      :<td> <?php  echo $leave_data[0]->leave_reason; ?> </td>
                                    
                                    <th>Leave Status   </th>      :<td> <?php  echo $leave_data[0]->leave_status ; ?></td>
                                <?php } ?>
                                <?php if($leave_data[0]->leave_mode == 'HALF DAY') { ?>
                                <th>Session   </th>      :<td> <?php  echo $leave_data[0]->session ; ?></td>
                                <?php } ?>
                                </tr>
                                
                                
                                <?php if($status=='approve') {?>
                                    <?php if($leave_data[0]->lookup_meaning=='PERMISSION'){ ?>
                                    <tr>     
                                    <th>Approved Hours   </th>    :<td> <?php  echo $leave_data[0]->alloted_hrs; ?></td>
                                    <th> Approval Reason   </th>  :<td> <?php  echo $leave_data[0]->approval_reason ; ?> </td>
                                    </tr>
                                    <?php } else if($leave_data[0]->lookup_meaning=='ON-DUTY'){ ?>
                                    <tr>     
                                    <th>Approved OD Days   </th>    :<td> <?php  echo $leave_data[0]->od_alloted_days; ?></td>
                                    <th> Approval Reason   </th>  :<td> <?php  echo $leave_data[0]->approval_reason ; ?> </td>
                                    </tr>
                                    <?php }else { ?>
                                    <tr>     
                                    <th>Approved Days   </th>    :<td> <?php  echo $leave_data[0]->alloted_days; ?></td>
                                    <th> Approval Reason   </th>  :<td> <?php  echo $leave_data[0]->approval_reason ; ?> </td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                            </table>
                            <?php if($status!='approve') {?>
                            <a href="{{URL::to('home')}}">Approve</a>
                            <a href="{{URL::to('home')}}">Reject</a>
                            <?php } ?>
                        </div>
 
		
           
                
</body>
</html>