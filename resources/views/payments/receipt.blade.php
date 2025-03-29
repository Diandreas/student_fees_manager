<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $school->term('receipt', 'Reçu de Paiement') }} - {{ $payment->student->fullName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            color: #374151;
            background-color: #f9fafb;
            line-height: 1.5;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border-radius: 8px;
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
            border-radius: 8px;
        }
        .school-name {
            font-weight: bold;
            font-size: 24px;
            color: {{ $school->theme_color }};
            margin: 0 0 5px 0;
        }
        .school-subtitle {
            font-size: 14px;
            color: #6B7280;
            margin: 0;
        }
        .school-contact {
            font-size: 12px;
            margin: 0;
            color: #6B7280;
        }
        .receipt-title {
            text-align: center;
            font-size: 22px;
            margin: 20px 0;
            color: {{ $school->theme_color }};
            text-transform: uppercase;
            font-weight: 600;
        }
        .receipt-info {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 8px;
            border-left: 4px solid {{ $school->theme_color }};
        }
        .receipt-info .row {
            display: flex;
            margin-bottom: 10px;
        }
        .receipt-info .label {
            width: 150px;
            font-weight: 600;
            color: #4B5563;
        }
        .receipt-info .value {
            flex: 1;
            color: #1F2937;
        }
        .payment-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
        }
        .payment-details th {
            background-color: {{ $school->theme_color }};
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        .payment-details td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .payment-details tr:last-child td {
            border-bottom: none;
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .payment-details .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6B7280;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            text-align: center;
            color: #4B5563;
        }
        .receipt-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
            color: #6B7280;
        }
        .payment-method {
            background-color: #f3f4f6;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .payment-method p {
            margin: 5px 0;
        }
        .text-red-600 {
            color: #dc2626;
        }
        .text-green-600 {
            color: #16a34a;
        }
        .status-paid {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(16, 185, 129, 0.15);
            font-weight: bold;
            text-transform: uppercase;
            border: 10px solid rgba(16, 185, 129, 0.15);
            padding: 10px 30px;
            pointer-events: none;
            z-index: 10;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background-color: #ffffff;
            }
            .container {
                box-shadow: none;
                max-width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-paid">{{ $school->term('paid', 'Payé') }}</div>
        
        <div class="header">
            <div class="school-info">
                <h1 class="school-name">{{ $school->report_settings['header_title'] ?? $school->name }}</h1>
                @if(isset($school->report_settings['header_subtitle']) && !empty($school->report_settings['header_subtitle']))
                    <p class="school-subtitle">{{ $school->report_settings['header_subtitle'] }}</p>
                @endif
                <p class="school-contact">
                    {{ $school->report_settings['header_address'] ?? $school->address }}<br>
                    <i class="fas fa-envelope"></i> {{ $school->report_settings['header_email'] ?? $school->contact_email ?? $school->email }} | 
                    <i class="fas fa-phone"></i> {{ $school->report_settings['header_phone'] ?? $school->contact_phone ?? $school->phone }}
                </p>
            </div>
            <div class="logo">
                @if($school->logo)
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}">
                @endif
            </div>
        </div>

        <div class="receipt-title">
            {{ $school->term('receipt', 'Reçu de Paiement') }}
        </div>
        
        <div class="receipt-meta">
            <div><strong>{{ $school->term('receipt_number', 'Reçu N°') }}:</strong> {{ $payment->receipt_number }}</div>
            <div><strong>{{ $school->term('date', 'Date') }}:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
        </div>

        <div class="receipt-info">
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
            
            @php
                // Récupérer les informations sur les paiements
                $paymentController = app('App\Http\Controllers\PaymentController');
                $paymentInfo = $paymentController->getStudentPaymentInfo($payment->student_id);
                $totalFees = $paymentInfo['totalFees'];
                $totalPaid = $paymentInfo['totalPaid'];
                $remainingAmount = $paymentInfo['remainingAmount'];
                $paymentPercentage = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
            @endphp
            
            <div class="row">
                <div class="label">{{ $school->term('total_fees', 'Frais totaux') }}:</div>
                <div class="value">{{ number_format($totalFees, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</div>
            </div>
            <div class="row">
                <div class="label">{{ $school->term('total_paid', 'Total payé') }}:</div>
                <div class="value">{{ number_format($totalPaid, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }} ({{ $paymentPercentage }}%)</div>
            </div>
            <div class="row">
                <div class="label">{{ $school->term('remaining_amount', 'Reste à payer') }}:</div>
                <div class="value">
                    <strong class="{{ $remainingAmount > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($remainingAmount, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}
                    </strong>
                </div>
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
                    <td class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>{{ $school->term('total', 'Total') }}</strong></td>
                    <td class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="payment-method">
            <p><strong>{{ $school->term('payment_method', 'Mode de paiement') }}:</strong> 
                @if($payment->payment_method == 'cash')
                <i class="fas fa-money-bill-wave"></i>
                @elseif($payment->payment_method == 'bank')
                <i class="fas fa-university"></i>
                @elseif($payment->payment_method == 'mobile')
                <i class="fas fa-mobile-alt"></i>
                @else
                <i class="fas fa-credit-card"></i>
                @endif
                {{ ucfirst($payment->payment_method ?? 'Espèces') }}
            </p>
            @if($payment->notes)
            <p><strong>{{ $school->term('notes', 'Notes') }}:</strong> {{ $payment->notes }}</p>
            @endif
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
            @if(isset($school->report_settings['header_footer']) && !empty($school->report_settings['header_footer']))
                <p>{{ $school->report_settings['header_footer'] }}</p>
            @endif
            <p>{{ $school->name }} - {{ $school->term('receipt', 'Reçu de Paiement') }} #{{ $payment->receipt_number }}</p>
            <p>{{ date('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin: 20px auto; max-width: 800px;">
        <button onclick="window.print();" style="padding: 10px 20px; background-color: {{ $school->theme_color }}; color: white; border: none; border-radius: 5px; cursor: pointer; font-family: 'Inter', sans-serif;">
            <i class="fas fa-print mr-2"></i> {{ $school->term('print', 'Imprimer') }}
        </button>
        <button onclick="window.close();" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px; font-family: 'Inter', sans-serif;">
            <i class="fas fa-times mr-2"></i> {{ $school->term('close', 'Fermer') }}
        </button>
    </div>
</body>
</html>
