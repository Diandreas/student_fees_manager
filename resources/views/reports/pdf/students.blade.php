<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des étudiants</title>
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
        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-partial {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-unpaid {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-table th {
            background-color: #f1f5f9;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($school->logo)
            <img src="{{ public_path('storage/' . $school->logo) }}" height="60" alt="{{ $school->name }}">
        @endif
        <h1>Rapport des étudiants</h1>
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
            <div class="stat-value">{{ count($students) }}</div>
            <div class="stat-label">Total des étudiants</div>
        </div>
        
        @php
            $totalFees = 0;
            $totalPaid = 0;
            $paidCount = 0;
            $partialCount = 0;
            $unpaidCount = 0;
            
            foreach ($students as $student) {
                if (!$student->field) continue;
                
                $fees = $student->field->fees;
                $paid = $student->payments->sum('amount');
                $totalFees += $fees;
                $totalPaid += $paid;
                
                if ($paid >= $fees && $fees > 0) {
                    $paidCount++;
                } elseif ($paid > 0) {
                    $partialCount++;
                } else {
                    $unpaidCount++;
                }
            }
            
            $recoveryRate = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100) : 0;
        @endphp
        
        <div class="stat-box">
            <div class="stat-value">{{ $paidCount }}</div>
            <div class="stat-label">Étudiants en règle</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $partialCount }}</div>
            <div class="stat-label">Paiements partiels</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $unpaidCount }}</div>
            <div class="stat-label">Non payés</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $recoveryRate }}%</div>
            <div class="stat-label">Taux de recouvrement</div>
        </div>
    </div>

    <!-- Tableau récapitulatif par campus -->
    <h2 style="color: #4F46E5; font-size: 14px; margin-top: 25px;">Récapitulatif par campus</h2>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Campus</th>
                <th>Nombre d'étudiants</th>
                <th>Frais attendus</th>
                <th>Montants payés</th>
                <th>Taux</th>
            </tr>
        </thead>
        <tbody>
            @php
                $campusStats = [];
                foreach ($students as $student) {
                    if (!$student->field || !$student->field->campus) continue;
                    
                    $campusName = $student->field->campus->name;
                    if (!isset($campusStats[$campusName])) {
                        $campusStats[$campusName] = [
                            'count' => 0,
                            'fees' => 0,
                            'paid' => 0
                        ];
                    }
                    
                    $campusStats[$campusName]['count']++;
                    $campusStats[$campusName]['fees'] += $student->field->fees;
                    $campusStats[$campusName]['paid'] += $student->payments->sum('amount');
                }
            @endphp
            
            @foreach($campusStats as $campusName => $stats)
                <tr>
                    <td>{{ $campusName }}</td>
                    <td style="text-align:center;">{{ $stats['count'] }}</td>
                    <td style="text-align:right;">{{ number_format($stats['fees'], 0, ',', ' ') }}</td>
                    <td style="text-align:right;">{{ number_format($stats['paid'], 0, ',', ' ') }}</td>
                    <td style="text-align:center;">{{ $stats['fees'] > 0 ? round(($stats['paid'] / $stats['fees']) * 100) : 0 }}%</td>
                </tr>
            @endforeach
            
            <tr style="font-weight: bold;">
                <td>Total</td>
                <td style="text-align:center;">{{ count($students) }}</td>
                <td style="text-align:right;">{{ number_format($totalFees, 0, ',', ' ') }}</td>
                <td style="text-align:right;">{{ number_format($totalPaid, 0, ',', ' ') }}</td>
                <td style="text-align:center;">{{ $recoveryRate }}%</td>
            </tr>
        </tbody>
    </table>

    <!-- Regroupement des étudiants par campus et filière -->
    @php
        $campusGroups = $students->groupBy(function($student) {
            return $student->field && $student->field->campus ? $student->field->campus->id : 'Sans campus';
        });
    @endphp

    @foreach($campusGroups as $campusId => $campusStudents)
        @php
            $campusName = 'Sans campus';
            if ($campusId !== 'Sans campus') {
                $campus = \App\Models\Campus::find($campusId);
                if ($campus) {
                    $campusName = $campus->name;
                }
            }
            
            // Grouper par filière
            $fieldGroups = $campusStudents->groupBy(function($student) {
                return $student->field ? $student->field->id : 'Sans filière';
            });
        @endphp

        <div class="campus-title">
            CAMPUS: {{ $campusName }}
        </div>

        @foreach($fieldGroups as $fieldId => $fieldStudents)
            @php
                $fieldName = 'Sans filière';
                $fieldFees = 0;
                
                if ($fieldId !== 'Sans filière') {
                    $field = \App\Models\Field::find($fieldId);
                    if ($field) {
                        $fieldName = $field->name;
                        $fieldFees = $field->fees;
                    }
                }
            @endphp

            <div class="field-title">
                FILIÈRE: {{ $fieldName }} - Frais: {{ number_format($fieldFees, 0, ',', ' ') }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Nom complet</th>
                        <th width="20%">Email</th>
                        <th width="10%">Téléphone</th>
                        <th width="15%">Frais</th>
                        <th width="15%">Payé</th>
                        <th width="15%">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fieldStudents as $student)
                        @php
                            if (!$student->field) continue;
                            
                            $totalFees = $student->field->fees;
                            $totalPaid = $student->payments->sum('amount');
                            $remainingAmount = max(0, $totalFees - $totalPaid);
                            
                            // Déterminer le statut de paiement
                            $statusClass = 'status-unpaid';
                            if ($remainingAmount == 0 && $totalFees > 0) {
                                $paymentStatus = 'Payé intégralement';
                                $statusClass = 'status-paid';
                            } elseif ($totalPaid > 0) {
                                $paymentStatus = 'Partiellement payé';
                                $statusClass = 'status-partial';
                            } else {
                                $paymentStatus = 'Aucun paiement';
                                $statusClass = 'status-unpaid';
                            }
                        @endphp
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->fullName }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone ?: 'Non spécifié' }}</td>
                            <td style="text-align:right;">{{ number_format($totalFees, 0, ',', ' ') }}</td>
                            <td style="text-align:right;">{{ number_format($totalPaid, 0, ',', ' ') }}</td>
                            <td><span class="payment-status {{ $statusClass }}">{{ $paymentStatus }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

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