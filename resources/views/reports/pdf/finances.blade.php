<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport financier</title>
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
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
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
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
    <div class="header">
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
    </div>
</body>
</html> 