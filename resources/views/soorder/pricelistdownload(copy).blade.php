@extends('layouts.header')
@section('content')

<?php //dd($pageMethod); ?>

<h2 class="heads">Pricelist Download</h2>
<?php 
//dd(\Session::all());
    $groupname=\Session::get('groupname'); 
    $emp_id=\Session::get('emp_id');
    $zone=\Session::get('zone');
?>
<div class="panel panel-visible" id="spy1" style="padding-bottom: 100px !important;padding-top: 40px;">
    <div class="panel-title ">
	    <div class="row">
	        <div class="col-md-12">
                
            </div>
        </div>
    </div>
    <!-- OUR CONTENT STARTS HERE -->
    <div class="row">
        <div class="col-md-12" style="padding: 35px;">
            <ul class="nav nav-pills nav-justified">
                <?php if($job_id =='42' || $job_id =='37') { ?>
                <li class="active nav-item"><a class="nav-link" data-toggle="tab" href="#stockiest">STOCKIEST PRICELIST</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chemist">CHEMIST PRICELIST</a></li>
                <?php }else { ?>
                <li class="active nav-item"><a data-toggle="tab" href="#distributor">DISTRIBUTOR PRICELIST</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#stockiest">STOCKIEST PRICELIST</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chemist">CHEMIST PRICELIST</a></li>
                <?php } ?>
            </ul>
        </div>    
