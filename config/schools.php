<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des écoles
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient la configuration pour la gestion des écoles.
    |
    */

    // Rôles disponibles pour les administrateurs d'école
    'admin_roles' => [
        'admin' => 'Administrateur',
        'manager' => 'Gestionnaire',
        'finance' => 'Finance',
        'secretary' => 'Secrétariat',
        'viewer' => 'Lecteur',
    ],
    
    // Permissions disponibles pour les administrateurs
    'permissions' => [
        'manage_students' => 'Gestion des étudiants',
        'manage_fees' => 'Gestion des frais',
        'manage_teachers' => 'Gestion des enseignants',
        'manage_programs' => 'Gestion des programmes',
        'manage_reports' => 'Accès aux rapports',
    ],
    
    // Couleurs par défaut pour les nouvelles écoles
    'default_colors' => [
        'primary' => '#16a34a',
        'secondary' => '#10b981',
    ],
]; 