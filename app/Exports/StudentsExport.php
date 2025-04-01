<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Field;
use App\Models\Campus;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsExport implements FromArray, WithStyles, ShouldAutoSize, WithEvents
{
    protected $fieldId = null;
    protected $studentIds = [];
    protected $school = null;
    protected $paymentStatus = null;
    protected $headings = [];
    protected $headerRows = [];

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
        $this->headings = [
            'ID',
            'Nom Complet',
            'Email',
            'Téléphone',
            'Filière',
            'Frais de scolarité',
            'Montant payé',
            'Reste à payer',
            'Statut de paiement'
        ];
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $students = $this->getStudents();
        $data = [];
        $this->headerRows = [];
        
        // Grouper les étudiants par campus
        $campusGroups = $students->groupBy(function($student) {
            return $student->field && $student->field->campus ? $student->field->campus->id : 'Sans campus';
        });
        
        $rowCounter = 1; // Commencer après la ligne d'en-tête
        
        foreach($campusGroups as $campusId => $campusStudents) {
            // Nom du campus
            $campusName = 'Sans campus';
            
            if ($campusId !== 'Sans campus') {
                $campus = Campus::find($campusId);
                if ($campus) {
                    $campusName = $campus->name;
                }
            }
            
            // Ajouter l'en-tête du campus
            $data[] = [
                "CAMPUS: {$campusName}",
                "", "", "", "", "", "", "", ""
            ];
            $this->headerRows[] = $rowCounter;
            $rowCounter++;
            
            // Ajouter l'entête des colonnes
            $data[] = $this->headings;
            $rowCounter++;
            
            // Grouper les étudiants par filière dans ce campus
            $fieldGroups = $campusStudents->groupBy(function($student) {
                return $student->field ? $student->field->id : 'Sans filière';
            });
            
            foreach($fieldGroups as $fieldId => $fieldStudents) {
                // Nom de la filière
                $fieldName = 'Sans filière';
                
                if ($fieldId !== 'Sans filière') {
                    $field = Field::find($fieldId);
                    if ($field) {
                        $fieldName = $field->name;
                    }
                }
                
                // Ajouter l'en-tête de la filière
                $data[] = [
                    "FILIÈRE: {$fieldName}",
                    "", "", "", "", "", "", "", ""
                ];
                $this->headerRows[] = $rowCounter;
                $rowCounter++;
                
                // Ajouter les étudiants de cette filière
                foreach($fieldStudents as $student) {
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
                    
                    $data[] = [
                        $student->id,
                        $student->fullName,
                        $student->email,
                        $student->phone,
                        $student->field ? $student->field->name : 'N/A',
                        number_format($totalFees, 0, ',', ' '),
                        number_format($totalPaid, 0, ',', ' '),
                        number_format($remainingAmount, 0, ',', ' '),
                        $paymentStatus
                    ];
                    $rowCounter++;
                }
                
                // Ajouter une ligne vide après chaque filière
                $data[] = ["", "", "", "", "", "", "", "", ""];
                $rowCounter++;
            }
            
            // Ajouter une ligne vide après chaque campus
            $data[] = ["", "", "", "", "", "", "", "", ""];
            $rowCounter++;
        }
        
        return $data;
    }
    
    /**
     * Récupère les étudiants selon les filtres
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getStudents() 
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
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style pour les en-têtes de colonnes (première ligne)
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);
        
        // Appliquer des styles à chaque en-tête de campus et filière
        foreach ($this->headerRows as $row) {
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB'],
                ],
            ]);
            
            // Fusionner les cellules pour l'en-tête
            $sheet->mergeCells("A{$row}:I{$row}");
            
            // Alignement à gauche pour les en-têtes
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }

        return [];
    }
    
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Obtenir la feuille
                $sheet = $event->sheet->getDelegate();
                
                // Ajouter des bordures autour des groupes
                foreach ($this->headerRows as $index => $row) {
                    // Vérifier s'il s'agit d'un en-tête de campus (chaque premier en-tête est un campus)
                    if ($index % 2 == 0) {
                        // Appliquer un style de police différent pour les campus
                        $sheet->getStyle("A{$row}")->getFont()->setSize(14);
                    }
                }
                
                // Ajuster automatiquement la largeur des colonnes
                for ($i = 'A'; $i <= 'I'; $i++) {
                    $sheet->getColumnDimension($i)->setAutoSize(true);
                }
            },
        ];
    }
} 