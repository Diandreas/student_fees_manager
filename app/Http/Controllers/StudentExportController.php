<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Models\Field;
use App\Models\Student;

class StudentExportController extends Controller
{
    /**
     * Exporte la liste des étudiants au format Excel
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        try {
            $school = session('current_school');
            
            if (!$school) {
                return redirect()->route('schools.index')
                    ->with('error', 'Veuillez sélectionner une école pour exporter la liste des étudiants.');
            }
            
            $paymentStatus = $request->query('payment_status');
            $fieldId = $request->query('field_id');
            $filename = 'etudiants_groupes_';

            if ($paymentStatus) {
                if ($paymentStatus === 'fully_paid') {
                    $filename .= 'en_regle_';
                } elseif ($paymentStatus === 'not_paid') {
                    $filename .= 'non_en_regle_';
                } elseif ($paymentStatus === 'partially_paid') {
                    $filename .= 'partiellement_en_regle_';
                }
            }

            if ($fieldId) {
                $field = Field::find($fieldId);
                if ($field) {
                    $filename .= Str::slug($field->name) . '_';
                }
            }

            $filename .= date('Y-m-d') . '.xlsx';
            return Excel::download(new StudentsExport($fieldId, null, $paymentStatus), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Exporte la liste des étudiants au format CSV
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportCsv(Request $request)
    {
        try {
            $school = session('current_school');
            
            if (!$school) {
                return redirect()->route('schools.index')
                    ->with('error', 'Veuillez sélectionner une école pour exporter la liste des étudiants.');
            }
            
            // Filtrer directement par l'école actuelle
            $query = Student::with(['field.campus', 'payments'])
                            ->where('school_id', $school->id);
            
            // Filtrer par statut de paiement
            $title = 'Liste de tous les étudiants';
            
            if ($request->has('payment_status')) {
                $paymentStatus = $request->payment_status;
                
                // Récupérer tous les étudiants avec leurs filières et paiements
                $students = $query->get();
                
                // Filtrer manuellement les étudiants en fonction de leur statut de paiement
                $students = $students->filter(function($student) use ($paymentStatus) {
                    if (!$student->field) {
                        return false;
                    }
                    
                    $totalFees = $student->field->fees;
                    $totalPaid = $student->payments->sum('amount');
                    
                    if ($paymentStatus === 'fully_paid') {
                        // Étudiants qui ont entièrement payé
                        $title = 'Liste des étudiants en règle';
                        return $totalPaid >= $totalFees;
                    } elseif ($paymentStatus === 'not_paid') {
                        // Étudiants qui n'ont pas entièrement payé
                        $title = 'Liste des étudiants pas en règle';
                        return $totalPaid < $totalFees;
                    }
                    
                    return true;
                });
            } else {
                $students = $query->get();
            }
            
            // Créer le tableau de données pour l'exportation
            $data = [];
            
            // En-têtes du fichier Excel
            $headers = [
                'Nom complet', 
                'Email', 
                'Téléphone', 
                'Campus',
                'Filière', 
                'Frais de scolarité', 
                'Montant payé', 
                'Reste à payer', 
                'Statut de paiement'
            ];
            
            $data[] = $headers;
            
            // Groupe par campus
            $campusGroups = $students->groupBy(function($student) {
                return $student->field && $student->field->campus ? $student->field->campus->name : 'Sans campus';
            });
            
            foreach ($campusGroups as $campusName => $campusStudents) {
                // Ajouter le nom du campus comme ligne d'en-tête
                $data[] = ["CAMPUS: $campusName", "", "", "", "", "", "", "", ""];
                
                // Grouper par filière
                $fieldGroups = $campusStudents->groupBy(function($student) {
                    return $student->field ? $student->field->name : 'Sans filière';
                });
                
                foreach ($fieldGroups as $fieldName => $fieldStudents) {
                    // Ajouter le nom de la filière comme ligne d'en-tête
                    $data[] = ["FILIÈRE: $fieldName", "", "", "", "", "", "", "", ""];
                    
                    // Ajouter les étudiants de cette filière
                    foreach ($fieldStudents as $student) {
                        if (!$student->field) {
                            continue;
                        }
                        
                        $totalFees = $student->field->fees;
                        $totalPaid = $student->payments->sum('amount');
                        $remainingAmount = max(0, $totalFees - $totalPaid);
                        
                        // Déterminer le statut de paiement
                        if ($remainingAmount == 0) {
                            $paymentStatus = $school->term('fully_paid', 'Payé intégralement');
                        } elseif ($totalPaid > 0) {
                            $paymentStatus = $school->term('partially_paid', 'Partiellement payé');
                        } else {
                            $paymentStatus = $school->term('no_payment', 'Aucun paiement');
                        }
                        
                        $data[] = [
                            $student->fullName,
                            $student->email,
                            $student->phone,
                            $student->field->campus ? $student->field->campus->name : 'N/A',
                            $student->field->name,
                            number_format($totalFees, 0, ',', ' '),
                            number_format($totalPaid, 0, ',', ' '),
                            number_format($remainingAmount, 0, ',', ' '),
                            $paymentStatus
                        ];
                    }
                    
                    // Ligne vide après chaque filière
                    $data[] = ["", "", "", "", "", "", "", "", ""];
                }
                
                // Ligne vide après chaque campus
                $data[] = ["", "", "", "", "", "", "", "", ""];
            }
            
            // Générer le fichier CSV
            $fileName = $school->name . '_etudiants_groupes_' . Str::slug($title) . '_' . date('Y-m-d') . '.csv';
            
            // Créer une réponse pour le téléchargement
            return response()->streamDownload(function() use ($data) {
                $output = fopen('php://output', 'w');
                
                // Utiliser l'encodage UTF-8 avec BOM pour Excel
                fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Écrire chaque ligne dans le fichier CSV
                foreach ($data as $row) {
                    fputcsv($output, $row, ';');
                }
                
                fclose($output);
            }, $fileName, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
