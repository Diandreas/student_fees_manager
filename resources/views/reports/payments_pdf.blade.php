<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des paiements - {{ $school->name }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
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
            border-bottom: 2px solid #0d47a1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .school-logo {
            max-width: 80px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            color: #0d47a1;
        }
        .report-title {
            font-size: 18px;
            margin: 10px 0;
            color: #666;
            font-weight: 600;
        }
        .school-info {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #0d47a1;
            color: #0d47a1;
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
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .info-block {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #0d47a1;
        }
        .info-block h3 {
            margin-top: 0;
            color: #0d47a1;
            font-size: 14px;
        }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary th {
            background-color: #0d47a1;
            color: white;
            padding: 8px;
            text-align: left;
        }
        .summary td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .summary tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .payments-table th {
            background-color: #0d47a1;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .payments-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        .payments-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .amount {
            text-align: right;
            font-family: monospace;
        }
        .date {
            white-space: nowrap;
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
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .generated-by {
            font-size: 9px;
            color: #999;
            text-align: center;
            margin-top: 5px;
            font-style: italic;
        }
        .page-break {
            page-break-after: always;
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
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 