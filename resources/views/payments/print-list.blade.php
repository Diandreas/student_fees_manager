<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($student) ? 'Paiements de ' . $student->full_name : 'Liste des paiements' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            color: #374151;
            background-color: #f9fafb;
            line-height: 1.5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
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
            border-radius: 8px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
            margin: 0;
        }
        .school-details {
            color: #6B7280;
            font-size: 13px;
        }
        .title {
            font-size: 22px;
            font-weight: 600;
            margin: 20px 0;
            color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
            text-transform: uppercase;
        }
        .student-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid {{ $currentSchool->theme_color ?? '#0d47a1' }};
        }
        .student-info h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
            font-size: 18px;
            font-weight: 600;
        }
        .student-info p {
            margin: 8px 0;
            color: #4B5563;
        }
        .payment-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
        }
        .summary-box {
            flex: 1;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .total-fees {
            background-color: #EFF6FF;
            border: 1px solid #DBEAFE;
        }
        .total-paid {
            background-color: #ECFDF5;
            border: 1px solid #D1FAE5;
        }
        .remaining {
            background-color: #FEF3C7;
            border: 1px solid #FDE68A;
        }
        .summary-box h4 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 500;
            color: #4B5563;
        }
        .summary-box p {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        .total-fees p {
            color: #1E40AF;
        }
        .total-paid p {
            color: #047857;
        }
        .remaining p {
            color: #B45309;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        th, td {
            border: 1px solid #E5E7EB;
            padding: 12px 16px;
            text-align: left;
        }
        th {
            background-color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
            color: white;
            font-weight: 600;
            white-space: nowrap;
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
        .date {
            white-space: nowrap;
        }
        .receipt-number {
            font-family: monospace;
            background-color: #F3F4F6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 12px;
            color: #6B7280;
        }
        .timestamp {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 15px;
        }
        .no-print {
            margin-top: 30px;
            text-align: center;
        }
        .print-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: {{ $currentSchool->theme_color ?? '#0d47a1' }};
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .print-btn:hover {
            background-color: #0D47A1;
        }
        .close-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #6B7280;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            margin-left: 10px;
            transition: background-color 0.2s;
        }
        .close-btn:hover {
            background-color: #4B5563;
        }
        .btn-icon {
            margin-right: 6px;
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
                padding: 15px;
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
                    <p class="school-details">{{ $currentSchool->address ?? '' }}<br>
                        <i class="fas fa-envelope"></i> {{ $currentSchool->contact_email ?? '' }} | <i class="fas fa-phone"></i> {{ $currentSchool->contact_phone ?? '' }}</p>
                </div>
                @if(isset($currentSchool) && $currentSchool->getLogoUrlAttribute())
                <img src="{{ $currentSchool->getLogoUrlAttribute() }}" alt="{{ $currentSchool->name }}" class="logo">
                @endif
            </div>
            <h2 class="title">
                @if(isset($student))
                    <i class="fas fa-file-invoice-dollar"></i> Paiements de {{ $student->full_name }}
                @else
                    <i class="fas fa-file-invoice-dollar"></i> Liste des paiements
                @endif
            </h2>
        </div>

        @if(isset($student))
            <div class="student-info">
                <h3><i class="fas fa-user-graduate"></i> Informations sur l'étudiant</h3>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px; margin-right: 20px;">
                        <p><strong>Nom :</strong> {{ $student->full_name }}</p>
                        <p><strong>Identifiant :</strong> {{ $student->student_id ?? 'N/A' }}</p>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <p><strong>Filière :</strong> {{ $student->field->name ?? 'N/A' }}</p>
                        <p><strong>Campus :</strong> {{ $student->field->campus->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="payment-summary">
                <div class="summary-box total-fees">
                    <h4><i class="fas fa-tag"></i> Frais totaux</h4>
                    <p>{{ number_format($totalFees, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="summary-box total-paid">
                    <h4><i class="fas fa-check-circle"></i> Total payé</h4>
                    <p>{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="summary-box remaining">
                    <h4><i class="fas fa-hourglass-half"></i> Reste à payer</h4>
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
                        <td><span class="receipt-number">{{ $payment->receipt_number ?? 'N/A' }}</span></td>
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
                        <td colspan="{{ isset($student) ? 5 : 7 }}" style="text-align: center; padding: 30px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 24px; color: #9CA3AF; margin-bottom: 10px;"></i>
                            <p style="margin: 0; color: #6B7280;">Aucun paiement trouvé</p>
                        </td>
                    </tr>
                @endif
                @if($payments->count() > 0)
                    <tr>
                        <td colspan="{{ isset($student) ? 6 : 6 }}" style="text-align: right; font-weight: bold; background-color: #F3F4F6;">Total</td>
                        <td class="amount" style="background-color: #F3F4F6;">{{ number_format($payments->sum('amount'), 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="footer">
            <div class="timestamp">
                <span><i class="fas fa-calendar-alt"></i> Document généré le {{ now()->format('d/m/Y à H:i') }}</span>
                <span>Page 1/1</span>
            </div>
            <p>{{ $currentSchool->name ?? 'École' }} - Tous droits réservés &copy; {{ date('Y') }}</p>
        </div>

        <div class="no-print">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print btn-icon"></i> Imprimer ce document
            </button>
            <button onclick="window.close()" class="close-btn">
                <i class="fas fa-times btn-icon"></i> Fermer
            </button>
        </div>
    </div>
</body>
</html> 