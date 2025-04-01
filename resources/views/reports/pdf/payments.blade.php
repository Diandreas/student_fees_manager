<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des paiements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .header h1 {
            color: #4F46E5;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header .school-info {
            margin-bottom: 5px;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .stat-box {
            flex: 1;
            background-color: #f9fafb;
            border-radius: 5px;
            padding: 10px;
            margin: 5px;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .stat-box .stat-value {
            font-size: 22px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .stat-box .stat-label {
            font-size: 11px;
            color: #6B7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #E5E7EB;
        }
        th {
            background-color: #f9fafb;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 8px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .period-title {
            background-color: #4F46E5;
            color: white;
            padding: 8px;
            font-size: 14px;
            margin: 20px 0 10px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .page-break {
            page-break-after: always;
        }
        .payment-status {
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .chart-container {
            margin: 20px 0;
            text-align: center;
        }
        .chart-placeholder {
            border: 1px dashed #ccc;
            border-radius: 5px;
            padding: 30px;
            background-color: #f9fafb;
            color: #6B7280;
            text-align: center;
            font-style: italic;
        }
        .payment-method {
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            background-color: #e2e8f0;
            color: #475569;
        }
        .summary-title {
            color: #4F46E5;
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($school->logo)
            <img src="{{ public_path('storage/' . $school->logo) }}" height="60" alt="{{ $school->name }}">
        @endif
        <h1>Rapport des paiements</h1>
        <div class="school-info">
            {{ $school->name }} | {{ $school->address ?? 'Adresse non définie' }} | {{ $school->phone ?? 'Téléphone non défini' }}
        </div>
        <div>
            Généré le: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-value">{{ number_format($totalPayments, 0, ',', ' ') }}</div>
            <div class="stat-label">Total des paiements</div>
        </div>
        
        <div class="stat-box">
            <div class="stat-value">{{ number_format($totalStudents, 0, ',', ' ') }}</div>
            <div class="stat-label">Nombre d'étudiants</div>
        </div>

        <div class="stat-box">
            <div class="stat-value">{{ number_format($studentsWithPayments, 0, ',', ' ') }}</div>
            <div class="stat-label">Étudiants ayant payé</div>
        </div>
        
        <div class="stat-box">
            <div class="stat-value">{{ number_format($paymentRate, 1, ',', ' ') }}%</div>
            <div class="stat-label">Taux de paiement</div>
        </div>
    </div>

    <!-- Graphiques (représentation visuelle) -->
    <div class="chart-container">
        <div class="chart-placeholder">
            [Graphique: Évolution des paiements mensuels]
            <br><small>Ce graphique est disponible dans la version web du rapport.</small>
        </div>
    </div>

    <!-- Tableau récapitulatif par mois -->
    <div class="summary-title">Récapitulatif des paiements mensuels</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Mois</th>
                <th width="40%" style="text-align: right;">Montant</th>
                <th width="40%" style="text-align: right;">Pourcentage</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = array_sum($paymentData); @endphp
            @foreach($monthLabels as $index => $month)
                <tr>
                    <td>{{ $month }}</td>
                    <td style="text-align: right;">{{ number_format($paymentData[$index] ?? 0, 0, ',', ' ') }}</td>
                    <td style="text-align: right;">
                        @if($totalAmount > 0)
                            {{ number_format((($paymentData[$index] ?? 0) / $totalAmount) * 100, 1, ',', ' ') }}%
                        @else
                            0,0%
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #f1f5f9;">
                <td>Total</td>
                <td style="text-align: right;">{{ number_format($totalAmount, 0, ',', ' ') }}</td>
                <td style="text-align: right;">100,0%</td>
            </tr>
        </tbody>
    </table>

    <!-- Tableau des paiements récents -->
    <div class="summary-title">Détail des paiements récents</div>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="15%">Date</th>
                <th width="30%">Étudiant</th>
                <th width="15%">Montant</th>
                <th width="20%">Méthode</th>
                <th width="15%">Référence</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                    <td>
                        <strong>{{ $payment->student->fullName }}</strong>
                        <br>
                        <small>{{ $payment->student->field->name ?? 'Non assigné' }}</small>
                    </td>
                    <td style="text-align: right;">{{ number_format($payment->amount, 0, ',', ' ') }}</td>
                    <td>
                        <span class="payment-method">{{ $payment->payment_method }}</span>
                    </td>
                    <td>{{ $payment->reference_number ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Aucun paiement trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Analyse par campus -->
    <div class="summary-title">Paiements par campus</div>
    <table>
        <thead>
            <tr>
                <th>Campus</th>
                <th style="text-align: right;">Nombre de paiements</th>
                <th style="text-align: right;">Montant total</th>
                <th style="text-align: right;">Montant moyen</th>
                <th style="text-align: right;">% du total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $campusPayments = [];
                foreach ($payments as $payment) {
                    if (!$payment->student->field || !$payment->student->field->campus) continue;
                    
                    $campusName = $payment->student->field->campus->name;
                    if (!isset($campusPayments[$campusName])) {
                        $campusPayments[$campusName] = [
                            'count' => 0,
                            'total' => 0
                        ];
                    }
                    
                    $campusPayments[$campusName]['count']++;
                    $campusPayments[$campusName]['total'] += $payment->amount;
                }

                $totalPaymentAmount = array_sum(array_column($campusPayments, 'total'));
            @endphp
            
            @foreach($campusPayments as $campusName => $stats)
                <tr>
                    <td>{{ $campusName }}</td>
                    <td style="text-align: right;">{{ number_format($stats['count'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($stats['total'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($stats['count'] > 0 ? $stats['total'] / $stats['count'] : 0, 0, ',', ' ') }}</td>
                    <td style="text-align: right;">
                        @if($totalPaymentAmount > 0)
                            {{ number_format(($stats['total'] / $totalPaymentAmount) * 100, 1, ',', ' ') }}%
                        @else
                            0,0%
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Analyse par méthode de paiement -->
    <div class="summary-title">Paiements par méthode</div>
    <table>
        <thead>
            <tr>
                <th>Méthode</th>
                <th style="text-align: right;">Nombre</th>
                <th style="text-align: right;">Montant total</th>
                <th style="text-align: right;">% du total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $methodStats = [];
                foreach ($payments as $payment) {
                    $method = $payment->payment_method ?? 'Non spécifié';
                    
                    if (!isset($methodStats[$method])) {
                        $methodStats[$method] = [
                            'count' => 0,
                            'total' => 0
                        ];
                    }
                    
                    $methodStats[$method]['count']++;
                    $methodStats[$method]['total'] += $payment->amount;
                }

                $totalAmount = array_sum(array_column($methodStats, 'total'));
            @endphp
            
            @foreach($methodStats as $method => $stats)
                <tr>
                    <td>{{ $method }}</td>
                    <td style="text-align: right;">{{ number_format($stats['count'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($stats['total'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;">
                        @if($totalAmount > 0)
                            {{ number_format(($stats['total'] / $totalAmount) * 100, 1, ',', ' ') }}%
                        @else
                            0,0%
                        @endif
                    </td>
                </tr>
            @endforeach
            
            <tr style="font-weight: bold; background-color: #f1f5f9;">
                <td>Total</td>
                <td style="text-align: right;">{{ count($payments) }}</td>
                <td style="text-align: right;">{{ number_format($totalAmount, 0, ',', ' ') }}</td>
                <td style="text-align: right;">100,0%</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        {{ $school->name }} - {{ date('Y') }} | Tous droits réservés<br>
        Rapport généré le {{ now()->format('d/m/Y') }} à {{ now()->format('H:i') }}
    </div>
</body>
</html> 