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
  /*font-weight: bold;*/  
        }
        table td,table th{
          text-align: left;
        }
</style>  
  
  
</head>
<body>

              <br>
         
                       
                        <div class="main_div">
                              <label  class="control-label">Dear Team,</label> <br>
    <p><b>Inquiry No & Date:</b> <?php  echo $salesinquiry_data[0]->inquiry_no; ?> & <?php  echo $salesinquiry_data[0]->inquiry_date; ?></p>
    @foreach ($salesinquiry_data as $key=>$value)
    <p><b>Product Name:</b> <?php  echo $value->concatenated_product ; ?></p>
    <p><b>Required Qty:</b> <?php  echo $value->required_qty ; ?></p>
    <p><b>Need by Date:</b> <?php  echo $value->need_by_date ; ?></p>
     @endforeach                
    <p><b>Remarks:</b> <?php  echo $salesinquiry_data[0]->remarks; ?></p> 
                        </div>
        <br>
		<p>Regards,</p>
		<p>Team - Logistics</p>
           
                
</body>
</html>