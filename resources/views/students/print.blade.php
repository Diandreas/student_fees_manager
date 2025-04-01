<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
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
            width: 60%;
            max-height: 60%;
            object-fit: contain;
            pointer-events: none;
        }
        .document-container {
            position: relative;
            border: 1px solid rgba(13, 71, 161, 0.2);
            padding: 10px;
            margin: 0 auto;
            border-radius: 5px;
        }
        .official-stamp {
            position: absolute;
            top: 35%;
            right: 5%;
            transform: rotate(45deg);
            font-size: 24px;
            color: rgba(13, 71, 161, 0.08);
            font-weight: bold;
            text-transform: uppercase;
            border: 3px solid rgba(13, 71, 161, 0.08);
            padding: 10px 20px;
            z-index: -1;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #0d47a1;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            color: #0d47a1;
            text-transform: uppercase;
            text-align: center;
            border-bottom: 2px solid #0d47a1;
            padding-bottom: 5px;
        }
        .meta {
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 4px;
            border-left: 3px solid #0d47a1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        .status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-fully_paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-partially_paid {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-not_paid {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .generated-by {
            font-size: 9px;
            color: #999;
            font-style: italic;
            text-align: center;
            margin-top: 5px;
        }
        .amount {
            text-align: right;
            font-family: monospace;
        }
        .page-break {
            page-break-after: always;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <!-- Filigrane avec le logo de l'école -->
    @if($school->logo)
        <img src="{{ public_path('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="watermark">
    @endif

    <div class="document-container">
        <div class="official-stamp">Document Officiel</div>
        
        <div class="header">
            @if($school->logo)
                <img src="{{ public_path('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="logo">
            @endif
            <div class="school-name">{{ $school->name }}</div>
            <div>{{ $school->address }}</div>
            <div>{{ $school->email }} | {{ $school->phone }}</div>
        </div>

        <h1 class="title">{{ $title }}</h1>
        
        <div class="meta">
            <strong>Date de génération:</strong> {{ $generatedAt }} | 
            <strong>Total étudiants:</strong> {{ $students->count() }}
        </div>

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
                        <td class="amount">{{ number_format($student->field->fees, 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($student->payments->sum('amount'), 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($student->remaining_amount, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <span class="status status-{{ $student->payment_status }}">
                                {{ $student->payment_status_text }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">Aucun étudiant trouvé</td>
                    </tr>
                @endforelse

                @if($students->count() > 0)
                    <tr class="total-row">
                        <td colspan="4" style="text-align: right;">Total</td>
                        <td class="amount">{{ number_format($students->sum(function($student) { return $student->field->fees; }), 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($students->sum(function($student) { return $student->payments->sum('amount'); }), 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($students->sum('remaining_amount'), 0, ',', ' ') }} FCFA</td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="footer">
            <p>Document généré le {{ $generatedAt }} | {{ $school->name }} - {{ $school->address }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 