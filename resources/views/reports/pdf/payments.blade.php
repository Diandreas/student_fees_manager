<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des paiements</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
            position: relative;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            z-index: -1;
            width: 70%;
            max-height: 70%;
            pointer-events: none;
        }
        .document-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: -1;
        }
        .document-container {
            position: relative;
            padding: 10px;
        }
        .official-stamp {
            position: absolute;
            top: 40%;
            right: 5%;
            transform: rotate(30deg);
            font-size: 24px;
            color: rgba(0, 0, 0, 0.06);
            font-weight: bold;
            text-transform: uppercase;
            border: 3px solid rgba(0, 0, 0, 0.06);
            padding: 10px 20px;
            z-index: -1;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0d47a1;
            padding-bottom: 20px;
        }
        .school-logo {
            max-width: 80px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #0d47a1;
        }
        .report-title {
            font-size: 18px;
            color: #666;
            font-weight: 600;
            margin-top: 10px;
        }
        .date {
            font-size: 12px;
            color: #777;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        td {
            padding: 10px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 30px;
            font-size: 12px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #0d47a1;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #777;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .generated-by {
            font-size: 9px;
            color: #999;
            text-align: center;
            margin-top: 5px;
            font-style: italic;
        }
        .payment-method {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            background-color: #e3f2fd;
            color: #1565c0;
        }
    </style>
</head>
<body>
    <!-- Filigrane avec le logo de l'école -->
    @if(isset($school->logo))
        <img src="{{ public_path('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="watermark">
    @endif
    
    <div class="document-container">
        <div class="document-border"></div>
        <div class="official-stamp">Document Officiel</div>
        
        <div class="header">
            @if(isset($school->logo))
                <img src="{{ public_path('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="school-logo">
            @endif
            <div class="school-name">{{ $school->name }}</div>
            <div class="report-title">Rapport des Paiements</div>
            <div class="date">Généré le {{ date('d/m/Y à H:i') }}</div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>N° Reçu</th>
                    <th>Étudiant</th>
                    <th>Filière</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Méthode</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->receipt_number }}</td>
                        <td>{{ $payment->student->fullName }}</td>
                        <td>{{ $payment->student->field->name }}</td>
                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                        <td>
                            <span class="payment-method">
                                @if($payment->payment_method == 'cash')
                                    Espèces
                                @elseif($payment->payment_method == 'bank')
                                    Banque
                                @elseif($payment->payment_method == 'mobile')
                                    Mobile
                                @else
                                    {{ ucfirst($payment->payment_method) }}
                                @endif
                            </span>
                        </td>
                        <td>{{ $payment->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="summary">
            <p><strong>Nombre total de paiements:</strong> {{ count($payments) }}</p>
            <p><strong>Montant total:</strong> {{ number_format($payments->sum('amount'), 0, ',', ' ') }} FCFA</p>
        </div>
        
        <div class="footer">
            <p>{{ $school->name }} - Rapport généré le {{ date('d/m/Y à H:i') }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 