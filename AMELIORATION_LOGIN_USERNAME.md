# Amélioration : Utiliser Username au lieu de Name

## 🎯 Problème Potentiel

Actuellement, le système utilise le champ `name` pour la connexion, mais ce champ n'est **pas unique** dans la base de données. Cela peut causer des problèmes si plusieurs utilisateurs ont le même nom.

## ✅ Solution Recommandée

Utiliser le champ `username` qui est **unique** dans la base de données.

## 🔧 Modification à Effectuer

### Option 1 : Username OU Email (Recommandé)

Modifier `LoginController.php` ligne 66 :

```php
// AVANT
$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

// APRÈS
$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
```

Modifier `login.blade.php` :

```html
<!-- AVANT -->
<input type="text" name="login" placeholder="Email ou Nom d'utilisateur">

<!-- APRÈS (optionnel, déjà correct) -->
<input type="text" name="login" placeholder="Email ou Nom d'utilisateur">
```

### Option 2 : Email, Username OU Name (Maximum de Flexibilité)

Pour permettre les 3 options, modifier `LoginController.php` :

```php
protected function attemptLogin(Request $request)
{
    $login = $request->input('login');
    $password = $request->input('password');
    
    // Déterminer le type de champ
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $fieldType = 'email';
    } else {
        // Essayer d'abord avec username, puis avec name
        $user = \App\Models\User::where('username', $login)
                    ->orWhere('name', $login)
                    ->first();
        
        if ($user) {
            return Auth::attempt(
                ['id' => $user->id, 'password' => $password],
                $request->filled('remember')
            );
        }
        
        return false;
    }
    
    // Tentative de connexion par email
    return Auth::attempt(
        [$fieldType => $login, 'password' => $password],
        $request->filled('remember')
    );
}
```

## 📊 Comparaison

| Champ    | Unique | Nullable | Recommandé |
|----------|--------|----------|------------|
| email    | ✅ Oui | ✅ Oui   | ✅ Oui     |
| username | ✅ Oui | ✅ Oui   | ✅ Oui     |
| name     | ❌ Non | ❌ Non   | ⚠️ Risqué  |

## 🔍 Vérification des Usernames

Pour vérifier si les utilisateurs ont des usernames :

```bash
php artisan tinker --execute="echo App\Models\User::whereNotNull('username')->count() . ' utilisateurs avec username';"
```

## 💡 Recommandation Finale

**Utiliser l'Option 1** : Email OU Username

**Raisons** :
1. ✅ Garantit l'unicité
2. ✅ Plus sécurisé
3. ✅ Évite les conflits
4. ✅ Standard de l'industrie

## 🚀 Implémentation

Si vous voulez implémenter cette amélioration, je peux le faire maintenant.
