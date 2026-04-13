<head>
<title>ERP</title>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php

<!---------------------------- Fav icon ------------------------->
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('images/fav.png')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/style.min.css')); ?>">

<link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/font-awesome.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/meanmenu.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/bootstrap-select.min.css')); ?>" />

<link rel="stylesheet" href="<?php echo e(asset('css/jquery-ui.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/ui.jqgrid.css')); ?>">
=======
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/meanmenu.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css')}}">
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css')}}" />

<link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('css/ui.jqgrid.css')}}">
>>>>>>> 11751cbbee6bfd460bc0f38465a2047d4d53da0a:resources/views/layouts/header.blade_16052018.php
<!---------------------- Date Picker ------------------------->
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
<!----------------------- customize css ---------------------->
<link rel="stylesheet" href="{{asset('css/customize.css')}}">
<link rel="stylesheet" href="{{asset('css/sweet-alert.css')}}">


<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">


<link href="<?php echo asset('js/plugins/parsley/src/parsley.css') ?>" rel="stylesheet">
<script src="<?php echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>



<script src="{{ asset('js/jquery-1.12.0.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<!-- Mega Menu js -->
<script src="{{ asset('js/jquery.meanmenu.js')}}"></script>

<!------------------------ Date Picker --------------------------->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/i18n/grid.locale-en.js"></script>
<script src="{{asset('js/jquery.jqgrid.min.js')}}"></script>
<script src="{{ asset('js/jquery.jCombo.min.js') }}"></script>
<script src="{{ asset('js/reCopy.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script src="{{ asset('js/clone.js') }}"></script>

</head>
    <div class="headermenus">

        <div class="container topborder">
          <div class="row">
            <div class="col-md-3 col-sm-3">
              <div class="header-logo home2-header-logo home2-header-logo2">
<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
                  <a href="#"><img src="<?php echo e(asset('images/logo.png')); ?>" alt="Company Logo" class="logoimg" />
=======
                  <a href="#"><img src="{{ asset('images/logo_white.png') }}" alt="Company Logo" style="width:100%;" />
>>>>>>> 11751cbbee6bfd460bc0f38465a2047d4d53da0a:resources/views/layouts/header.blade_16052018.php
                  </a>
              </div>
            </div>
            <div class="col-md-3 col-sm-3"></div>
            <div class="col-md-3 col-sm-3">
              <!--<table id="box-table-b" summary="Corparate Details">

              <tbody >
                  <tr>
                      <td>Transaction Organization</td>
                      <td>I Five Technology Private Ltd</td>
                      <td>Company</td>
                      <td>I Five</td>
                  </tr>

                  <tr>
                      <td>Location</td>
                      <td>F5-Symtec towers</td>
                      <td>Department</td>
                      <td>Admin</td>
                  </tr>

              </tbody>
          </table>-->

            </div>
<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
            <div class="col-md-3 col-sm-3 col-xs-3">
              <ul class="navbar-nav my-lg-0">
              <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-anchor"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>

                        </li>


                    <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"> <i class="icon-action-redo"></i>
                                                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                                                </a>
                                                <ul class="dropdown-menu">
                                                  <li><a href="#"><i class="fa  fa-laptop"></i>Dashboard</a></li>
                                                  <li><a href="#"><i class="fa fa-user"></i>Profile</a></li>
                                                  <li><a href="#"><i class="fa fa-sign-out"></i>Logout</a></li>
                                                </ul>
                    </li>
                    <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"> <i class="icon-login"></i>

                                  </a>
                                  <ul class="dropdown-menu">
                                    <li><a href="#"><i class="fa  fa-laptop"></i>Dashboard</a></li>
                                    <li><a href="#"><i class="fa fa-user"></i>Profile</a></li>
                                    <li><a href="#"><i class="fa fa-sign-out"></i>Logout</a></li>
                                  </ul>
                  </li>
                      </ul>

              <!--<div class="userprofile dropdown">
                <img src="<?php echo e(asset('images/users.png')); ?>" class="dropdown-toggle " type="button" data-toggle="dropdown" />
