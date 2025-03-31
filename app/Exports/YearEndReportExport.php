<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class YearEndReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $schoolId;
    protected $year;

    public function __construct($schoolId, $year)
    {
        $this->schoolId = $schoolId;
        $this->year = $year;
    }

    public function collection()
    {
        return Student::where('school_id', $this->schoolId)
            ->with(['payments', 'invoices'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Numéro d\'identification',
            'Nom complet',
            'Campus',
            'Filière',
            'Montant total à payer',
            'Montant total versé',
            'Montant restant à verser',
            'Statut de paiement',
            'Dernier paiement',
            'Nombre de paiements',
            'Email',
            'Téléphone',
            'Date d\'inscription'
        ];
    }

    public function map($student): array
    {
        $totalInvoiced = $student->invoices->sum('amount');
        $totalPaid = $student->payments->sum('amount');
        $remainingAmount = $totalInvoiced - $totalPaid;
        $paymentStatus = ($remainingAmount <= 0) ? 'Payé' : ($totalPaid > 0 ? 'Partiellement payé' : 'Non payé');
        
        $lastPayment = $student->payments->sortByDesc('date')->first();
        $lastPaymentDate = $lastPayment ? $lastPayment->date->format('d/m/Y') : 'N/A';
        
        return [
            $student->student_id,
            $student->name,
            optional($student->campus)->name ?? 'N/A',
            optional($student->field)->name ?? 'N/A',
            number_format($totalInvoiced, 0, ',', ' ') . ' FCFA',
            number_format($totalPaid, 0, ',', ' ') . ' FCFA',
            number_format($remainingAmount, 0, ',', ' ') . ' FCFA',
            $paymentStatus,
            $lastPaymentDate,
            $student->payments->count(),
            $student->email,
            $student->phone,
            $student->created_at->format('d/m/Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 