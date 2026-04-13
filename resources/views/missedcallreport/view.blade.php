@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            <h2> Doctor DCR Details</h2>
            <span class="ui_close_btn">  <a href="../doctordcr" class="collapse-close pull-right btn btn-xs btn-danger" onclick="doctordcr"></a></span>
        </div>

        <div class="card-body">   
        <div class="row">
        <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Doctor DCR Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>

                        <tbody>

                            <tr>
                                <td> 
                                    <p><b>Tour Plan Date:</b><?php echo date("d-m-Y",strtotime($doctordcr->tp_date));?></p> <br>
                                    <p><b>Divert Details:</b>{{$doctordcr->divert_detail}}</p> <br>
                                    <p><b>From Area:</b>{{$from_area}}</p> <br>
                                    <p><b>To Area:</b>{{$to_area}}</p> <br>
                                    <p><b>Doctor Name:</b> {{ $doctor_id }}</p> <br>
                                                                        
                                </td>
                                <td class="text-right">
                                    
                                    
                                    <p><b>Other Product:</b> {{$other_product }}</p> <br>
                                    <p><b>Visit With:</b> {{$visit_with }}</p> <br>
                                    <p><b>DR Reminder:</b> {{$doctordcr->dcr_reminder }}</p> <br>
                                    <p><b>Timing:</b> {{$doctordcr->timing }}</p> <br>
                                    <p><b>Remarks:</b> {{$doctordcr->remarks }}</p> <br>
                                </td>
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
</form>


@endsection	

