<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi Pembayaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f5f5;
        }

        .receipt-box{
            max-width:700px;
            margin:40px auto;
            background:white;
            padding:40px;
            border-radius:16px;
            box-shadow:0 4px 20px rgba(0,0,0,0.08);
        }

        .title{
            font-size:28px;
            font-weight:700;
        }

        .line{
            border-bottom:2px dashed #ddd;
            margin:20px 0;
        }

        @media print {
            .no-print{
                display:none;
            }

            body{
                background:white;
            }

            .receipt-box{
                box-shadow:none;
                margin:0;
                max-width:100%;
            }
        }
    </style>
</head>
<body>

<div class="receipt-box">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="d-flex align-items-center gap-3">

        <img src="{{ asset('assets/images/logo.png') }}"
        alt="Logo"
        style="width:70px;height:70px;object-fit:contain;"
    >

        <div>
            <div class="title">KWITANSI</div>

            <div class="text-muted">
                {{ $payment->student->name }} -
                {{ $payment->receipt_number ?? 'PAY-'.$payment->id }}
            </div>
        </div>

    </div>

    <button onclick="window.print()" class="btn btn-primary no-print">
        Print
    </button>

</div>

    <div class="line"></div>

        <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Receipt No</th>
                <th>Transaction</th>
                <th>Program</th>
                <th>Paid for (Month)</th>
                <th>Total (Rp.)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>
                    {{ $payment->student->name }} -
                    {{ $payment->receipt_number ?? 'RCPT-'.$payment->id }}
                </td>
                <td>Pembayaran Kursus</td>
                <td>{{ $payment->student->program }}</td>
                <td>{{ $payment->paid_for_month }}</td>
                <td>
                    Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>
    <div class="line"></div>
        <div class="mt-5">

        <strong>Remark :</strong>

        <div class="mt-2 text-muted" style="line-height:1.8">
            Pembayaran dapat dilakukan dengan transfer <br>
            ke Bank Index dengan No. Rekening <br>
            2208095958 a/n Edsel Jeremy
        </div>
</div>
    <div class="mt-5 text-end">
        <p class="mb-5">
            Bandung, {{ now()->format('d M Y') }}
        </p>

        <strong>Admin EduPay</strong>
    </div>

</div>

</body>
</html>