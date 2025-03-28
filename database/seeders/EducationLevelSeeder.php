<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationLevel;
use App\Models\School;

class EducationLevelSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        
        if (!$school) {
            throw new \Exception('Aucune école trouvée. Veuillez d\'abord créer une école.');
        }

        $levels = [
            [
                'school_id' => $school->id,
                'name' => 'Première année',
                'code' => 'L1',
                'order' => 1,
                'duration_years' => 1,
                'is_active' => true,
            ],
            [
                'school_id' => $school->id,
                'name' => 'Deuxième année',
                'code' => 'L2',
                'order' => 2,
                'duration_years' => 1,
                'is_active' => true,
            ],
            [
                'school_id' => $school->id,
                'name' => 'Troisième année',
                'code' => 'L3',
                'order' => 3,
                'duration_years' => 1,
                'is_active' => true,
            ],
            [
                'school_id' => $school->id,
                'name' => 'Master 1',
                'code' => 'M1',
                'order' => 4,
                'duration_years' => 1,
                'is_active' => true,
            ],
            [
                'school_id' => $school->id,
                'name' => 'Master 2',
                'code' => 'M2',
                'order' => 5,
                'duration_years' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($levels as $level) {
            EducationLevel::create($level);
        }
    }
} 