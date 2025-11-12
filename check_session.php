<?php

/**
 * Script de vérification de la session scolaire
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        VÉRIFICATION DE LA SESSION SCOLAIRE                 ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Récupérer la session actuelle
$currentSetting = Setting::where('type', 'current_session')->first();

if (!$currentSetting) {
    echo "❌ ERREUR: Paramètre 'current_session' non trouvé!\n";
    exit(1);
}

echo "📅 Session Actuelle en Base de Données\n";
echo "   └─ " . $currentSetting->description . "\n\n";

// Calculer les sessions suggérées
$currentYear = date('Y');
$currentMonth = date('m');

// Si on est après septembre, on est dans la nouvelle année scolaire
$schoolYear = ($currentMonth >= 9) ? $currentYear : ($currentYear - 1);
$nextYear = $schoolYear + 1;
$suggestedSession = $schoolYear . '-' . $nextYear;

echo "📆 Informations Temporelles\n";
echo "   ├─ Année civile actuelle: " . $currentYear . "\n";
echo "   ├─ Mois actuel: " . date('F') . " (" . $currentMonth . ")\n";
echo "   └─ Session suggérée: " . $suggestedSession . "\n\n";

// Vérifier si la session est à jour
if ($currentSetting->description === $suggestedSession) {
    echo "✅ La session est À JOUR!\n\n";
} else {
    echo "⚠️  La session n'est PAS à jour!\n";
    echo "   ├─ Session actuelle: " . $currentSetting->description . "\n";
    echo "   └─ Session recommandée: " . $suggestedSession . "\n\n";
    
    echo "💡 Pour mettre à jour, utilisez:\n";
    echo "   php artisan session:update " . $suggestedSession . "\n\n";
}

// Afficher les sessions disponibles (3 ans en arrière, 1 an en avant)
echo "📋 Sessions Disponibles (générées automatiquement)\n";
for ($y = date('Y', strtotime('- 3 years')); $y <= date('Y', strtotime('+ 1 years')); $y++) {
    $sessionYear = $y;
    $sessionNext = $y + 1;
    $session = $sessionYear . '-' . $sessionNext;
    
    $marker = '';
    if ($session === $currentSetting->description) {
        $marker = ' ← ACTUELLE';
    } elseif ($session === $suggestedSession) {
        $marker = ' ← SUGGÉRÉE';
    }
    
    echo "   • " . $session . $marker . "\n";
}

echo "\n";

// Afficher d'autres paramètres importants
echo "🏫 Autres Paramètres Système\n";
$systemName = Setting::where('type', 'system_name')->first();
$termEnds = Setting::where('type', 'term_ends')->first();
$termBegins = Setting::where('type', 'term_begins')->first();

if ($systemName) {
    echo "   ├─ Nom de l'école: " . $systemName->description . "\n";
}
if ($termEnds) {
    echo "   ├─ Fin du trimestre: " . $termEnds->description . "\n";
}
if ($termBegins) {
    echo "   └─ Début du prochain trimestre: " . $termBegins->description . "\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "Pour plus d'informations, consultez: GESTION_SESSION_SCOLAIRE.md\n";
echo "═══════════════════════════════════════════════════════════\n";