=======
            <div class="col-md-1 col-sm-1 col-xs-3">
              <div class="userprofile dropdown">
                <img src="{{ asset('images/users.png') }}" class="dropdown-toggle " type="button" data-toggle="dropdown" />
>>>>>>> 11751cbbee6bfd460bc0f38465a2047d4d53da0a:resources/views/layouts/header.blade_16052018.php
                <ul class="dropdown-menu">
                  <li><a href="#"><i class="fa  fa-laptop"></i>Dashboard</a></li>
                  <li><a href="#"><i class="fa fa-user"></i>Profile</a></li>
                  <li><a href="#"><i class="fa fa-sign-out"></i>Logout</a></li>
                </ul>
              </div>-->

            </div>
          </div>
        </div>

        <!--header main menu start-->
        <div id="sticky-header" class="main-menu-wrapper home2-menu header-sticky">
            <div class="container">


                <div class="row">

                    <div class="col-md-12 col-sm-12 hidden-xs">
                        <nav>
                            <ul class="main-menu">

                                <!-------------------------------first list------------>
                                <li class="mega-parent">
                                    <a href="#" class="menuhover">Adminstration <i class="zmdi zmdi-chevron-down"></i></a>
                                    <div class="mega-menu-area hp2style2 mega-menu-area2">
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">ORG Settings</a>
                                            </li>
                                            <li><a href="#">Business Group </a></li>
                                             <li><a href="{{URL::to('company')}}">Company</a></li>
                                              <li><a href="#">Location</a></li>
                                              <li><a href="#">Organization</a></li>

                                        </ul>
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a>Users And Roles</a>
                                            </li>

                                            <li><a href="#">Create Users </a></li>
                                            <li><a href="#">Assign Employee </a></li>
                                            <li><a href="#">User Orgs Assignment</a></li>
                                            <li><a href="#">Create Roles</a></li>
                                            <li><a href="#">User Roles Assignment</a></li>
                                            <li><a href="#">Menu Permission</a></li>

                                        </ul>
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">Projects</a></li>
                                            <li><a href="#">Project Types </a></li>
                                            <li><a href="#">  Create Projects</a></li>

                                        </ul>
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">General</a></li>
                                            <li> <a href="{{URL::to('toolsmenuconfig')}}">Tools Menu </a></li>
                                            <li> <a href="{{URL::to('floatingpointsettings')}}">Floating Point</a></li>
                                            <li> <a href="{{URL::to('dateformatssettings')}}">Date Format Settings</a></li>
                                            <li> <a href="{{URL::to('timeformats')}}">Time Format Settings</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="mega-parent">
                                    <a href="#" class="">HRMS <i class="zmdi zmdi-chevron-down"></i></a>
                                    <div class="mega-menu-area hp2style2 mega-menu-area2">
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">Master</a></li>
                                            <li><a href="{{URL::to('createemployee')}}">Create Employee</a></li>
                                            <li><a href="{{URL::to('employeeupload')}}">Employee Upload</a></li>
                                            <li><a href="{{URL::to('employeedepartment')}}">Department</a></li>
                                            <li><a href="{{URL::to('employeejobtitle')}}">Employee Job Title</a></li>
                                            <li><a href="{{URL::to('employeeposition')}}">Employee Position</a></li>
                                            <li><a href="{{URL::to('employeedocument')}}">Employee Document</a></li>
                                            <li><a href="{{URL::to('companydocument')}}">Company Document</a></li>
                                            <li><a href="{{URL::to('interviewprocess')}}">Interview Process</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <!-- -------------------------------- End First list--->
                                <!---------------------------------- second list---->
