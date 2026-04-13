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
	 .image_style{
    float: right;
    position:  absolute;
    top:  12px;
    right: 32px;
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
</style>  
  
  
</head>
<body>
         
            
<label  class="control-label col-md-4 text-left">Dear Sir,<b> </b><br>
                    <p>{{$datas['msg']}}</p>
                    <img src="{{$message->embed(public_path().'/images/jrks.png')}}" class="image_style" width="104px;" height="height:34px;">
                    <br>
		</label>
                        <!-- transport booking -->
                        @if($datas['status'] == 1)
                        <table class="minimalistBlack">
                                <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>Employee Name</th>
                                    <th>Reference No</th>
                                    <th>No Of Passage</th>
                                    <th>Pick-up Point</th>
                                    <th>Drop Point</th>
                                    <th>Return Date</th>
                                    <th>Travel Purpose</th>
                                </tr>
                                </thead>
                                <tbody>	
                                    <tr>
                                        <td>{{($key + 1)}}</td>
                                        <td>{{$datas['query_list'][0]->first_name}}</td>
                                        <td>{{$datas['query_list'][0]->reference_no}}</td>
                                        <td>{{$datas['query_list'][0]->no_of_passage}}</td>
                                        <td>{{$datas['query_list'][0]->pickup_point}}</td>
                                        <td>{{$datas['query_list'][0]->drop_point}}</td>
                                        <td>{{$datas['query_list'][0]->return_date}}</td>
                                        <td>{{$datas['query_list'][0]->travel_purpose}}</td>
                                    </tr>
                                </tbody>
                        </table>
                        <!-- Guest house booking -->
                        @elseif($datas['status'] == 2)
                        <table class="minimalistBlack">
                                <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>Employee Name</th>
                                    <th>Reference No</th>
                                    <th>No Of Occupie</th>
                                    <th>No of Days</th>
                                    <th>Arriving Date</th>
                                    <th>Depature Date</th>
                                    <th>Guest Details</th>
                                    <th>Designation Details</th>
                                    <th>Visitor Type</th>
                                </tr>
                                </thead>
                                <tbody>	
                                    <tr>
                                        <td>{{($key + 1)}}</td>
                                        <td>{{$datas['query_list'][0]->first_name}}</td>
                                        <td>{{$datas['query_list'][0]->reference_no}}</td>
                                        <td>{{$datas['query_list'][0]->no_of_occupie}}</td>
                                        <td>{{$datas['query_list'][0]->no_of_day}}</td>
                                        <td>{{$datas['query_list'][0]->arriving_date}}</td>
                                        <td>{{$datas['query_list'][0]->depature_date}}</td>
                                        <td>{{$datas['query_list'][0]->guest_detail}}</td>
                                        <td>{{$datas['query_list'][0]->designation}}</td>
                                        <td><?php  if($datas['query_list'][0]->visitor_type=="1") { echo "Company";} else{
                                        echo "Out Side" ; } ?></td>
                                    </tr>
                                </tbody>
                        </table>
                        @elseif($datas['status'] == 3)
                        <table class="minimalistBlack">
                                <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>Employee Name</th>
                                    <th>Reference No</th>
                                    <th>Issue Type</th>
                                    <th>Description</th>
                                    <th>Issue Date</th>
                                    
                                </tr>
                                </thead>
                                <tbody>	
                                    <tr>
                                        <td>{{($key + 1)}}</td>
                                        <td>{{$datas['query_list'][0]->first_name}}</td>
                                        <td>{{$datas['query_list'][0]->reference_no}}</td>
                                        <td><?php  if($datas['query_list'][0]->issue_type=="1") { echo "Electricity";} else if($datas['query_list'][0]->issue_type=="2") { echo "Plumbing";} else if($datas['query_list'][0]->issue_type=="3") { echo "Water";} else if($datas['query_list'][0]->issue_type=="4") { echo "Others";}  ?></td>
                                        <td>{{$datas['query_list'][0]->description}}</td>
                                        <td>{{$datas['query_list'][0]->issue_date}}</td>
                                    </tr>
                                </tbody>
                        </table>
                         <!-- event booking -->
                        @elseif($datas['status'] == 4)
                        <table class="minimalistBlack">
                                <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>Event Date</th>
                                    <th>Employee Type</th>
                                    <th>Event Description</th>
                                </tr>
                                </thead>
                                <tbody>	
                                    <tr>
                                        <td>1</td>
                                        <td>{{$datas['query_list'][0]->event_date}}</td>
                                        <td>{{$datas['query_list'][0]->employee_type}}</td>
                                        <td>{{$datas['query_list'][0]->event_name}}</td>                                        
                                    </tr>
                                </tbody>
                        </table>
                        @endif
                        
		 <br/><br/><br/><br/>
           
                
</body>
</html>














































































