<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Field;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $fieldId = null;
    protected $studentIds = [];
    protected $school = null;
    protected $paymentStatus = null;

    /**
     * @param int|null $fieldId Filtre par filière
     * @param array|null $studentIds Liste des IDs d'étudiants
     * @param string|null $paymentStatus Statut de paiement (fully_paid, partially_paid, not_paid)
     */
    public function __construct($fieldId = null, $studentIds = null, $paymentStatus = null)
    {
        $this->fieldId = $fieldId;
        $this->studentIds = $studentIds;
        $this->paymentStatus = $paymentStatus;
        $this->school = session('current_school');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Student::with(['field.campus', 'payments']);

        // Filtre par école
        if ($this->school) {
            $query->where('school_id', $this->school->id);
        }

        // Filtre par filière
        if ($this->fieldId) {
            $query->where('field_id', $this->fieldId);
        }

        // Filtre par IDs d'étudiants spécifiques
        if (!empty($this->studentIds)) {
            $query->whereIn('id', $this->studentIds);
        }

        $students = $query->get();

        // Filtre par statut de paiement
        if ($this->paymentStatus) {
            return $students->filter(function($student) {
                if (!$student->field) {
                    return false;
                }
                
                $totalFees = $student->field->fees;
                $totalPaid = $student->payments->sum('amount');
                
                if ($this->paymentStatus === 'fully_paid') {
                    return $totalPaid >= $totalFees && $totalFees > 0;
                } elseif ($this->paymentStatus === 'partially_paid') {
                    return $totalPaid > 0 && $totalPaid < $totalFees;
                } elseif ($this->paymentStatus === 'not_paid') {
                    return $totalPaid == 0;
                }
                
                return true;
            });
        }

        return $students;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nom Complet',
            'Email',
            'Téléphone',
            'Filière',
            'Campus',
            'Frais de scolarité',
            'Montant payé',
            'Reste à payer',
            'Statut de paiement'
        ];
    }

    /**
     * @param mixed $student
     * @return array
     */
    public function map($student): array
    {
        $totalFees = $student->field ? $student->field->fees : 0;
        $totalPaid = $student->payments->sum('amount');
        $remainingAmount = max(0, $totalFees - $totalPaid);
        
        // Déterminer le statut de paiement
        if ($totalFees == 0) {
            $paymentStatus = 'Non applicable';
        } elseif ($remainingAmount == 0) {
            $paymentStatus = $this->school ? $this->school->term('fully_paid', 'Payé intégralement') : 'Payé intégralement';
        } elseif ($totalPaid > 0) {
            $paymentStatus = $this->school ? $this->school->term('partially_paid', 'Partiellement payé') : 'Partiellement payé';
        } else {
            $paymentStatus = $this->school ? $this->school->term('no_payment', 'Aucun paiement') : 'Aucun paiement';
        }

        return [
            $student->id,
            $student->fullName,
            $student->email,
            $student->phone,
            $student->field ? $student->field->name : 'N/A',
            $student->field && $student->field->campus ? $student->field->campus->name : 'N/A',
            number_format($totalFees, 0, ',', ' '),
            number_format($totalPaid, 0, ',', ' '),
            number_format($remainingAmount, 0, ',', ' '),
            $paymentStatus
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        return [];
    }
} 