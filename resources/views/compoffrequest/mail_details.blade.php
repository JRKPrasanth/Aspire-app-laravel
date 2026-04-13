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
                            <?php if($status=='approve') {?>
                            <label  class="control-label">Dear <?php  echo $leave_data[0]->first_name ; ?>,</b> </b>
                             <?php } else { ?>  
                              <label  class="control-label">Dear Sir/Madam,</b> </b>
                             <?php } ?>  
    </label>
                            <table>
                                <tr>
                                    <th>Employee Name </th>   : <td><?php  echo $leave_data[0]->employee_number."-".$leave_data[0]->first_name ; ?></td>
                                    <th>Request For  </th>        :<td> <?php  echo "Comp-Off"; ?></td>
                                    <th>CompOff Mode </th>         :<td> <?php  echo $leave_data[0]->leave_mode ; ?> </td>
                                </tr>
                                <tr>
                                    <th>Start Date   </th>       :<td> <?php  echo $leave_data[0]->start_date ; ?></td>
                                    <th>End Date </th>           :<td> <?php  echo $leave_data[0]->end_date ; ?></td>
                                    <th>Requested CompOff  </th>   : <td><?php  echo $leave_data[0]->no_of_days ; ?> </td>
                               </tr>
                                <tr>
                                      <th>CompOff Reason   </th>      :<td> <?php  echo $leave_data[0]->leave_reason; ?> </td>
                                    
                                    <th>CompOff Status   </th>      :<td> <?php  echo $leave_data[0]->leave_status ; ?></td>
                                </tr>
                                <?php if($status=='approve') {?>
                                    <tr>     
                                    <th>Approved Days   </th>    :<td> <?php  echo $leave_data[0]->alloted_days; ?></td>
                                    <th> Approval Reason   </th>  :<td> <?php  echo $leave_data[0]->approval_reason ; ?> </td>
                                   
                               </tr>
                                <?php } ?>
                            </table>
                            <?php if($status!='approve') {?>
   <a href="{{URL::to('home')}}">Approve</a>
                            <a href="{{URL::to('home')}}">Reject</a>
                       <?php } ?>     
                        </div>
 
		
           
                
</body>
</html>