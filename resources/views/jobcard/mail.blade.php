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

<p>Due to the following reason, we have done the Jobcard pre-closure for {{ $product_name}}</p>

        <table style="border:1px;">
   

    <tbody>
        <tr><td><b>JOB NUMBER</b></td><td>:</td><td>{{ $job_no }}</td></tr>
        <tr><td><b>JOB DATE</b></td><td>:</td><td>{{ $created_date}}</td></tr>
        <tr><td><b>PRODUCT NAME</b></td><td>:</td><td>{{ $product_name}}</td></tr>
        <tr><td><b>JOB STATUS</b></td><td>:</td><td>{{ $job_status}}</td></tr>
         <tr><td><b>JOBCARD CLOSED BY</b></td><td>:</td><td>{{ $user_clear}}</td></tr>
         <tr><td><b>REASON FOR CLOSURE </b></td><td>:</td><td>{{ $remarks}}</td></tr>
         
     </tbody>
 
     </table>
<br>     
<p>Regards,</p>
{{ $user_clear}}
</body>
</html>