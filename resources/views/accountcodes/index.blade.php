@extends('layouts.header')
@section('content')

<h5> <i class="fa fa-table">&nbsp;</i> Account Code </h5>
<button type="button" class="btn btn-info btn-lg open_modal" data-toggle="modal" style="display:none;" data-target="#myModal">Open Modal</button>	
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Account</h4>
        </div>
        <div class="modal-body">
           <div class="form-group  " > 
					<label for="Account Head" class=" control-label col-md-4 text-left">
					Account Head
                                        </label>
					<div class="col-md-6">
                                            <input type="text" class="form-control account_head" readonly="true">
                                            <input type="hidden" class="parent_id" name="parent_id">
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>
              <div class="form-group  " > 
					<label for="Account Name" class=" control-label col-md-4 text-left">
					Account Name
                                        </label>
					<div class="col-md-6">
                                            <input type="text" name="accountclass_name" class="form-control accountclass_name">
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-success add_account"  data-dismiss="modal">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
     <style>
            .label-default{
                padding: 3px !important;
                font-size: 10px !important;
                font-weight: bold !important;
                background-color: white;
                margin-left: 10px;
                    }
                    
                    
                .tree {
    min-height:20px;
    padding:19px;
    margin-bottom:20px;
/*    background-color:#fbfbfb;
    border:1px solid #999;*/
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
    position:relative;
    overflow: visible !important;
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #777;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #777;
    height:20px;
    top:25px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    /*-webkit-border-radius:5px;*/
    border:1px solid #777;
    /*border-radius:5px;*/
    display:inline-block;
    padding:6px 12px;
    text-decoration:none;
    font-size: 12px;
    font-weight: 500;
    text-transform:uppercase;
}
.parent{
    background-color: #1486e8;
    color:white;
}
.child{
    background-color: #088478;
    color:white;
}
.child2{
        background-color: #fff;
    color: rgb(8, 132, 120);
    border: 1px solid #088478 !important;
}
li> span> a{
    color:white !important;
}
.tree li.parent_li>span {
    cursor:pointer
}
.tree>ul>li::before, .tree>ul>li::after {
    /*border:0*/
}
.tree li:last-child::before {
    height:26px;
}
.tree li.parent_li>span:hover, .tree li.parent_li>span:hover+ul li span {
    /*background:#14b4fc;*/
    border:1px solid #777;
    color:#eee;
}     
        </style>

@endsection
