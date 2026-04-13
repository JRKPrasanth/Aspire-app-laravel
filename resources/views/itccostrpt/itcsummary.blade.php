@extends('layouts.header')
@section('content')
<style type="text/css">

body{
    background:aliceblue;
}
.divhide .card{
    padding: 5px;
    border: 1px solid #ccc;
}
.divhide .table{
    width: 100%;
}
.card-header{
    border-bottom:1px solid #ccc;
}
</style>
    <h2 class="heads">ITC Reversal Summary</h2>
            <div class="row">
                  <div class="col-md-4">
          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                            <div class="input-group form_date "      data-date=""   data-link-format="yyyy-mm-dd">
                     <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;">
                                             
                            </div>
            </div>
        </div>  
                </div> 
                 <div class="col-md-4">
                      <div class="form-group row"> 
                <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
                    <div class="col-md-6">
                            <div class="input-group form_date "      data-date=""   data-link-format="yyyy-mm-dd">
                     <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;">
                                             
                            </div>
                    </div> 
                    <div class="col-md-2">

                    </div>
                    </div>
                    </div>
               
                <div class="col-md-4">
                      <div class="form-group row " > 
                    <label for="year" class=" control-label col-md-4 text-left"> 
                     
                    </label>
                    <div class="col-md-6">
                    
                         <button name="submit" type="button" style="
                margin-top: 0;" class="btn search report_search" >Search</button>
             
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>

                </div>
                
              </div>
           
<div class="card-body card-block">
<div class="divhide">
    </div>
<div class="row">
    <div class="col-md-12" >
  <div id="ITC_Summary" style="margin:5px auto;"></div>   
  </div>
  
  </div>

    <script src="{{ asset('js/pqgrid.min.js')}}"></script>
    <script src="{{ asset('js/pqselect.min.js')}}"></script>
    <script src="{{ asset('js/pq-localize-en.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/pqselect.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/pqgrid.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/pqgrid.ui.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/pqgrid.css')}}" />
    <script src="{{ asset('js/filesaver.js')}}"></script>
    
      <script type="text/javascript">



$(document).ready(function () {
    // Column configurations for grids
    var colM1 = [
        { title: "Month", minWidth: "10%", align: "left", dataIndx: "MonthYY" },
        { title: "Taxable Value", minWidth: "10%", align: "right", dataIndx: "Taxable_Val" },
        { title: "IGST", minWidth: "10%", align: "right", dataIndx: "IGST_Tax" },
        { title: "CGST", minWidth: "10%", align: "right", dataIndx: "CGST_Tax" },
        { title: "SGST", minWidth: "10%", align: "right", dataIndx: "SGST_Tax" },
        { title: "Month to file", minWidth: "10%", align: "right", dataIndx: "RefDate" },
        { title: "No of Days", minWidth: "10%", align: "right", dataIndx: "NoOfDays" },
        { title: "Int IGST", minWidth: "10%", align: "right", dataIndx: "Int_IGST" },
        { title: "Int CGST", minWidth: "10%", align: "right", dataIndx: "Int_CGST" },
        { title: "Int SGST", minWidth: "10%", align: "right", dataIndx: "Int_SGST" }
    ];

    

    // Generic function to create a grid with unique toolbar
    function createGrid(gridId, colModel, exportUrl) {
        var dataModel = {
            location: "remote",
            dataType: "JSON",
            method: "GET",
            url: exportUrl + `?start_date=${$("#start_date").val()}&end_date=${$("#end_date").val()}`,
            getData: function (dataJSON) {
                return {
                    curPage: dataJSON.curPage,
                    totalRecords: dataJSON.totalRecords,
                    data: dataJSON.data
                };
            }
        };


        var obj = {
            width: '100%',
            dataModel: dataModel,
            flex: { one: true },
            colModel: colModel,
            pageModel: { type: "remote", rPP: 10, strRpp: "{0}", strPage: "{0} of {1}" },
            wrap: false,
            showBottom: true,
            editable: false,
            numberCell: { show: false },
            selectionModel: { type: 'row' },
            filterModel: { on: true, header: true, type: 'remote', menuIcon: true },
            resizable: true,
            toolbar: {
                items: [
                            {
            type: 'button',
            label: `Export ${gridId} to Excel`,
            icon: 'ui-icon-arrowthickstop-1-s',
            listener: function () {
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
        
                if (!start_date || !end_date) {
                    notyMsg("info", "Please Select Date");
                    return;
                }
        
                // Construct the export URL with parameters
                var exportUrlWithParams = exportUrl + `?start_date=${start_date}&end_date=${end_date}&download=1`;
        
                // Fetch data from the server
                $.get(exportUrlWithParams, function (data) {
                    var blob = new Blob([JSON.stringify(data)], { type: "application/json" });
                    var fileName = `${gridId}_Report_${start_date}_to_${end_date}.json`;
        
                    // Convert data to Excel format using pqGrid's exportData method
                    var excelBlob = $("#" + gridId).pqGrid("exportData", {
                        format: "xlsx",
                        render: true,
                        type: "blob",
                        data: data // Pass the fetched data for export
                    });
                    saveAs(excelBlob, fileName.replace(".json", ".xlsx"));
                });
            }
        }

                ]
            }
        };

        $("#" + gridId).pqGrid(obj);
        $("#" + gridId).pqGrid({ scrollModel: { autoFit: true } });
    }

    // URLs for individual grid data and export
    var gridUrls = {
        ITC_Summary: "{{URL::to('getitcsummary')}}",
        
    };

    // Create individual grids with specific column models and export URLs
    createGrid("ITC_Summary", colM1, gridUrls.ITC_Summary);
    
    // Search button functionality
    $(document).on("click", ".report_search", function () {
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();

        if (!start_date || !end_date) {
            notyMsg("info", "Please Select Date");
            return;
        }

        ["ITC_Summary"].forEach(function (gridId) {
            var url = gridUrls[gridId] + `?start_date=${start_date}&end_date=${end_date}`;
            $("#" + gridId).pqGrid("option", "dataModel.url", url);
            $("#" + gridId).pqGrid("refreshDataAndView");
        });
    });
});





$(document).ready(function()
{
         var data = "<?php echo \Session('j_date_format'); ?>";
         
          var grid_min_date="{{\Session::get('js_griddate')}}";
          var grid_max_date="{{\Session::get('js_gridenddate')}}";
          console.log(grid_max_date);
                        $('.start_date').datepicker({
                        changeMonth: true,
                        dateFormat: data,
                        changeYear: true,
                        minDate: grid_min_date,
                        maxDate: grid_max_date,

      
  });
                        $('.end_date').datepicker({
                        changeMonth: true,
                        dateFormat: data,
                        changeYear: true,
                        minDate: grid_min_date,
                        maxDate: grid_max_date,

                              
  });
  });
function highchart(data)
{
    
}


  </script>

@include('layouts.php_js_validation')
@endsection