<div class="tab-content">
    <div id="distributor" class="tab-pane fade in active">
        <div class="col-md-12">
        <h3>Distributor Pricelist</h3>        
        <?php if($groupname == "14" || $emp_id == "302" || $emp_id == "152" || $emp_id == "156" || $emp_id == "151" || $emp_id == "160" || $emp_id == "155" || $emp_id == "474" || $emp_id == "531" || $emp_id == "598") { ?>
        <div class="col-md-4"   style="padding: 35px;">
            Click here for Download <b>PRICE LIST WEF May 24_PIONEER:</b><a href="../public/Uploads/pricelist_uploads/distributor/distributor_PRICELIST W.E.F May'2023-PIONEER.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_PIONEER</a><!--<a href="../public/Uploads/pricelist_uploads/PRICE LIST WEF May 24.pdf" target="_blank"  id="" value="" download><i class="fa fa-file-pdf-o"></i></a>-->
        </div>
        <div class="col-md-4"   style="padding: 35px;">
            Click here for Download <b>PRICE LIST WEF May 24_AYURVEDIC:</b><a href="../public/Uploads/pricelist_uploads/distributor/distributor_PRICE LIST WEF MAY'24-AYURVEDIC SASTRIC.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_AYURVEDIC</a>
        </div>
        <div class="col-md-4"   style="padding: 35px;">
            Click here for Download <b>PRICE LIST_COSMETICS_WEF May 24:</b><a href="../public/Uploads/pricelist_uploads/distributor/distributor_PRICE LIST WEF May 24-COSMETICS.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST_COSMETICS_WEF May 24</a>
        </div>
        <?php }else { ?>
        <div class="container" style="text-align:center;">
            <div id="notfound" style="background:none;">
                <div class="notfound" style="">
                    <div class="notfound-404">
                        <img src="../public/images/error_images/sign.png" width="100" height="100" style="margin-top: -1%;">
                        <h1>ACCESS <span>RESTRICTED</span> !</h1>
                    </div>
                    <h2 style="text-shadow: 0 2px 2px rgba(0,0,0,0.6);margin-top: 5px;margin-bottom: 11px;">SORRY</h2>
                    <h2 style="color: #262626">YOU DO NOT HAVE ACCESS THIS PAGE</h2>
                    <!-- <span class="blink">_</span></h2> -->
                    <p style="cursor: pointer;"><a href="../public/home" class="btn">Home </a></p>
                </div>
            </div>
        </div>
        <?php } ?>
        </div>
        <div class="row">
        <div class="col-md-12" style="padding: 35px;">
        <?php if(($groupname == "14" && $zone != '16')  || $emp_id == "302" || $emp_id == "152" || $emp_id == "156" || $emp_id == "151" || $emp_id == "160" || $emp_id == "53" || $emp_id == "155" || $emp_id == "474" || $emp_id == "531" || $emp_id == "598") { ?>    
        <div class="col-md-6">
            Click here for Download <b>PRICE LIST WEF May 24_SIDDHA SASTRIC:</b><a href="../public/Uploads/pricelist_uploads/distributor/distributor_PRICE LIST WEF MAY'24-SIDDHA SASTRIC.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_SIDDHA SASTRIC</a>
        </div>
        <?php } ?>
        
        <div class="col-md-6">
            <!--Click here for Download <b>PRICE LIST WEF May 24_AYURVEDIC:</b><a href="../public/Uploads/pricelist_uploads/" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24</a> -->
        </div>
        
        </div>
    </div>
    </div>
    <div id="stockiest" class="tab-pane fade">
        <div class="col-md-12">
        <h3>Stockiest Pricelist</h3>        
        <?php if($groupname == "14" || $emp_id == "302" || $emp_id == "152" || $emp_id == "156" || $emp_id == "151" || $emp_id == "160" || $emp_id == "155" || $emp_id == "474" || $emp_id == "531" || $emp_id == "598") { ?>
        <div class="col-md-4" style="padding: 35px;">
            Click here for Download <b>PRICE LIST WEF May 24_PIONEER:</b><a href="../public/Uploads/pricelist_uploads/stockiest/stockiest_PRICE LIST WEF May 24-PIONEER.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_PIONEER</a><!--<a href="../public/Uploads/pricelist_uploads/PRICE LIST WEF May 24.pdf" target="_blank"  id="" value="" download><i class="fa fa-file-pdf-o"></i></a>-->
        </div>
        <div class="col-md-4" style="padding: 35px;">
            Click here for Download <b>PRICE LIST WEF May 24_AYURVEDIC:</b><a href="../public/Uploads/pricelist_uploads/stockiest/stockiest_PRICE LIST WEF May 24-AYURVEDIC SASTRIC.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_AYURVEDIC</a>
        </div>
        <div class="col-md-4" style="padding: 35px;">
            Click here for Download <b>PRICE LIST_COSMETICS_WEF May 24:</b><a href="../public/Uploads/pricelist_uploads/stockiest/stockiest_PRICE LIST WEF May 24-COSMETICS.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST_COSMETICS_WEF May 24</a>
        </div>
        <?php }else { ?>
        <div class="container" style="text-align:center;">
            <div id="notfound" style="background:none;">
                <div class="notfound" style="">
                    <div class="notfound-404">
                        <img src="../public/images/error_images/sign.png" width="100" height="100" style="margin-top: -1%;">
                        <h1>ACCESS <span>RESTRICTED</span> !</h1>
                    </div>
                    <h2 style="text-shadow: 0 2px 2px rgba(0,0,0,0.6);margin-top: 5px;margin-bottom: 11px;">SORRY</h2>
                    <h2 style="color: #262626">YOU DO NOT HAVE ACCESS THIS PAGE</h2>
                    <!-- <span class="blink">_</span></h2> -->
                    <p style="cursor: pointer;"><a href="../public/home" class="btn">Home </a></p>
                </div>
            </div>
        </div>
        <?php } ?>
        </div>
        <div class="row">
        <div class="col-md-12" style="padding: 35px;">
        <?php if(($groupname == "14" && $zone != '16')  || $emp_id == "302" || $emp_id == "152" || $emp_id == "156" || $emp_id == "151" || $emp_id == "160" || $emp_id == "53" || $emp_id == "155" || $emp_id == "474" || $emp_id == "531" || $emp_id == "598") { ?>    
        <div class="col-md-6">
            Click here for Download <b>PRICE LIST WEF May 24_SIDDHA SASTRIC:</b><a href="../public/Uploads/pricelist_uploads/stockiest/stockiest_PRICE LIST WEF May 24-SIDDHA SASTRIC.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_SIDDHA SASTRIC</a>
        </div>
        <?php } ?>
        
        <div class="col-md-6">
            <!--Click here for Download <b>PRICE LIST WEF May 24_AYURVEDIC:</b><a href="../public/Uploads/pricelist_uploads/" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24</a> -->
        </div>
        
        </div>
    </div>
    </div>
    <div id="chemist" class="tab-pane fade">
        <div class="col-md-12">
        <h3>Chemist Pricelist</h3>        
        <?php if($groupname == "14" || $emp_id == "302" || $emp_id == "152" || $emp_id == "156" || $emp_id == "151" || $emp_id == "160" || $emp_id == "155" || $emp_id == "474" || $emp_id == "531" || $emp_id == "598") { ?>
        <div class="col-md-4"   style="padding: 35px;">
            Click here for Download <b>PRICE LIST WEF May 24_PIONEER:</b><a href="../public/Uploads/pricelist_uploads/chemist/chemist_PRICE LIST WEF May 24-PIONEER.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_PIONEER</a><!--<a href="../public/Uploads/pricelist_uploads/PRICE LIST WEF May 24.pdf" target="_blank"  id="" value="" download><i class="fa fa-file-pdf-o"></i></a>-->
        </div>
        <div class="col-md-4"   style="padding: 35px;">
            Click here for Download <b>PRICE LIST WEF May 24_AYURVEDIC:</b><a href="../public/Uploads/pricelist_uploads/chemist/chemist_PRICE LIST WEF May 24-AYURVEDIC SASTRIC.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_AYURVEDIC</a>
        </div>
        <div class="col-md-4"   style="padding: 35px;">
            Click here for Download <b>PRICE LIST_COSMETICS_WEF May 24:</b><a href="../public/Uploads/pricelist_uploads/chemist/chemist_PRICE LIST WEF May 24-COSMETICS.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST_COSMETICS_WEF May 24</a>
        </div>
        <?php }else { ?>
        <div class="container" style="text-align:center;">
            <div id="notfound" style="background:none;">
                <div class="notfound" style="">
                    <div class="notfound-404">
                        <img src="../public/images/error_images/sign.png" width="100" height="100" style="margin-top: -1%;">
                        <h1>ACCESS <span>RESTRICTED</span> !</h1>
                    </div>
                    <h2 style="text-shadow: 0 2px 2px rgba(0,0,0,0.6);margin-top: 5px;margin-bottom: 11px;">SORRY</h2>
                    <h2 style="color: #262626">YOU DO NOT HAVE ACCESS THIS PAGE</h2>
                    <!-- <span class="blink">_</span></h2> -->
                    <p style="cursor: pointer;"><a href="../public/home" class="btn">Home </a></p>
                </div>
            </div>
        </div>
        <?php } ?>
        </div>
        <div class="row">
        <div class="col-md-12" style="padding: 35px;">
        <?php if(($groupname == "14" && $zone != '16')  || $emp_id == "302" || $emp_id == "152" || $emp_id == "156" || $emp_id == "151" || $emp_id == "160" || $emp_id == "53" || $emp_id == "155" || $emp_id == "474" || $emp_id == "531" || $emp_id == "598") { ?>    
        <div class="col-md-6">
            Click here for Download <b>PRICE LIST WEF May 24_SIDDHA SASTRIC:</b><a href="../public/Uploads/pricelist_uploads/chemist/chemist_PRICE LIST WEF May 24-SIDDHA SASTRIC.pdf" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24_SIDDHA SASTRIC</a>
        </div>
        <?php } ?>
        
        <div class="col-md-6">
            <!--Click here for Download <b>PRICE LIST WEF May 24_AYURVEDIC:</b><a href="../public/Uploads/pricelist_uploads/" class="btn download " id="" value="" style="margin-left: 40px;" download>PRICE LIST WEF May 24</a> -->
        </div>
        
        </div>
    </div>
    </div>
        
    
    <!-- OUR CONTENT ENDS HERE -->
    </div>
</div>
</div>

@endsection
