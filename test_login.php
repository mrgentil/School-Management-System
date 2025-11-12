<?php

/**
 * Script de test pour la connexion Email OU Nom
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        TEST DE CONNEXION EMAIL OU NOM                     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Récupérer quelques utilisateurs pour tester
$users = User::take(5)->get(['id', 'name', 'email', 'user_type']);

if ($users->isEmpty()) {
    echo "❌ Aucun utilisateur trouvé dans la base de données!\n";
    exit(1);
}

echo "📋 Utilisateurs disponibles pour test:\n\n";
echo str_pad("ID", 5) . str_pad("NOM", 30) . str_pad("EMAIL", 35) . "TYPE\n";
echo str_repeat("─", 80) . "\n";

foreach ($users as $user) {
    echo str_pad($user->id, 5) . 
         str_pad($user->name, 30) . 
         str_pad($user->email ?? 'N/A', 35) . 
         $user->user_type . "\n";
}

echo "\n";
echo "✅ Vous pouvez maintenant vous connecter avec:\n";
echo "   • L'EMAIL (ex: " . ($users->first()->email ?? 'N/A') . ")\n";
echo "   • Le NOM (ex: " . $users->first()->name . ")\n\n";

// Tester la détection email vs nom
echo "🔍 Test de Détection:\n\n";

$testCases = [
    'admin@admin.com' => 'email',
    'Jean Dupont' => 'name',
    'user@example.com' => 'email',
    'Super Admin' => 'name',
    'test.user@school.com' => 'email',
];

foreach ($testCases as $input => $expected) {
    $detected = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    $status = ($detected === $expected) ? '✓' : '✗';
    echo "   $status '$input' → détecté comme: $detected\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════\n";
echo "Pour tester la connexion:\n";
echo "1. Ouvrez http://localhost:8000/login\n";
echo "2. Entrez un email OU un nom d'utilisateur\n";
echo "3. Entrez le mot de passe\n";
echo "4. Cliquez sur 'Se connecter'\n";
echo "═══════════════════════════════════════════════════════════\n";
