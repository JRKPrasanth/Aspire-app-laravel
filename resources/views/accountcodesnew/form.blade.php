@extends('layouts.header')
@section('content')
<h3 class="text-danger">Account Codes</h3>
@include('layouts.breadcrumb')
<style type="text/css">
    #tree-table {

        width: 100%;
        overflow: hidden;
        overflow-x: scroll;
    }

    .treegrid-indent {
        width: 0px;
        height: 16px;
        display: inline-block;
        position: relative;
    }

    .treegrid-expander {
        width: 0px;
        height: 16px;
        color: #07234e;
        display: inline-block;
        position: relative;
        left: -17px;
        cursor: pointer;
    }

    #tree-table th {
        background: #07234e;
        border: transparent;
        color: #fff;
    }

    ol>ol {
        display: none;
    }

    #linedesc_table th:nth-child(1),
    #linedesc_table tbody td:nth-child(1) {
        display: none;
    }

    #linedesc_table tbody td:nth-child(2) {
        color: #FFF;
    }

    .modal-dialog {
        width: 70% !important;
    }

    .shedule_bom_page_lines_div_left>h5 {
        color: #fff;
        margin-left: 15px;
    }

    .shedule_bom_page_lines_div_left {
        background-clip: border-box;
        background-color: rgba(26, 126, 136, 0.69);
        border: 2px solid gold;
        border-radius: 10px;
        box-shadow: 5px 0 6px 2px #888;
        padding: 5px;
    }

    .modal-dialog {
        margin: 30px auto;
        width: 690px;
    }

    .treeload_refresh>img {
        height: 20px;
        width: 20px;
    }



    /* CSS Tree menu styles */
    .sbox ol.tree {
        padding: 0 0 0 30px;
        /*width: 100%;*/
    }

    .sbox li {
        position: relative;
        margin-left: -15px;
        list-style: none;
    }

    .sbox li input {
        position: absolute;
        left: 0;
        margin-left: 0;
        opacity: 0;
        z-index: 2;
        cursor: pointer;
        height: 1em;
        width: 1em;
        top: 0;
    }

    .sbox li input+ol {
        background: url(images/toggle-small-expand.png) 40px 0 no-repeat;
        margin: -1.600em 0px 8px -44px;
        height: 1em;
    }

    .sbox li input+ol>li {
        display: none;
        margin-left: -14px !important;
        padding-left: 1px;
    }

    .sbox li label {
        background: url(images/folder.png) 15px 1px no-repeat;
        cursor: pointer;
        display: block;
        padding-left: 37px;
        /*background:red;*/
    }

    .sbox li label a {

        color: #FFFFFF;
    }

    .sbox li input:checked+ol {
        background: url(images/toggle-small.png) 40px 5px no-repeat;
        margin: -1.96em 0 0 -44px;
        padding: 1.563em 0 0 80px;
        height: auto;
    }

    .sbox li input:checked+ol>li {
        display: block;
        margin: 8px 0px 0px 0.125em;
    }

    .sbox li input:checked+ol>li:last-child {
        margin: 8px 0 0.063em;
    }



    /*TREE MENUS*/


    .shedule_bom_treesmenus {
        margin-top: 50px;
        padding: 5px 0;
    }


    .shedule_bom_treesmenus {
        height: 245px;
        overflow: auto;
    }

    .bom_header_div_tree.col-md-6 {
        border: 1px solid #999;
        border-radius: 10px;
        box-shadow: 0 0 10px #999;
    }

    .dataTables_filter {
        display: none;
    }
</style>



