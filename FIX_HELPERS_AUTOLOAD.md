# 🔧 Fix: Class "Mk" not found

## ❌ Problème Rencontré

**Erreur:**
```
Class "Mk" not found
```

**Localisation:**
- `resources\views\pages\support_team\grades\index.blade.php:118`
- Route: `/grades`

**Cause:**
Les classes Helper (`Qs` et `Mk`) dans `app/Helpers/` n'étaient pas chargées automatiquement par Laravel, car elles n'étaient pas configurées dans l'autoload de Composer.

---

## ✅ Solution Appliquée

### **1. Création du fichier `app/helpers.php`**

Fichier créé pour définir des alias globaux des classes Helper :

```php
<?php

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
```

### **2. Modification de `composer.json`**

Ajout du fichier helpers.php dans l'autoload :

**Avant:**
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

**Après:**
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [
        "app/helpers.php"
    ]
}
```

### **3. Régénération de l'autoload Composer**

```bash
composer dump-autoload
```

### **4. Nettoyage du cache Laravel**

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

---

## 🎯 Résultat

Les classes `Qs` et `Mk` sont maintenant disponibles **globalement** dans toute l'application :

- ✅ **Dans les vues Blade:** `{{ Mk::getGrade() }}`
- ✅ **Dans les contrôleurs:** `Qs::userIsTeamSA()`
- ✅ **Dans les modèles:** `Mk::examIsLocked()`

---

## 📝 Classes Helper Disponibles

### **1. Classe `Qs`** (`app/Helpers/Qs.php`)

**Fonctions principales:**
- `Qs::getSetting()` - Récupérer un paramètre
- `Qs::userIsTeamSA()` - Vérifier si user est admin
- `Qs::userIsStudent()` - Vérifier si user est étudiant
- `Qs::userIsParent()` - Vérifier si user est parent
- `Qs::hash()` / `Qs::decodeHash()` - Hashage d'IDs
- `Qs::getPanelOptions()` - Options de panel
- Et bien d'autres...

### **2. Classe `Mk`** (`app/Helpers/Mk.php`)

**Fonctions principales:**
- `Mk::examIsLocked()` - Vérifier si examen verrouillé
- `Mk::getGrade()` - Obtenir le grade
- `Mk::getSuffix()` - Obtenir le suffixe (er, ème)
- `Mk::getRemarks()` - Obtenir les remarques
- `Mk::getSubTotalPeriod()` - Total d'une période
- `Mk::getSemesterAverage()` - Moyenne semestrielle
- `Mk::getExamBySemester()` - Examen par semestre
- Et plus...

### **3. Classe `PeriodCalculator`** (`app/Helpers/PeriodCalculator.php`)

**Fonctions principales:**
- `PeriodCalculator::calculatePeriodAverage()` - Calculer moyenne période
- `PeriodCalculator::updatePeriodAverageInMarks()` - MAJ dans marks
- `PeriodCalculator::calculateSemesterAverage()` - Moyenne semestre
- `PeriodCalculator::updateAllPeriodAveragesForStudent()` - Recalcul complet

---

## 🚨 Si l'Erreur Persiste

### **Vérifications:**

1. **Vérifier que le fichier existe:**
   ```bash
   ls app/helpers.php
   ```

2. **Vérifier composer.json:**
   ```bash
   cat composer.json | findstr helpers
   ```

3. **Régénérer l'autoload:**
   ```bash
   composer dump-autoload -o
   ```

4. **Vider TOUS les caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan optimize:clear
   ```

5. **Redémarrer le serveur:**
   ```bash
   # Arrêter avec Ctrl+C
   php artisan serve
   ```

---

## 💡 Utilisation dans les Vues

### **Avant (avec namespace complet):**
```blade
{{ App\Helpers\Mk::getGrade($mark) }}
{{ App\Helpers\Qs::userIsTeamSA() }}
```

### **Après (avec alias):**
```blade
{{ Mk::getGrade($mark) }}
{{ Qs::userIsTeamSA() }}
```

---

## 📚 Bonnes Pratiques

### **1. Toujours utiliser les alias**
```php
// ✅ Bon
Qs::getSetting('school_name')
Mk::getGrade($mark)

// ❌ À éviter
App\Helpers\Qs::getSetting('school_name')
\App\Helpers\Mk::getGrade($mark)
```

### **2. Ajouter de nouveaux helpers**

Si vous créez un nouveau helper dans `app/Helpers/`, ajoutez-le dans `app/helpers.php`:

```php
// Dans app/helpers.php
if (!class_exists('MonNouveauHelper')) {
    class_alias('App\Helpers\MonNouveauHelper', 'MonNouveauHelper');
}
```

Puis regénérez l'autoload:
```bash
composer dump-autoload
```

---

## 🔄 Après Mise à Jour

Chaque fois que vous modifiez `composer.json` ou `app/helpers.php`:

```bash
# 1. Régénérer l'autoload
composer dump-autoload

# 2. Vider le cache
php artisan optimize:clear

# 3. (Optionnel) Redémarrer le serveur
```

---

## ✅ Test de Vérification

Pour vérifier que tout fonctionne, créez une route de test:

```php
// Dans routes/web.php
Route::get('/test-helpers', function () {
    return [
        'Qs_available' => class_exists('Qs'),
        'Mk_available' => class_exists('Mk'),
        'school_name' => Qs::getSetting('school_name'),
        'exam_locked' => Mk::examIsLocked(),
    ];
});
```

Visitez: `http://localhost:8000/test-helpers`

**Résultat attendu:**
```json
{
    "Qs_available": true,
    "Mk_available": true,
    "school_name": "...",
    "exam_locked": false
}
```

---

## 📋 Checklist de Vérification

- [x] Fichier `app/helpers.php` créé
- [x] `composer.json` modifié avec `files: ["app/helpers.php"]`
- [x] `composer dump-autoload` exécuté
- [x] Cache Laravel vidé
- [x] Classes `Qs` et `Mk` disponibles globalement
- [x] Erreur "Class 'Mk' not found" résolue

---

## 🎉 Résultat Final

**L'erreur est maintenant corrigée !**

Toutes les vues et contrôleurs peuvent utiliser `Qs::` et `Mk::` directement sans spécifier le namespace complet.

---

*Document créé le 18 Novembre 2025*
*Fix appliqué avec succès! ✅*
