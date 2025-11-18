<?php

/**
 * Fichier d'autoload des Helpers
 * Ce fichier charge automatiquement les classes Helper pour les rendre disponibles globalement
 */

// Charger la classe Qs
if (!class_exists('Qs')) {
    class_alias('App\Helpers\Qs', 'Qs');
}

// Charger la classe Mk
if (!class_exists('Mk')) {
    class_alias('App\Helpers\Mk', 'Mk');
}

// Charger la classe PeriodCalculator si elle existe
if (!class_exists('PeriodCalculator') && class_exists('App\Helpers\PeriodCalculator')) {
    class_alias('App\Helpers\PeriodCalculator', 'PeriodCalculator');
}
