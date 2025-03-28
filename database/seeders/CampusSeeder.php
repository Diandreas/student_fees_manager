<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campus;
use App\Models\School;

class CampusSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        
        if (!$school) {
            throw new \Exception('Aucune école trouvée. Veuillez d\'abord créer une école.');
        }

        $campuses = [
            [
                'school_id' => $school->id,
                'name' => 'Campus Principal',
                'description' => 'Campus principal de l\'établissement'
            ],
            [
                'school_id' => $school->id,
                'name' => 'Campus Annexe',
                'description' => 'Campus annexe de l\'établissement'
            ]
        ];

        foreach ($campuses as $campus) {
            Campus::create($campus);
        }
    }
} 