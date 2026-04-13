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
	font-size:15px;
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
	
	  <center><header> INTERVIEW RESCHEDULE </header></center><br>
	   <div class="form-group  custom" > 
                    
                    <img src="{{$message->embed(public_path().'/images/jrks.png')}}" class="image_style" width="104px;" height="height:34px;">
                    <label  class=" control-label col-md-4 text-left" > 
                     DEAR {!!$name_of_the_candidate!!}<br><br>
                    </label>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-6">
                    I’d like to inform you that, unfortunately, we need to reschedule our interview for the{!!$job_title!!} position that we had arranged for <?php echo date(' j \of F Y h:i A' ,strtotime($interview_date)); ?> at our office in
                    </div> 
                    <div class="col-md-2">
                        <?php echo $address1.",".$street.",".$area.",".$city.",".$location_name ;?><br><br> 
                    </div>
                    <div class="col-md-2">
                  Could we reschedule for <?php echo date(' j \of F Y h:i A' ,strtotime($datetime)); ?>? Let me know if that works for you or if you would prefer another date or time this week.<br><br>
                    </div>
     <div class="col-md-2">
                    </div>
     <div class="col-md-2">
                   Please accept my sincerest apologies for any inconvenience.<br><br><br><br>
                    </div>
                 <div class="col-md-2">                
                       Regards,<br><br>
                     
                    </div>
                    <div class="col-md-2">                    
                      <?php echo $company_name."<br/>".$email."<br/>".$website_address;?>

                    </div>
                </div>
        <table style="border:1px;">
   
  
     <tbody>
          
     </tbody>
     </tr>
     </table>
	
	 
                   
   <script>
$(document).ready(function() {
    $('.search_view').click(function(){


    });



});
</script>                             
     
                                
                
</body>
</html>














































































