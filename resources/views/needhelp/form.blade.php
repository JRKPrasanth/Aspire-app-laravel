@extends('layouts.header')
@section('content')

<style type="text/css">
    @media only screen and (min-width: 1500px) {

        .bulk_line_no {
            width: 50px !important;
        }

        .bulk_active {
            width: 300px !important;
        }

        .bulk_locationid {
            width: 500px !important;
        }

        .bulk_activities {
            width: 800px !important;
        }

    }

    @media only screen and (min-width: 2000px) {
        .bulk_line_no {
            width: 50px !important;
        }

        .bulk_active {
            width: 300px !important;
        }

        .bulk_locationid {
            width: 600px !important;
        }

        .bulk_activities {
            width: 800px !important;
        }
    }

    <?php if ($pageMethod == "companyedit") { ?>@media only screen and (min-width: 1500px) {

        .bulk_line_no {
            width: 50px !important;
        }

        .bulk_active {
            width: 300px !important;
        }

        .bulk_locationid {
            width: 500px !important;
        }

        .bulk_activities {
            width: 800px !important;
        }

    }


    .bulk_line_no {
        width: 50px;
    }

    .bulk_active {
        width: 350px;
    }

    .bulk_activities {
        width: 650px
    }



    <?php } ?>.bulk_line_no {
        width: 50px !important;
    }

    .bulk_active {
        width: 350px !important;
    }

    .bulk_locationid {
        width: 450px !important;
    }

    .bulk_activities {
        width: 550px !important;
    }
</style>

<?php error_reporting(0);
?>

<div class="ajaxLoading"></div>
<h3 class="heads">Create SOP</h3>

