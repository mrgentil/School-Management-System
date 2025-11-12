<?php

/**
 * Script de vérification de la migration Laravel 12
 * 
 * Ce script vérifie que tous les composants nécessaires sont correctement configurés
 */

echo "=== Vérification de la Migration Laravel 8 → Laravel 12 ===\n\n";

// 1. Vérifier la version de Laravel
echo "1. Version de Laravel: ";
$composerJson = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
echo $composerJson['require']['laravel/framework'] ?? 'Non trouvée';
echo "\n";

// 2. Vérifier bootstrap/app.php
echo "\n2. Vérification de bootstrap/app.php: ";
$bootstrapApp = file_get_contents(__DIR__ . '/bootstrap/app.php');
if (strpos($bootstrapApp, '$middleware->alias') !== false) {
    echo "✓ Middlewares enregistrés\n";
} else {
    echo "✗ Middlewares non enregistrés\n";
}

// 3. Vérifier bootstrap/providers.php
echo "\n3. Vérification de bootstrap/providers.php: ";
$bootstrapProviders = file_get_contents(__DIR__ . '/bootstrap/providers.php');
if (strpos($bootstrapProviders, 'RouteServiceProvider') !== false) {
    echo "✓ RouteServiceProvider enregistré\n";
} else {
    echo "✗ RouteServiceProvider non enregistré\n";
}

// 4. Vérifier les middlewares personnalisés
echo "\n4. Vérification des middlewares personnalisés:\n";
$middlewares = [
    'Admin',
    'SuperAdmin',
    'TeamSA',
    'TeamSAT',
    'TeamAccount',
    'ExamIsLocked',
    'MyParent',
    'Student'
];

foreach ($middlewares as $middleware) {
    $path = __DIR__ . '/app/Http/Middleware/Custom/' . $middleware . '.php';
    if (file_exists($path)) {
        echo "   ✓ {$middleware}\n";
    } else {
        echo "   ✗ {$middleware} (manquant)\n";
    }
}

// 5. Vérifier les routes
echo "\n5. Vérification de routes/web.php: ";
$webRoutes = file_get_contents(__DIR__ . '/routes/web.php');
if (strpos($webRoutes, '@') !== false && strpos($webRoutes, '::class') === false) {
    echo "⚠ Syntaxe obsolète détectée (Controller@method)\n";
} else {
    echo "✓ Syntaxe moderne\n";
}

// 6. Vérifier Kernel.php
echo "\n6. Vérification de app/Http/Kernel.php: ";
$kernel = file_get_contents(__DIR__ . '/app/Http/Kernel.php');
if (strpos($kernel, '$routeMiddleware') !== false) {
    echo "⚠ Propriété obsolète \$routeMiddleware détectée\n";
} else {
    echo "✓ Pas de propriétés obsolètes\n";
}

echo "\n=== Vérification terminée ===\n";
echo "\nPour tester l'application, exécutez:\n";
echo "  php artisan serve\n";
echo "  Puis visitez: http://localhost:8000/users\n\n";
