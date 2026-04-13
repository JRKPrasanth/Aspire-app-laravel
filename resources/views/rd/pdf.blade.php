<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>R&D Entry PDF</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        table th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
        }

        .watermark {
            position: fixed;
            top: 45%;
            left: 20%;
            transform: rotate(-30deg);
            font-size: 60px;
            color: rgba(200, 200, 200, 0.25);
            z-index: -1000;
        }
    </style>
</head>
<body>

{{-- WATERMARK --}}
@if($entry->approval_status !== 'Approved')
    <div class="watermark">DRAFT</div>
@else
    <div class="watermark">APPROVED</div>
@endif

{{-- HEADER --}}
<div class="header">
    <h2>R & D Entry Report</h2>
    <p>Generated on {{ date('d-m-Y H:i') }}</p>
</div>

{{-- BASIC DETAILS --}}
<div class="section">
    <div class="section-title">Project Details</div>
    <table>
        <tr>
            <th width="25%">Project Name</th>
            <td width="25%">{{ $entry->project_name }}</td>
            <th width="25%">Batch No</th>
            <td width="25%">{{ $entry->batch_no }}</td>
        </tr>
        <tr>
            <th>Product Name</th>
            <td>{{ $entry->product_name }}</td>
            <th>Stage</th>
            <td>{{ $entry->current_stage }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $entry->approval_status }}</td>
            <th>Created Date</th>
            <td>{{ optional($entry->created_at)->format('d-m-Y') }}</td>
        </tr>
    </table>
</div>

{{-- TEST / R&D DETAILS --}}
<div class="section">
    <div class="section-title">R & D Details</div>
    <table>
        <tr>
            <th width="30%">Test Parameters</th>
            <td width="70%">{{ $entry->test_parameters ?? '-' }}</td>
        </tr>
        <tr>
            <th>Observations</th>
            <td>{{ $entry->observations ?? '-' }}</td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td>{{ $entry->remarks ?? '-' }}</td>
        </tr>
    </table>
</div>

{{-- APPROVAL DETAILS --}}
<div class="section">
    <div class="section-title">Approval Information</div>
    <table>
        <tr>
            <th width="30%">Approved By</th>
            <td width="70%">{{ $entry->approved_by ?? '-' }}</td>
        </tr>
        <tr>
            <th>Approved Date</th>
            <td>{{ $entry->approved_at ? date('d-m-Y', strtotime($entry->updated_at)) : '-' }}</td>
        </tr>
        <tr>
            <th>Approval Remarks</th>
            <td>{{ $entry->approval_remarks ?? '-' }}</td>
        </tr>
    </table>
</div>

{{-- FOOTER --}}
<div class="footer">
    <p>This is a system generated document.</p>
</div>

</body>
</html>