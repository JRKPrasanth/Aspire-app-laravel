
<style>
.leave_bal{
text-align:center;
background:#0CAFFF;
color:#000;
font-size: 13px;
font-weight: 800;
width:10%;
}

</style>

<h5>Dear {{$emp_name[0]->first_name }}</h5>

 <p>We are sending here with in your leave balance as on {{date("Y/m/d")}} <br> If there is no leave balance , that is considered as LOP.</p>                                                      


<div class="col-lg-12">
       <table class="table table-bordered border-primary" style="width: 100%;text-align: center !important;font-weight: 800;color: #000;width: 100%;margin-bottom: 30px;" id="leaveTable">
                    <thead>
                    <!--  <tr>
                        
                          <th colspan="1" style="font-size: 15px; font-weight: 700;background:none;color:#000;border:1px solid black;">Name :</th>
                          <th colspan="2" style="text-align:center;font-size: 15px; font-weight: 700;background:none;color:#000;border:1px solid black;">{{$emp_name[0]->first_name }}</th>
                          <th colspan="1" style="font-size: 15px; font-weight: 700;background:none;color:#000;border:1px solid black;">Employee No :</th>
                          <th colspan="2" style="text-align:center;font-size: 15px; font-weight: 700;background:none;color:#000;border:1px solid black;">{{$emp_num[0]->employee_number }}</th>
                      </tr>   -->
                    
                    <tr>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Leave Type</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Op Bal Leave as DOJ – no. of days</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Eligible no. of days for current year/period</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Leave taken till date in current period ( no. of days)</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Balance</th>
                      
                    </tr>
                  </thead>
                  <tbody>
         <tr>
               <th style="border:1px solid black">CASUAL LEAVE</th>
                <td style="border:1px solid black">{{ $cl_opening[0]->ocl}}</td>
              <td style="border:1px solid black">{{$cl_elijible}}</td>
              <td style="border:1px solid black">{{ $cl_taken[0]->count}}</td>
              <td style="border:1px solid black">{{$cl_elijible -  $cl_taken[0]->count}}</td>
          </tr>
          
          <!--  <tr>
               <th style="border:1px solid black">SICK LEAVE</th>
                <td style="border:1px solid black">{{ $sl_opening[0]->osl}}</td>
                <td style="border:1px solid black">-</td>
                <td style="border:1px solid black">{{$sl_elijible[0]->sick_leave }}</td>
                <td style="border:1px solid black">{{ $sl_taken[0]->count}}</td>
                <td style="border:1px solid black">{{$sl_opening[0]->osl -  $sl_taken[0]->count}}</td>
          </tr> -->
          <tr>
               <th style="border:1px solid black">EARN LEAVE</th>
                <td style="border:1px solid black">{{ $el_opening[0]->oel}}</td>
                <td style="border:1px solid black">{{$el_elijible[0]->earn_leave }}</td>
                <td style="border:1px solid black">{{ $el_taken[0]->count}}</td>
                <td style="border:1px solid black">{{$el_opening[0]->oel -  $el_taken[0]->count}}</td>
          </tr>
  
          <tr>
               <th style="border:1px solid black">LEAVE WITH NO PAY</th>
                <td style="border:1px solid black">-</td>
                <td style="border:1px solid black">-</td>
                <td style="border:1px solid black">{{ $lop_taken[0]->count}}</td>
                <td style="border:1px solid black">-</td>
          </tr>
          <tr>
               <th style="border:1px solid black">COMPENSATION LEAVE</th>
                <td style="border:1px solid black">{{$col_opening[0]->ocol}}</td>
                <td style="border:1px solid black">{{$col_elijible[0]->comp_off_leave}}</td>
                <td style="border:1px solid black">{{ $col_taken[0]->count}}</td>
                <td style="border:1px solid black">{{$col_opening[0]->ocol - $col_taken[0]->count }} </td>
          </tr>
          
          <tr>
               <th style="border:1px solid black;"> # Total</th>
                <td style="border:1px solid black">{{ $cl_opening[0]->ocl + $el_opening[0]->oel + $col_opening[0]->ocol }}</td>
              <td style="border:1px solid black">{{ $cl_elijible + $el_elijible[0]->earn_leave  + $col_elijible[0]->comp_off_leave}}</td>
              <td style="border:1px solid black">{{ $cl_taken[0]->count + $el_taken[0]->count + $lop_taken[0]->count + $col_taken[0]->count  }}</td>
           <td style="border:1px solid black;">
              {{ $cl_elijible -  $cl_taken[0]->count + $el_opening[0]->oel -  $el_taken[0]->count + $col_opening[0]->ocol - $col_taken[0]->count }}
           </td>
           </tr>
      </tbody>
        </table>
    
       <table class="table table-bordered border-primary" style="width: 100%;text-align: center !important;font-weight: 800;color: #000;width: 100%;margin-bottom: 30px;" id="leaveTable">
                    <thead>
                    <tr>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Permission</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Op Bal Permission current Mon – no. of Hrs</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Eligible no. of Hrs for current Month</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Permission taken till date in current Mon ( no. of Hrs)</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Balance</th>
                      
                    </tr>
                  </thead>
                  <tbody>
  
          <?php  $totalPermissions = 0; ?> 
            <tr>
                <th style="border:1px solid black">PERMISSION</th>
                <td style="border:1px solid black">{{ $totalPermissions += 3 }}</td>
                <td style="border:1px solid black">{{ $totalPermissions -= $permission_taken[0]->count }}</td>
                <td style="border:1px solid black">{{ $permission_taken[0]->count }}</td>
                <td style="border:1px solid black">{{ $totalPermissions }}</td>
            </tr>
         
      </tbody>
        </table>

       <table class="table table-bordered border-primary" style="width: 100%;text-align: center !important;font-weight: 800;color: #000;width: 100%;margin-bottom: 30px;" id="leaveTable">
                    <thead>
                    
                    <tr>
                  <th scope="col" class="leave_bal" style="border:1px solid black">On Duty</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Days</th>
                  <th scope="col" class="leave_bal" style="border:1px solid black">Hours</th>
  
                      
                    </tr>
                  </thead>
                  <tbody>
  
          
          <tr>
               <th style="border:1px solid black">ON-DUTY</th>
                <td style="border:1px solid black">{{ $onduty_dys[0]->count}}</td>
                <td style="border:1px solid black">{{ $onduty_hrs[0]->count}}</td>
          </tr>
         
      </tbody>
    </table>
    </div>

<div class="col-lg-12"></div>
<h5>Thanks and Regards</h5>
<h5>{{$hr_name[0]->first_name }}</h5>     
</div>
