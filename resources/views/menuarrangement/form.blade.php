@extends('layouts.header')
@section('content')
<h2 class="text-danger">Menu Arrangement</h2>
@include('layouts.breadcrumb')
<style type="text/css">
  .iconpicker {
    width: 30px;
    height: 30px;
    background: #313949;
    border: #313949;
}
.panel-default > .panel-heading {
    color: #fff;
    background-color: #455986;
    border-color: #EEEEEE;
}
#btnReload{
    color: #000;
}
.panel-primary>.panel-heading {
    color: #fff;
    background-color: #455986;
    border-color: #455986;
}
	    .btn-group-xs>.btn, .btn-xs {
    padding: 1px 5px;
    font-size: 12px;
    line-height: 18px;
    margin: 0;
    margin-left: 5px !important;
    min-width: 25px;
    min-height: 22px;
    border-radius: 0;
    box-shadow: none;
}
.btn-group-xs>.btn.glyphicon {
    padding-top: 2px;
}
.btn i {
    margin-top: 3px;
}
.btn {
    box-shadow: none;
    border-radius: 0;
    padding: 6px 7px;
   
    line-height: 20px;
    margin: 0;
}
h4 {
    margin: 8px 0;
}

</style>
 
<div class="card shadow-lg rounded-4 border-0">
 <div class="card-body card-block">
            
    <div id="preloader">
       <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <h4 class="pull-left">Menu</h4>
                            <div class="pull-right">
                                <button id="btnReload" type="button" class="btn">
                                    <i class="glyphicon glyphicon-triangle-right"></i> Load Data</button>
                            </div>
                        </div>
                        <div class="panel-body" id="cont">
                            <ul id="myEditor" class="sortableLists list-group">
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <button id="btnOut" type="button" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Output</button>
                    </div>
                    <div class="form-group"><textarea id="out" class="form-control" cols="50" rows="10"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Edit item</div>
                        <div class="panel-body">
                            <form id="frmEdit" class="form-horizontal">
                                <div class="form-group">
                                    <label for="text" class="col-sm-2 control-label">Text</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Text">
                                            <div class="input-group-btn">
                                                <button type="button" id="myEditor_icon" class="btn1" data-iconset="fontawesome"></button>
                                            </div>
                                            <input type="hidden" name="icon" class="item-menu">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="href" class="col-sm-2 control-label">URL</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control item-menu" id="href" name="href" placeholder="URL">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="target" class="col-sm-2 control-label">Target</label>
                                    <div class="col-sm-10">
                                        <select name="target" id="target" class="form-control item-menu">
                                            <option value="_self">Self</option>
                                            <option value="_blank">Blank</option>
                                            <option value="_top">Top</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">Tooltip</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title" class="form-control item-menu" id="title" placeholder="Tooltip">
                                    </div>
                                </div>
                                    <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">Create URL</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="createurl" class="form-control item-menu" id="createurl" placeholder="CreateUrl">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="panel-footer">
                            <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fa fa-refresh"></i> Update</button>
                            <button type="button" id="btnAdd" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>
                    
               
                </div>
            </div>
</div>
</div>

    	<?php error_reporting(0);
          $headers=DB::table("tb_menus")->select('*')->where('parent_id', '=', 0)->orderBy('menus_id', 'asc')->get();
           $subsubmenu=array();
           foreach($headers as $key=>$value){
                                      
		$subsubmenu[$key]->text=$value->menus_name;
		$subsubmenu[$key]->href=$value->controller_name;
		$subsubmenu[$key]->icon='';
		$subsubmenu[$key]->target='';
		$subsubmenu[$key]->title='';
		$subsubmenu[$key]->createurl=$value->createurl;
        $subhead=DB::table("tb_menus")->select('*')->where('parent_id', '=', $value->menus_id)->orderBy('menus_id', 'asc')->get();
if($subhead)
{
   $subsubmenu[$key]->children=array();
}
                                foreach($subhead as $k=>$v){
                                   
$subsubmenu[$key]->children[$k]->text=$v->menus_name;
$subsubmenu[$key]->children[$k]->href=$v->controller_name;
$subsubmenu[$key]->children[$k]->icon='';
$subsubmenu[$key]->children[$k]->target='';
$subsubmenu[$key]->children[$k]->title='';
$subsubmenu[$key]->children[$k]->createurl=$v->createurl;

                                $subname=DB::table("tb_menus")->select('*')->where('parent_id', '=', $v->menus_id)->orderBy('menus_id', 'asc')->get();
          if($subname)
          {
            $subsubmenu[$key]->children[$k]->children=array(); 
           
          }
                              
                                foreach($subname as $k1=>$v1){
                                    
                         $subsubmenu[$key]->children[$k]->children[$k1]->text=$v1->menus_name;
						$subsubmenu[$key]->children[$k]->children[$k1]->href=$v1->controller_name;
						$subsubmenu[$key]->children[$k]->children[$k1]->icon='';
						$subsubmenu[$key]->children[$k]->children[$k1]->target='';
						$subsubmenu[$key]->children[$k]->children[$k1]->title='';
						$subsubmenu[$key]->children[$k]->children[$k1]->createurl=$v1->createurl;;
                                }

                                }


                              }                    
                               ?>

@endsection
@push('scripts')

<script>
 jQuery(document).ready(function () {
                // menu items
                var strjson =<?php  echo json_encode($subsubmenu);?>; 
                //icon picker options
                var iconPickerOptions = {searchText: 'Buscar...', labelHeader: '{0} de {1} Pags.'};
                //sortable list options
                var sortableListOptions = {
                    placeholderCss: {'background-color': 'cyan'}
                };

                var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions, labelEdit: 'Edit'});
                editor.setForm($('#frmEdit'));
                editor.setUpdateButton($('#btnUpdate'));
                
                $('#btnReload').on('click', function () {
                        editor.setData(strjson);
                });

                $('#btnOut').on('click', function () {
                    
                    var str = editor.getString();
                    $('.preloader').show();
                    $.post('menuarrange',{menu_data:str,_token:"{{csrf_token()}}"},function(data){
                       if(data==1){
      notyMsg("success","Sucessfully");   
   
        }
                        
                        
                    });
                    $("#out").text(str);
                });

                $("#btnUpdate").click(function(){
                    editor.update();
                });

                $('#btnAdd').click(function(){
                    editor.add();
                });
            });

</script>
@endpush