<form method="POST" action="" id="help_form" class="help_form" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card">
        <div class="card-body card-block ">
            <div class="row">
                <div class="col-md-12">
                <div>
                    <!------------------------------------- Body content start here ---------------------------->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red; ">*</span>Primary Menu</label>
                                    <div class="col-md-6">
                                        <select style="width:100%" name='primary_menu_id' id='primary_menu_id' rows='5' class='form-control primary_menu_id select2' value="{{$row->primary_menu}}" required>
                                            {!! $primary_menu !!}
                                        </select>

                                    </div>
                                </div>

                                     <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4"><span style="color: red; ">*</span>Url</label>
                                    <div class="col-md-6">
                                        <input name="sop_id" type="hidden" class="form-control" id="sop_id" value="{{$row->sop_id}}">
                                        <select name="url" type="text" class="form-control url select2" id="url" value="{{$row->url}}" required>
                                        {!! $url !!}
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4"> Sub Menu </label>
                                    <div class="col-md-6">
                                        <select id="sub_menu_id" style="width:100%" name='sub_menu_id' rows='5' class='form-control sub_menu_id select2' value="{{$row->submenu}}" required>
                                            {!!$submenu !!}
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Created By</label>
                                    <div class="col-md-6" style="pointer-events:none;">
                                        <select name='created_by' rows='5' class='select2 created_by' id="created_by" readonly>
                                            {!! $created_by !!}
                                        </select>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-4">

                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-4">Menu</label>
                                    <div class="col-md-6">
                                        <select style="width:100%" name='menu_id' id='menu_id' rows='5' class='form-control menu_id select2' value="{{$row->menu}}" required>
                                            {!!$menu !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!--Karthigaa Purpose For Display Dynamic Columns from Column Permission Setting-->
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascript:void(0);" class="add_row additem" rel=".rcopy1"><i class="fa fa-plus"></i> ADD</a>
                        <div id="preview-area" class="chandru">
                            <table class="overflow-y preview sop_table">
                                <thead>
                                    <tr>
                                        <th>Line No</th>
                                        <th>Activities</th>
                                        <th class="pdtdiv">Active</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="company_lines_body">
                                    <?php if (count($linedata) >= 1) { ?>
                                        @foreach($linedata as $key=>$value)
                                        <tr class="clone rcopy1">
                                            <td>
                                                <input type="hidden" name="bulk_sop_line_id[]" class="form-control input-sm bulk_sop_line_id" value="{{ $value->sop_line_id }}">
                                            </td>
                                            <td style="display:none;">
                                                <input type="hidden" name="bulk_sopid[]" class="form-control input-sm bulk_sopid" value="{{ $value->sopid }}">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_activities[]" class="form-control input-sm bulk_activities" value="{{$value->activities}}" required>
                                            </td>
                                            <td>
                                            <select name="bulk_active[]" class="form-control bulk_active select2" data-show-subtext="true" data-live-search="true" required>
                                                <option value="">--Please Select--</option>
                                                <option value="Yes" {{ $value->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ $value->active == 'No' ? 'selected' : '' }}>No</option>
                                            </select>


                                            </td>

                                            <td>
                                                <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                                <input type="hidden" name="counter[]">
                                            </td>
                                        </tr>
                                        @endforeach
                                    <?php }
                                    if (count($linedata) < 1) { ?>
                                        <tr class="clone rcopy1">
                                            <td>
                                                <input type="hidden" name="bulk_sop_line_id[]" class="form-control input-sm sop_line_id" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                                            </td>

                                            <td>
                                                <input type="text" name="bulk_activities[]" class="form-control input-sm bulk_activities" value="" required>
                                            </td>

                                            <td>
                                                <select name="bulk_active[]" class="form-control bulk_active select2" data-live-search="true" required>
                                                    <option value="">--Please Select--</option>
                                                <option value="Yes" {{ $value->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ $value->active == 'No' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                                <input type="hidden" name="counter[]">
                                            </td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="enable-masterdetail" value="true">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">

                            <button name="save" type="button" class="btn save saveform">Save</button>
                            <a class='btn cancel' onclick='location.href="{{ URL::to(needhelp) }}"'>Cancel</a>

                        </div>
                    </div>
                </div>
</form>
</div>
    <div class="row">
        <div class="col-md-12">
            <table id="grid1"></table>
        </div>
    </div>

<script>
    // menu dropdown

    $(document).on('change', '.primary_menu_id', function() {
        var prdgroup = $('.primary_menu_id').select2('val');
        var condition = ' parent_id=' + prdgroup;
        if (prdgroup != '') {
            $(".sub_menu_id").jCombo("{{ URL::to('jcomboform?table=tb_menus:menus_id:menus_name') }}&order_by=menus_name asc" + '&parent=' + condition, {
                selected_value: ""
            });
        }

    });

    $(document).on('change', '.sub_menu_id', function() {
        var prdgroup = $('.primary_menu_id').select2('val');
        var category = $('.sub_menu_id').select2('val');
        

        var condition = ' parent_id=' + category;
        if (prdgroup == "") {

            notyMsg("info", "Please select primary menu");
            $('.sub_menu_id').val('').select2();

        }
        if (category != "") {
           
            $(".menu_id").jCombo("{{ URL::to('jcomboform?table=tb_menus:menus_id:menus_name') }}&order_by=menus_name asc" + '&parent=' + condition, {
                selected_value: ""
                
            });
        } 

         else {
            $('.menu_id').select2('val', ['']);
        }
    });


$(document).on('change', '.menu_id', function() {
    var controller = $('.menu_id').select2('val');
    var condition = ' menus_id=' + controller;
    
    if (controller != "") {
        $(".url").jCombo("{{ URL::to('jcomboform?table=tb_menus:controller_name:controller_name') }}&order_by=controller_name asc" + '&parent=' + condition, {
            selected_value: ""
        });
    } else {
        $('.url').select2('val', ['']);
    }
});


    // -----END-----


    // new form


    $(document).ready(function() {
        // Initialize Parsley on the form
        $('.reset').click(function() {
            $(':input', '#help_form')
                .not(':button, :submit, :reset')
                .val('')
                .prop('checked', false);
        });

        $(".add_row").on('click', function() {
            var form = $('#help_form');
            form.parsley().destroy();
        });

        var data = "{{\Session::get('j_date_format')}}";
        $(".add_row").relCopy(data);
        changeclassfields();

        $('.add_row').click(function() {
            changeclassfields();
        });


    // save function
        $(document).on('click', '.save', function(e) {
            e.preventDefault();

            var btnval = $(this).val();
            $('#savestatus').val(btnval);
            var url = "{{ URL::to('needhelpsave') }}";
            validationrule('help_form');
            var form = $('#help_form');
            form.parsley().validate();
            var form = $('#help_form');
            form.parsley().validate();

            if (form.parsley().isValid()) {
                var saveurl = "{{ url('needhelpsave') }}";
                var red_url = "{{ url('needhelp') }}";
                validationrule('save');
                var form = $("#help_form");
                var formData = form.serialize();

                $.post(saveurl, formData, function(data) {
                    var status = data.status;
                    var msg = data.message;
                    notyMsg(status, msg);

                    setTimeout(function() {
                        window.location.href = red_url;

                    }, 1500);

                });
            }
        });
    // ---END---

        $(document).on('click', '.remove', function() {
            var rowCount = $('.sop_table tbody tr').length;
            if (rowCount > 1) {
                $(this).closest("tr").remove();
                removeclassfields();
            } else {
                notyMsg("info", "You Can't Delete. At least one row should be there");
            }
        });
    });


    function changeclassfields() {
        changeClassName('bulk_sop_line_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_activities');
        changeClassName('bulk_active');

    }

    function removeclassfields() {
        removeClass('bulk_sop_line_id');
        removeClass('bulk_line_no');
        removeClass('bulk_activities');
        removeClass('bulk_active');

    }

    function removeClass(className) {
        $('.sop_table tbody tr').each(function(index) {
            $(this).find('.' + className).removeClass(className + index);
        });
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", "readonly");
            }
            $(this).addClass(className + index);
        });
    }

    function changeClassName(className) {
        $('.' + className).each(function(index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", "readonly");
            }
            $(this).removeClass(className + '0');
            $(this).addClass(className + index);
        });
    }

    // ---END---
</script>
@include('layouts.php_js_validation')
@endsection