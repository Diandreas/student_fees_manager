<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($student) ? 'Paiements de ' . $student->fullName : 'Liste des paiements' }}</title>
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
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            pointer-events: none;
            width: 60%;
            max-width: 500px;
            height: auto;
            z-index: 0;
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
        .document-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 1px solid {{ $currentSchool->primary_color ?? '#0d47a1' }};
            opacity: 0.2;
            pointer-events: none;
            border-radius: 6px;
            z-index: 0;
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
        .student-info {
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid {{ $currentSchool->primary_color ?? '#0d47a1' }};
            display: flex;
            justify-content: space-between;
        }
        .student-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: {{ $currentSchool->primary_color ?? '#0d47a1' }};
            margin: 0 0 8px 0;
        }
        .student-details, .field-details {
            flex: 1;
        }
        .student-details p, .field-details p {
            margin: 4px 0;
            font-size: 12px;
        }
        .payment-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }
        .summary-box {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
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
            margin: 0 0 5px 0;
            font-size: 12px;
            font-weight: 500;
            color: #4B5563;
        }
        .summary-box p {
            font-size: 16px;
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
        .date {
            white-space: nowrap;
        }
        .receipt-number {
            font-family: monospace;
            background-color: #F3F4F6;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            font-size: 10px;
            color: #6B7280;
        }
        .generated-by {
            text-align: center;
            font-size: 9px;
            font-style: italic;
            color: #9CA3AF;
            margin-top: 5px;
        }
        .timestamp {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #9CA3AF;
            margin-top: 8px;
        }
        .no-print {
            margin-top: 20px;
            text-align: center;
        }
        .print-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background-color: {{ $currentSchool->primary_color ?? '#0d47a1' }};
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            transition: background-color 0.2s;
        }
        .print-btn:hover {
            background-color: #0D47A1;
        }
        .close-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background-color: #6B7280;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            margin-left: 10px;
            transition: background-color 0.2s;
        }
        .close-btn:hover {
            background-color: #4B5563;
        }
        .btn-icon {
            margin-right: 6px;
        }
        .official-document {
            position: absolute;
            top: 40%;
            right: 3%;
            transform: rotate(45deg);
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: rgba(107, 114, 128, 0.1);
            border: 3px solid rgba(107, 114, 128, 0.1);
            padding: 5px 10px;
            pointer-events: none;
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
            .no-print {
                display: none;
            }
            .watermark {
                opacity: 0.03;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .official-document {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Filigrane avec le logo de l'école -->
    @if(isset($currentSchool) && $currentSchool->logo)
        <img src="{{ asset('storage/' . $currentSchool->logo) }}" alt="{{ $currentSchool->name ?? 'Logo' }}" class="watermark">
    @endif

    <div class="container">
        <div class="document-border"></div>
        <div class="official-document">Document officiel</div>
        
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
                @if(isset($student))
                    <i class="fas fa-file-invoice-dollar"></i> Paiements<br>{{ $student->fullName }}
                @else
                    <i class="fas fa-file-invoice-dollar"></i> Liste des paiements
                @endif
            </h2>
        </div>

        @if(isset($student))
            <div class="student-info">
                <div class="student-details">
                    <h3><i class="fas fa-user-graduate"></i> Informations étudiant</h3>
                    <p><strong>Nom:</strong> {{ $student->fullName }}</p>
                    <p><strong>ID:</strong> {{ $student->student_id ?? 'N/A' }}</p>
                </div>
                <div class="field-details">
                    <h3><i class="fas fa-graduation-cap"></i> Formation</h3>
                    <p><strong>Filière:</strong> {{ $student->field->name ?? 'N/A' }}</p>
                    <p><strong>Campus:</strong> {{ $student->field->campus->name ?? 'N/A' }}</p>
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
                        <td>{{ $payment->student->fullName ?? 'N/A' }}</td>
                        <td>{{ $payment->student->field->name ?? 'N/A' }}</td>
                        @endif
                        <td>{{ $payment->description }}</td>
                        <td class="date">{{ $payment->payment_date ? Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}</td>
                        <td class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="{{ isset($student) ? 6 : 6 }}" style="text-align: right; font-weight: bold; background-color: #F3F4F6;">Total</td>
                        <td class="amount" style="background-color: #F3F4F6; color: #047857;">{{ number_format($payments->sum('amount'), 0, ',', ' ') }} FCFA</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="{{ isset($student) ? 5 : 7 }}" style="text-align: center; padding: 20px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 18px; color: #9CA3AF; margin-bottom: 8px;"></i>
                            <p style="margin: 0; color: #6B7280;">Aucun paiement trouvé</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="footer">
            <p>{{ isset($currentSchool) ? $currentSchool->name : 'École' }} - Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
            <div class="generated-by">Généré par ScolarPay</div>
        </div>

        <div class="no-print">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print btn-icon"></i> Imprimer
            </button>
            <button onclick="window.close()" class="close-btn">
                <i class="fas fa-times btn-icon"></i> Fermer
            </button>
        </div>
    </div>
</body>
</html> 