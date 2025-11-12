# Gestion de la Session Scolaire

## 🔍 Problème Identifié

La session scolaire affichée était **2018-2019** alors que nous sommes en **2025**.

## 📊 Analyse

### Où est stockée la session ?

La session courante est stockée dans la **base de données** dans la table `settings` :

```sql
SELECT * FROM settings WHERE type = 'current_session';
```

| type            | description |
|-----------------|-------------|
| current_session | 2018-2019   |

### Comment est-elle affichée ?

La session est affichée dans le header de l'application via :

```php
// resources/views/partials/header.blade.php (ligne 13)
{{ Qs::getSetting('current_session') }}
```

### Est-ce programmé ou en dur ?

**C'est programmé !** La valeur vient de la base de données, pas du code.

Le fichier `database/seeders/SettingsTableSeeder.php` contient la valeur initiale :

```php
['type' => 'current_session', 'description' => '2018-2019'],
```

Cette valeur est insérée lors de l'initialisation de la base de données (seeding).

## ✅ Solution Appliquée

J'ai **mis à jour la session à 2024-2025** dans la base de données.

## 🛠️ Comment Modifier la Session Scolaire

### Méthode 1 : Via l'Interface Web (Recommandé)

1. Connectez-vous en tant que **Super Admin**
2. Allez dans **Settings** (Paramètres)
3. Modifiez le champ **"Current Session"**
4. Sélectionnez la session souhaitée dans la liste déroulante
5. Cliquez sur **"Submit form"**

**Note** : La liste déroulante génère automatiquement les sessions de **3 ans en arrière** jusqu'à **1 an en avant**.

### Méthode 2 : Via la Ligne de Commande (Rapide)

J'ai créé une commande Artisan pour faciliter la mise à jour :

```bash
# Mise à jour interactive (avec confirmation)
php artisan session:update

# Mise à jour directe avec une session spécifique
php artisan session:update 2024-2025

# Mise à jour sans interaction
php artisan session:update 2025-2026 --no-interaction
```

**Exemple d'utilisation interactive** :

```
$ php artisan session:update

Session actuelle: 2024-2025
Session suggérée: 2025-2026
Entrez la nouvelle session (format: YYYY-YYYY) [2025-2026]:
> 2025-2026

Voulez-vous vraiment changer la session de '2024-2025' à '2025-2026'? (yes/no) [yes]:
> yes

✓ Session mise à jour avec succès!
Nouvelle session: 2025-2026
```

### Méthode 3 : Via Tinker (Pour les développeurs)

```bash
php artisan tinker
```

Puis dans Tinker :

```php
$setting = App\Models\Setting::where('type', 'current_session')->first();
$setting->description = '2025-2026';
$setting->save();
exit
```

### Méthode 4 : Via SQL Direct

```sql
UPDATE settings 
SET description = '2025-2026' 
WHERE type = 'current_session';
```

## 📝 Format de la Session

Le format attendu est : **YYYY-YYYY**

Exemples valides :
- `2024-2025`
- `2025-2026`
- `2026-2027`

## 🔄 Génération Automatique des Sessions

Le formulaire de paramètres génère automatiquement les sessions disponibles :

```php
// resources/views/pages/super_admin/settings.blade.php (ligne 27-29)
@for($y=date('Y', strtotime('- 3 years')); $y<=date('Y', strtotime('+ 1 years')); $y++)
    <option>{{ ($y-=1).'-'.($y+=1) }}</option>
@endfor
```

**En 2025, cela génère** :
- 2021-2022
- 2022-2023
- 2023-2024
- 2024-2025
- 2025-2026

## 🎯 Recommandations

### Pour l'Année Scolaire 2024-2025

```bash
php artisan session:update 2024-2025 --no-interaction
```

### Pour l'Année Scolaire 2025-2026

```bash
php artisan session:update 2025-2026 --no-interaction
```

## 🔍 Vérification

Pour vérifier la session actuelle :

```bash
# Via Artisan
php artisan tinker --execute="echo App\Models\Setting::where('type', 'current_session')->first()->description;"

# Via SQL
mysql -u root -e "SELECT description FROM eschool.settings WHERE type='current_session';"
```

## 📌 Notes Importantes

1. **Cache** : Après modification, les caches sont automatiquement vidés par la commande
2. **Permissions** : Seul le Super Admin peut modifier les paramètres via l'interface web
3. **Impact** : La modification affecte immédiatement toute l'application
4. **Examens** : Les examens sont créés pour la session courante
5. **Promotions** : Les promotions d'étudiants utilisent la session courante

## 🗂️ Fichiers Concernés

- **Modèle** : `app/Models/Setting.php`
- **Helper** : `app/Helpers/Qs.php` (méthode `getSetting()`)
- **Seeder** : `database/seeders/SettingsTableSeeder.php`
- **Vue** : `resources/views/pages/super_admin/settings.blade.php`
- **Contrôleur** : `app/Http/Controllers/SuperAdmin/SettingController.php`
- **Commande** : `app/Console/Commands/UpdateCurrentSession.php` ✨ (nouveau)

## ✅ État Actuel

- ✅ Session mise à jour : **2024-2025**
- ✅ Commande Artisan créée : `php artisan session:update`
- ✅ Cache vidé
- ✅ Documentation créée

## 🚀 Prochaines Étapes

1. Rafraîchir la page dans le navigateur
2. Vérifier que la session affichée est maintenant **2024-2025**
3. Si nécessaire, ajuster à **2025-2026** pour l'année en cours
