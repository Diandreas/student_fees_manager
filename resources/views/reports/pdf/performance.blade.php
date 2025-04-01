<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de performance des campus</title>
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
        .campus-title {
            background-color: #4F46E5;
            color: white;
            padding: 8px;
            font-size: 14px;
            margin: 20px 0 10px;
            border-radius: 5px;
        }
        .field-title {
            background-color: #E5E7EB;
            padding: 6px;
            font-size: 13px;
            margin: 15px 0 5px;
            border-radius: 3px;
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
            margin-bottom: 15px;
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
        .progress-container {
            width: 100%;
            background-color: #f3f4f6;
            height: 14px;
            border-radius: 7px;
            margin: 10px 0;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            border-radius: 7px;
        }
        .progress-high {
            background-color: #10b981;
        }
        .progress-medium {
            background-color: #f59e0b;
        }
        .progress-low {
            background-color: #ef4444;
        }
        .summary-title {
            color: #4F46E5;
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .campus-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border: 1px solid #E5E7EB;
            border-radius: 5px;
            padding: 10px;
            background-color: #f9fafb;
        }
        .campus-meta {
            flex: 1;
        }
        .campus-meta h3 {
            margin: 0 0 5px 0;
            color: #4F46E5;
            font-size: 14px;
        }
        .campus-meta p {
            margin: 0;
            font-size: 11px;
            color: #6B7280;
        }
        .campus-stats {
            flex: 1;
            text-align: right;
        }
        .campus-stat {
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 10px;
            color: #6B7280;
        }
        .stat-value {
            font-weight: bold;
            font-size: 12px;
        }
        .high-value {
            color: #10b981;
        }
        .medium-value {
            color: #f59e0b;
        }
        .low-value {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($school->logo)
            <img src="{{ public_path('storage/' . $school->logo) }}" height="60" alt="{{ $school->name }}">
        @endif
        <h1>Rapport de performance des campus</h1>
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
            <div class="stat-value">{{ count($campusData) }}</div>
            <div class="stat-label">Campus</div>
        </div>
        
        @php
            $totalStudents = 0;
            $totalFees = 0;
            $totalPaid = 0;
            
            foreach ($campusData as $data) {
                $totalStudents += $data['studentCount'];
                $totalFees += $data['totalFees'];
                $totalPaid += $data['totalPaid'];
            }
            
            $globalRecoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100, 1) : 0;
        @endphp
        
        <div class="stat-box">
            <div class="stat-value">{{ number_format($totalStudents, 0, ',', ' ') }}</div>
            <div class="stat-label">Étudiants</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ number_format($totalFees, 0, ',', ' ') }}</div>
            <div class="stat-label">Frais attendus</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $globalRecoveryRate }}%</div>
            <div class="stat-label">Taux de recouvrement global</div>
        </div>
    </div>

    <!-- Tableau comparatif des campus -->
    <div class="summary-title">Comparaison des campus</div>
    <table>
        <thead>
            <tr>
                <th>Campus</th>
                <th style="text-align: right;">Étudiants</th>
                <th style="text-align: right;">Frais attendus</th>
                <th style="text-align: right;">Montant payé</th>
                <th style="text-align: right;">Reste à payer</th>
                <th style="text-align: center;">Taux</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campusData as $data)
                @php
                    $rateClass = '';
                    if ($data['recoveryRate'] >= 80) {
                        $rateClass = 'high-value';
                    } elseif ($data['recoveryRate'] >= 50) {
                        $rateClass = 'medium-value';
                    } else {
                        $rateClass = 'low-value';
                    }
                @endphp
                <tr>
                    <td>{{ $data['campus']->name }}</td>
                    <td style="text-align: right;">{{ number_format($data['studentCount'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($data['totalFees'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($data['totalPaid'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($data['totalFees'] - $data['totalPaid'], 0, ',', ' ') }}</td>
                    <td style="text-align: center;" class="{{ $rateClass }}">{{ number_format($data['recoveryRate'], 1, ',', ' ') }}%</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #f1f5f9;">
                <td>Total</td>
                <td style="text-align: right;">{{ number_format($totalStudents, 0, ',', ' ') }}</td>
                <td style="text-align: right;">{{ number_format($totalFees, 0, ',', ' ') }}</td>
                <td style="text-align: right;">{{ number_format($totalPaid, 0, ',', ' ') }}</td>
                <td style="text-align: right;">{{ number_format($totalFees - $totalPaid, 0, ',', ' ') }}</td>
                <td style="text-align: center;">{{ $globalRecoveryRate }}%</td>
            </tr>
        </tbody>
    </table>

    <!-- Graphiques (représentation visuelle) -->
    <div class="chart-container">
        <div class="chart-placeholder">
            [Graphique: Comparaison des campus]
            <br><small>Ce graphique est disponible dans la version web du rapport.</small>
        </div>
    </div>

    <!-- Détail par campus -->
    <div class="summary-title">Détail par campus</div>
    
    @foreach($campusData as $data)
        <div class="campus-info">
            <div class="campus-meta">
                <h3>{{ $data['campus']->name }}</h3>
                <p>{{ $data['campus']->address ?? 'Adresse non définie' }}</p>
                <p>{{ $data['studentCount'] }} étudiants</p>
            </div>
            <div class="campus-stats">
                <div class="campus-stat">
                    <span class="stat-label">Frais attendus:</span>
                    <span class="stat-value">{{ number_format($data['totalFees'], 0, ',', ' ') }}</span>
                </div>
                <div class="campus-stat">
                    <span class="stat-label">Montant payé:</span>
                    <span class="stat-value">{{ number_format($data['totalPaid'], 0, ',', ' ') }}</span>
                </div>
                <div class="campus-stat">
                    <span class="stat-label">Taux de recouvrement:</span>
                    @php
                        $valueClass = '';
                        if ($data['recoveryRate'] >= 80) {
                            $valueClass = 'high-value';
                        } elseif ($data['recoveryRate'] >= 50) {
                            $valueClass = 'medium-value';
                        } else {
                            $valueClass = 'low-value';
                        }
                    @endphp
                    <span class="stat-value {{ $valueClass }}">{{ number_format($data['recoveryRate'], 1, ',', ' ') }}%</span>
                </div>
            </div>
        </div>

        <!-- Barre de progression -->
        <div class="progress-container">
            @php
                $barClass = 'progress-low';
                if ($data['recoveryRate'] >= 80) {
                    $barClass = 'progress-high';
                } elseif ($data['recoveryRate'] >= 50) {
                    $barClass = 'progress-medium';
                }
            @endphp
            <div class="progress-bar {{ $barClass }}" style="width: {{ min(100, $data['recoveryRate']) }}%"></div>
        </div>

        <!-- Tableau des filières par campus -->
        @if(isset($data['fields']) && count($data['fields']) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Filière</th>
                        <th style="text-align: right;">Étudiants</th>
                        <th style="text-align: right;">Frais</th>
                        <th style="text-align: right;">Payé</th>
                        <th style="text-align: center;">Taux</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['fields'] as $field)
                        @php
                            $fieldRecoveryRate = $field['fees'] > 0 ? round(($field['paid'] / $field['fees']) * 100, 1) : 0;
                            $rateClass = '';
                            if ($fieldRecoveryRate >= 80) {
                                $rateClass = 'high-value';
                            } elseif ($fieldRecoveryRate >= 50) {
                                $rateClass = 'medium-value';
                            } else {
                                $rateClass = 'low-value';
                            }
                        @endphp
                        <tr>
                            <td>{{ $field['name'] }}</td>
                            <td style="text-align: right;">{{ $field['students'] }}</td>
                            <td style="text-align: right;">{{ number_format($field['fees'], 0, ',', ' ') }}</td>
                            <td style="text-align: right;">{{ number_format($field['paid'], 0, ',', ' ') }}</td>
                            <td style="text-align: center;" class="{{ $rateClass }}">{{ $fieldRecoveryRate }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; font-style: italic; color: #6B7280;">Aucune filière trouvée pour ce campus</p>
        @endif

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="footer">
        {{ $school->name }} - {{ date('Y') }} | Tous droits réservés<br>
        Rapport généré le {{ now()->format('d/m/Y') }} à {{ now()->format('H:i') }}
    </div>
</body>
</html> 