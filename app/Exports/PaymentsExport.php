<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $studentId = null;
    protected $studentIds = [];
    protected $school = null;
    protected $remainingAmounts = [];
    
    public function __construct($studentId = null, $studentIds = [], $remainingAmounts = [])
    {
        $this->studentId = $studentId;
        $this->studentIds = $studentIds;
        $this->school = session('current_school');
        
        // Si un seul étudiant avec un montant restant
        if ($studentId && !is_array($remainingAmounts)) {
            $this->remainingAmounts = [$studentId => $remainingAmounts];
        } else {
            $this->remainingAmounts = $remainingAmounts;
        }
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Payment::with(['student.field.campus']);
        
        if ($this->studentId) {
            // Exporter pour un étudiant spécifique
            $query->where('student_id', $this->studentId);
        } elseif (!empty($this->studentIds)) {
            // Exporter pour tous les étudiants de l'école actuelle
            $query->whereIn('student_id', $this->studentIds);
        }
        
        return $query->latest('payment_date')->get();
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        // Utiliser la terminologie de l'école si disponible
        if ($this->school) {
            return [
                'ID',
                $this->school->term('receipt_number', 'Reçu N°'),
                $this->school->term('student', 'Étudiant'),
                $this->school->term('field', 'Filière'),
                $this->school->term('campus', 'Campus'),
                $this->school->term('amount', 'Montant'),
                $this->school->term('total_fees', 'Frais totaux'),
                $this->school->term('total_paid', 'Montant payé'),
                $this->school->term('remaining_amount', 'Reste à payer'),
                $this->school->term('description', 'Description'),
                $this->school->term('payment_method', 'Méthode de paiement'),
                $this->school->term('payment_date', 'Date de paiement'),
                $this->school->term('notes', 'Notes'),
                $this->school->term('created_at', 'Créé le')
            ];
        }
        
        // Valeurs par défaut si l'école n'est pas disponible
        return [
            'ID',
            'Reçu N°',
            'Étudiant',
            'Filière',
            'Campus',
            'Montant',
            'Frais totaux',
            'Montant payé',
            'Reste à payer',
            'Description',
            'Méthode de paiement',
            'Date de paiement',
            'Notes',
            'Créé le'
        ];
    }
    
    /**
     * @param Payment $payment
     * @return array
     */
    public function map($payment): array
    {
        $student = $payment->student;
        $totalFees = $student->field->fees ?? 0;
        
        // Calculer le total payé et le reste à payer
        $totalPaid = $student->payments->sum('amount');
        $remainingAmount = $this->remainingAmounts[$payment->student_id] ?? max(0, $totalFees - $totalPaid);
        
        return [
            $payment->id,
            $payment->receipt_number,
            $payment->student->fullName ?? 'N/A',
            $payment->student->field->name ?? 'N/A',
            $payment->student->field->campus->name ?? 'N/A',
            $payment->amount,
            $totalFees,
            $totalPaid,
            $remainingAmount,
            $payment->description,
            $payment->payment_method ?? 'N/A',
            $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A',
            $payment->notes,
            $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : 'N/A'
        ];
    }
    
    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Appliquer des styles personnalisés basés sur la couleur de l'école
        if ($this->school && $this->school->theme_color) {
            $colorHex = $this->school->theme_color;
            
            // Convertir la couleur hexadécimale en composantes RGB
            $rgb = $this->hexToRgb($colorHex);
            
            return [
                1 => [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => substr($colorHex, 1)] // Enlever le # du début
                    ]
                ],
            ];
        }
        
        // Style par défaut si aucune école n'est définie
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
    
    /**
     * Convertit une couleur hexadécimale en composantes RGB
     * 
     * @param string $hex
     * @return array
     */
    private function hexToRgb($hex) {
        // Supprimer le # si présent
        $hex = str_replace('#', '', $hex);
        
        // Convertir en composantes RGB
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
