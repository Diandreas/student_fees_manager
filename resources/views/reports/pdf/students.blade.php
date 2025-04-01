<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des étudiants</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
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
        .school-info {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        .summary-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #0d47a1;
        }
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 0;
            color: #0d47a1;
            margin-bottom: 10px;
        }
        .summary-stats {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .stat-box {
            flex: 1;
            min-width: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #0d47a1;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
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
            background-color: #0d47a1;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
        }
        td {
            padding: 10px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-fully-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-partially-paid {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-not-paid {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .amount {
            text-align: right;
            font-family: monospace;
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
            <div class="school-name">{{ $school->name }}</div>
            <div class="school-info">
                {{ $school->address }}<br>
                Email: {{ $school->contact_email ?? $school->email }} | 
                Tél: {{ $school->contact_phone ?? $school->phone }}
            </div>
            <div class="report-title">Rapport des Étudiants</div>
            <div class="date">Généré le {{ date('d/m/Y à H:i') }}</div>
        </div>
        
        <div class="summary-section">
            <h3 class="summary-title">Résumé</h3>
            <div class="summary-stats">
                <div class="stat-box">
                    <div class="stat-value">{{ $students->count() }}</div>
                    <div class="stat-label">Total étudiants</div>
                </div>
                
                @php
                    $paidCount = $students->filter(function($student) {
                        return $student->payment_status === 'fully-paid' || 
                               ($student->field && $student->payments->sum('amount') >= $student->field->fees);
                    })->count();
                    
                    $partialCount = $students->filter(function($student) {
                        return $student->payment_status === 'partially-paid' || 
                               ($student->field && $student->payments->sum('amount') > 0 && $student->payments->sum('amount') < $student->field->fees);
                    })->count();
                    
                    $unpaidCount = $students->filter(function($student) {
                        return $student->payment_status === 'not-paid' || 
                               ($student->field && $student->payments->sum('amount') == 0);
                    })->count();
                    
                    $totalFees = $students->sum(function($student) {
                        return $student->field ? $student->field->fees : 0;
                    });
                    
                    $totalPaid = $students->sum(function($student) {
                        return $student->payments ? $student->payments->sum('amount') : 0;
                    });
                    
                    $paymentPercentage = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
                @endphp
                
                <div class="stat-box">
                    <div class="stat-value">{{ $paidCount }}</div>
                    <div class="stat-label">Payé intégralement</div>
                </div>
                
                <div class="stat-box">
                    <div class="stat-value">{{ $partialCount }}</div>
                    <div class="stat-label">Payé partiellement</div>
                </div>
                
                <div class="stat-box">
                    <div class="stat-value">{{ $paymentPercentage }}%</div>
                    <div class="stat-label">Taux de recouvrement</div>
                </div>
            </div>
        </div>
        
        <div class="section-title">Liste des étudiants</div>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Étudiant</th>
                    <th width="20%">Filière</th>
                    <th width="10%">Campus</th>
                    <th width="10%">Frais</th>
                    <th width="10%">Payé</th>
                    <th width="10%">Reste</th>
                    <th width="10%">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $student->fullName }}</strong>
                            @if($student->email)
                                <br><small>{{ $student->email }}</small>
                            @endif
                            @if($student->phone)
                                <br><small>{{ $student->phone }}</small>
                            @endif
                        </td>
                        <td>{{ $student->field->name ?? 'Non assigné' }}</td>
                        <td>{{ $student->field->campus->name ?? 'Non assigné' }}</td>
                        <td class="amount">{{ number_format($student->field->fees ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($student->payments->sum('amount'), 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format(($student->field->fees ?? 0) - $student->payments->sum('amount'), 0, ',', ' ') }} FCFA</td>
                        <td>
                            @php
                                $status = 'not-paid';
                                $statusText = 'Non payé';
                                
                                if ($student->field && $student->payments->sum('amount') >= $student->field->fees) {
                                    $status = 'fully-paid';
                                    $statusText = 'Payé';
                                } elseif ($student->field && $student->payments->sum('amount') > 0) {
                                    $status = 'partially-paid';
                                    $statusText = 'Partiel';
                                }
                            @endphp
                            <span class="status status-{{ $status }}">{{ $statusText }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Aucun étudiant trouvé</td>
                    </tr>
                @endforelse
                
                @if($students->count() > 0)
                    <tr style="background-color: #f5f5f5; font-weight: bold;">
                        <td colspan="4" style="text-align: right;">Total</td>
                        <td class="amount">{{ number_format($totalFees, 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($totalFees - $totalPaid, 0, ',', ' ') }} FCFA</td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
        
        <div class="footer">
            <p>{{ $school->name }} - Rapport généré le {{ date('d/m/Y à H:i') }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 