<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport financier</title>
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
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #0d47a1;
            color: #0d47a1;
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
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #0d47a1;
        }
        .summary-item {
            margin-bottom: 10px;
            font-size: 12px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 200px;
        }
        .value {
            font-weight: normal;
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
        .progress-container {
            width: 100%;
            height: 15px;
            background-color: #eee;
            border-radius: 10px;
            margin-top: 5px;
        }
        .progress-bar {
            height: 100%;
            background-color: #4caf50;
            border-radius: 10px;
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
            <div class="report-title">Rapport Financier</div>
            <div class="date">Généré le {{ date('d/m/Y à H:i') }}</div>
        </div>
        
        <div class="section-title">Résumé Financier</div>
        
        <div class="summary">
            <div class="summary-item">
                <span class="label">Frais totaux attendus:</span>
                <span class="value">{{ number_format($totalFees, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item">
                <span class="label">Montant total perçu:</span>
                <span class="value">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item">
                <span class="label">Montant restant à percevoir:</span>
                <span class="value">{{ number_format($remainingAmount, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item">
                <span class="label">Taux de recouvrement:</span>
                <span class="value">{{ $recoveryRate }}%</span>
                <div class="progress-container">
                    <div class="progress-bar" style="width: {{ $recoveryRate }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="section-title">Détails par filière</div>
        
        <table>
            <thead>
                <tr>
                    <th>Filière</th>
                    <th>Étudiants</th>
                    <th>Frais</th>
                    <th>Montant attendu</th>
                    <th>Montant perçu</th>
                    <th>Restant</th>
                    <th>Taux</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statsByField as $fieldStats)
                    <tr>
                        <td>{{ $fieldStats['name'] }}</td>
                        <td>{{ $fieldStats['students_count'] }}</td>
                        <td>{{ number_format($fieldStats['fees'], 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($fieldStats['expected_amount'], 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($fieldStats['paid_amount'], 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($fieldStats['remaining_amount'], 0, ',', ' ') }} FCFA</td>
                        <td>{{ $fieldStats['recovery_rate'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <p>{{ $school->name }} - Rapport généré le {{ date('d/m/Y à H:i') }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 