# Installation du Système Académique RDC

## 📋 Prérequis

- Laravel 8
- PHP 8.2
- Base de données MySQL/MariaDB
- Accès administrateur à l'application

## 🚀 Installation

### Étape 1 : Sauvegarder la Base de Données

**IMPORTANT** : Faites une sauvegarde complète avant de procéder !

```bash
# Via ligne de commande
mysqldump -u root -p eschool > backup_before_rdc_$(date +%Y%m%d).sql

# Ou via phpMyAdmin : Exporter la base de données
```

### Étape 2 : Exécuter les Migrations

Ouvrez un terminal dans le dossier du projet et exécutez :

```bash
php artisan migrate
```

Cette commande va :
1. ✅ Renommer `exams.term` → `exams.semester`
2. ✅ Ajouter le champ `assignments.period`
3. ✅ Ajouter les colonnes pour les 4 périodes dans `marks`
4. ✅ Ajouter les paramètres système
5. ✅ Convertir les données existantes

### Étape 3 : Vérifier la Migration

Connectez-vous à votre base de données et vérifiez :

```sql
-- Vérifier la table exams
DESCRIBE exams;
-- Devrait avoir une colonne 'semester' au lieu de 'term'

-- Vérifier la table assignments
DESCRIBE assignments;
-- Devrait avoir une colonne 'period'

-- Vérifier les settings
SELECT * FROM settings WHERE type IN ('academic_system', 'period_count', 'semester_count');
```

### Étape 4 : (Optionnel) Créer des Examens de Test

Pour créer automatiquement les examens des 2 semestres :

```bash
php artisan db:seed --class=RdcAcademicSystemSeeder
```

### Étape 5 : Vider le Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## ✅ Vérification Post-Installation

### 1. Vérifier les Examens

1. Connectez-vous en tant qu'administrateur
2. Allez dans **Académique > Examens**
3. Cliquez sur **Ajouter un Examen**
4. Vérifiez que le champ "Semestre" affiche :
   - Semestre 1 (Périodes 1 & 2)
   - Semestre 2 (Périodes 3 & 4)

### 2. Vérifier les Devoirs

1. Allez dans **Académique > Devoirs**
2. Cliquez sur **Créer un Devoir**
3. Vérifiez que le champ "Période" affiche :
   - Période 1 (Semestre 1)
   - Période 2 (Semestre 1)
   - Période 3 (Semestre 2)
   - Période 4 (Semestre 2)

### 3. Tester la Création

Créez un examen et un devoir de test pour vous assurer que tout fonctionne correctement.

## 🔄 Ajustements Post-Migration

### Devoirs Existants

Tous les devoirs existants ont été assignés à la **Période 1** par défaut. Pour les réassigner :

1. Allez dans **Académique > Devoirs**
2. Cliquez sur le bouton **Modifier** (crayon) pour chaque devoir
3. Sélectionnez la bonne période
4. Enregistrez

### Examens Existants

- Les anciens examens avec `term 1 ou 2` → **Semestre 1**
- Les anciens examens avec `term 3` → **Semestre 2**

Vérifiez que cette conversion est correcte pour vos données.

## ⚠️ En Cas de Problème

### Rollback de la Migration

Si vous rencontrez des problèmes, vous pouvez annuler les migrations :

```bash
php artisan migrate:rollback --step=3
```

Puis restaurez votre sauvegarde :

```bash
mysql -u root -p eschool < backup_before_rdc_YYYYMMDD.sql
```

### Erreurs Courantes

#### Erreur : "Column already exists"

Si vous obtenez cette erreur, c'est que la migration a déjà été partiellement exécutée.

**Solution** :
```bash
php artisan migrate:fresh --seed
# ⚠️ ATTENTION : Cela supprime TOUTES les données !
# À utiliser UNIQUEMENT en développement
```

En production, contactez l'administrateur système.

#### Erreur : "Unknown column 'semester'"

Le cache n'a pas été vidé.

**Solution** :
```bash
php artisan cache:clear
php artisan config:clear
```

## 📞 Support

Pour toute assistance :

1. Consultez la documentation complète : `SYSTEME_RDC_PERIODES.md`
2. Vérifiez les logs : `storage/logs/laravel.log`
3. Contactez l'équipe de développement

## 📊 Résumé des Changements

| Élément | Avant | Maintenant |
|---------|-------|------------|
| **Périodes** | 3 terms | 4 périodes |
| **Examens** | Par term (1, 2, 3) | Par semestre (1, 2) |
| **Devoirs** | Sans période | Avec période (1-4) |
| **Structure** | 3 terms indépendants | 2 semestres de 2 périodes |

---

**Date d'installation** : _______________

**Installé par** : _______________

**Notes** : 
```
_____________________________________________________________________
_____________________________________________________________________
_____________________________________________________________________
```

✅ Installation réussie !
