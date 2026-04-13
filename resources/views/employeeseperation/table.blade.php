@extends('layouts.header')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-visible" id="spy1">
<div class="panel-heading">
<div class="panel-title hidden-xs">
    <span class="glyphicon glyphicon-tasks"></span>
    <a href='adddepartment' class='btn btn-sm btn-success'> <i class="fa fa-plus">Create</i></a>
    <a id="editdata"  class="btn sec"><i class="fa fa-edit">Edit</i></a>
    <a id="viewdata"  class="btn vie"><i class="fa fa-eye">View</i></a>
    <button type='button' href='' class='btn del'><i class="fa fa-trash">Delete</i></button>
</div>
</div>
</div>
</div>
<div class="col-md-12">
<table id="grid1"></table>
</div>
</div>
@extends('layouts.footer')
</div>

<script type="text/javascript">
$( document ).ready(function() {
});
  </script>
@endsection
