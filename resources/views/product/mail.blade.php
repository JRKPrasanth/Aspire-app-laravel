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

<p>Product Master has been {{ $product_status }}. Please find the Product Details as follows,</p>

        <table style="border:1px;">
   

    <tbody>
        <tr><td><b>PRODUCT CODE</b></td><td>:</td><td>{{ $product_code }}</td></tr>
        <tr><td><b>PRODUCT NAME</b></td><td>:</td><td>{{ $concatenated_product}}</td></tr>
        <tr><td><b>PRODUCT GROUP</b></td><td>:</td><td>{{ $group_name}}</td></tr>
        <tr><td><b>PRODUCT CATEGORY</b></td><td>:</td><td>{{ $category_name}}</td></tr>
        <tr><td><b>PRODUCT SUB-CATEGORY</b></td><td>:</td><td>{{ $subcategory_name}}</td></tr>
        <?php if($product_status == "INITIATED"){ ?>
        <tr><td><b>HSN CODE</b></td><td>:</td><td>{{ $classification_code}}</td></tr>
         <tr><td><b>ACCOUNT CODE</b></td><td>:</td><td>{{ $account_code}}</td></tr>
         <tr><td><b>CONTROL ACCOUNT </b></td><td>:</td><td>{{ $control_account}}</td></tr>
         <tr><td><b>DISCOUNT ACCOUNT CODE</b></td><td>:</td><td>{{ $discount_account}}</td></tr>
         <?php } ?>
         <tr><td><b>SUB-INVENTORY NAME</b></td><td>:</td><td>{{ $subinventory_name}}</td></tr>
         <tr><td><b>SUB-LOCATOR CODE</b></td><td>:</td><td>{{ $locator_code}}</td></tr>
     </tbody>
 
     </table>
<br>     
<p>Regards,</p>
{{ $user_clear}}
</body>
</html>