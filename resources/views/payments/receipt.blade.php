<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .receipt-number {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        .date {
            font-size: 14px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px auto;
            gap: 10px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .amount-section {
            margin: 30px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            text-align: right;
        }
        .amount-section .info-grid {
            display: grid;
            grid-template-columns: auto auto;
            gap: 15px;
            align-items: center;
            text-align: right;
            margin-bottom: 10px;
        }
        .amount-section .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .amount-section .label {
            color: #666;
            font-weight: 600;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { padding: 0; }
            .receipt { border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="receipt">
    <div class="header">
        <div class="receipt-title">PAYMENT RECEIPT</div>
        <div class="receipt-number">Receipt #: {{ $payment->receipt_number }}</div>
        <div class="date">Date: {{ $payment->payment_date }}</div>
    </div>

    <div class="info-section">
        <div class="info-title">Student Information</div>
        <div class="info-grid">
            <div class="label">Name:</div>
            <div>{{ $payment->student->fullName }}</div>
            <div class="label">Field:</div>
            <div>{{ $payment->student->field->name }}</div>
            <div class="label">Campus:</div>
            <div>{{ $payment->student->field->campus->name }}</div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-title">Payment Details</div>
        <div class="info-grid">
            <div class="label">Description:</div>
            <div>{{ $payment->description }}</div>
            <div class="label">Payment Date:</div>
            <div>{{ $payment->payment_date }}</div>
        </div>
    </div>

    <div class="amount-section">
        <div class="info-grid">
            <div class="label">Total Amount Paid:</div>
            <div class="total-amount">{{ number_format($payment->amount, 0, '.', ' ') }} FCFA</div>
            <div class="label">Total Remaining:</div>
            <div class="total-amount">{{ number_format($remainingAmount, 0, '.', ' ') }} FCFA</div>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for your payment!</p>
        <p>This is an official receipt of your payment. Please keep it for your records.</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px;">Print Receipt</button>
    </div>
</div>
</body>
</html>
