@extends('layouts.header')

@section('content')

<link href="package/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="package/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="package/css/style.css" rel="stylesheet">
<link href="package/vendor/simple-datatables/style.css" rel="stylesheet">
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="package/vendor/simple-datatables/simple-datatables.js"></script>

<style>
  .bread {
    display: none;
  }

  .dob-column {
    width: 19% !important;
    max-width: 19% !important;
  }
  .leave_bal{
text-align:center;
background:#0CAFFF;
color:#000;
font-size: 13px;
font-weight: 800;
width:10%;
}
.empcard-text{
    

    font-size: 16px;
}
.overlay-container {
  position: relative;
}

.overlay-image {
    
    position: absolute;
    top: 60px;
    left: 89px;
    width: 40%;
    height: 50%;
    object-fit: cover;
}

</style>

<div class="row">
  <div class="col-lg-12">
      <div class="col-lg-10">
    <h4 class="dash_title">Ticket System Dashboard</h4>
    </div>
  </div>
</div>
<div class="section dashboard">
    <div class="row">
          
          
    </div>
</div>
















<script src="package/vendor/chart.js/chart.umd.js"></script>
<script src="package/vendor/echarts/echarts.min.js"></script>
<script src="package/vendor/tinymce/tinymce.min.js"></script>
<script src="package/js/main.js"></script>
@endsection