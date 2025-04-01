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
            border-bottom: 2px solid {{ $currentSchool->primary_color ?? '#3b82f6' }};
        }
        .logo {
            max-width: 80px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: {{ $currentSchool->primary_color ?? '#3b82f6' }};
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
        .campus-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid {{ $currentSchool->primary_color ?? '#3b82f6' }};
        }
        .campus-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: {{ $currentSchool->primary_color ?? '#3b82f6' }};
            margin: 0 0 10px 0;
        }
        .campus-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .campus-details p {
            margin: 5px 0;
            font-size: 12px;
        }
        .summary {
            margin-bottom: 20px;
            background-color: #f8fafc;
            padding: 10px;
            border-radius: 6px;
            border-top: 3px solid {{ $currentSchool->primary_color ?? '#3b82f6' }};
        }
        .summary p {
            margin: 0;
            font-size: 12px;
            color: #4B5563;
        }
        .summary strong {
            color: {{ $currentSchool->primary_color ?? '#3b82f6' }};
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
            background-color: {{ $currentSchool->primary_color ?? '#3b82f6' }};
            color: white;
            font-weight: 600;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody tr:hover {
            background-color: #f3f4f6;
        }
        .amount {
            text-align: right;
            font-family: monospace;
            font-size: 11px;
        }
        .status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
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
            color: #b91c1c;
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
    @if(isset($currentSchool) && $currentSchool->logo)
        <img src="{{ asset('storage/' . $currentSchool->logo) }}" alt="{{ $currentSchool->name ?? 'Logo' }}" class="watermark">
    @endif

    <div class="document-container">
        <div class="official-stamp">Document Officiel</div>
        
        <div class="header">
            @if(isset($currentSchool) && $currentSchool->logo)
                <img src="{{ asset('storage/' . $currentSchool->logo) }}" alt="{{ $currentSchool->name }}" class="logo">
            @endif
            <h1 class="school-name">{{ $currentSchool->name ?? 'École' }}</h1>
            <p class="school-info">
                {{ $currentSchool->address ?? '' }} | 
                <i class="fas fa-envelope"></i> {{ $currentSchool->contact_email ?? '' }} | 
                <i class="fas fa-phone"></i> {{ $currentSchool->contact_phone ?? '' }}
            </p>
        </div>

        <h2 class="title">{{ $title }}</h2>

        <div class="campus-info">
            <h3><i class="fas fa-building"></i> Informations du campus</h3>
            <div class="campus-details">
                <p><strong>Nom:</strong> {{ $campus->name }}</p>
                <p><strong>Nombre de filières:</strong> {{ $campus->fields->count() }}</p>
                <p><strong>Total étudiants:</strong> {{ $campus->fields->sum(function($field) { return $field->students->count(); }) }}</p>
            </div>
        </div>

        <div class="summary">
            <p><i class="fas fa-info-circle"></i> {{ $description }} - Total: <strong>{{ $students->count() }}</strong> étudiants</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="20%">Nom complet</th>
                    <th width="15%">Filière</th>
                    <th width="15%">Email</th>
                    <th width="10%">Téléphone</th>
                    <th width="10%">Montant payé</th>
                    <th width="10%">Reste à payer</th>
                    <th width="10%">Statut</th>
                </tr>
            </thead>
            <tbody>
                @if($students->count() > 0)
                    @foreach($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $student->fullName }}</strong></td>
                        <td>{{ $student->field_name ?? 'N/A' }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone ?? 'N/A' }}</td>
                        <td class="amount">{{ number_format($student->paid_amount, 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($student->remaining_amount, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($student->paid_amount >= ($student->field_fees ?? 0))
                                <span class="status status-paid">Payé</span>
                            @elseif($student->paid_amount > 0)
                                <span class="status status-partial">Partiel</span>
                            @else
                                <span class="status status-unpaid">Non payé</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" style="text-align: center;">
                            <p style="margin: 20px 0; color: #6B7280;">Aucun étudiant trouvé</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        
        <div class="footer">
            <p>{{ $currentSchool->name ?? 'École' }} - Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 