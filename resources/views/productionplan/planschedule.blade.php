<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; font-size:14px; color:#333;">

<p>Dear Team,</p>

<p>Please find the plan schedule details of the following products:</p>

<table width="100%" border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
    <thead style="background-color:#f2f2f2;">
        <tr>
            <th align="left">Plan No</th>
            <th align="left">Product Name</th>
            <th align="left">Requirement Date</th>
            <th align="left">Due Date</th>
            <th align="left">Production Schedule Date</th>
            <th align="right">Plan Quantity</th>
        </tr>
    </thead>

    <tbody>
        @foreach($plans as $p)
        <tr>
            <td>{{ $p->plan_no }}</td>
            <td>{{ $p->concatenated_product }}</td>
            <td>
                {{ $p->plan_date ? date('d-m-Y', strtotime($p->plan_date)) : '-' }}
            </td>
            <td>
                {{ $p->workorder_due_date ? date('d-m-Y', strtotime($p->workorder_due_date)) : '-' }}
            </td>
            <td>
                {{ $p->start_date ? date('d-m-Y', strtotime($p->start_date)) : '-' }}
            </td>
            <td align="right">{{ number_format($p->production_qty) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<p>Regards,</p>
<p><strong>Production Team</strong></p>

</body>
</html>
