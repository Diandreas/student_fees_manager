<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $field->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 12px;
            color: #374151;
            background-color: #f9fafb;
            line-height: 1.4;
            position: relative;
        }
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 40%;
            background-image: url("{{ $currentSchool->logo_url ?? '' }}");
            opacity: 0.05;
            z-index: -1;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid {{ $currentSchool->primary_color ?? '#0d47a1' }};
            padding-bottom: 10px;
        }
        .school-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo {
            max-width: 60px;
            max-height: 60px;
            border-radius: 6px;
        }
        .school-details {
            flex: 1;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: {{ $currentSchool->primary_color ?? '#0d47a1' }};
            margin: 0 0 3px 0;
        }
        .contact-info {
            color: #6B7280;
            font-size: 11px;
            margin: 0;
        }
        .document-title {
            text-align: right;
            font-size: 16px;
            font-weight: 600;
            color: {{ $currentSchool->primary_color ?? '#0d47a1' }};
            text-transform: uppercase;
            margin: 0;
        }
        .field-info {
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid {{ $currentSchool->primary_color ?? '#0d47a1' }};
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .field-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: {{ $currentSchool->primary_color ?? '#0d47a1' }};
            margin: 0 0 8px 0;
            width: 100%;
        }
        .field-details, .campus-details {
            flex: 1;
            min-width: 200px;
        }
        .field-details p, .campus-details p {
            margin: 4px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #E5E7EB;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: {{ $currentSchool->primary_color ?? '#0d47a1' }};
            color: white;
            font-weight: 600;
            white-space: nowrap;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        tbody tr:hover {
            background-color: #F3F4F6;
        }
        .amount {
            text-align: right;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }
        .status-paid {
            color: #047857;
            font-weight: 600;
        }
        .status-partial {
            color: #B45309;
            font-weight: 600;
        }
        .status-unpaid {
            color: #DC2626;
            font-weight: 600;
        }
        .summary {
            margin-bottom: 15px;
            background-color: #EFF6FF;
            border: 1px solid #DBEAFE;
            padding: 10px;
            border-radius: 6px;
        }
        .summary p {
            margin: 0;
            font-size: 13px;
            color: #1E40AF;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            font-size: 10px;
            color: #6B7280;
        }
        .timestamp {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #9CA3AF;
            margin-top: 8px;
        }
        @media print {
            body {
                padding: 0;
                background-color: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .container {
                box-shadow: none;
                max-width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="school-info">
                @if(isset($currentSchool) && $currentSchool->logo)
                <img src="{{ $currentSchool->logo_url }}" alt="{{ $currentSchool->name }}" class="logo">
                @endif
                <div class="school-details">
                    <h1 class="school-name">{{ $currentSchool->name ?? 'École' }}</h1>
                    <p class="contact-info">
                        {{ $currentSchool->address ?? '' }} <br>
                        <i class="fas fa-envelope"></i> {{ $currentSchool->contact_email ?? '' }} | <i class="fas fa-phone"></i> {{ $currentSchool->contact_phone ?? '' }}
                    </p>
                </div>
            </div>
            <h2 class="document-title">
                <i class="fas fa-list"></i> {{ $title }} <br>{{ $field->name }}
            </h2>
        </div>

        <div class="field-info">
            <h3><i class="fas fa-graduation-cap"></i> Informations de la filière</h3>
            <div class="field-details">
                <p><strong>Nom:</strong> {{ $field->name }}</p>
                <p><strong>Frais:</strong> {{ number_format($field->fees, 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="campus-details">
                <p><strong>Campus:</strong> {{ $field->campus->name }}</p>
                @if($field->educationLevel)
                <p><strong>Niveau:</strong> {{ $field->educationLevel->name }}</p>
                @endif
            </div>
        </div>

        <div class="summary">
            <p><i class="fas fa-info-circle"></i> {{ $description }} - Total: <strong>{{ $students->count() }}</strong> étudiants</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Montant payé</th>
                    <th>Reste à payer</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @if($students->count() > 0)
                    @foreach($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->fullName }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone ?? 'N/A' }}</td>
                        <td class="amount">{{ number_format($student->paid_amount, 0, ',', ' ') }} FCFA</td>
                        <td class="amount">{{ number_format($student->remaining_amount, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($student->paid_amount >= $field->fees)
                                <span class="status-paid">Payé</span>
                            @elseif($student->paid_amount > 0)
                                <span class="status-partial">Partiel</span>
                            @else
                                <span class="status-unpaid">Non payé</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 18px; color: #9CA3AF; margin-bottom: 8px;"></i>
                            <p style="margin: 0; color: #6B7280;">Aucun étudiant trouvé</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="footer">
            <p>{{ $currentSchool->name ?? 'École' }} - Tous droits réservés &copy; {{ date('Y') }}</p>
            <div class="timestamp">
                <span><i class="fas fa-calendar-alt"></i> Document généré le {{ now()->format('d/m/Y à H:i') }}</span>
                <span>Page 1/1</span>
            </div>
        </div>
    </div>
</body>
</html> 