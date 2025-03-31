<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\School;
use App\Models\Campus;
use App\Models\Field;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Archive;
use App\Models\YearlyStat;
use Illuminate\Support\Facades\Storage;

class TestSeeder extends Seeder
{
    /**
     * Seed the application database with test data
     *
     * @return void
     */
    public function run()
    {
        // Nettoyer le système de fichiers de stockage
        Storage::disk('public')->deleteDirectory('archives');
        Storage::disk('public')->makeDirectory('archives');
        
        // Désactiver les déclencheurs d'événements pour éviter les logs d'activité pendant le seeding
        \Illuminate\Database\Eloquent\Model::unguard();
        
        // Récupérer ou créer un utilisateur administrateur
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'is_superadmin' => true,
            ]);
        }
        
        // Vérifier si l'école existe déjà
        $school = School::where('name', 'École de Test')->first();
        if (!$school) {
            // Créer une école
            $school = School::create([
                'name' => 'École de Test',
                'primary_color' => '#16a34a',
                'contact_email' => 'contact@ecoledetest.com',
            ]);
            
            // Lier l'administrateur à l'école
            if (!DB::table('school_admins')->where('user_id', $admin->id)->where('school_id', $school->id)->exists()) {
                DB::table('school_admins')->insert([
                    'user_id' => $admin->id,
                    'school_id' => $school->id,
                ]);
            }
            
            // Créer des campus
            $campuses = [
                'Campus Principal',
                'Campus Nord',
                'Campus Sud',
            ];
            
            $campusModels = [];
            foreach ($campuses as $name) {
                $campusModels[$name] = Campus::create([
                    'name' => $name,
                    'school_id' => $school->id,
                ]);
            }
            
            // Créer des filières
            $fields = [
                'Informatique' => 950000,
                'Gestion' => 850000,
                'Santé' => 1250000,
                'Droit' => 1500000,
                'Commerce' => 1100000,
                'Arts' => 750000,
                'Sciences' => 900000,
            ];
            
            $fieldModels = [];
            foreach ($fields as $name => $tuitionFee) {
                $fieldModels[$name] = Field::create([
                    'name' => $name,
                    'school_id' => $school->id,
                    'fees' => $tuitionFee,
                ]);
            }
        } else {
            // Récupérer les campus et filières existants
            $campusModels = [];
            $campuses = Campus::where('school_id', $school->id)->get();
            
            if ($campuses->isEmpty()) {
                // Créer des campus si aucun n'existe
                $campusNames = [
                    'Campus Principal',
                    'Campus Nord',
                    'Campus Sud',
                ];
                
                foreach ($campusNames as $name) {
                    $campusModels[$name] = Campus::create([
                        'name' => $name,
                        'school_id' => $school->id,
                    ]);
                }
            } else {
                foreach ($campuses as $campus) {
                    $campusModels[$campus->name] = $campus;
                }
            }
            
            $fieldModels = [];
            $fields = Field::where('school_id', $school->id)->get();
            
            if ($fields->isEmpty()) {
                // Créer des filières si aucune n'existe
                $fieldData = [
                    'Informatique' => 950000,
                    'Gestion' => 850000,
                    'Santé' => 1250000,
                    'Droit' => 1500000,
                    'Commerce' => 1100000,
                    'Arts' => 750000,
                    'Sciences' => 900000,
                ];
                
                foreach ($fieldData as $name => $fees) {
                    $fieldModels[$name] = Field::create([
                        'name' => $name,
                        'school_id' => $school->id,
                        'fees' => $fees,
                        'campus_id' => $campusModels[array_rand($campusModels)]->id,
                    ]);
                }
            } else {
                foreach ($fields as $field) {
                    $fieldModels[$field->name] = $field;
                }
            }
        }
        
        // Années académiques à générer
        $academicYears = [
            '2021-2022',
            '2022-2023',
            '2023-2024',
        ];
        
        // Vérifier si des données existent déjà pour ces années
        $existingYears = YearlyStat::where('school_id', $school->id)
            ->whereIn('academic_year', $academicYears)
            ->pluck('academic_year')
            ->toArray();
        
        // Tableau pour stocker le nombre d'étudiants par année
        $studentsByYear = [];
        
        // Pour chaque année académique qui n'existe pas encore
        foreach ($academicYears as $index => $academicYear) {
            if (in_array($academicYear, $existingYears)) {
                $this->command->info("Année académique {$academicYear} déjà existante, ignorée.");
                continue;
            }
            
            // Extraire les années
            $years = explode('-', $academicYear);
            $startYear = (int)$years[0];
            $endYear = (int)$years[1];
            
            // Définir les dates de début et de fin d'année académique
            $startDate = Carbon::createFromDate($startYear, 9, 1);  // 1er septembre
            $endDate = Carbon::createFromDate($endYear, 8, 31);     // 31 août
            
            // Le nombre d'étudiants augmente chaque année (25, 40, 60)
            $numStudents = 25 + (15 * $index);
            $studentsByYear[$academicYear] = $numStudents;
            
            // Créer des étudiants pour cette année
            for ($i = 0; $i < $numStudents; $i++) {
                $gender = rand(0, 1) ? 'M' : 'F';
                $firstname = $gender === 'M' ? $this->getRandomMaleName() : $this->getRandomFemaleName();
                $lastname = $this->getRandomLastName();
                
                // Assigner aléatoirement campus et filière
                $campusKey = array_rand($campusModels);
                $fieldKey = array_rand($fieldModels);
                
                // Créer l'étudiant avec une date d'inscription dans cette année académique
                $student = Student::create([
                    'fullName' => "{$firstname} {$lastname}",
                    'address' => rand(1, 100) . ' Rue des Écoles, Ville Test',
                    'user_id' => $admin->id,  // Utiliser l'administrateur comme utilisateur par défaut
                    'school_id' => $school->id,
                    'field_id' => $fieldModels[$fieldKey]->id,
                    'created_at' => $startDate->copy()->addDays(rand(0, 60)),
                ]);
                
                // Récupérer les frais de scolarité de la filière
                $tuitionFee = $fieldModels[$fieldKey]->fees;
                
                // Générer des paiements avec différents schémas
                $paymentScheme = rand(1, 5);  // Différents schémas de paiement
                $paidAmount = 0;
                $creationDate = $student->created_at->copy()->addDays(rand(1, 7));
                
                switch ($paymentScheme) {
                    case 1:  // Paiement complet
                        $paymentDate = $creationDate->copy()->addDays(rand(1, 30));
                        if ($paymentDate->lt($endDate)) {
                            Payment::create([
                                'student_id' => $student->id,
                                'school_id' => $school->id,
                                'amount' => $tuitionFee,
                                'payment_date' => $paymentDate->format('Y-m-d'),
                                'description' => "Paiement complet des frais de scolarité {$academicYear}",
                                'receipt_number' => 'REC-' . $startYear . '-' . uniqid(),
                                'created_at' => $paymentDate,
                            ]);
                            $paidAmount = $tuitionFee;
                        }
                        break;
                        
                    case 2:  // Paiement en 2 tranches
                        for ($j = 0; $j < 2; $j++) {
                            $trancheAmount = $tuitionFee / 2;
                            $paymentDate = $creationDate->copy()->addMonths($j * 3)->addDays(rand(1, 30));
                            if ($paymentDate->lt($endDate)) {
                                Payment::create([
                                    'student_id' => $student->id,
                                    'school_id' => $school->id,
                                    'amount' => $trancheAmount,
                                    'payment_date' => $paymentDate->format('Y-m-d'),
                                    'description' => "Paiement tranche " . ($j + 1) . "/2 des frais de scolarité {$academicYear}",
                                    'receipt_number' => 'REC-' . $startYear . '-' . uniqid(),
                                    'created_at' => $paymentDate,
                                ]);
                                $paidAmount += $trancheAmount;
                            }
                        }
                        break;
                        
                    case 3:  // Paiement en 3 tranches
                        for ($j = 0; $j < 3; $j++) {
                            $trancheAmount = $tuitionFee / 3;
                            $paymentDate = $creationDate->copy()->addMonths($j * 2)->addDays(rand(1, 30));
                            if ($paymentDate->lt($endDate)) {
                                Payment::create([
                                    'student_id' => $student->id,
                                    'school_id' => $school->id,
                                    'amount' => $trancheAmount,
                                    'payment_date' => $paymentDate->format('Y-m-d'),
                                    'description' => "Paiement tranche " . ($j + 1) . "/3 des frais de scolarité {$academicYear}",
                                    'receipt_number' => 'REC-' . $startYear . '-' . uniqid(),
                                    'created_at' => $paymentDate,
                                ]);
                                $paidAmount += $trancheAmount;
                            }
                        }
                        break;
                        
                    case 4:  // Paiement partiel
                        $percentToPay = rand(40, 80);
                        $partialAmount = ($tuitionFee * $percentToPay) / 100;
                        $paymentDate = $creationDate->copy()->addDays(rand(1, 60));
                        if ($paymentDate->lt($endDate)) {
                            Payment::create([
                                'student_id' => $student->id,
                                'school_id' => $school->id,
                                'amount' => $partialAmount,
                                'payment_date' => $paymentDate->format('Y-m-d'),
                                'description' => "Paiement partiel ({$percentToPay}%) des frais de scolarité {$academicYear}",
                                'receipt_number' => 'REC-' . $startYear . '-' . uniqid(),
                                'created_at' => $paymentDate,
                            ]);
                            $paidAmount = $partialAmount;
                        }
                        break;
                        
                    case 5:  // Aucun paiement
                        // Ne rien faire
                        break;
                }
            }
            
            // Générer l'archive pour cette année
            $this->generateArchiveForYear($school, $academicYear, $startDate, $endDate);
        }
        
        // Réactiver les déclencheurs d'événements
        \Illuminate\Database\Eloquent\Model::reguard();
        
        // Résumé des données générées
        $this->command->info('Seed de test terminé :');
        $this->command->info('- École de Test avec ses campus et filières');
        
        foreach ($academicYears as $year) {
            if (isset($studentsByYear[$year])) {
                $this->command->info("- Année {$year} : {$studentsByYear[$year]} étudiants, factures et paiements générés");
            } else {
                $this->command->info("- Année {$year} : déjà existante, non modifiée");
            }
        }
        
        $this->command->info('- Archives et statistiques générées pour chaque nouvelle année académique');
        $this->command->info('Utilisateur admin : admin@example.com (mot de passe: password si vous l\'avez réinitialisé)');
    }
    
    /**
     * Générer une archive pour une année académique spécifique
     */
    private function generateArchiveForYear($school, $academicYear, $startDate, $endDate)
    {
        // Générer le rapport Excel
        $filename = "archive_{$school->id}_{$academicYear}_" . time() . ".xlsx";
        $filePath = "archives/{$school->id}/{$filename}";
        
        Storage::disk('public')->makeDirectory("archives/{$school->id}");
        
        // Nous n'avons pas besoin de générer un fichier Excel réel pour le seed
        // Nous allons simplement créer un fichier texte à la place
        Storage::disk('public')->put($filePath, "Archive pour l'année {$academicYear}");
        $fileSize = Storage::disk('public')->size($filePath);
        
        // Récupérer les statistiques pour cette année
        $students = Student::where('school_id', $school->id)
                          ->where('created_at', '>=', $startDate)
                          ->where('created_at', '<=', $endDate)
                          ->get();
        $studentsCount = $students->count();
        
        // Calculer le total facturé (somme des frais de scolarité des étudiants)
        $totalInvoiced = 0;
        foreach ($students as $student) {
            $field = Field::find($student->field_id);
            if ($field) {
                $totalInvoiced += $field->fees;
            }
        }
        
        $totalPaid = Payment::where('school_id', $school->id)
                           ->whereBetween('payment_date', [$startDate, $endDate])
                           ->sum('amount');
        
        $totalRemaining = $totalInvoiced - $totalPaid;
        
        // Créer l'enregistrement d'archive
        $archive = Archive::create([
            'school_id' => $school->id,
            'academic_year' => $academicYear,
            'file_path' => $filePath,
            'file_name' => $filename,
            'file_size' => $fileSize,
            'students_count' => $studentsCount,
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'created_by' => 1, // ID de l'admin
            'notes' => "Archive générée automatiquement pour l'année académique {$academicYear}",
        ]);
        
        // Générer les statistiques par campus
        $campusStats = [];
        $campuses = Campus::where('school_id', $school->id)->get();
        foreach ($campuses as $campus) {
            $campusStudents = Student::where('school_id', $school->id)
                                    ->where('campus_id', $campus->id)
                                    ->pluck('id')
                                    ->toArray();
            
            $campusPaid = Payment::where('school_id', $school->id)
                                ->whereBetween('payment_date', [$startDate, $endDate])
                                ->whereIn('student_id', $campusStudents)
                                ->sum('amount');
            
            $campusStats[$campus->name] = $campusPaid;
        }
        
        // Statistiques par filière
        $fieldStats = [];
        $fields = Field::where('school_id', $school->id)->get();
        foreach ($fields as $field) {
            $fieldStudents = Student::where('school_id', $school->id)
                                   ->where('field_id', $field->id)
                                   ->pluck('id')
                                   ->toArray();
            
            $fieldPaid = Payment::where('school_id', $school->id)
                               ->whereBetween('payment_date', [$startDate, $endDate])
                               ->whereIn('student_id', $fieldStudents)
                               ->sum('amount');
            
            $fieldStats[$field->name] = $fieldPaid;
        }
        
        // Statistiques mensuelles
        $monthlyPayments = [];
        $startYear = (int)explode('-', $academicYear)[0];
        $endYear = (int)explode('-', $academicYear)[1];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate($startYear, $month, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            
            // Ajuster pour l'année académique (sept - août)
            if ($month < 9) {
                $startOfMonth->setYear($endYear);
                $endOfMonth->setYear($endYear);
            } else {
                $startOfMonth->setYear($startYear);
                $endOfMonth->setYear($startYear);
            }
            
            $monthlyAmount = Payment::where('school_id', $school->id)
                                  ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                                  ->sum('amount');
            
            $monthlyPayments[$month] = $monthlyAmount;
        }
        
        // Calculer le taux de recouvrement
        $recoveryRate = $totalInvoiced > 0 ? ($totalPaid / $totalInvoiced) * 100 : 0;
        
        // Créer l'enregistrement des statistiques annuelles
        YearlyStat::create([
            'school_id' => $school->id,
            'academic_year' => $academicYear,
            'total_students' => $studentsCount,
            'new_students' => $studentsCount, // Pour simplifier, tous sont de nouveaux étudiants
            'graduated_students' => 0, // À ajuster selon les besoins
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'recovery_rate' => $recoveryRate,
            'campus_stats' => $campusStats,
            'field_stats' => $fieldStats,
            'monthly_payments' => $monthlyPayments,
            'archive_id' => $archive->id
        ]);
    }
    
    /**
     * Générer un prénom masculin aléatoire
     */
    private function getRandomMaleName()
    {
        $names = [
            'Jean', 'Pierre', 'Paul', 'Jacques', 'François', 'Michel', 'Robert', 'David',
            'Emmanuel', 'Nicolas', 'Olivier', 'Bernard', 'Antoine', 'Christophe', 'Patrick',
            'Thierry', 'Marc', 'Thomas', 'Éric', 'Daniel', 'Vincent', 'Joseph', 'Alain',
            'Philippe', 'Louis', 'Claude', 'Dominique', 'Julien', 'Guillaume', 'Sébastien',
            'Alexandre', 'Christian', 'Henri', 'Mathieu', 'Frédéric', 'Stéphane', 'André',
            'Xavier', 'Pascal', 'Charles', 'Yves', 'Gérard', 'Patrice', 'Serge', 'Guy'
        ];
        
        return $names[array_rand($names)];
    }
    
    /**
     * Générer un prénom féminin aléatoire
     */
    private function getRandomFemaleName()
    {
        $names = [
            'Marie', 'Jeanne', 'Françoise', 'Catherine', 'Anne', 'Monique', 'Isabelle', 'Sophie',
            'Martine', 'Nicole', 'Sylvie', 'Christine', 'Nathalie', 'Valérie', 'Jacqueline',
            'Julie', 'Patricia', 'Aurélie', 'Caroline', 'Sandrine', 'Véronique', 'Élodie',
            'Chantal', 'Claire', 'Émilie', 'Céline', 'Laura', 'Charlotte', 'Laurence', 'Josette',
            'Camille', 'Stéphanie', 'Élisabeth', 'Pauline', 'Mathilde', 'Brigitte', 'Alice',
            'Manon', 'Marguerite', 'Sarah', 'Madeleine', 'Marion', 'Margot', 'Louise', 'Lucie'
        ];
        
        return $names[array_rand($names)];
    }
    
    /**
     * Générer un nom de famille aléatoire
     */
    private function getRandomLastName()
    {
        $names = [
            'Martin', 'Bernard', 'Thomas', 'Petit', 'Robert', 'Richard', 'Durand', 'Dubois',
            'Moreau', 'Laurent', 'Simon', 'Michel', 'Lefebvre', 'Leroy', 'Roux', 'David',
            'Bertrand', 'Morel', 'Fournier', 'Girard', 'Bonnet', 'Dupont', 'Lambert', 'Fontaine',
            'Rousseau', 'Vincent', 'Muller', 'Lefevre', 'Faure', 'Andre', 'Mercier', 'Blanc',
            'Guerin', 'Boyer', 'Garnier', 'Chevalier', 'Francois', 'Legrand', 'Gauthier', 'Garcia',
            'Perrin', 'Robin', 'Clement', 'Morin', 'Nicolas', 'Henry', 'Roussel', 'Mathieu', 'Gautier'
        ];
        
        return $names[array_rand($names)];
    }
    
    /**
     * Générer une méthode de paiement aléatoire
     */
    private function getRandomPaymentMethod()
    {
        $methods = ['cash', 'bank', 'mobile', 'other'];
        return $methods[array_rand($methods)];
    }
} 