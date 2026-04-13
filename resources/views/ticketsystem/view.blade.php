@extends('layouts.header')
@section('content')
<style type="text/css">
    .heads1{
        width: 100%;
    }
</style>

 



<form>

<div class="card">
<div class="card-header">
 
 <?php if($pageMethod=="createissue") {?>
    <span class="ui_close_btn"><a href="{{URL::to('createissue')}}" class="collapse-close pull-right btn-danger"></a></span>

                                       <?php }else if($pageMethod=="allocateengineer"){ ?>
    <span class="ui_close_btn"><a href="{{URL::to('allocateengineer')}}" class="collapse-close pull-right btn-danger"></a></span>
                                     <?php  }else if($pageMethod=="allocatetechnician"){?>
    <span class="ui_close_btn"><a href="{{URL::to('allocatetechnician')}}" class="collapse-close pull-right btn-danger"></a></span>
 
                                     <?php } else if($pageMethod=="requestraise"){ ?>
    <span class="ui_close_btn"><a href="{{URL::to('requestraise')}}" class="collapse-close pull-right btn-danger"></a></span>
                                        <?php } 
                                            else{ ?>
    <span class="ui_close_btn"><a href="{{URL::to('approverequest')}}" class="collapse-close pull-right btn-danger"></a></span>
                                    <?php } ?>

<div class="card-body card-block normalform">
    
   <div class="row">
    <div class="col-md-12">
        
        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">

 


                <tbody >
                 <?php if($pageMethod=="createissue") {?>
                 <h2 class="heads1">Issue Details</h2> 
                <?php }else if($pageMethod=="allocateengineer"){ ?>

                 <h2 class="heads1">ALLOCATE ENGINEER</h2>
                <?php  }else if($pageMethod=="allocatetechnician"){?>
                  <h2 class="heads1">ALLOCATE TECHNICIAN</h2>
              <?php }else if($pageMethod=="requestraise"){ ?>
                  <h2 class="heads1">RAISE REQUEST</h2> <?php } 
                                            else{ ?>
                  <h2 class="heads1">approve request</h2> 

                 <?php } ?>






                   <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Department:</b> {!! $department_name !!}</p>
                                            <br>
                                            <p><b>Machine:</b> {!! $machine_name !!}</p>
                                            <br>
                                            <p><b>Breakdown Type:</b> {!! $breakdown_name !!}</p>
                                            <br> 

                                        </td>
										 <td>
                                            <p><b>Issue Date:</b> {!! $issue_date !!}</p>
                                            <br>
                                            <p><b>Causes of Breakdown:</b> {!! $causes !!}</p>
                                            <br>
                                            <p><b>Breakdown Severity:</b> {!! $severity_name !!}</p>
                                            <br>
                                            
                                        </td>
                                        <td>
                                             <p><b>Maintenance Type:</b> {!! $maintenance_type !!}</p>
                                             <br>
                                             <p><b>Active:</b> {!! $active !!}</p>
                                             <br>
                                        </td>
 <?php if($pageMethod=="allocateengineer" || $pageMethod=="allocatetechnician" ||$pageMethod=="requestraise" ||$pageMethod=="approverequest" ) {?>

                                        <td>
                                             <p><b>Allocate Engineer:</b> {!! $first_name !!}</p>
                                             <br>
                                             <p><b>Allocate Technician:</b> {!!$first_name !!}</p>
                                             <br>
                                        </td>
                                    <?php }?>

                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>    
</div>	
</div>

@endsection