-
                                <li class="mega-parent">
                                    <a href="#" target="_blank">Purchase <i class="zmdi zmdi-chevron-down"></i></a>
                                    <div class="mega-menu-area hp2style2 mega-menu-area2">
                                        <ul class="single-mega-item single-mega-item2">


                                            <li class="mega-title"><a href="#">PURCHASE-SETUPS</a></li>
                                            <li><a href="{{URL::to('suppliertypes') }}">Supplier Types</a></li>
                                            <li><a href="{{URL::to('paymentterms') }}">Payment Terms</a></li>
                                            <li><a href="{{URL::to('paymentmethods') }}">Payment Methods</a></li>
                                            <li><a href="{{URL::to('deliveryterms') }}">Delivery Terms </a></li>
                                            <li><a href="{{URL::to('freightterms') }}">  Freight Terms </a></li>
                                            <li><a   href="{{URL::to('purchasefreightcarriershdr') }}">Freight Carriers </a></li>
                                            <li><a href="{{URL::to('supplier') }}">Create Supplier </a></li>

                                        </ul>


                                            <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">PURCHASE ENQUIRIES</a></li>
<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
                                            <li><a href="<?php echo e(URL::to('purchaseenquiry')); ?>">Create Enquiry</a></li>

      		                              <li><a href="<?php echo e(URL::to('purchaseenquirytorequestion')); ?>">Enquiry From Requisition</a></li>


                                        </ul>
=======
                                            <li><a href="{{ URL::to('purchaseenquiry') }}">Create Enquiry</a></li>
<<<<<<< HEAD
      		                              <li><a href="{{ URL::to('purchaseenquirytorequestion') }}">Enquiry From Requisition</a></li>
                                        </ul>
		                         <li><a href="{{ URL::to('purchaseenquirytorequestion') }}">Enquiry From Requestion</a></li>
                                             </ul>
=======
					                         <li><a href="{{ URL::to('purchaseenquirytorequestion') }}">Enquiry From Requestion</a></li>
                                             </ul>
>>>>>>> 77d8570ea077762eb970351024c4c830c9e4ebee:resources/views/layouts/header.blade_16052018.php


>>>>>>> 486fc63a4b51ead6e5d82eb5bbdbc10e7327fa0c
                                             <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">PURCHASE REQUISITIONS</a></li>
                                            <li><a href="{{ URL::to('purchaserequisition') }}">Create Requisition </a></li>

<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
                                                 <li><a href="<?php echo e(URL::to('purchaserequisitionapprove')); ?>">Requestion Approvel </a></li>

                                            </ul>

=======
<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
                                                 <li><a href="<?php echo e(URL::to('purchaserequisitionapprove')); ?>">Requestion Approvel </a></li>

                                            </ul>

=======
                                                 <li><a href="{{ URL::to('purchaserequisitionapprove') }}">Requestion Approvel </a></li>   
                                            
                                            </ul>
>>>>>>> 77d8570ea077762eb970351024c4c830c9e4ebee:resources/views/layouts/header.blade_16052018.php
>>>>>>> 11751cbbee6bfd460bc0f38465a2047d4d53da0a:resources/views/layouts/header.blade_16052018.php
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">PURCHASE QUOTES</a></li>
<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
                                            <li><a href="<?php echo e(URL::to('purchasequotation')); ?>">Create Quotation </a></li>

                                            <li><a href="<?php echo e(URL::to('purchaseenquirytoquote')); ?>">Quotation From Enquiry </a></li>
=======
                                            <li><a href="{{URL::to('purchasequotation')}}">Create Quotation </a></li>
                                                
                                            <li><a href="{{URL::to('purchaseenquirytoquote')}}">Quotation From Enquiry </a></li>
