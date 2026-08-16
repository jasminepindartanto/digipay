<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }

        .receipt-box {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .title {
            font-size: 28px;
            font-weight: 700;
        }

        .line {
            border-bottom: 2px dashed #ddd;
            margin: 20px 0;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
            }

            .receipt-box {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="receipt-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="width:70px;height:70px;object-fit:contain;">
            <div>
                <div class="title">KWITANSI</div>
                <div class="text-muted">
                    {{ $payment->student->name }} - {{ $payment->receipt_number ?? 'PAY-'.$payment->id }}
                </div>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-primary no-print">
            Print
        </button>
    </div>

    <div class="line"></div>

    <table class="table">
    <tr>
        <th width="45%">No. Kwitansi</th>
        <td>{{ $payment->receipt_number }}</td>
    </tr>
    <tr>
        <th width="40%">Siswa</th>
        <td>{{ $payment->student->name }}</td>
    </tr>
    <tr>
        <th>Program</th>
        <td>{{ $payment->student->program_detail }}</td>
    </tr>
    <tr>
        <th>Paket</th>
        <td>{{ $payment->student->package_type }}</td>
    </tr>
    <tr>
        <th>Status Membership</th>
        <td>
            @if($payment->membership_status === 'free')
                Bebas Biaya Membership
            @elseif($payment->membership_status === 'included')
                Termasuk Membership
            @else
                Membership
            @endif
        </td>
    </tr>
    <tr>
        <th>Family Status</th>
        <td>
            @if($payment->family_type == 'Family')
                Family
            @elseif($payment->family_type == 'Non Family')
                Non-Family
            @else
                -
            @endif
        </td>
    </tr>
</table>

    <div class="line"></div>

    <h5 class="mt-4">Rincian Tagihan</h5>
    <table class="table table-striped">
        <tbody>
            <tr>
                <td>Harga Paket</td>
                <td class="text-end">Rp {{ number_format($payment->package_price,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Membership Fee</td>
                <td class="text-end">Rp {{ number_format($payment->membership_fee,0,',','.') }}</td>
            </tr>
            
            <tr>
                <td>Diskon Family</td>
                <td class="text-end text-danger">- Rp {{ number_format($payment->discount_amount,0,',','.') }}</td>
            </tr>
                        <tr class="table-success">
                <th>TOTAL</th>
                <th class="text-end">Rp {{ number_format($payment->amount_due,0,',','.') }}</th>
            </tr>
        </tbody>
    </table>

    <h5 class="mt-4">Payment Information</h5>
    <table class="table">
        <tr>
            <th>Payment Date</th>
            <td>{{ optional($payment->payment_date)->format('d M Y') ?? '-' }}</td>
        </tr>
        <tr>
            <th>Paid For</th>
            <td>{{ $payment->paid_for_month }}</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td>{{ $payment->payment_method ?? '-' }}</td>
        </tr>
        <tr>
            <th>Amount Paid</th>
            <td>
                <strong>
                    Rp {{ number_format($payment->amount_paid,0,',','.') }}
                </strong>
            </td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($payment->status=='Lunas')
                    <span class="badge bg-success">Lunas</span>
                @else
                    <span class="badge bg-danger">Belum Bayar</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="mt-5">
        <strong>Remark :</strong>
        <div class="mt-2 text-muted" style="line-height:1.8">
            Pembayaran dapat dilakukan dengan transfer <br>
            ke Bank Index dengan No. Rekening <br>
            2208xxx a/n ...
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