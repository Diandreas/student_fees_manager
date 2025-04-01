<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
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
        }
        .header h1 {
            color: #4F46E5;
            font-size: 16px;
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
            margin: 15px 0 10px;
            border-radius: 5px;
        }
        .field-title {
            background-color: #E5E7EB;
            padding: 6px;
            font-size: 13px;
            margin: 10px 0 5px;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f1f5f9;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 5px;
            font-size: 10px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
        .page-break {
            page-break-after: always;
        }
        .payment-fully {
            color: #16a34a;
            font-weight: bold;
        }
        .payment-partial {
            color: #ca8a04;
            font-weight: bold;
        }
        .payment-none {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($school->logo)
            <img src="{{ public_path('storage/' . $school->logo) }}" height="50" alt="{{ $school->name }}">
        @endif
        <h1>{{ $title }}</h1>
        <div class="school-info">
            {{ $school->name }} | {{ $school->address }} | {{ $school->phone }}
        </div>
        <div>
            Généré le: {{ $generatedAt }}
        </div>
    </div>

    @foreach($campusData as $campus)
        <div class="campus-title">
            CAMPUS: {{ $campus['name'] }}
        </div>

        @foreach($campus['fields'] as $field)
            <div class="field-title">
                FILIÈRE: {{ $field['name'] }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Frais</th>
                        <th>Payé</th>
                        <th>Reste</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($field['students'] as $studentData)
                        @php
                            $student = $studentData['student'];
                            $paymentStatus = $studentData['paymentStatus'];
                            $statusClass = 'payment-none';
                            
                            if ($studentData['remainingAmount'] == 0) {
                                $statusClass = 'payment-fully';
                            } elseif ($studentData['totalPaid'] > 0) {
                                $statusClass = 'payment-partial';
                            }
                        @endphp
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->fullName }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>{{ number_format($studentData['totalFees'], 0, ',', ' ') }}</td>
                            <td>{{ number_format($studentData['totalPaid'], 0, ',', ' ') }}</td>
                            <td>{{ number_format($studentData['remainingAmount'], 0, ',', ' ') }}</td>
                            <td class="{{ $statusClass }}">{{ $paymentStatus }}</td>
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
        {{ $school->name }} - {{ date('Y') }} | Tous droits réservés
    </div>
</body>
</html> 