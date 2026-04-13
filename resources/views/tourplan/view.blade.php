@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            <h2> Tour Plan</h2>
            <span class="ui_close_btn">  <a href="../tourplan" class="collapse-close pull-right btn btn-xs btn-danger" onclick="tourplan"></a></span>
        </div>

        <div class="card-body">   
        <div class="row">
        <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Tour Plan Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Tour Date:</b> <?php echo date("d-m-Y",strtotime($tourplan->tour_date));?></p> <br>
                                    <p><b>Tour Type:</b> {{$tour_type }}</p> <br>
                                </td>
                                <td class="text-right">
                                    <p><b>Tour Area:</b> {{$tour_area }}</p> <br>
                                    <p><b>Remarks:</b> {{ $tourplan->remarks }}</p> <br>
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

