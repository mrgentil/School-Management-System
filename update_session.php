<?php

/**
 * Script pour mettre à jour la session scolaire courante
 * 
 * Ce script met à jour la session courante dans la base de données
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;

echo "=== Mise à jour de la Session Scolaire ===\n\n";

// Afficher la session actuelle
$currentSetting = Setting::where('type', 'current_session')->first();
echo "Session actuelle en base de données: " . $currentSetting->description . "\n\n";

// Calculer la nouvelle session (année en cours)
$currentYear = date('Y');
$nextYear = $currentYear + 1;
$newSession = $currentYear . '-' . $nextYear;

echo "Nouvelle session proposée: " . $newSession . "\n";
echo "\nVoulez-vous mettre à jour la session? (o/n): ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$response = trim($line);

if (strtolower($response) === 'o' || strtolower($response) === 'oui' || strtolower($response) === 'y' || strtolower($response) === 'yes') {
    $currentSetting->description = $newSession;
    $currentSetting->save();
    
    echo "\n✓ Session mise à jour avec succès!\n";
    echo "Nouvelle session: " . $newSession . "\n";
} else {
    echo "\nAnnulé. Aucune modification effectuée.\n";
}

fclose($handle);

echo "\n=== Terminé ===\n";
