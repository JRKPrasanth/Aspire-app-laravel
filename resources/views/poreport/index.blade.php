@extends('layouts.header')
@section('content')
<style>
    
    .tree {
    min-height:20px;
    padding:19px;
    margin-bottom:20px;
    background-color:#fbfbfb;
    border:1px solid #999;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    -webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    -moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05)
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #999;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #999;
    height:20px;
    top:25px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border:1px solid #999;
    border-radius:5px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none
}
.tree li.parent_li>span {
    cursor:pointer
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    background:#eee;
    border:1px solid #94a0b4;
    color:#000
}
    
    
    
</style>

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                        PO Report
                                    </a>
                                </h4>
                            </div>

</div>







<div class="card">

<div class="card-body card-block">
	<div class="row"> <?php include("toolbar.php");?>
<div class="col-lg-12 col-md-12" style="padding: 15px;">



<div class="tree">
     <ul>
<?php foreach($po_number as $k=>$v ) {  ?>  
   <?php  if(isset($grn_number[$v])){ 
       
       $color="background-color:green";
       
       ?>
         
   <?php } else {  $color="background-color:red";?>
         
   <?php } ?>
       
        <li>
            <span style="<?php echo $color ?>"><i class="icon-calendar"> </i><?php echo $v ?></span>
           <?php  if(isset($grn_number[$v])){ ?>
                <?php foreach($grn_number[$v] as $key=>$value ) { ?>
            
            <ul>
                <li>
                	<span class="badge badge-success"><i class="icon-minus-sign"></i>{{$value}} </span>

                </li>
		    </ul>
            
                 <ul>
                <li>
                	 <span class="badge badge-success"><i class="icon-minus-sign"></i>{{$qc_number[$v][$key]}} </span>

                </li>
		    </ul>
            
                 <ul>
                <li>
                	<span class="badge badge-success"><i class="icon-minus-sign"></i>{{$invoice[$v][$key]}} </span>

                </li>
		    </ul>
            
                 <ul>
                <li>
                	<span class="badge badge-success"><i class="icon-minus-sign"></i>{{$inventory[$v][$key]}} </span>

                </li>
		    </ul>
           
                	
                	
           <?php } } ?>
        </li>
  
<?php } ?>  
          </ul>
    
</div>
    
    
    
    
</div>
</div>
</div>
</div>


<script type="text/javascript">
jQuery(document).ready(function() {

$(function () {
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
        }
        e.stopPropagation();
    });
});


      
});
    </script>
@endsection
