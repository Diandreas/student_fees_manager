<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $school->term('receipt', 'Reçu de Paiement') }} - {{ $payment->student->fullName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid {{ $school->theme_color }};
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .school-info {
            flex: 1;
        }
        .logo {
            flex: 0 0 120px;
            text-align: right;
        }
        .logo img {
            max-width: 100px;
            max-height: 100px;
        }
        .school-name {
            font-weight: bold;
            font-size: 24px;
            color: {{ $school->theme_color }};
            margin: 0 0 5px 0;
        }
        .school-contact {
            font-size: 12px;
            margin: 0;
        }
        .receipt-title {
            text-align: center;
            font-size: 20px;
            margin: 20px 0;
            color: {{ $school->theme_color }};
            text-transform: uppercase;
        }
        .receipt-info {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .receipt-info .row {
            display: flex;
            margin-bottom: 10px;
        }
        .receipt-info .label {
            width: 150px;
            font-weight: bold;
        }
        .receipt-info .value {
            flex: 1;
        }
        .payment-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .payment-details th {
            background-color: {{ $school->theme_color }};
            color: #fff;
            padding: 10px;
            text-align: left;
        }
        .payment-details td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .payment-details tr:last-child td {
            border-bottom: none;
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .payment-details .amount {
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="school-info">
                <h1 class="school-name">{{ $school->name }}</h1>
                <p class="school-contact">
                    {{ $school->address }}<br>
                    Email: {{ $school->contact_email }} | Tel: {{ $school->contact_phone }}
                </p>
            </div>
            <div class="logo">
                @if($school->logo)
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}">
                @endif
            </div>
        </div>

        <div class="receipt-title">
            {{ $school->term('receipt', 'Reçu de Paiement') }} #{{ $payment->receipt_number }}
        </div>

        <div class="receipt-info">
            <div class="row">
                <div class="label">Date:</div>
                <div class="value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
            </div>
            <div class="row">
                <div class="label">{{ $school->term('student', 'Étudiant') }}:</div>
                <div class="value">{{ $payment->student->fullName }}</div>
            </div>
            <div class="row">
                <div class="label">{{ $school->term('field', 'Filière') }}:</div>
                <div class="value">{{ $payment->student->field->name }}</div>
            </div>
            <div class="row">
                <div class="label">{{ $school->term('campus', 'Campus') }}:</div>
                <div class="value">{{ $payment->student->field->campus->name }}</div>
            </div>
        </div>

        <table class="payment-details">
            <thead>
                <tr>
                    <th>{{ $school->term('description', 'Description') }}</th>
                    <th>{{ $school->term('period', 'Période') }}</th>
                    <th class="amount">{{ $school->term('amount', 'Montant') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->description }}</td>
                    <td>{{ $payment->period ?? '-' }}</td>
                    <td class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>{{ $school->term('total', 'Total') }}</strong></td>
                    <td class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tbody>
        </table>

        <div>
            <p><strong>{{ $school->term('payment_method', 'Mode de paiement') }}:</strong> {{ $payment->payment_method ?? 'Espèces' }}</p>
            <p><strong>{{ $school->term('notes', 'Notes') }}:</strong> {{ $payment->notes ?? 'Aucune note' }}</p>
        </div>

        <div class="signature">
            <div class="signature-box">
                <p>{{ $school->term('cashier', 'Caissier') }}</p>
                <p>_______________________</p>
            </div>
            <div class="signature-box">
                <p>{{ $school->term('student', 'Étudiant') }}</p>
                <p>_______________________</p>
            </div>
        </div>

        <div class="footer">
            <p>{{ $school->name }} &copy; {{ date('Y') }}</p>
            <p>{{ $school->address }}</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print();" style="padding: 10px 20px; background-color: {{ $school->theme_color }}; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Imprimer
        </button>
        <button onclick="window.close();" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Fermer
        </button>
    </div>
</body>
</html>
