@extends("layouts.header")
@section("content")
<style type="text/css">
     .table1{
        table-layout: fixed;
    }
   
    .table1 th,.table1 td{
/*        min-width: 221px;
*/
    }
    .invoice-box{
        max-width: 1120px;
    }
    .linetable{
        max-width: 1120px;
        background-color:#fff;
        border:0px solid #ddd;
        margin: auto;
        padding: 15px;
        overflow-x:auto;
    }
</style>
					<div class="card">
					 <div class="card-header">
					<span class="ui_close_btn"><span class="ui_close_btn"><a href="{{ URL::to('proofview') }}" class="collapse-close pull-right btn-danger" onclick=" Investment Declare"></a></span></div>
                                           <!-- <div class="col-lg-12">-->
						 <div class="card-body card-block normalform">
							 <form>
							 <div class="row">
                         <div class="col-md-12">

<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Investment Declaretion</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                              <td><p><b>EMPLOYEE NUMBER :</b> {!! $hdr[0]->employee_name !!}</p>
                              <br>
                              <p><b>PAN NUMBER :</b> {!! $pan_no !!}</p>
                              <br>
                              <p><b>Date of Joining:</b> {!! $doj !!}</p>
                              <br>
                              <p><b>Date of Birth:</b> {!! $dob !!}</p>
                              <br>
                              </td>
                              <td>
                               <p><b>Basic :</b> {!! $basic !!}</p>
                              <br>
                              <p><b>DA :</b> {!! $da !!}</p>
                              <br>
                              <p><b>HRA:</b> {!! $hra !!}</p>
                              <br>
                               </td>
                              <td>
                               <p><b>Monthly House Rent Paid :</b> {!! $hdr[0]->Monthly_House_Rent_Paid !!}</p>
                              <br>
                              <p><b>Select Rent Location :</b> {!! $hdr[0]->rent_location !!}</p>
                              <br>
                              <?php if($hdr[0]->dmt==1){
                                $dmt="40%-79%";
                              }else{
                                $dmt="80%";
                              } 
                               if($hdr[0]->smt==1){
                                $smt="40%-79%";
                              }else{
                                $smt="80%";
                              } 
                              ?>
                              <p><b>Dependent Medical Treatment :</b> {!! $dmt !!}</p>
                              <br>
                               <p><b>Self Medical Treatment :</b> {!! $smt !!}</p>
                              <br> 


                              </td>
                            </tr>
                           <tr>
<td>
    <?php foreach($allowance as $ak=>$av) { ?>
 <p><b>{!!$av->allowance_name!!}:</b> {!! $av->allowance_value !!}</p>
  <br>
      <?php } ?>  
</td>

                           </tr>

                        </tbody>
                    </table>
                </td>
            </tr>
           
        </tbody>
    </table>
</div>
<div class="linetable">
     <table class="table table-bordered table-hover table1 ">
<thead>
                            <tr>
                             <th>Line No</th>
                             <th>Taxability</th>
                             <th>Provisional Old</th>
                             <th>Provisional New</th>
                             <th>Actual Old</th>
                             <th>Actual New</th>
                            <th>Document</th>
                            </tr>
                            </thead>
                            <tbody> <?php //dd($vlinesdata); ?>
                                @foreach ($lines as $key=>$value)
                                <tr>

                                   <td>{{ $key+1 }}</td>
                                    <td style="width: 41%;">{{ $value->title }}</td>
                                   <td>{{ $value->old}}</td>
                                   <td>{{ $value->new}}</td>
                                   <td>{{ $value->actual_old}}</td>
                                    <td>{{ $value->actual_new}}</td>
                                    <?php if($value->choosefile!='' && $value->choosefile!='null' ) { 
                                         $row = json_decode($value->choosefile);
                                         ?>
                                          <td style="width: 18%;" >  
                                        @foreach($row as $key => $v)
                                        <p><a download href="{{URL::to('')}}/uploads/investmentdeclaration/{{$value->inv_lines_id}}/{{$v}}">{{$v}}&nbsp;<img src='{{URL::to('')}}/images/download.png' height="20px" width="20px" ></a></p>
                                        @endforeach
                                    </td>

                                       <?php }else{ ?> 
                                     <td style="width: 18%;" >No Files</td>
                                 <?php } ?>
                                </tr>
                                @endforeach
                            </tbody>
                </table>
</div>

<div class="linetable">
     <table class="table table-bordered table-hover table1 ">
<thead>
                            <tr>
                           
                             <th>Taxable Income</th>
                             <th>Old</th>
                             <th>New</th>
                             </tr>
                            </thead>
                            <tbody> 
                                <tr>
                                   <td style="width: 70%;">Taxable Income</td>
                                    <td style="width: 15%;" >{!! $hdr[0]->old_tottincome !!}</td>
                                   <td style="width: 15%;">{!! $hdr[0]->new_dus80 !!}</td>
                                 </tr>
                                  <tr>
                                   <td>Income Tax</td>
                                    <td>{!! $hdr[0]->old_intax !!}</td>
                                   <td>{!! $hdr[0]->new_intax !!}</td>
                                 </tr>
                                  <tr>
                                   <td>Less: Rebate 87A</td>
                                    <td>{!! $hdr[0]->old_rebate87a !!}</td>
                                   <td>{!! $hdr[0]->new_rebate87a !!}</td>
                                 </tr>
                                  <tr>
                                   <td>Balance Tax Liability</td>
                                    <td>{!! $hdr[0]->old_btlib !!}</td>
                                   <td>{!! $hdr[0]->new_btlib !!}</td>
                                 </tr>
                                  <tr>
                                   <td>Add: Surcharge</td>
                                    <td>{!! $hdr[0]->old_adsur !!}</td>
                                   <td>{!! $hdr[0]->new_adsur !!}</td>
                                 </tr>
                                  <tr>
                                   <td>Total Tax</td>
                                    <td>{!! $hdr[0]->old_totaltax !!}</td>
                                   <td>{!! $hdr[0]->new_totaltax  !!}</td>
                                 </tr>
                                  <tr>
                                   <td>Add: Edu. Health Cess</td>
                                    <td>{!! $hdr[0]->old_addeduhc !!}</td>
                                   <td>{!! $hdr[0]->new_addeduhc !!}</td>
                                 </tr>
                                  <tr>
                                   <td>Net Annual Tax</td>
                                    <td>{!! $hdr[0]->old_netannualtax !!}</td>
                                   <td>{!! $hdr[0]->new_netannualtax !!}</td>
                                 </tr>
                             
                </table>
</div>

</div>
                                            <!--</div>-->
       
				</div>
				</form>
			</div>

		</div>

@endsection

