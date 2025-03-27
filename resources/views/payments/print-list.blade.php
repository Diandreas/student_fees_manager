<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($student) ? 'Paiements de ' . $student->full_name : 'Liste des paiements' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid {{ $currentSchool->theme_color ?? '#0d47a1' }};
            padding-bottom: 20px;
        }
        .school-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 100px;
            max-height: 100px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
            margin: 0;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
        }
        .student-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .student-info h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
        }
        .student-info p {
            margin: 5px 0;
        }
        .payment-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-box {
            flex: 1;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 0 5px;
        }
        .total-fees {
            background-color: #e3f2fd;
        }
        .total-paid {
            background-color: #e8f5e9;
        }
        .remaining {
            background-color: #fff3e0;
        }
        .summary-box h4 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .summary-box p {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .date {
            white-space: nowrap;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .no-print {
            margin-top: 30px;
            text-align: center;
        }
        @media print {
            body {
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="school-info">
                <div>
                    <h1 class="school-name">{{ $currentSchool->name ?? 'École' }}</h1>
                    <p>{{ $currentSchool->address ?? '' }}</p>
                    <p>Email: {{ $currentSchool->contact_email ?? '' }} | Tel: {{ $currentSchool->contact_phone ?? '' }}</p>
                </div>
                @if(isset($currentSchool) && $currentSchool->getLogoUrlAttribute())
                <img src="{{ $currentSchool->getLogoUrlAttribute() }}" alt="{{ $currentSchool->name }}" class="logo">
                @endif
            </div>
            <h2 class="title">
                @if(isset($student))
                    Paiements de {{ $student->full_name }}
                @else
                    Liste des paiements
                @endif
            </h2>
        </div>

        @if(isset($student))
            <div class="student-info">
                <h3>Informations sur l'étudiant</h3>
                <p><strong>Nom :</strong> {{ $student->full_name }}</p>
                <p><strong>Identifiant :</strong> {{ $student->student_id ?? 'N/A' }}</p>
                <p><strong>Filière :</strong> {{ $student->field->name ?? 'N/A' }}</p>
                <p><strong>Campus :</strong> {{ $student->field->campus->name ?? 'N/A' }}</p>
            </div>

            <div class="payment-summary">
                <div class="summary-box total-fees">
                    <h4>Frais totaux</h4>
                    <p>{{ number_format($totalFees, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="summary-box total-paid">
                    <h4>Total payé</h4>
                    <p>{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="summary-box remaining">
                    <h4>Reste à payer</h4>
                    <p>{{ number_format($remainingAmount, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reçu N°</th>
                    @if(!isset($student))
                    <th>Étudiant</th>
                    <th>Filière</th>
                    @endif
                    <th>Description</th>
                    <th>Date</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @if($payments->count() > 0)
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->receipt_number ?? 'N/A' }}</td>
                        @if(!isset($student))
                        <td>{{ $payment->student->full_name ?? 'N/A' }}</td>
                        <td>{{ $payment->student->field->name ?? 'N/A' }}</td>
                        @endif
                        <td>{{ $payment->description }}</td>
                        <td class="date">{{ $payment->payment_date ? Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}</td>
                        <td class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ isset($student) ? 5 : 7 }}" class="text-center">Aucun paiement trouvé</td>
                    </tr>
                @endif
                @if($payments->count() > 0)
                    <tr>
                        <td colspan="{{ isset($student) ? 6 : 6 }}" class="text-right"><strong>Total</strong></td>
                        <td class="amount">{{ number_format($payments->sum('amount'), 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="footer">
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>{{ $currentSchool->name ?? 'École' }} - Tous droits réservés &copy; {{ date('Y') }}</p>
        </div>

        <div class="no-print">
            <button onclick="window.print()" style="padding: 10px 20px; background-color: {{ $currentSchool->theme_color ?? '#0d47a1' }}; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Imprimer ce document
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px; cursor: pointer;">
                Fermer
            </button>
        </div>
    </div>
</body>
</html> 