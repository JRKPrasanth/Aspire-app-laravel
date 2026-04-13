@extends('layouts.header')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <div class="container">
    <h3>Monthly Stock Report</h3>

    <form id="filterForm">
        <label>From:</label>
        <input type="date" id="from_date" name="from_date" value="{{ date('Y-m-01') }}">
        <label>To:</label>
        <input type="date" id="to_date" name="to_date" value="{{ date('Y-m-t') }}">
        <button type="button" id="searchBtn">Search</button>
    </form>

    <table id="stockReportTable" class="table table-bordered">
        <thead>
            <tr id="headerRow1">
                <th rowspan="2">Product Name</th>
                <th rowspan="2">Batch Number</th>
            </tr>
            <tr id="headerRow2"></tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    function loadData() {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();

        $.ajax({
            url: "{{ route('getmonthwiseqohrpt.data') }}",
            type: "GET",
            data: { from_date, to_date },
            success: function(response) {
                let columns = [
                    { data: 'product_name', title: 'Product Name' },
                    { data: 'batch_number', title: 'Batch Number' }
                ];
                let headerRow1 = `<th rowspan="2">Product Name</th><th rowspan="2">Batch Number</th>`;
                let headerRow2 = ``;

                Object.keys(response.months).forEach(month => {
                    columns.push({ data: month + '_stock', title: month + ' - Stock' });
                    columns.push({ data: month + '_value', title: month + ' - Value' });

                    headerRow1 += `<th colspan="2">${month}</th>`;
                    headerRow2 += `<th>Stock</th><th>Value</th>`;
                });

                $('#headerRow1').html(headerRow1);
                $('#headerRow2').html(headerRow2);

                $('#stockReportTable').DataTable({
                    destroy: true,
                    data: response.data,
                    columns: columns,
                    processing: true,
                    paging: false,
                    searching: false,
                    ordering: false
                });
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Error loading data.");
            }
        });
    }

    $('#searchBtn').click(function() {
        loadData();
    });

    loadData(); // Load initially
});
</script>
@endsection