>>>>>>> 77d8570ea077762eb970351024c4c830c9e4ebee:resources/views/layouts/header.blade_16052018.php

				                             <li><a href="{{URL::to('purchaserequestiontoquote')}}">Quotation From Requisition </a></li>
                                             <li><a href="{{URL::to('purchasequtoetionapprove')}}">Quotation Approvel </a></li>

				                           <li><a href="{{URL::to('purchaserequestiontoquote')}}">Quotation From Requisition </a></li>

                                            <li><a href="#">Quotation Compare </a></li>

                                           </ul>


                                        <ul class="single-mega-item single-mega-item2">
                                             <li class="mega-title"><a href="#"> PURCHASE ORDER</a></li>
<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
                                             <li><a href="<?php echo e(URL::to('purchaseorder')); ?>">Create PO </a></li>
                                            <li><a href="<?php echo e(URL::to('purchaseenquirytopo')); ?>">Po From Enquiry</a></li>
                                            <li><a href="<?php echo e(URL::to('purchasequtoetopo')); ?>">Po From Qutoe</a></li>
                                            <li><a href="<?php echo e(URL::to('purchaserequestiontopo')); ?>">Po From Requestion</a></li>
                                             <li><a href="<?php echo e(URL::to('poapproval')); ?>">PO Approval </a></li>
=======
                                             <li><a href="{{ URL::to('purchaseorder') }}">Create PO </a></li>
                                            <li><a href="{{ URL::to('purchaseenquirytopo') }}">Po From Enquiry</a></li>
                                            <li><a href="{{ URL::to('purchasequtoetopo') }}">Po From Qutoe</a></li>
                                            <li><a href="{{ URL::to('purchaserequestiontopo') }}">Po From Requestion</a></li>											
                                             <li><a href="{{ URL::to('poapproval') }}">PO Approval </a></li>
