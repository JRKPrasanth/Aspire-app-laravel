@extends('layouts.header')
@section('content')

<style type="text/css">
    /*styles for jqgrid pagination*/
    .ui-jqgrid .ui-pg-table td {
        font-weight: normal;
        vertical-align: middle;
        padding: 6px;
    }
</style>

<div id="accordion">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
            <a role="button">

                Need Help
            </a>
        </h4>
    </div>
</div>
<!-- <h2 class="heads">User Access</h2> -->

<div class="card">



    <div class="card-body card-block">

        <div class="row">
            <div class="col-md-12">

                <div class="panel-title">
                <?php include('toolbar.php'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <hr class="xlg">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="grid1"></table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <hr class="xlg">
            </div>
        </div>

    </div>

</div>



<script type="text/javascript">
    $(document).ready(function() {

        var data = "{{$help_rec}}";
        var result = jQuery.parseJSON(data.replace(/&quot;/g, '"'));
        $("#grid1").jqGrid({

            colModel: [{
                    name: "sop_id",
                    label: "SNO",
                    hidden: true,
                    width: 100
                },
                {
                    name: "primary_menu",
                    label: "Primary Menu",
                    width: 200,
                    editable: true,
                    editrules: {
                        date: true
                    }
                },
                {
                    name: "submenu",
                    label: "Sub Menu",
                    width: 200,
                    editable: true,
                    editrules: {
                        date: true
                    }
                },
                {
                    name: "menu",
                    label: " Menu",
                    width: 200,
                    editable: true,
                    editrules: {
                        date: true
                    }
                },
                {
                    name: "url",
                    label: "Url",
                    width: 200,
                    editable: true,
                    editrules: {
                        date: true
                    }
                },

            ],
            datatype: 'local',
            data: result,
            iconSet: "fontAwesome",
            rownumbers: true,
            sortname: "user_name ",
            sortorder: "asc",
            threeStateSort: true,
            sortIconsBeforeText: true,
            headertitles: true,

            pager: '#grid1',
            rowNum: 10,
            viewrecords: true,
            searching: {
                defaultSearch: "cn"
            }
        });
        jQuery("#grid1").jqGrid('filterToolbar', {
            stringResult: true,
            searchOnEnter: false
        });
        $("#grid1").jqGrid("setLabel", "rn", "S.No");

        /* create Function*/
        $(".create").click(function() {

            var url = "{{ url('createsop/0') }}";

            window.location.replace(url);

        });
    /*End*/

        // edit function

        $(".edit").click(function() {

            var index = $("#grid1").jqGrid('getGridParam', 'selrow');
            var pohdrid = $("#grid1").jqGrid('getCell', index, 'sop_id');
            if (index) {
                window.location.replace('createsop/' + pohdrid);
            } else {
                notyMsg("info", "Please Select a Row");
            }

        });
        /*End*/


        /*View Function*/
        $(".view").click(function() {

            var index = $("#grid1").jqGrid('getGridParam', 'selrow');
            var pohdrid = $("#grid1").jqGrid('getCell', index, 'sop_id');
            if (index) {
                window.location.replace('needhelpview/' + pohdrid);
            } else {
                notyMsg("info", "Please Select a Row");
            }

        });

        /*End*/

        //delete function
        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var gr = jQuery("#grid1").jqGrid('getGridParam', 'selrow');
            var cellValue = jQuery("#grid1").jqGrid('getCell', gr, 'sop_id');
            if (cellValue) {
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: !0,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }, function(e) {
                    if (e == true) {

                        $.get('needhelpdelete/' + cellValue, function(data, status) {
                            var data = $.trim(data);
                            if (data == '1') {
                                notyMsg('success', 'Deleted Successfully');
                                setTimeout(function() {
                                    window.location.href = "{{ url('needhelp') }}";
                                }, 1500);
                            }
                            if (data == '2') {
                                notyMsg('error', "You Cant't delete  Used in SomeWhere");
                                setTimeout(function() {
                                    window.location.href = "{{ url('needhelp') }}";
                                }, 1500);
                            }
                        });
                    } else {
                        $('.apply').css('display', 'none');
                        swal("Cancelled");
                    }
                })
                $('.apply').css('display', 'none');
            } else {
                notyMsg('info', "Please Select a Row");
            }
        });

    });
    /*End*/
</script>
@endsection