<form action="" method="post" class="acc_code_form" id="acc_code_form" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />
    {{ csrf_field()}}

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header">

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-1 bg-light rounded-3 border text-center">
                        <h6 class="text-muted mb-1">Account Class : <span class="mb-0 text-primary fw-semibold">{{
                                $account_class_name }}</span></h6>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-1 bg-light rounded-3 border text-center">
                        <h6 class="text-muted mb-1">Main Account Code : <span class="mb-0 text-primary fw-semibold">{{
                                $main_account_code }}</span></h6>
                    </div>
                </div>
            </div>



        </div>
        <div class="card-body card-block">
            <!------------------------------------- Body content start here ---------------------------->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group  col-md-4 row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-5">Account Class Name</label>
                        <div class="col-md-7">
                            <input type="text" id="account_class_id" name="account_class_id"
                                class="form-control 	account_class_id" required value="{{$account_class_id}}" readonly>
                            <select name='account_class_id' rows='5' class='form-control account_class_id'
                                style="pointer-events:none;">
                                {!! $account_class_id !!}
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-md-4 row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-5">Main Account Code</label>
                        <div class="col-md-7">
                            <input type="text" id="main_account_code" name="main_account_code"
                                class="form-control 	main_account_code" required value="{{$main_account_code}}"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group  col-md-4 row" style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-5">Active</label>
                        <div class="col-md-7">
                            <select name="active" class="form-control active">

                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <!------------------------------------------------------------------------------------------>
            <div class="row">


                <div class="col-md-12">
                    <div class="list-group list-group-tree well">
                        <?php echo $tree_menu; ?>
                    </div>
                </div>
            </div>




            <div class="row mt-2">


                <div class="col-md-6">
                    <div class="form-group   row">
                        <label for="inputIsValid" class="form-control-label align1 col-md-6 fw-bold text-primary">Parent
                            Account Code</label>
                        <div class="col-md-6">
                            <input type="text" name="parent_account_code" class="form-control parent_account_code">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group   row">
                        <label for="inputIsValid" class="form-control-label align1 col-md-6 fw-bold text-primary">Parent
                            Account Code Meaning</label>
                        <div class="col-md-6">
                            <input type="text" name="parent_account_code_meaning"
                                class="form-control parent_account_code_meaning">
                        </div>
                    </div>
                    <input type="hidden" name="child_parent_id" class="form-control child_parent_id">
                    <input type="hidden" name="acc_parent_id" class="form-control acc_parent_id">
                </div>


            </div>


            <!-------------------------Linedata -------------------------------->

            <div class="row mt-4">
                <div class="col-md-12">

                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table">

                            <thead class="table-light">
                                <tr>
                                    <th>Line No</th>
                                    <th>Account Code</th>
                                    <th>Account Code Meaning</th>
                                    <th>Description</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>

                            </thead>

                            <tbody class="clone_lines_body">
                                <?php if(count($linedata)>=1) { ?>
                                @foreach($linedata as $key=>$value)
                                <tr class="rcopy clone">

                                    <td>
                                        <input type="hidden" name="parent_id[]" class="bulk_parent_id">
                                        <input type="hidden" name="bulk_account_codes_line_id[]"
                                            class="form-control input-sm bulk_account_codes_line_id"
                                            value="{{ $value->account_codes_line_id }}">

                                        <input type="hidden" name="bulk_account_codes_hdr_id[]"
                                            class="form-control input-sm bulk_account_codes_hdr_id" value="">

                                        <input type="text" name="bulk_line_no[]"
                                            class="form-control input-sm bulk_line_no" value="" readonly="readonly">
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_account_code[]"
                                            class="form-control input-sm bulk_account_code" value="" required>
                                        <span class="btn btn-danger dup_name" style="display:none;"></span>
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_account_code_meaning[]"
                                            class="form-control input-sm bulk_account_code_meaning " value="" required>
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_description[]"
                                            class="form-control input-sm bulk_description" value="">
                                    </td>
                                    <td>
                                        <select name="bulk_active[]" class="select2 bulk_active" required>
                                            <option value="">--Please Select--</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach

                                <?php } if(count($linedata) < 1 ) { ?>
                                <tr class="rcopy clone">

                                    <td>
                                        <input type="hidden" name="parent_id[]" class="bulk_parent_id">
                                        <input type="hidden" name="bulk_account_codes_line_id[]"
                                            class="form-control input-sm bulk_account_codes_line_id" value="">

                                        <input type="hidden" name="bulk_account_codes_hdr_id[]"
                                            class="form-control input-sm bulk_account_codes_hdr_id" value="">

                                        <input type="text" name="bulk_line_no[]"
                                            class="form-control input-sm bulk_line_no" value="">
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_account_code[]"
                                            class="form-control input-sm bulk_account_code" value="" required>
                                        <span class="btn btn-danger dup_name" style="display:none;"></span>
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_account_code_meaning[]"
                                            class="form-control input-sm bulk_account_code_meaning " value="" required>
                                    </td>
                                    <td>
                                        <input type="text" name="bulk_description[]"
                                            class="form-control input-sm bulk_description" value="">
                                    </td>
                                    <td>
                                        <select name="bulk_active[]" class="select2 bulk_active " required>
                                            <option value="">--Please Select--</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>

                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <button type="button" class="btn btn-success btn-sm add-row">
                                <i class="fas fa-plus-circle"></i> Add Row
                            </button>
                        </div>

                        <input type="hidden" name="enable-masterdetail" value="true">

                        <div class="col-lg-12 col-md-12 mt-4 mb-3">
                            <div class="form-group text-center">
                                <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                                <button type="button" id="save" class="btn btn-success px-4 me-2 saveform"
                                    value="SAVE">Save</button>
                                <a class='btn btn-danger px-4 me-2'
                                    onclick="location.href ='{{url('accountcodesnew')}}'">Cancel</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <!-------------------------Linedata End-------------------------------->

        </div>



    </div>

</form>





@endsection
@push('scripts')


<script>

    var dup_chk = true;
    function duplicate_validate() {
        var account_code = [];
        var edit_id = [];

        $(".bulk_account_code").each(function (index) {
            account_code[index] = $(this).val();
            edit_id[index] = $('.bulk_account_codes_line_id' + index).val();
        });

        $.ajax({
            cache: false,
            url: "{{URL::to('accountbuildcheckname/')}}", //this is your uri
            type: 'GET',
            dataType: 'json',
            async: false,
            data: { account_code: account_code, edit_id: edit_id },
            success: function (response) {
                console.log(response);
                if (jQuery.inArray('1', response)) {
                    for (var i = 0; i < response.length; i++) {
                        if (response[i] == 1) {
                            $('.dup_name' + i).show();
                            $('.dup_name' + i).html('Account Code:' + account_code[i] + ' Already Exists');
                            $(".bulk_account_code" + i).val('');
                        }
                    }
                }
                else {
                    var html = "";
                    $('.dup_name').hide();
                    dup_chk = true;
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    }

    $("#expList> li").click(function () {


        var $this = $(this);
        //console.dir($(this));
        if (false == $(this).next().is(':visible')) {
            $('#expList > ol').slideUp(600);
        }

        var slideup = $(this);
        $(this).next().slideDown(600).children().slideDown();

    });

    $('#expList > ol').hide();




    $(document).ready(function () {
        // delegated handler
        $(".list-group-tree").on('click', "[data-toggle=collapse]", function () {
            $(this).toggleClass('in')
            $(this).next(".list-group.collapse").collapse('toggle');

            // next up, when you click, dynamically load contents with ajax - THEN toggle
            return false;
        })

    });


    $(document).ready(function () {

        /*Save Function*/
        $('#savestatus').val('');
        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();

            if (btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
            else (btnval == 'SAVE')
            var savestatus = 'SAVE';

            $('#savestatus').val(savestatus);
            $('.submit_type').val("save");

            var url = "{{ URL::to('accountcodesnewsave') }}";
            var red_url = "{{ URL::to('accountcodesnew') }}";
            var formdata = $('#acc_code_form').serialize();
            var form = $('#acc_code_form');
            if (btnval != 'APPLYCHANGES') {
                form.parsley().validate();
                var form = $('#acc_code_form');
                form.parsley().validate();
                duplicate_validate();
                if (form.parsley().isValid()) {
                    if (dup_chk == true) {

                        $.post(url, formdata, function (data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;

                            if (btnval != 'SAVE') {
                                showCustomAlert(msg, status);
                                setTimeout(function () {
                                }, 1500);
                            }
                            else {
                                showCustomAlert(msg, status);
                                setTimeout(function () {
                                    location.reload();
                                }, 1500);
                            }
                        });
                    }
                }
            }
            else {
                $.post(url, formdata, function (data) {
                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ URL::to('accountcodescreate') }}/" + id;
                    showCustomAlert(msg, status);
                    setTimeout(function () {
                        window.location.href = edit_url;
                    }, 1500);
                });
            }
        });




        $(document).on('click', '.subacc_btn', function () {
            var id = $(this).data("id");
            addfunction(id);
            $(".subacc_btn").each(function (index) {
                if (index != 0) {
                    $('.bulk_account_code' + index).closest('tr').remove();
                }
                else {
                    $('.bulk_account_codes_line_id0').val('');
                    $('.bulk_account_code0').val('');
                    $('.bulk_account_code_meaning0').val('');
                    $('.bulk_description0').val('');
                    $('.bulk_active0').val('').change();
                }
            })
        });

        var index = $('.clone').closest('tr').index();
        changeclassfields();
    });

    function addfunction(id) {

        $.get("{{ URL::to('subaccountcode') }}/" + id, function (data) {
            $('.parent_account_code_meaning').val(data['query'][0].account_code_meaning);
            $('.parent_account_code').val(data['query'][0].account_code);
            $('.child_parent_id').val(data['query'][0].account_codes_line_id);
            $('.acc_parent_id').val(data['query'][0].account_codes_hdr_id);

            var sub = data.sub;

            $.each(sub, function (k, value) {

                if (k != "0") {
                    $('.add_row').trigger('click');

                }
                $('.bulk_account_codes_line_id' + k).val(value.account_codes_line_id);
                $('.bulk_account_code' + k).val(value.account_code);
                $('.bulk_active' + k).val(value.active);
                $('.bulk_parent_id').val(value.parent_class_id);
                $('.bulk_account_code_meaning' + k).val(value.account_code_meaning);
                $('.bulk_description' + k).val(value.description);
                $('.bulk_active' + k).val(value.active).change();

            })

        });


    }

    function changeClassName(className) {
        $('.' + className).each(function (index) {
            if (className == "bulk_line_no") {
                $(this).val(index + 1).attr("readonly", 1);
            }

            $(this).removeClass(className + '0');
            $(this).addClass(className + index);
        });
    }


    function changeclassfields() {
        changeClassName('bulk_account_codes_line_id');
        changeClassName('bulk_line_no');
        changeClassName('bulk_account_code');
        changeClassName('bulk_account_code_meaning');
        changeClassName('bulk_description');
        changeClassName('bulk_parent_id');
        changeClassName('parent_class_id');
        changeClassName('bulk_active');
        changeClassName('dup_name');
    }

    // Add Row
    $(document).on('click', '.add-row', function () {
        const $lastRow = $('.clone_lines_body tr:last');
        const $newRow = $lastRow.clone(false, false); // clone without events or data

        // Clear all input and select values in the cloned row
        $newRow.find('input').val('');
        $newRow.find('select').val('').trigger('change');

        // Remove any Select2 artifacts before reinitializing
        $newRow.find('select.select2').each(function () {
            if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
            }
            $(this).removeAttr('data-select2-id');
            $(this).next('.select2').remove(); // remove the select2 container
        });

        // Append the cleaned-up cloned row
        $('.clone_lines_body').append($newRow);

        // Reinitialize select2
        $newRow.find('select.select2').select2({ width: '100%' });

        // Update line numbers
        updateLineNumbers();
    });



    // Remove button
    $(document).on('click', '.remove-row', function () {
        const rowCount = $('.clone_lines_body tr').length;
        if (rowCount > 1) {
            $(this).closest('tr').remove();
            updateLineNumbers();
        } else {
            showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
        }
    });

    // Renumber Line Nos
    function updateLineNumbers() {
        $('.clone_lines_body tr').each(function (index) {
            $(this).find('.bulk_line_no').val(index + 1);
        });
    }

</script>

@endpush