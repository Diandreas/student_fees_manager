<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des paiements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .school-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            color: #666;
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
        .summary {
            margin-top: 30px;
            font-size: 12px;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #777;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
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
    <div class="header">
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
    </div>
</body>
</html> 