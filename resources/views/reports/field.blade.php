<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport - {{ $field->name }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
            position: relative;
            margin: 0;
            padding: 20px;
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
        .container {
            width: 100%;
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #0d47a1;
        }
        .school-logo {
            max-width: 80px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 22px;
            margin: 0 0 5px;
            color: #0d47a1;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
            color: #0d47a1;
        }
        .school-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
        }
        .field-info {
            margin-bottom: 30px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #0d47a1;
        }
        .field-info h2 {
            font-size: 18px;
            margin: 0 0 15px;
            color: #0d47a1;
        }
        .field-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .field-info-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
        }
        .field-info-table tr:last-child td {
            border-bottom: none;
        }
        .field-info-table td:first-child {
            font-weight: bold;
            width: 180px;
            color: #555;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #0d47a1;
            color: #0d47a1;
        }
        .stats-section {
            margin-bottom: 30px;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .stats-table th, .stats-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .stats-table th {
            background-color: #f5f5f5;
            color: #333;
        }
        .stats-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .stats-table tr:last-child td {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .student-table th, .student-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .student-table th {
            background-color: #0d47a1;
            color: white;
        }
        .student-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .student-table tr:hover {
            background-color: #f3f4f6;
        }
        .status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .partial {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .unpaid {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .amount {
            text-align: right;
            font-family: monospace;
        }
        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .generated-by {
            font-size: 9px;
            font-style: italic;
            color: #999;
            text-align: center;
            margin-top: 5px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Filigrane avec le logo de l'école -->
    @if(isset($field->school) && isset($field->school->logo))
        <img src="{{ public_path('storage/' . $field->school->logo) }}" alt="{{ $field->school->name }}" class="watermark">
    @endif

    <div class="container">
        <div class="document-border"></div>
        <div class="official-stamp">Document Officiel</div>

        <div class="header">
            @if(isset($field->school) && isset($field->school->logo))
                <img src="{{ public_path('storage/' . $field->school->logo) }}" alt="{{ $field->school->name }}" class="school-logo">
            @endif
            
            @if(isset($field->school))
                <div class="school-name">{{ $field->school->name }}</div>
                <div class="school-info">
                    {{ $field->school->address }}<br>
                    Email: {{ $field->school->contact_email ?? $field->school->email }} | 
                    Tél: {{ $field->school->contact_phone ?? $field->school->phone }}
                </div>
            @endif
            
            <h1>RAPPORT DE FILIÈRE</h1>
            <p>Généré le {{ date('d/m/Y à H:i') }}</p>
        </div>
        
        <div class="field-info">
            <h2>{{ $field->name }}</h2>
            <table class="field-info-table">
                <tr>
                    <td>Campus:</td>
                    <td>{{ $field->campus->name }}</td>
                </tr>
                @if($field->educationLevel)
                <tr>
                    <td>Niveau d'éducation:</td>
                    <td>{{ $field->educationLevel->name }}</td>
                </tr>
                @endif
                <tr>
                    <td>Frais de scolarité:</td>
                    <td>{{ number_format($field->fees, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td>Nombre d'étudiants:</td>
                    <td>{{ $totalStudents }}</td>
                </tr>
                @if($field->code)
                <tr>
                    <td>Code de la filière:</td>
                    <td>{{ $field->code }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="section-title">Statistiques de paiement</div>

        <div class="stats-section">
            <table class="stats-table">
                <tr>
                    <th>Catégorie</th>
                    <th>Nombre</th>
                    <th>Pourcentage</th>
                </tr>
                <tr>
                    <td>Payé intégralement</td>
                    <td>{{ $paidCount }}</td>
                    <td>{{ $totalStudents > 0 ? round(($paidCount / $totalStudents) * 100) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Partiellement payé</td>
                    <td>{{ $partialCount }}</td>
                    <td>{{ $totalStudents > 0 ? round(($partialCount / $totalStudents) * 100) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Aucun paiement</td>
                    <td>{{ $unpaidCount }}</td>
                    <td>{{ $totalStudents > 0 ? round(($unpaidCount / $totalStudents) * 100) : 0 }}%</td>
                </tr>
                <tr>
                    <td><strong>Montant total des frais</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalFees, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                <tr>
                    <td><strong>Montant total payé</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalPaid, 0, ',', ' ') }} FCFA ({{ $paymentPercentage }}%)</strong></td>
                </tr>
                <tr>
                    <td><strong>Montant restant</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalFees - $totalPaid, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            </table>
        </div>

        <div class="section-title">Liste des étudiants</div>
        
        <table class="student-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Montant payé</th>
                    <th>Reste à payer</th>
                </tr>
            </thead>
            <tbody>
                @foreach($field->students as $student)
                <tr>
                    <td><strong>{{ $student->fullName }}</strong></td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone ?? 'N/A' }}</td>
                    <td>
                        @if($student->payment_status === 'paid')
                            <span class="status paid">Payé</span>
                        @elseif($student->payment_status === 'partial')
                            <span class="status partial">Partiel</span>
                        @else
                            <span class="status unpaid">Non payé</span>
                        @endif
                    </td>
                    <td class="amount">{{ number_format($student->paid_amount, 0, ',', ' ') }} FCFA</td>
                    <td class="amount">{{ number_format($student->remaining_amount, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>{{ isset($field->school) ? $field->school->name : 'École' }} - Rapport généré le {{ date('d/m/Y à H:i') }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>
    </div>
</body>
</html> 