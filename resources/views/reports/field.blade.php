<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport - {{ $field->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 5px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .field-info {
            margin-bottom: 30px;
        }
        .field-info h2 {
            font-size: 18px;
            margin: 0 0 10px;
        }
        .field-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .field-info-table td {
            padding: 5px 10px;
        }
        .field-info-table td:first-child {
            font-weight: bold;
            width: 150px;
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
            background-color: #f2f2f2;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .student-table th, .student-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        .student-table th {
            background-color: #f2f2f2;
        }
        .paid {
            color: #28a745;
        }
        .partial {
            color: #ffc107;
        }
        .unpaid {
            color: #dc3545;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
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

        <div class="stats-section">
            <h3>Statistiques de paiement</h3>
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

        <h3>Liste des étudiants</h3>
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
                    <td>{{ $student->fullName }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone ?? 'N/A' }}</td>
                    <td class="{{ $student->payment_status }}">
                        @if($student->payment_status === 'paid')
                            Payé
                        @elseif($student->payment_status === 'partial')
                            Partiel
                        @else
                            Non payé
                        @endif
                    </td>
                    <td>{{ number_format($student->paid_amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($student->remaining_amount, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Rapport généré le {{ date('d/m/Y à H:i') }} | Système de gestion des frais scolaires</p>
        </div>
    </div>
</body>
</html> 