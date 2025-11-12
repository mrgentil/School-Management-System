# Connexion par Email OU Nom d'Utilisateur

## ✅ Fonctionnalité Implémentée

Les utilisateurs peuvent maintenant se connecter en utilisant **soit leur email, soit leur nom**.

## 🔧 Modifications Effectuées

### 1. **LoginController.php**
Ajout de la logique pour détecter automatiquement si l'utilisateur entre un email ou un nom :

```php
protected function attemptLogin(Request $request)
{
    $login = $request->input('login');
    $password = $request->input('password');
    
    // Déterminer si c'est un email ou un nom
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    
    // Tentative de connexion
    return Auth::attempt(
        [$fieldType => $login, 'password' => $password],
        $request->filled('remember')
    );
}
```

### 2. **login.blade.php**
Modification du champ de saisie :

**Avant** :
```html
<input type="email" name="email" placeholder="Email">
```

**Après** :
```html
<input type="text" name="login" placeholder="Email ou Nom d'utilisateur">
```

## 🎯 Comment ça Fonctionne

### Détection Automatique

Le système détecte automatiquement le type d'identifiant :

1. **Si l'utilisateur entre un email** (ex: `user@example.com`)
   - Le système cherche dans la colonne `email`
   
2. **Si l'utilisateur entre un nom** (ex: `Jean Dupont`)
   - Le système cherche dans la colonne `name`

### Exemples de Connexion

#### Connexion par Email
```
Login: admin@example.com
Password: ********
```

#### Connexion par Nom
```
Login: Jean Dupont
Password: ********
```

## 📋 Structure de la Table Users

La table `users` contient les champs suivants pour l'authentification :

```sql
- id (primary key)
- name (string) ← Utilisé pour la connexion
- email (string, unique, nullable) ← Utilisé pour la connexion
- username (string, unique, nullable)
- password (string)
```

## 🔍 Validation

### Messages d'Erreur Personnalisés

```php
'login.required' => 'Veuillez entrer votre email ou nom d\'utilisateur.'
'password.required' => 'Veuillez entrer votre mot de passe.'
```

### Règles de Validation

```php
'login' => 'required|string'
'password' => 'required|string'
```

## 🧪 Tests

### Test 1 : Connexion par Email
1. Aller sur `/login`
2. Entrer un email valide (ex: `admin@admin.com`)
3. Entrer le mot de passe
4. Cliquer sur "Se connecter"
5. ✅ Connexion réussie

### Test 2 : Connexion par Nom
1. Aller sur `/login`
2. Entrer un nom complet (ex: `Admin Admin`)
3. Entrer le mot de passe
4. Cliquer sur "Se connecter"
5. ✅ Connexion réussie

### Test 3 : Identifiants Invalides
1. Aller sur `/login`
2. Entrer un email/nom inexistant
3. Entrer un mot de passe
4. Cliquer sur "Se connecter"
5. ❌ Message d'erreur affiché

## 💡 Avantages

1. **Flexibilité** : Les utilisateurs peuvent choisir leur méthode préférée
2. **Simplicité** : Un seul champ pour les deux options
3. **Détection Automatique** : Pas besoin de sélectionner le type
4. **Compatibilité** : Fonctionne avec l'existant

## ⚠️ Notes Importantes

### Unicité des Noms

Si plusieurs utilisateurs ont le même nom, seul le premier trouvé sera authentifié. Pour éviter ce problème :

**Option 1** : Utiliser le champ `username` au lieu de `name`
**Option 2** : Rendre les noms uniques dans la base de données
**Option 3** : Encourager l'utilisation de l'email

### Recommandation

Pour une meilleure sécurité et unicité, vous pouvez modifier le système pour utiliser le champ `username` au lieu de `name` :

```php
// Dans LoginController.php, ligne 66
$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
```

Et mettre à jour le placeholder :
```html
<input type="text" name="login" placeholder="Email ou Nom d'utilisateur">
```

## 🔐 Sécurité

- ✅ Les mots de passe sont hashés (bcrypt)
- ✅ Protection CSRF activée
- ✅ Validation des entrées
- ✅ Limitation des tentatives (throttling)
- ✅ Option "Se souvenir de moi"

## 📝 Fichiers Modifiés

1. `app/Http/Controllers/Auth/LoginController.php`
   - Ajout de `attemptLogin()`
   - Ajout de `credentials()`
   - Ajout de `validateLogin()`
   - Ajout de `username()`

2. `resources/views/auth/login.blade.php`
   - Modification du champ `email` → `login`
   - Modification du type `email` → `text`
   - Modification du placeholder

## 🚀 Utilisation

### Pour les Utilisateurs

1. Accéder à la page de connexion
2. Entrer soit :
   - Votre adresse email
   - Votre nom complet
3. Entrer votre mot de passe
4. Cliquer sur "Se connecter"

### Pour les Administrateurs

Aucune configuration supplémentaire nécessaire. Le système fonctionne automatiquement.

## 🔄 Retour en Arrière (Rollback)

Si vous souhaitez revenir à la connexion par email uniquement :

1. Restaurer `LoginController.php` :
   - Supprimer les méthodes `attemptLogin()`, `credentials()`, `validateLogin()`, `username()`

2. Restaurer `login.blade.php` :
   ```html
   <input type="email" name="email" placeholder="Email">
   ```

## ✅ Checklist de Vérification

- [x] LoginController modifié
- [x] Vue login modifiée
- [x] Détection automatique email/nom
- [x] Messages d'erreur en français
- [x] Documentation créée
- [ ] Tests effectués
- [ ] Utilisateurs informés

## 📚 Ressources

- [Laravel Authentication](https://laravel.com/docs/12.x/authentication)
- [Custom Authentication](https://laravel.com/docs/12.x/authentication#authenticating-users)
