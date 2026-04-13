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
	<p>Dear Team,</p>

<p>Ticket has been {{ $status }}. Please find the WebOps Track Details as follows,</p>

        <table style="border:1px;">
   

    <tbody>
        <tr><td><b>TICKET NUMBER</b></td><td>:</td><td>{{ $ticket_number }}</td></tr>
        <tr><td><b>REQUESTED DATE</b></td><td>:</td><td>{{ $start_date}}</td></tr>
        <tr><td><b>EXPECTED DATE</b></td><td>:</td><td>{{ $end_date}}</td></tr>
        <tr><td><b>PRIORITY </b></td><td>:</td><td>{{ $priority}}</td></tr>
        <tr><td><b>CATEGORY </b></td><td>:</td><td>{{ $task_category}}</td></tr>
        <tr><td><b>SUB-CATEGORY </b></td><td>:</td><td>{{ $task_subcategory}}</td></tr>
        <tr><td><b>TASK DETAILS</b></td><td>:</td><td>{{ $task}}</td></tr>
         
     </tbody>
 
     </table>
<br>     
<p>Regards,</p>
{{ $user_clear}}
</body>
</html>