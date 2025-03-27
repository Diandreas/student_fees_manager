<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paramètres globaux de l'application
    |--------------------------------------------------------------------------
    |
    | Ce fichier permet de définir manuellement les paramètres de l'application
    | sans avoir besoin de passer par l'interface ou la base de données.
    | Ces valeurs seront utilisées si la table settings n'existe pas.
    |
    */

    // Apparence
    'app_name' => 'Student Fees Manager',
    'app_logo' => 'logo.png',
    'app_favicon' => 'favicon.ico',
    'default_theme' => 'light',  // Options: light, dark, auto
    'show_footer' => true,
    'show_breadcrumbs' => true,
    'enable_animations' => true,
    
    // Couleurs du thème
    'colors' => [
        'primary' => '#0A3D62',
        'secondary' => '#1E5B94',
        'accent' => '#D4AF37',
        'dark_blue' => '#071E3D',
    ],
    
    // Notifications
    'email_from' => 'noreply@example.com',
    'email_name' => 'Student Fees Manager',
    'email_notifications' => ['new_student', 'new_payment', 'payment_due'],
    
    // Langue
    'default_language' => 'fr',
    'available_languages' => ['fr', 'en'],
    
    // Export
    'paper_size' => 'a4',  // Options: a4, letter, legal
    'export_format' => 'xlsx', // Options: xlsx, csv, pdf
    'receipt_footer' => 'Merci pour votre paiement. Ce reçu est généré automatiquement et ne nécessite pas de signature.',
    'include_logo' => true,
    
    // Paramètres d'affichage
    'items_per_page' => 10,
    'date_format' => 'd/m/Y',
    'currency' => 'XOF',
    'maintenance_mode' => false,
    
    // Modes d'affichage par défaut
    'display_modes' => [
        'students' => 'list',  // Options: list, card
        'payments' => 'list',  // Options: list, card
        'fields' => 'list',    // Options: list, card
        'campuses' => 'list',  // Options: list, card
    ],
]; 