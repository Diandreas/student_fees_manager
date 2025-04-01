<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
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
            width: 60%;
            max-height: 60%;
            pointer-events: none;
        }
        .document-container {
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .official-stamp {
            position: absolute;
            top: 40%;
            right: 5%;
            transform: rotate(45deg);
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
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid {{ $school->primary_color ?? '#3b82f6' }};
        }
        .logo {
            max-width: 80px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: {{ $school->primary_color ?? '#3b82f6' }};
            margin: 0;
        }
        .school-info {
            font-size: 10px;
            color: #666;
            margin: 5px 0;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            text-align: center;
            text-transform: uppercase;
        }
        .meta-info {
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
            border-left: 3px solid {{ $school->primary_color ?? '#3b82f6' }};
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: {{ $school->primary_color ?? '#3b82f6' }};
            color: white;
            font-weight: 600;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .fully-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .partially-paid {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .not-paid {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .amount {
            text-align: right;
            font-family: monospace;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .generated-by {
            font-size: 9px;
            font-style: italic;
            color: #9ca3af;
            margin-top: 5px;
            text-align: center;
        }
        .summary {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .summary-box {
            flex: 1;
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .summary-box.primary {
            border-top: 3px solid #3b82f6;
        }
        .summary-box.success {
            border-top: 3px solid #10b981;
        }
        .summary-box.warning {
            border-top: 3px solid #f59e0b;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .summary-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        @media print {
            body {
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .document-container {
                border: none;
                max-width: 100%;
                padding: 0;
            }
            .watermark {
                opacity: 0.03;
            }
        }
    </style>
</head>
<body>
    <!-- Filigrane avec le logo de l'école -->
    @if(isset($school->logo))
        <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="watermark">
    @endif

    <div class="document-container">
        <div class="official-stamp">Document Officiel</div>
        
        <div class="header">
            @if(isset($school->logo))
                <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="logo">
            @endif
            <h1 class="school-name">{{ $school->name }}</h1>
            <p class="school-info">{{ $school->address }} | {{ $school->email }} | {{ $school->phone }}</p>
        </div>
        
        <h2 class="title">{{ $title }}</h2>
        
        <div class="meta-info">
            <strong>Date de génération:</strong> {{ date('d/m/Y H:i') }} | 
            <strong>Période:</strong> Année académique en cours | 
            <strong>Nombre d'étudiants:</strong> {{ $students->count() }}
        </div>
        
        @if(isset($showSummary) && $showSummary)
        <div class="summary">
            <div class="summary-box primary">
                <div class="summary-value">{{ number_format($totalFees, 0, '.', ' ') }} FCFA</div>
                <div class="summary-label">Total des frais</div>
            </div>
            <div class="summary-box success">
                <div class="summary-value">{{ number_format($totalPaid, 0, '.', ' ') }} FCFA</div>
                <div class="summary-label">Total payé</div>
            </div>
            <div class="summary-box warning">
                <div class="summary-value">{{ number_format($totalRemaining, 0, '.', ' ') }} FCFA</div>
                <div class="summary-label">Reste à payer</div>
            </div>
        </div>
        @endif
        
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="20%">Étudiant</th>
                    <th width="15%">Contacts</th>
                    <th width="15%">Filière</th>
                    <th width="10%">Campus</th>
                    <th width="10%">Frais</th>
                    <th width="10%">Payé</th>
                    <th width="10%">Reste</th>
                    <th width="5%">État</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $student->lastname }} {{ $student->firstname }}</strong>
                    </td>
                    <td>
                        @if($student->email)<div>{{ $student->email }}</div>@endif
                        @if($student->phone)<div>{{ $student->phone }}</div>@endif
                    </td>
                    <td>{{ $student->field->name ?? 'Non assigné' }}</td>
                    <td>{{ $student->field->campus->name ?? 'Non assigné' }}</td>
                    <td class="amount">{{ number_format($student->field->fees ?? 0, 0, '.', ' ') }} FCFA</td>
                    <td class="amount">{{ number_format($student->payments->sum('amount'), 0, '.', ' ') }} FCFA</td>
                    <td class="amount">{{ number_format(($student->field->fees ?? 0) - $student->payments->sum('amount'), 0, '.', ' ') }} FCFA</td>
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
                        <span class="status {{ $status }}">{{ $statusText }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;">Aucun étudiant trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="footer">
            <p>{{ $school->name }} - Document généré le {{ date('d/m/Y à H:i') }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 