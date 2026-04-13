@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Detail Report</h3>
  @include('layouts.breadcrumb')

  <?php $groupname = \Session::get('groupname'); ?>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="RptTbl" class="table table-striped table-bordered">

          <?php if ($groupname == "11") { ?>

          <thead>

            <tr class="table-warning">
              <th>Employee Code</th>
              <th>Prefix</th>
              <th class="freeze">Employee Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Company</th>
              <th>Location Name</th>
              <th>Department</th>
              <th>Reporting Manager</th>
              <th>Reporting Manager 1</th>
              <th>Area</th>
              <th>Position</th>
              <th>Grade</th>
              <th>Date Of Joining</th>
              <th>ESI Number</th>
              <th>Aadhar Number</th>
              <th>ESI Dispensary</th>
              <th>PF Date</th>
              <th>Provident Fund Number</th>
              <th>UAN Number</th>
              <th>Mobile Number</th>
              <th>Alternative Number</th>
              <th>Biometric Emp No</th>
              <th>Employee type</th>
              <th>Zone</th>
              <th>Group Type</th>
              <th>Active</th>
              <th>Date Of Leaving</th>
              <th>Emp. Service Status</th>
              <th>Gender</th>
              <th>Permanent Street</th>
              <th>Permanent Address</th>
              <th>Permanent Flat No</th>
              <th>Permanent Country</th>
              <th>Permanent State</th>
              <th>Permanent City</th>
              <th>Permanent Pincode</th>
              <th>Permanent Locality</th>
              <th>Current Street</th>
              <th>Current Address</th>
              <th>Current Flat No</th>
              <th>Current Country</th>
              <th>Current State</th>
              <th>Current City</th>
              <th>Current Pincode</th>
              <th>Current Locality</th>


            </tr>

            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prefix</span>
              </th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Last
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Email</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Company</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Location
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Department</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting
                  Manager</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting
                  Manager 1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Area</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Position</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Grade</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date Of
                  Joining</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ESI
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Aadhar
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ESI
                  Dispensary</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PF Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Provident Fund
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UAN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mobile
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Alternative
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Biometric Emp
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date Of
                  Leaving</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Emp. Service
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Gender</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Street</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Address</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent Flat
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Country</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  State</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  City</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Pincode</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Locality</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Street</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Address</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current Flat
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Country</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  State</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  City</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Pincode</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Locality</span></th>

            </tr>
          </thead>


          <?php } else if ($groupname == "13") { ?>

          <thead>

            <tr class="table-warning">

              <th>Employee Number</th>
              <th>Prefix Name</th>
              <th class="freeze">First Name</th>
              <th>Company Name</th>
              <th>Department Name</th>
              <th>Report Name</th>
              <th>Job Title Name</th>
              <th>Work Telephone Number</th>
              <th>Alternative Telephone Number</th>
              <th>Zone Name</th>
              <th>Group Type</th>
              <th>Active</th>
              <th>Date of Leaving</th>
              <th>FF Status</th>
              <th>Gender</th>
              <th>Personal Mobile</th>
              <th>Current State Name</th>

            </tr>

            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prefix
                  Name</span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">First Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Company
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Department
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Report
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Title
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Work Telephone
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Alternative
                  Telephone Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of
                  Leaving</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">FF
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Gender</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Personal
                  Mobile</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current State
                  Name</span></th>



            </tr>
          </thead>

          <?php  } else if ($groupname == "5") { ?>

          <thead>

            <tr class="table-warning">
              <th>Employee Number</th>
              <th>Prefix Name</th>
              <th class="freeze">First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Company Name</th>
              <th>Location Name</th>
              <th>Department Name</th>
              <th>Report Name</th>
              <th>Report Name 1</th>
              <th>Area Name</th>
              <th>Job Title Name</th>
              <th>Position</th>
              <th>Date of Joining</th>
              <th>Work Telephone Number</th>
              <th>Alternative Telephone Number</th>
              <th>Employee Type</th>
              <th>Zone Name</th>
              <th>Group Type</th>
              <th>Active</th>
              <th>Date of Leaving</th>
              <th>FF Status</th>
              <th>Gender</th>
              <th>Date of Birth</th>
              <th>Personal Mail</th>
              <th>Personal Mobile</th>
              <th>Permanent Street</th>
              <th>Permanent Street Address</th>
              <th>Permanent Flat No</th>
              <th>Country Name</th>
              <th>State Name</th>
              <th>City Name</th>
              <th>Permanent Postal Code</th>
              <th>Permanent Locality</th>
              <th>Current Street</th>
              <th>Current Street Address</th>
              <th>Current Flat No</th>
              <th>Current Country Name</th>
              <th>Current State Name</th>
              <th>Current City Name</th>
              <th>Current Postal Code</th>
              <th>Current Locality</th>



            </tr>

            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prefix
                  Name</span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">First Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Last
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Email</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Company
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Location
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Department
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Report
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Report Name
                  1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Area
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job Title
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Position</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of
                  Joining</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Work Telephone
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Alternative
                  Telephone Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of
                  Leaving</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">FF
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Gender</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of
                  Birth</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Personal
                  Mail</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Personal
                  Mobile</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Street</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Street Address</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent Flat
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Country
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">City
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Postal Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Locality</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Street</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current Street
                  Address</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current Flat
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Country Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current State
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current City
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current Postal
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Locality</span></th>


            </tr>
          </thead>
          <?php  } else { ?>

          <thead>

            <tr class="table-warning">
              <th>Employee Code</th>
              <th>Prefix</th>
              <th class="freeze">Employee Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Company</th>
              <th>Location Name</th>
              <th>Department</th>
              <th>Reporting Manager</th>
              <th>Reporting Manager 1</th>
              <th>Area</th>
              <th>Position</th>
              <th>Grade</th>
              <th>Date Of Joining</th>
              <th>ESI Number</th>
              <th>Aadhar Number</th>
              <th>ESI Dispensary</th>
              <th>PF Date</th>
              <th>Provident Fund Number</th>
              <th>UAN Number</th>
              <th>Mobile Number</th>
              <th>Alternative Number</th>
              <th>Biometric Emp No</th>
              <th>Employee type</th>
              <th>Zone</th>
              <th>Group Type</th>
              <th>Active</th>
              <th>Date Of Leaving</th>
              <th>Emp. Service Status</th>
              <th>Casual Leave</th>
              <th>Sick Leave</th>
              <th>Earn Leave</th>
              <th>OT Formula</th>
              <th>Gender</th>
              <th>Marital Status</th>
              <th>Nationality</th>
              <th>Date of Birth</th>
              <th>Age</th>
              <th>Mother Tongue</th>
              <th>Religion</th>
              <th>Blood Group</th>
              <th>Personal Mail ID</th>
              <th>Personal Contact</th>
              <th>Bank Name</th>
              <th>Branch</th>
              <th>IFSC Code</th>
              <th>Account Name</th>
              <th>Account Number</th>
              <th>PAN Number</th>
              <th>AADHAR Number</th>
              <th>ID Type 1</th>
              <th>ID Number 1</th>
              <th>ID Type 2</th>
              <th>ID Number 2</th>
              <th>Father Name</th>
              <th>Father AADHAR Number</th>
              <th>Mother Name</th>
              <th>Mother AADHAR Number</th>
              <th>Spouse Name</th>
              <th>Spouse AADHAR Number</th>
              <th>Spouse DOB</th>
              <th>Number of Child</th>
              <th>Permanent Street</th>
              <th>Permanent Address</th>
              <th>Permanent Flat No</th>
              <th>Permanent Country</th>
              <th>Permanent State</th>
              <th>Permanent City</th>
              <th>Permanent Pincode</th>
              <th>Permanent Locality</th>
              <th>Current Street</th>
              <th>Current Address</th>
              <th>Current Flat No</th>
              <th>Current Country</th>
              <th>Current State</th>
              <th>Current City</th>
              <th>Current Pincode</th>
              <th>Current Locality</th>

            </tr>

            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prefix</span>
              </th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Last
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Email</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Company</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Location
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Department</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting
                  Manager</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting
                  Manager 1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Area</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Position</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Grade</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date Of
                  Joining</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ESI
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Aadhar
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ESI
                  Dispensary</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PF Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Provident Fund
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UAN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mobile
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Alternative
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Biometric Emp
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date Of
                  Leaving</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Emp. Service
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Casual
                  Leave</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sick
                  Leave</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Earn
                  Leave</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">OT
                  Formula</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Gender</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Marital
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Nationality</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date of
                  Birth</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Age</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mother
                  Tongue</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Religion</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Blood
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Personal Mail
                  ID</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Personal
                  Contact</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Bank
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Branch</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IFSC
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PAN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">AADHAR
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ID Type
                  1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ID Number
                  1</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ID Type
                  2</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ID Number
                  2</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Father
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Father AADHAR
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mother
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mother AADHAR
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Spouse
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Spouse AADHAR
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Spouse
                  DOB</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Number of
                  Child</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Street</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Address</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent Flat
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Country</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  State</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  City</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Pincode</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Permanent
                  Locality</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Street</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Address</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current Flat
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Country</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  State</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  City</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Pincode</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Current
                  Locality</span></th>
            </tr>
          </thead>

          <?php  } ?>

        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')



  <script>

    $(document).ready(function () {

      var table = $('#RptTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('getemployeedetail') }}",
          type: "GET",
        },
        columns: [

          <?php if ($groupname == "11") { ?>	 

          { data: "employee_number" },
          { data: "prefix_name" },
          { class: 'freeze', data: "first_name" },
          { data: "last_name" },
          { data: "email" },
          { data: "company_name" },
          { data: "location_name" },
          { data: "department_name" },
          { data: "report_name" },
          { data: "report_name1" },
          { data: "area_name" },
          { data: "job_title_name" },
          { data: "position" },
          { data: "date_of_joining" },
          { data: "esi_no" },
          { data: "aadhar_number" },
          { data: "esi_dispensary" },
          { data: "pf_date" },
          { data: "pf_no" },
          { data: "uan_no" },
          { data: "work_telephone_number" },
          { data: "alternative_telephone_number" },
          { data: "biometric_empno" },
          { data: "emp_type" },
          { data: "zone_name" },
          { data: "grp_type" },
          { data: "active" },
          { data: "date_of_leaving" },
          { data: "ff_status" },
          { data: "gender_name" },
          { data: "permanent_street" },
          { data: "permanent_street_address" },
          { data: "permanent_flat_no" },
          { data: "country_name" },
          { data: "state_name" },
          { data: "city_name" },
          { data: "permanent_postal_code" },
          { data: "permanent_locality" },
          { data: "current_street" },
          { data: "current_street_address" },
          { data: "current_flat_no" },
          { data: "current_count_name" },
          { data: "current_state_name" },
          { data: "current_city_name" },
          { data: "current_postal_code" },
          { data: "current_locality" }

      <?php } else if ($groupname == "13") { ?>

      { data: "employee_number" },
          { data: "prefix_name" },
          { class: 'freeze', data: "first_name" },
          { data: "company_name" },
          { data: "department_name" },
          { data: "report_name" },
          { data: "job_title_name" },
          { data: "work_telephone_number" },
          { data: "alternative_telephone_number" },
          { data: "zone_name" },
          { data: "grp_type" },
          { data: "active" },
          { data: "date_of_leaving" },
          { data: "ff_status" },
          { data: "gender_name" },
          { data: "personal_mobile" },
          { data: "current_state_name" } 

       <?php  } else if ($groupname == "5") { ?>

      { data: "employee_number" },
          { data: "prefix_name" },
          { class: 'freeze', data: "first_name" },
          { data: "last_name" },
          { data: "email" },
          { data: "company_name" },
          { data: "location_name" },
          { data: "department_name" },
          { data: "report_name" },
          { data: "report_name1" },
          { data: "area_name" },
          { data: "job_title_name" },
          { data: "position" },
          { data: "date_of_joining" },
          { data: "work_telephone_number" },
          { data: "alternative_telephone_number" },
          { data: "emp_type" },
          { data: "zone_name" },
          { data: "grp_type" },
          { data: "active" },
          { data: "date_of_leaving" },
          { data: "ff_status" },
          { data: "gender_name" },
          { data: "date_of_birth" },
          { data: "personal_mail" },
          { data: "personal_mobile" },
          { data: "permanent_street" },
          { data: "permanent_street_address" },
          { data: "permanent_flat_no" },
          { data: "country_name" },
          { data: "state_name" },
          { data: "city_name" },
          { data: "permanent_postal_code" },
          { data: "permanent_locality" },
          { data: "current_street" },
          { data: "current_street_address" },
          { data: "current_flat_no" },
          { data: "current_count_name" },
          { data: "current_state_name" },
          { data: "current_city_name" },
          { data: "current_postal_code" },
          { data: "current_locality" } 

    <?php  } else { ?>


      { data: "employee_number" },
          { data: "prefix_name" },
          { class: 'freeze', data: "first_name" },
          { data: "last_name" },
          { data: "email" },
          { data: "company_name" },
          { data: "location_name" },
          { data: "department_name" },
          { data: "report_name" },
          { data: "report_name1" },
          { data: "area_name" },
          { data: "job_title_name" },
          { data: "position" },
          { data: "date_of_joining" },
          { data: "esi_no" },
          { data: "aadhar_number" },
          { data: "esi_dispensary" },
          { data: "pf_date" },
          { data: "pf_no" },
          { data: "uan_no" },
          { data: "work_telephone_number" },
          { data: "alternative_telephone_number" },
          { data: "biometric_empno" },
          { data: "emp_type" },
          { data: "zone_name" },
          { data: "grp_type" },
          { data: "active" },
          { data: "date_of_leaving" },
          { data: "ff_status" },
          { data: "c_l" },
          { data: "s_l" },
          { data: "e_l" },
          { data: "ot" },
          { data: "gender_name" },
          { data: "marital_status_name" },
          { data: "nation_name" },
          { data: "date_of_birth" },
          { data: "age" },
          { data: "mother_t_name" },
          { data: "religion_name" },
          { data: "blood_name" },
          { data: "personal_mail" },
          { data: "personal_mobile" },
          { data: "bank_name" },
          { data: "branch_name" },
          { data: "ifsc_code" },
          { data: "account_holder_name" },
          { data: "acc_num" },
          { data: "pan_number" },
          { data: "aadhar_number" },
          { data: "idtype" },
          { data: "id_number" },
          { data: "idtype1" },
          { data: "id_number1" },
          { data: "father_name" },
          { data: "father_aadhar_number" },
          { data: "mother_name" },
          { data: "mother_aadhar_number" },
          { data: "spouse_name" },
          { data: "spouce_aadhar_number" },
          { data: "spouse_dob" },
          { data: "no_of_children" },
          { data: "permanent_street" },
          { data: "permanent_street_address" },
          { data: "permanent_flat_no" },
          { data: "country_name" },
          { data: "state_name" },
          { data: "city_name" },
          { data: "permanent_postal_code" },
          { data: "permanent_locality" },
          { data: "current_street" },
          { data: "current_street_address" },
          { data: "current_flat_no" },
          { data: "current_count_name" },
          { data: "current_state_name" },
          { data: "current_city_name" },
          { data: "current_postal_code" },
          { data: "current_locality" }

        <?php  } ?>
        ],

         initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        },

      });


      // Column search
      $('#RptTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });


    });


  </script>

@endpush