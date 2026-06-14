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

    <h2>Laporan Pembayaran</h2>

    <table>

        <thead>
            <tr>
                <th>Nama</th>
                <th>Program</th>
                <th>Bulan</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>

            @foreach($payments as $payment)

            <tr>

                <td>{{ $payment->student->name }}</td>

                <td>{{ $payment->student->program_detail }}</td>

                <td>{{ $payment->paid_for_month }}</td>

                <td>
                    Rp {{ number_format($payment->amount_paid,0,',','.') }}
                </td>

                <td>{{ $payment->status }}</td>

                <td>
                    {{ $payment->payment_date }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>