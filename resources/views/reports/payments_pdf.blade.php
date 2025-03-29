<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des paiements - {{ $school->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            color: #333;
        }
        .report-title {
            font-size: 18px;
            margin: 10px 0;
            color: #666;
        }
        .school-info {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .stat-box {
            width: 30%;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .stat-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="school-name">{{ $school->report_settings['header_title'] ?? $school->name }}</h1>
        @if(isset($school->report_settings['header_subtitle']) && !empty($school->report_settings['header_subtitle']))
            <p class="school-subtitle">{{ $school->report_settings['header_subtitle'] }}</p>
        @endif
        <p class="school-info">
            {{ $school->report_settings['header_address'] ?? $school->address }}<br>
            Email: {{ $school->report_settings['header_email'] ?? $school->contact_email ?? $school->email }} | 
            Tél: {{ $school->report_settings['header_phone'] ?? $school->contact_phone ?? $school->phone }}
        </p>
        <h2 class="report-title">Rapport des paiements</h2>
        <p>Période: {{ date('d/m/Y') }}</p>
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
        @if(isset($school->report_settings['header_footer']) && !empty($school->report_settings['header_footer']))
            <p>{{ $school->report_settings['header_footer'] }}</p>
        @endif
        <p>{{ $school->name }} - Rapport généré le {{ $generatedAt }}</p>
    </div>
</body>
</html> 