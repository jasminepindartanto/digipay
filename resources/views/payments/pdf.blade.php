<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran</title>

    <style>

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

    </style>
</head>
<body>

    <h2>
    Laporan Pembayaran Siswa
</h2>

<h3>
    Ringkasan Siswa
</h3>

<table>
    <tr>
        <td>Total Active Students</td>
        <td>{{ $activeStudents }}</td>
    </tr>

    <tr>
        <td>Paid</td>
        <td>{{ $paidStudents }}</td>
    </tr>

    <tr>
        <td>Not Yet Paid</td>
        <td>{{ $notPaidStudents }}</td>
    </tr>
</table>

<h3>
    Revenue by Program
</h3>

<table>
    <tr>
        <td>Digikidz</td>
        <td>Rp {{ number_format($digikidzRevenue,0,',','.') }}</td>
    </tr>
</table>

<h3>
    Package Summary
</h3>

<table>
    <tr>
        <td>Monthly</td>
        <td>Rp {{ number_format($monthlyRevenue,0,',','.') }}</td>
    </tr>

    <tr>
        <td>1 Level</td>
        <td>Rp {{ number_format($levelRevenue,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Full Course</td>
        <td>Rp {{ number_format($fullCourseRevenue,0,',','.') }}</td>
    </tr>
</table>

<h3>
    Program Detail
</h3>

<table>

    <tr>
        <th>Program Detail</th>
        <th>Total Revenue</th>
    </tr>

    @foreach($programDetailRevenue as $item)

    <tr>

        <td>
            {{ $item->program_detail }}
        </td>

        <td>
            Rp {{ number_format($item->total,0,',','.') }}
        </td>

    </tr>

    @endforeach

</table>

<h2>
    GRAND TOTAL :
    Rp {{ number_format($grandTotal,0,',','.') }}
</h2>
</html>