>>>>>>> 77d8570ea077762eb970351024c4c830c9e4ebee:resources/views/layouts/header.blade_16052018.php
                                             <li><a href="#">PO Cancellation </a></li>
                                        </ul>
                                         <ul class="single-mega-item single-mega-item2">
                                             <li class="mega-title"><a href="#"> PO RECEIVING</a></li>
                                             <li><a href="{{ URL::to('goodsinwardnote') }}">Goods Inward Note(GIN)</a></li>
                                            <li><a href="{{ URL::to('grn') }}">Goods Receipt Note(GRN)</a></li>
                                            <li><a href="{{ URL::to('purchaseqc') }}">Quality Checking</a></li>
                                            <li><a href="{{ URL::to('movetoinventory') }}">Move To Inventory</a></li>
                                            <li><a href="{{ URL::to('purchaseinvoice') }}">Purchase Invoice</a></li>

                                        </ul>
                                    </div>
                                </li>
                                <!-----------------------------------End Second list------------------------------------------->
                                <!-------------------------------third list---------->
                                <li class="mega-parent">
                                    <a href="#" target="_blank">Sales <i class="zmdi zmdi-chevron-down"></i></a>
                                    <div class="mega-menu-area hp2style2 mega-menu-area2">
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">Sales Setups</a></li>
                                            <li><a href="{{URL::to('freightcarriershdr') }}">Freight Carriers </a></li>
                                            <li><a href="{{URL::to('ardiscountshdr') }}">  Discounts </a></li>
                                            <li><a href="{{URL::to('freightterms') }}">  Freight Terms </a></li>
                                            <li><a href="{{URL::to('deliveryterms') }}">Delivery Terms </a></li>
                                            <li><a href="{{URL::to('salesperson') }}">Sales Person </a></li>
                                            <li><a href="{{URL::to('paymentterms') }}">Payment Terms</a></li>
                                            <li><a href="{{URL::to('paymentmethods') }}">Payment Methods</a></li>

                                </ul>
                                <ul class="single-mega-item single-mega-item2">
                                    <li class="mega-title"><a href="">Customers</a></li>
                                    <li><a href="{{URL::to('customers') }}">Customers</a></li>
                                    <li><a href="{{URL::to('mcustomertypes') }}">Customer Types</a></li>

                                </ul>
                                <ul class="single-mega-item single-mega-item2">
                                    <li class="mega-title"><a href="soinquiryhdr">Sales Enquiry</a></li>
                                    <li><a  href="{{URL::to('salesinquiry') }}">Create Sales Enquiry</a></li>
                                </ul>
                                <ul class="single-mega-item single-mega-item2">
                                    <li class="mega-title"><a href="{{URL::to('soquote') }}">Sales Quote</a></li>
                                    <li><a href="{{URL::to('soquote') }}">Create Sales Quote</a></li>
									<li><a href="{{URL::to('salesquotefromenquiry') }}">Create Sales Quote From Enquiry</a></li>
									<li><a href="{{URL::to('salesquoteapproval') }}">Sales Quote Approval</a></li>

                                </ul>
                                <ul class="single-mega-item single-mega-item2">
                                    <li class="mega-title"><a href="{{URL::to('soorder')}}">Sales Order</a></li>
                                    <li><a href="{{URL::to('soorder') }}">Create Sales Order</a></li>
                                    <li><a href="{{URL::to('soorderfromqo') }}">Sales Order From Quote</a></li>
									<li><a href="{{URL::to('salesorderapproval') }}">Sales order Approval</a></li>
							       <li><a href="{{URL::to('socancellation') }}">So Cancellation</a></li>
                                   <li><a href="{{URL::to('pickorder') }}">Pick order</a></li>
                                   <li><a href="{{URL::to('dispatch') }}">Dispatch</a></li>

                                   <li><a href="{{URL::to('salesdispatchshipconfirm') }}">Shipconfirm</a></li>

                                </ul>
                                <ul class="single-mega-item single-mega-item2">
                                    <li class="mega-title"><a href="soinvoice">Sales Invoice</a></li>
                                    <li><a href="{{URL::to('salesinvoice')}}">Create Sales Invoice</a></li>
                                    <li><a href="{{URL::to('salesinvoicefromorder')}}">Create Sales Invoice From Order</a></li>
									<li><a href="{{URL::to('salesinvoiceapproval') }}">Sales Invoice Approval</a></li>
                                    <li><a href="{{URL::to('salesinvoicefromdispatch')}}">Create Sales Invoice From Dispatch</a></li>

                                </ul>
                                </div>
                                </li>
                                <!-----------------------------------end third list---->
                                <!-------------------FourthList------------>
                                <li class="mega-parent">
                                    <a href="#" target="_blank">Inventory <i class="zmdi zmdi-chevron-down"></i></a>
                                    <div class="mega-menu-area hp2style2 mega-menu-area2">
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">Inventory-Steps</a></li>
                                            <li><a href="{{URL::to('productgroup')}}">Product Group</a></li>
                                            <li><a href="{{URL::to('productcategory')}}">Product Category</a></li>
                                            <li><a href="{{URL::to('productsubcategory')}}">Product SubCategory </a></li>
											   <li><a href="{{URL::to('product')}}">Product</a></li>
                                            <li><a href="{{URL::to('manufacturerpartno')}}">Manufacturer Part No</a></li>

                                        </ul>
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">Transaction Profiles</a></li>
                                            <li><a href="{{URL::to('mtltransactiontypes')}}">Transaction types</a></li>
                                            <li><a href="{{URL::to('subinventory')}}">Subinventory/Locator</a></li>
                                        </ul>
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">Transaction</a></li>
                                            <li><a href="{{URL::to('subinventorytransfer')}}">Subinventory Transfer</a></li>
                                            <li><a href="{{URL::to('openstockupload')}}">Open Stock</a></li>
                                            <li><a href="{{URL::to('rawquantityonhand')}}">Quantity On Hand(Raw Material)</a></li>
                                            <li><a href="{{URL::to('fgquantityonhand')}}">Quantity On Hand(FG)</a></li>
                                        </ul>
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#">Pricelist</a></li>
                                            <li><a href="{{URL::to('purchasepricelist')}}" class="contentonly2" >Pricelist(Purchase)</a></li>
                                            <li><a href="{{URL::to('salespricelist')}}">Pricelist(Sales)</a></li>

                                        </ul>
                                        <ul class="single-mega-item single-mega-item2">
                                            <li class="mega-title"><a href="#"> UOM</a></li>
                                            <li><a href="{{URL::to('uomcodes')}}">Uom Code</a></li>
                                            <li><a href="{{URL::to('uomconversion')}}">Uom Conversion</a></li>

                                        </ul>
                                    </div>
                                </li>

                                <!-------------------End fourth list-------------------------------------------------------------------------->
                            </ul>
                        </nav>
                    </div>

                    <!---------------------------- Mobile Menu Start -------------------------------->
                  </div>

            </div>
        </div>
        <!--header main menu end-->
    </div>
