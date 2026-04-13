@extends('layouts.header')
@section('content')
<h3 class="text-danger">Payables Aging Summary</h3>
@include('layouts.breadcrumb')


<style>
   body{
       background: #fff;
   }
   .order-card {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff);
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5);
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80);
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a);
}


.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
    
}

.card .card-block {
    padding: 25px;
}

.order-card i {
    font-size: 26px;
}

.f-left {
    float: left;
}

.f-right {
    float: right;
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
            background-color: #009358; /* Background color to make header stand out */
            z-index: 1; /* Ensure header stays above other content */
			color:white;
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
        background-color: #182564; /* Set background to avoid blending with content */
        text-align: right;
    }

    /* Align first column text to left, since all other columns are right-aligned */
    #invoiceTable thead th:first-child {
        text-align: left;
    }
    /* CSS */
.watermark-card {
  position: relative;
  background: #3498db; /* or a background image if desired */
  overflow: hidden; /* Ensures the icon does not spill outside the card */
}

.card-block {
  position: relative;
  z-index: 2;
  color: #fff; /* Adjust text color as needed for contrast */
}

.card-icon-watermark {
position: absolute;
    top: 61px;
    right: 90px;
    z-index: 1;
}

.card-icon-watermark i {
    font-size: 10rem;
    opacity: 0.25;
    color: #fff;
}
.text-right {
    color : #3a6060 !important;
}

</style>



<div class="container py-4">
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-primary position-relative overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">Suppliers (Outstanding)</h5>
                    <h2 class="fw-bold text-end">{{ $activesuppliersCount }}</h2>
                    <i class="fas fa-users position-absolute bottom-0 end-0 p-3 opacity-25 fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-success position-relative overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">Total Outstanding</h5>
                    <h2 class="fw-bold text-end">₹ {{ number_format($totalOutstanding, 2) }}</h2>
                    <i class="fa fa-inr position-absolute bottom-0 end-0 p-3 opacity-25 fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-dark bg-warning position-relative overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">120+ Days Outstanding</h5>
                    <h2 class="fw-bold text-end">₹ {{ number_format($total_120, 2) }}</h2>
                    <i class="fa fa-inr position-absolute bottom-0 end-0 p-3 opacity-25 fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100 text-white bg-danger position-relative overflow-hidden">
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
        <a href="#" class="export-btn" onclick="exportTableToExcel('invoiceTable', 'payablesagingsummary')">
        <i class="fas fa-file-excel"></i>Excel
    </a>
        <table id="invoiceTable" class="table table-striped table-hover" style="width: 100%;">
    <thead>
        <tr>
            <th scope="col" class="bg-danger text-white">Supplier Name</th>
            <th scope="col" class="bg-danger text-white" style="text-align: right;">0-30 Days</th>
            <th scope="col" class="bg-danger text-white" style="text-align: right;">31-60 Days</th>
            <th scope="col" class="bg-danger text-white" style="text-align: right;">61-90 Days</th>
            <th scope="col" class="bg-danger text-white" style="text-align: right;">91+ Days</th>
            <th scope="col" class="bg-danger text-white" style="text-align: right;">Total Outstanding</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($agingSummary))
            @foreach($agingSummary as $supplier)   
             <?php //dd($supplier); ?>
            <tr class="{{ $supplier->supplier == 'Total' ? 'grand-total' : '' }}">
                <th scope="row">{{ $supplier->supplier }}</th>
                <td class="{{ $supplier->{'0-30'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-supplier="{{ $supplier->supplier }}" data-outstanding="{{ $supplier->{'0-30'} }}"  data-days="0-30" data-total="{{ $supplier->total }}" style="background-color: {{ $supplier->{'0-30'} > 0 ? '#d4edda' : '' }};text-align: right;">
                    {{ $supplier->{'0-30'} }}
                </td>
                <td class="{{ $supplier->{'31-60'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-supplier="{{ $supplier->supplier }}" data-outstanding="{{ $supplier->{'31-60'} }}"  data-days="31-60" data-total="{{ $supplier->total }}" style="background-color: {{ $supplier->{'31-60'} > 0 ? '#fff3cd' : '' }};text-align: right;">
                    {{ $supplier->{'31-60'} }}
                </td>
                <td class="{{ $supplier->{'61-90'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-supplier="{{ $supplier->supplier }}" data-outstanding="{{ $supplier->{'61-90'} }}"  data-days="61-90" data-total="{{ $supplier->total }}" style="background-color: {{ $supplier->{'61-90'} > 0 ? '#ffeeba' : '' }};text-align: right;">
                    {{ $supplier->{'61-90'}  }}
                </td>
                <td class="{{ $supplier->{'91+'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-supplier="{{ $supplier->supplier }}" data-outstanding="{{ $supplier->{'91+'} }}"  data-days="91+" data-total="{{ $supplier->total }}" style="background-color: {{ $supplier->{'91+'} > 0 ? '#f8d7da' : '' }};text-align: right;">
                    {{ $supplier->{'91+'}  }}
                </td>
                <td  class="{{ $supplier->{'total'} > 0 ? 'clickable' : '' }}" data-bs-toggle="modal" data-bs-target="#transactionModal" data-supplier="{{ $supplier->supplier }}" data-outstanding="{{ $supplier->{'0-30'} }}"  data-days="total" data-total="{{ $supplier->total }}" data-total="total" style="text-align: right;">{{ $supplier->total }}</td>
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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel">Transaction Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p><strong>Supplier:</strong> <span id="modalSupplierName"></span></p>
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
                var supplierName = button.data('supplier'); // Extract info from data-* attributes
                var days = button.data('days');
                var total_out = button.data('total');
                var days_out = button.data('outstanding');
                var as_on_date = "{{ request('date_select') }}";
                
                // Update the modal's content.
                var modal = $(this);
                modal.find('#modalSupplierName').text(supplierName);
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
                    url: 'payablesagingsummarypopup',
                    method: 'POST',
                    data: { supplier: supplierName, days: days,as_on_date:as_on_date },
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