@extends('layouts.header')
@section('content')


<form>

<div class="card">
<div class="card-header">
<span class="ui_close_btn"><a href="../user" class="collapse-close pull-right btn-danger" onclick="../manufacturerpartno"></a></span>
</div>
<div class="card-body card-block normalform">
    
   <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">User Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>User Name:</b> {!! $user_name !!}</p><br>
                                            <p><b>First Name:</b> {!! $first_name !!}</p><br>
                                            <p><b>Location:</b> {!! $loc_id !!}</p><br>
                                            <p><b>Mobile No:</b> {!! $mobile_no !!}</p>
											
                                            <br>
                                            

                                        </td>
										 <td>
                                            <p><b>Group Name:</b> {!! $group_id !!}</p><br>
                                            <p><b>Last Name:</b> {!! $last_name !!}</p><br>
                                            <p><b>Department:</b> {!! $admindept_id !!}</p><br>
                                        	
                                            <br>
                                            

                                        </td>
                                        <td>
                                             
                                             <p><b>Organization:</b> {!! $org_id !!}</p><br>
                                             <p><b>Company:</b> {!! $company_id !!}</p><br>
                                             <p><b>Email:</b> {!! $email !!}</p><br>
                                         
                                            <br>
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

@endsection


