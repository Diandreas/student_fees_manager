<?php

/**
 * Helper pour récupérer un terme de la terminologie de l'école actuelle
 * 
 * @param string $key Le terme à récupérer
 * @param string|null $default La valeur par défaut si le terme n'existe pas
 * @return string Le terme traduit ou la valeur par défaut
 */
if (!function_exists('school_term')) {
    function school_term($key, $default = null) {
        $school = session('current_school');
        return $school ? $school->term($key, $default) : ($default ?? ucfirst($key));
    }
} 