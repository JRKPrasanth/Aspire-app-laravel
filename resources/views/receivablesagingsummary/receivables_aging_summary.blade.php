@extends('layouts.header')
@section('content')
<h3 class="text-danger">Receivables Aging Summary</h3>
@include('layouts.breadcrumb')

<style>
   body{
       background: #fff;
   }
    .card {
    background-color: #fff;
    border-radius: 10px;
    border: none;
    position: relative;
    margin-bottom: 30px !important;
    box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
}
.l-bg-cherry {
    background: linear-gradient(to right, #493240, #f09) !important;
    color: #fff;
}

.l-bg-blue-dark {
    background: linear-gradient(to right, #373b44, #4286f4) !important;
    color: #fff;
}

.l-bg-green-dark {
    background: linear-gradient(to right, #0a504a, #38ef7d) !important;
    color: #fff;
}

.l-bg-orange-dark {
    background: linear-gradient(to right, #a86008, #ffba56) !important;
    color: #fff;
}

.card .card-statistic-3 .card-icon-large .fas, .card .card-statistic-3 .card-icon-large .far, .card .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
    font-size: 110px;
}

.card .card-statistic-3 .card-icon {
    text-align: center;
    line-height: 50px;
    margin-left: 15px;
    color: #000;
    position: absolute;
    right: -5px;
    top: 20px;
    opacity: 0.1;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}

.l-bg-green {
    background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
    color: #fff;
}

.l-bg-orange {
    background: linear-gradient(to right, #f9900e, #ffba56) !important;
    color: #fff;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}
.table-container {
            position: relative; /* Make the container a positioned element */
            margin-top: 20px;
            max-height: 800px; /* Adjust the height as needed */
            overflow: auto;
        }

        .export-btn {
            margin-right: 20px;
            position: absolute;
            top: 0px; /* Adjust this value to move the button closer or further from the table */
            right: 0;
            padding: 8px 15px;
            background-color: #28a745; /* Green background */
            color: #fff; /* White text */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            text-decoration: none; /* Remove underline */
        }

        .export-btn i {
            margin-right: 5px; /* Spacing between icon and text */
        }

        .export-btn:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .table thead th {
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            top: 0; /* Adjust to your needs */
            background-color: #89c4ff; /* Background color to make header stand out */
            z-index: 1; /* Ensure header stays above other content */
        }

        .table tbody td {
            white-space: nowrap; /* Prevent text wrapping for table cells */
        }
        /* Style for the grand total row */
        .grand-total {
            font-weight: bold;
            font-size: 18px;
            background-color: #c3e6cb; /* Light gray background */
            color: #000; /* Black text color */
        }
        .clickable {
            cursor: pointer;
            text-decoration: underline;
        }
        /* Modal content styling */
.modal-content {
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Modal header */
.modal-header {
    border-bottom: 1px solid #e9ecef; /* Light border at the bottom */
    background-color: #f8f9fa; /* Light background color */
}

/* Modal title */
.modal-title {
    font-size: 1.25rem; /* Font size */
    font-weight: 600; /* Font weight */
}

/* Modal footer */
.modal-footer {
    border-top: 1px solid #e9ecef; /* Light border at the top */
    background-color: #f8f9fa; /* Light background color */
}

/* Modal body */
.modal-body {
    padding: 2rem; /* Padding inside the modal body */
}

/* Primary button */
.btn-primary {
    background-color: #007bff; /* Primary color */
    border-color: #007bff; /* Button border color */
    border-radius: 4px; /* Rounded corners */
}

/* Secondary button */
.btn-danger {
    border-radius: 4px; /* Rounded corners */
}
@media (max-width: 576px) {
    .modal-dialog {
        max-width: 90%;
    }
}
.modal-body {
    max-height: 60vh;
    overflow-y: auto;
}
    #invoiceTable {
        width: 100%;
        max-height: 400px; /* Adjust height as needed */
        overflow-y: auto;
        /* Necessary to ensure the table scrolls */
    }
    
    /* Make the header sticky */
    #invoiceTable thead th {
        position: sticky;
        top: 0;
        z-index: 1; /* Ensure the header stays above the rows */
        background-color: #89c4ff; /* Set background to avoid blending with content */
        text-align: right;
    }

    /* Align first column text to left, since all other columns are right-aligned */
    #invoiceTable thead th:first-child {
        text-align: left;
    }
</style>

<div class="container py-4">
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-info bg-gradient position-relative overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">Customers (Outstanding)</h5>
                    <h2 class="fw-bold text-end">{{ $activeCustomersCount }}</h2>
                    <i class="fas fa-users position-absolute bottom-0 end-0 p-3 opacity-25 fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-warning bg-gradient position-relative overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">Total Outstanding</h5>
                    <h2 class="fw-bold text-end">₹ {{ number_format($totalOutstanding, 2) }}</h2>
                    <i class="fa fa-inr position-absolute bottom-0 end-0 p-3 opacity-25 fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-dark bg-danger bg-gradient position-relative overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">120+ Days Outstanding</h5>
                    <h2 class="fw-bold text-end">₹ {{ number_format($total_120, 2) }}</h2>
                    <i class="fa fa-inr position-absolute bottom-0 end-0 p-3 opacity-25 fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-success bg-gradient position-relative overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">180+ Days Outstanding</h5>
                    <h2 class="fw-bold text-end">₹ {{ number_format($total_180, 2) }}</h2>
                    <i class="fa fa-inr position-absolute bottom-0 end-0 p-3 opacity-25 fs-1"></i>
                </div>
            </div>
        </div>

    </div>
</div>

	
	<div class="card shadow-lg rounded-4 border-0">
    <div class="col-md-12 table-container" style="padding: 20px;padding-top: 40px;">
        <a href="#" class="export-btn" onclick="exportTableToExcel('invoiceTable', 'receivablesagingsummary')">
        <i class="fas fa-file-excel"></i>Excel
    </a>
		
		
		
        <table id="invoiceTable" class="table table-striped table-hover" style="width: 100%;">
    <thead>
        <tr>
            <th scope="col" class="bg-primary text-white">Customer Name</th>
            <th scope="col" class="bg-primary text-white" style="text-align: right;">0-30 Days</th>
            <th scope="col" class="bg-primary text-white" style="text-align: right;">31-60 Days</th>
            <th scope="col" class="bg-primary text-white" style="text-align: right;">61-90 Days</th>
            <th scope="col" class="bg-primary text-white" style="text-align: right;">91+ Days</th>
            <th scope="col" class="bg-primary text-white" style="text-align: right;">Total Outstanding</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($agingSummary))
            @foreach($agingSummary as $customer)   
           
            <tr class="{{ $customer->customer == 'Total' ? 'grand-total' : '' }}">
                <th scope="row">{{ $customer->customer }}</th>
                <td class="{{ $customer->{'0-30'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-customer="{{ $customer->customer }}" data-outstanding="{{ $customer->{'0-30'} }}"  data-days="0-30" data-total="{{ $customer->total }}" style="background-color: {{ $customer->{'0-30'} > 0 ? '#d4edda' : '' }};text-align: right;">
                    {{ $customer->{'0-30'} }}
                </td>
                <td class="{{ $customer->{'31-60'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-customer="{{ $customer->customer }}" data-outstanding="{{ $customer->{'31-60'} }}"  data-days="31-60" data-total="{{ $customer->total }}" style="background-color: {{ $customer->{'31-60'} > 0 ? '#fff3cd' : '' }};text-align: right;">
                    {{ $customer->{'31-60'} }}
                </td>
                <td class="{{ $customer->{'61-90'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-customer="{{ $customer->customer }}" data-outstanding="{{ $customer->{'61-90'} }}"  data-days="61-90" data-total="{{ $customer->total }}" style="background-color: {{ $customer->{'61-90'} > 0 ? '#ffeeba' : '' }};text-align: right;">
                    {{ $customer->{'61-90'}  }}
                </td>
                <td class="{{ $customer->{'91+'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-customer="{{ $customer->customer }}" data-outstanding="{{ $customer->{'91+'} }}"  data-days="91+" data-total="{{ $customer->total }}" style="background-color: {{ $customer->{'91+'} > 0 ? '#f8d7da' : '' }};text-align: right;">
                    {{ $customer->{'91+'}  }}
                </td>
                <td  class="{{ $customer->{'total'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-customer="{{ $customer->customer }}" data-outstanding="{{ $customer->{'0-30'} }}"  data-days="total" data-total="{{ $customer->total }}" data-total="total" style="text-align: right;">{{ $customer->total }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">No data available.</td>
            </tr>
        @endif
    </tbody>
</table>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalLabel">Transaction Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p><strong>Customer:</strong> <span id="modalCustomerName"></span></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Days:</strong> <span id="modalDays"></span></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Days Outstanding:</strong> <span id="days_outstanding"></span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <p><strong>Total Outstanding:</strong> <span id="total_outstand"></span></p>
                    </div>
                </div>

                <hr>

                <div id="transactionDetails">
                    <!-- Transaction details will be loaded here -->
                    <div class="text-muted">Loading transaction data...</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')	
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
	<script>
	
        function exportTableToExcel(tableID, filename = ''){
            // Get the table element
            let table = document.getElementById(tableID);

            // Convert the table to a worksheet
            let wb = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
            
            // Export the workbook to Excel
            XLSX.writeFile(wb, filename ? `${filename}.xlsx` : 'export.xlsx');
        }

        $(document).ready(function() {
            $('#transactionModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var customerName = button.data('customer'); // Extract info from data-* attributes
                var days = button.data('days');
                var total_out = button.data('total');
                var days_out = button.data('outstanding');
                var as_on_date = "{{ request('date_select') }}";
                
                // Update the modal's content.
                var modal = $(this);
                modal.find('#modalCustomerName').text(customerName);
                modal.find('#modalDays').text(days);
                modal.find('#total_outstand').text(total_out);
                if(days == 'total'){
                modal.find('#days_outstanding').text(total_out);
                }else{
                    modal.find('#days_outstanding').text(days_out);
                }
                // Load transaction details (you would typically do this via an AJAX call)
                var transactionDetails = `<p>Loading...</p>`;
                modal.find('#transactionDetails').html(transactionDetails);
                
                console.log(days);
                
                $.ajax({
                    url: 'get-recagingtransactions',
                    method: 'POST',
                    data: { customer: customerName, days: days,as_on_date:as_on_date },
                    success: function(response) {
                    console.log(response); // Debug response
                    modal.find('#transactionDetails').html(response.html);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Log any errors
                    }
                    });
                    
            });
        });
        

    </script>    
	@endpush