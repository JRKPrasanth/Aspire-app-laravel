@extends('layouts.header')
@section('content')

<style type="text/css">
  .datepicker{

    z-index:1052 !important;}
.Menu {
    position: absolute;
    top: 77%;
    left: auto;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, .15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
}
</style>
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                        <?php if($pageMethod=="productionbeforecostrpt"){?>
                                       Production Before Cost Report 
                                       <?php }else{ ?>
                                  Production Cost Report
                                  <?php } ?>
                                    </a>
                                </h4>
                            </div>

</div>
<div class="card shadow-lg rounded-4 border-0">


<div class="card-body card-block">
 
 <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
            <label for="inputIsValid" class="form-control-label col-md-4"> <?php if($pageMethod=="productionbeforecostrpt"){?> Product  <?php }else{ ?> Job No <?php } ?> </label>
            <div class="col-md-4">
                <?php if($pageMethod=="productionbeforecostrpt"){ ?>
                <select class="form-control select2 product " >{!! $product  !!}</select>  
                <?php }else{ ?> 
                 <select class="form-control select2 job_no" >{!! $jobno !!}</select>  
                <?php } ?>
            </div>
        </div>
    </div>
  

            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    <button type="button" class="btn save search" value="SAVE">Search</button>
                    <button type="button" class="btn printMe" value="Print" onclick="window.print()" target="_blank">Print</button>
               </div>
            </div>
            <div class="row">
<div class="col-md-12"> 

<div class="lines_Datas lines_data">
    
    </div>
</div>
</div>
            </div>
            </div>
   

<script type="text/javascript">

$( document ).ready(function() {
    $('.search').click(function(){
         <?php if($pageMethod=="productionbeforecostrpt"){ ?>
         var product =$('.product').val();
         var url="{{ URL::to('prdbasedwipcostdetails') }}/"+product;
         <?php }else{ ?>
        var jobno=$('.job_no').select2('val');
        var url="{{ URL::to('jobbasedwipcostdetails') }}/"+jobno;
         <?php } ?>
        $.get(url,function(data)
            {
                               
                $('.lines_Datas').html(data);
                            $('.collaptable').aCollapTable({
    startCollapsed: true,
    addColumn: false, 
    plusButton: '<span class="i">+</span>', 
    minusButton: '<span class="i">-</span>' 
  });
                     
            });
    });
    
});
    </script>
@endsection
