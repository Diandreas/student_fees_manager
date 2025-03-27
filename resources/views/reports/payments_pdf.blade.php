<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des paiements - {{ $school->name }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            max-height: 100px;
            margin-bottom: 10px;
        }
        h1 {
            color: {{ $school->theme_color ?? '#1a56db' }};
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        h2 {
            color: {{ $school->theme_color ?? '#1a56db' }};
            font-size: 18px;
            margin: 0 0 20px 0;
            font-weight: normal;
        }
        .info-block {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 5px solid {{ $school->theme_color ?? '#1a56db' }};
        }
        .info-block h3 {
            margin-top: 0;
            color: {{ $school->theme_color ?? '#1a56db' }};
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.summary {
            margin-bottom: 30px;
        }
        table.summary th {
            background-color: {{ $school->theme_color ?? '#1a56db' }};
            color: #fff;
            padding: 10px;
            text-align: left;
        }
        table.summary td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .section-title {
            font-size: 16px;
            color: {{ $school->theme_color ?? '#1a56db' }};
            margin: 30px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid {{ $school->theme_color ?? '#1a56db' }};
        }
        .payments-table {
            font-size: 11px;
        }
        .payments-table th {
            background-color: {{ $school->theme_color ?? '#1a56db' }};
            color: #fff;
            padding: 8px;
            text-align: left;
        }
        .payments-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .date {
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .stats-box {
            width: 48%;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 5px solid {{ $school->theme_color ?? '#1a56db' }};
        }
        .stats-box h4 {
            margin-top: 0;
            color: {{ $school->theme_color ?? '#1a56db' }};
        }
        .stats-value {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($school->logo)
        <img src="{{ public_path('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="logo">
        @endif
        <h1>{{ $school->name }}</h1>
        <h2>Rapport des paiements</h2>
        <p>{{ $school->address }} | {{ $school->phone }} | {{ $school->email }}</p>
    </div>
    
    <div class="info-block">
        <h3>Résumé des paiements</h3>
        <p><strong>Montant total des paiements:</strong> {{ number_format($totalAmount, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</p>
        <p><strong>Nombre de paiements:</strong> {{ count($payments) }}</p>
        <p><strong>Période:</strong> Tous les paiements jusqu'au {{ Carbon\Carbon::now()->format('d/m/Y') }}</p>
        <p><strong>Rapport généré le:</strong> {{ $generatedAt }}</p>
    </div>
    
    <div class="section-title">Répartition par mois</div>
    
    <table class="summary">
        <thead>
            <tr>
                <th>Mois</th>
                <th>Nombre de paiements</th>
                <th>Montant total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paymentsByMonth as $month => $data)
            <tr>
                <td>{{ \Carbon\Carbon::createFromFormat('m-Y', $month)->format('F Y') }}</td>
                <td>{{ $data['count'] }}</td>
                <td class="amount">{{ number_format($data['total'], 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Aucune donnée disponible</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="section-title">Répartition par filière</div>
    
    <table class="summary">
        <thead>
            <tr>
                <th>Filière</th>
                <th>Nombre de paiements</th>
                <th>Montant total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paymentsByField as $field => $data)
            <tr>
                <td>{{ $field }}</td>
                <td>{{ $data['count'] }}</td>
                <td class="amount">{{ number_format($data['total'], 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Aucune donnée disponible</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="section-title">Liste des paiements</div>
    
    <table class="payments-table">
        <thead>
            <tr>
                <th>{{ $school->term('receipt_number', 'Reçu N°') }}</th>
                <th>{{ $school->term('student', 'Étudiant') }}</th>
                <th>{{ $school->term('field', 'Filière') }}</th>
                <th>{{ $school->term('payment_date', 'Date') }}</th>
                <th>{{ $school->term('description', 'Description') }}</th>
                <th>{{ $school->term('amount', 'Montant') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->receipt_number }}</td>
                <td>{{ $payment->student->fullName }}</td>
                <td>{{ $payment->student->field->name }}</td>
                <td class="date">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                <td>{{ $payment->description }}</td>
                <td class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $school->term('currency', 'FCFA') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Aucun paiement trouvé</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré automatiquement le {{ $generatedAt }}</p>
        <p>{{ $school->name }} - Tous droits réservés &copy; {{ date('Y') }}</p>
    </div>
</body>
</html> 