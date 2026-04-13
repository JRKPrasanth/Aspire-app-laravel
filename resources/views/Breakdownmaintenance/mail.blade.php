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
.top_bar{
    background: #fe0000;
    color: #000;
    padding: 20px;
    box-shadow: 0px 0px 10px 0px blue;
}
.bottom_bar
{
    background: #fe0000;
    color: #000;
    padding: 20px;
    box-shadow: 0px 0px 10px 0px blue;
    margin-top: 20px;

}
div{
	font-family: 'Open Sans', sans-serif;
font-family: 'Courgette', cursive;
font-family: 'Sorts Mill Goudy', serif;
font-family: 'Prata', serif;
	font-size:18px;
	color:#000;  
}

hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 2px solid #fe0000;
    margin:0;
    padding: 0; 
}

    .divRow
    {
       display:table-row;
       width:500px;
    }

    .divCell
    {
        float:left;/*fix for  buggy browsers*/
        display:table-column;
        width:230px;
              
    }
   
    .divCells
    {
        float:left;/*fix for  buggy browsers*/
        display:table-column;
        /*width:800px;*/
        width:100%;
        
    }
    .textal
    {
        text-align: center;
    }
    
    .btn_search{
        padding: 7px;
    color: #fff;
    background: #4f6cd8;
    padding-bottom: 4px;
    text-decoration: none;
   
    border-radius: 15px;
    box-shadow: 0px 0px 10px 0px blue;
}

.side_move{
    margin:0px auto !important;
}
 .btncell
    {
        float:left;/*fix for  buggy browsers*/
        display:table-column;
        width:200px;      
        
    }
</style>  
  
  
</head>
<body>
	
        <table style="border:1px;">
   

              <tbody>
         <tr><td>Ticket Number</td><td>:</td><td>{{ $ticket_number }}</td></tr>
         <tr><td>Ticket Date</td><td>:</td><td>{{ $issue_date}}</td></tr>
         <tr><td>Machine</td><td>:</td><td>{{ $machine_name}}</td></tr>
         <?php if($ticket_status == "OPEN"){ ?>
         <tr><td>Causes of Breakdown</td><td>:</td><td>{{ $causes}}</td></tr>
         <tr><td>Breakdown Severity</td><td>:</td><td>{{ $severity_name}}</td></tr>
         <?php } ?>
         <tr><td>Ticket Raised By</td><td>:</td><td>{{ $user_clear}}</td></tr>
         <?php if($ticket_status == "CLOSED"){ ?>
         <?php if(isset($full_name)){ ?>
         <tr><td>Allocate Engineer</td><td>:</td><td>{{ $full_name}}</td></tr>
         <?php } else { ?>
         <tr><td>Allocate Engineer</td><td>:</td><td> Nil</td></tr>
         <?php } ?>
         <?php if(isset($tech)){ ?>
         <tr><td>Allocate Technician</td><td>:</td><td>{{ $tech}}</td></tr>
         <?php } else { ?>
         <tr><td>Allocate Technician</td><td>:</td><td> Nil</td></tr>
         <?php } ?>
         <?php } ?>
         <?php if($ticket_status == "REQUESTED"){ ?>
         <tr><td>Closure Requested By</td><td>:</td><td>{{ $request_by}}</td></tr>
         <tr><td>Requested On</td><td>:</td><td>{{ $request_on}}</td></tr>
         <tr><td>Request Remarks</td><td>:</td><td>{{ $request_remark}}</td></tr>
         <?php } ?>
         <?php if($ticket_status == "APPROVED"){ ?>
         <tr><td>Closure Approved By</td><td>:</td><td>{{ $approve_by}}</td></tr>
         <tr><td>Approved On</td><td>:</td><td>{{ $approve_on}}</td></tr>
         <tr><td>Approve Remarks</td><td>:</td><td>{{ $approve_remark}}</td></tr>
         <?php } ?>
         <?php if($ticket_status == "REJECTED"){ ?>
         <tr><td>Closure Rejected By</td><td>:</td><td>{{ $approve_by}}</td></tr>
         <tr><td>Rejected On</td><td>:</td><td>{{ $approve_on}}</td></tr>
         <tr><td>Rejection Remarks</td><td>:</td><td>{{ $approve_remark}}</td></tr>
         <?php } ?>
         <tr><td>Ticket Status</td><td>:</td><td>{{ $ticket_status}}</td></tr>
     </tbody>
 
     </table>
</body>
</html>