<!----------------------- Bottom to top -------------------------------->
<button class="scroll-top tran3s">
				<i class="fa fa-angle-up" aria-hidden="true"></i>
</button>
<div class="contentdata">
@yield('content')
</div>

<<<<<<< HEAD:storage/framework/views/56a190b5dbcd3b995da19084c053a0c9e43c31e7.php
<!------------------------ Customize Js ---------------------------->
<script src="<?php echo e(asset('js/custom.js')); ?>"></script>
=======
<!------------------------ Customize Js --------------------------->
<script src="{{asset('js/custom.js')}}"></script>
>>>>>>> 11751cbbee6bfd460bc0f38465a2047d4d53da0a:resources/views/layouts/header.blade_16052018.php

<script>
$(document).on('click','.contentonly',function() {


	var content = $(this).attr('href');
	$.get(content,function(data){
		$('.contentdata').html(data);
	});
	return false;

});


	//reloadData('#soquote','soquote');
//function reloadData('#soquote', 'soquote'){

//}

function notyMsg(status,msg){
notif({
type: status,
msg: msg,
/*width: "all",*/
position: "right"
});
}

function cloneRow(cloneClass,cloneBody)
{
var cloned = $('.'+cloneClass).find('tr:eq(1)').clone();
cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
cloned.appendTo('.'+cloneBody);
}
function notyMessage(status,message,red_url,create_url,edit_url)
{
swal(
  {
	title: status,
	text: message,
	type: status,
	/*timer: 2e3 */
	  showCancelButton: !0,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Ok",
		cancelButtonText: "Create",
		confirmButtonText: "Ok",
		closeOnConfirm: !1,
		//timer: 2e3,
		closeOnCancel: !1
  }
)
setTimeout(function () {
//window.location.href=red_url;
}, 2000);

$(document).on('click','.confirm',function() {
	window.location.href=red_url;
});

$(document).on('click','.cancel',function() {
	window.location.href=create_url;
});

$(document).on('click','.apply',function() {
	window.location.href=edit_url;
});

}



function notyMsg1(status,message,red_url)
{
swal(
  {
	title: status,
	text: message,
	type: status,
	  timer:1500
  }
)
$('.apply,.confirm').css('display','none');
$(document).on('click','.confirm',function() {
	window.location.href=red_url;
});
}


function notysuccess(message,status)
{
swal({
  position: 'top-right',
  type: status,
  title: message,
  showConfirmButton: false,
  timer:1500

});
}

	$( document ).ready(function() {
	$(window).bind('resize', function()
{
$("#grid1").setGridWidth($(window).width()*0.96);
}).trigger('resize');
	});

</script>
<script src="{{ asset('js/sweet-alert.min.js') }}"></script>
<script src="{{ asset('js/sweet-alert.init.js') }}"></script>

<script src="{{ asset('js/notifIt.js') }}" type="text/javascript"></script>
<link href="{{ asset('css/notifIt.css') }}" type="text/css" rel="stylesheet">
