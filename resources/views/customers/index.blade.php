<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Demo which uses Bootstrap 4</title>
    <link rel="stylesheet" crossorigin="anonymous"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha256-NJWeQ+bs82iAeoT5Ktmqbi3NXwxcHlfaVejzJI2dklU=">
    <link rel="stylesheet" crossorigin="anonymous"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=">
    <link rel="stylesheet" crossorigin="anonymous"
          href="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.15.4/css/ui.jqgrid.min.css"
          integrity="sha256-gY7w+ZzYjTPCx5Gx1YexizMJigg1YYwcQ3fAnWgAUTE=">
    <script crossorigin="anonymous" src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="></script>
    <!-- the next line need be uncommented if you need to use bootstrap.min.js -->
    <!--<script crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha256-98vAGjEDGN79TjHkYWVD4s87rvWkdWLHPs5MC3FvFX4="></script>
    <script crossorigin="anonymous" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
            integrity="sha256-C8oQVJ33cKtnkARnmeWp6SDChkU+u7KvsNMFUzkkUzk="></script>-->
    <script>
        $.jgrid = $.jgrid || {};
        $.jgrid.no_legacy_api = true;
    </script>
    <script crossorigin="anonymous"
            src="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.15.4/jquery.jqgrid.min.js"
            integrity="sha256-GN28v8v0UEhIeH35OHeGh9LoP5liiKMRbiIFVQ5flTo="></script>
    <script>
    
/* Jqgrid Start */
  $(function () {
      "use strict";
      $("#grid5").jqGrid({
          colModel: [
              { name: "name", label: "Client", width: 53 },
              { name: "invdate", label: "Date", width: 90, align: "center", sorttype: "date",
                  formatter: "date", formatoptions: { newformat: "d-M-Y" },
                  searchoptions: { sopt: ["eq"] } },
              { name: "amount", label: "Amount", width: 65, template: "number" },
              { name: "tax", label: "Tax", width: 41, template: "number" },
              { name: "total", label: "Total", width: 51, template: "number" },
              { name: "closed", label: "Closed", width: 59, template: "booleanCheckbox", firstsortorder: "desc" },
              { name: "ship_via", label: "Shipped via", width: 87, align: "center",
                  formatter: "select",
                  formatoptions: { value: "FE:FedEx;TN:TNT;DH:DHL", defaultValue: "DH" },
                  stype: "select",
                  searchoptions: { value: ":Any;FE:FedEx;TN:TNT;DH:DHL" } }
          ],
          data: [
              { id: "10",  invdate: "2015-10-01", name: "test",   amount: "" },
              { id: "20",  invdate: "2015-09-01", name: "test2",  amount: "300.00", tax: "20.00", closed: false, ship_via: "DH", total: "320.00" },
              { id: "30",  invdate: "2015-09-01", name: "test3",  amount: "400.00", tax: "30.00", closed: false, ship_via: "FE", total: "430.00" },
              { id: "40",  invdate: "2015-10-04", name: "test4",  amount: "200.00", tax: "10.00", closed: true,  ship_via: "TN", total: "210.00" },
              { id: "50",  invdate: "2015-10-31", name: "test5",  amount: "300.00", tax: "20.00", closed: false, ship_via: "FE", total: "320.00" },
              { id: "60",  invdate: "2015-09-06", name: "test6",  amount: "400.00", tax: "30.00", closed: false, ship_via: "FE", total: "430.00" },
              { id: "70",  invdate: "2015-10-04", name: "test7",  amount: "200.00", tax: "10.00", closed: true,  ship_via: "TN", total: "210.00" },
              { id: "80",  invdate: "2015-10-03", name: "test8",  amount: "300.00", tax: "20.00", closed: false, ship_via: "FE", total: "320.00" },
              { id: "90",  invdate: "2015-09-01", name: "test9",  amount: "400.00", tax: "30.00", closed: false, ship_via: "TN", total: "430.00" },
              { id: "100", invdate: "2015-09-08", name: "test10", amount: "500.00", tax: "30.00", closed: true,  ship_via: "TN", total: "530.00" },
              { id: "110", invdate: "2015-09-08", name: "test11", amount: "500.00", tax: "30.00", closed: false, ship_via: "FE", total: "530.00" },
              { id: "120", invdate: "2015-09-10", name: "test12", amount: "500.00", tax: "30.00", closed: false, ship_via: "FE", total: "530.00" }
          ],
          iconSet: "fontAwesome",
          idPrefix: "g5_",
          rownumbers: true,
          sortname: "invdate",
          sortorder: "desc",
          threeStateSort: true,
          sortIconsBeforeText: true,
          headertitles: true,
          pager: true,
          rowNum: 5,
          viewrecords: true,
          searching: {
              defaultSearch: "cn"
          },
          caption: "The grid, which demonstrates formatters, templates and the pager"
      }).jqGrid("filterToolbar");
  });


//End

    </script>
</head>
<body>
<table id="grid5"></table>
</body>
</html>
<style>.ui-jqgrid.ui-jqgrid-bootstrap {
    border: 1px solid #003380;
}
.ui-jqgrid.ui-jqgrid-bootstrap .ui-jqgrid-caption {
    background-color: #e6f0ff;
}
.ui-jqgrid.ui-jqgrid-bootstrap .ui-jqgrid-hdiv {
    background-color: #cce0ff;
}

</style>
