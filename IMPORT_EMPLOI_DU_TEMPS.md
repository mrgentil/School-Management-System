# 📥 Import/Export Excel - Emploi du Temps

## Vue d'ensemble

Cette fonctionnalité permet aux super administrateurs d'importer et d'exporter des emplois du temps via des fichiers Excel, facilitant ainsi la gestion en masse des cours.

## 🎯 Fonctionnalités

### 1. Téléchargement du Template Excel
- Template pré-formaté avec les en-têtes requis
- Exemples de données pour guider l'utilisateur
- Onglet "Instructions" avec toutes les informations nécessaires
- Liste des matières disponibles pour la classe concernée

### 2. Import d'Emploi du Temps
- Upload de fichier Excel (.xlsx, .xls)
- Validation complète des données avant import
- Création automatique des créneaux horaires
- Mise à jour des cours existants
- Rapport détaillé des erreurs en cas de problème

### 3. Export d'Emploi du Temps
- Export de l'emploi du temps actuel vers Excel
- Format structuré et lisible
- Idéal pour sauvegarde ou partage

## 📋 Format du Fichier Excel

### Structure Requise

| Colonne A | Colonne B | Colonne C |
|-----------|-----------|-----------|
| Jour | Créneau Horaire | Matière |
| Monday | 08:00 AM - 09:00 AM | Mathématiques |
| Monday | 09:00 AM - 10:00 AM | Français |
| Tuesday | 08:00 AM - 09:00 AM | Anglais |

### Colonnes

#### Colonne A : Jour
Jours de la semaine en anglais (obligatoire) :
- `Monday`
- `Tuesday`
- `Wednesday`
- `Thursday`
- `Friday`
- `Saturday`
- `Sunday`

#### Colonne B : Créneau Horaire
Formats acceptés :
- Format 12 heures : `08:00 AM - 09:00 AM` ou `8:00 AM - 9:00 AM`
- Format 24 heures : `14:00 - 15:00` ou `08:00 - 09:00`

**Important :** Le système convertit automatiquement le format 24h en format 12h.

#### Colonne C : Matière
- Nom exact de la matière tel qu'enregistré dans le système
- La matière doit exister pour la classe concernée
- Sensible à la casse (respecter majuscules/minuscules)

## 🔧 Utilisation

### Étape 1 : Accéder à la Fonctionnalité
1. Connectez-vous en tant que Super Admin
2. Allez dans **Emplois du Temps** > **Gérer**
3. Sélectionnez l'emploi du temps à gérer
4. Cliquez sur l'onglet **📥 Import/Export Excel**

### Étape 2 : Télécharger le Template
1. Cliquez sur **"Télécharger le Template"**
2. Ouvrez le fichier Excel téléchargé
3. Consultez l'onglet "Instructions" pour plus de détails

### Étape 3 : Remplir le Fichier
1. Remplissez les données dans l'onglet principal
2. Respectez le format des colonnes
3. Utilisez uniquement les matières listées dans l'onglet "Instructions"
4. Vérifiez qu'il n'y a pas de lignes vides entre les données

### Étape 4 : Importer
1. Cliquez sur **"Parcourir"** et sélectionnez votre fichier
2. Cliquez sur **"Importer l'Emploi du Temps"**
3. Attendez la confirmation d'import
4. Consultez le message de succès ou les erreurs éventuelles

## ✅ Validation des Données

Le système valide automatiquement :

### Validations Obligatoires
- ✓ Toutes les colonnes sont remplies
- ✓ Le jour est valide (Monday-Sunday)
- ✓ Le créneau horaire est au bon format
- ✓ La matière existe pour la classe
- ✓ Les heures sont cohérentes (heure de début < heure de fin)

### Gestion des Erreurs
En cas d'erreur, le système :
- Annule l'import complet (aucune donnée n'est importée)
- Affiche un rapport détaillé avec le numéro de ligne et l'erreur
- Permet de corriger le fichier et de réessayer

## 📊 Exemples

### Exemple 1 : Emploi du Temps Complet

```
| Jour      | Créneau Horaire      | Matière        |
|-----------|---------------------|----------------|
| Monday    | 08:00 AM - 09:00 AM | Mathématiques  |
| Monday    | 09:00 AM - 10:00 AM | Français       |
| Monday    | 10:00 AM - 11:00 AM | Anglais        |
| Tuesday   | 08:00 AM - 09:00 AM | Physique       |
| Tuesday   | 09:00 AM - 10:00 AM | Chimie         |
| Wednesday | 08:00 AM - 09:00 AM | Histoire       |
| Wednesday | 09:00 AM - 10:00 AM | Géographie     |
| Thursday  | 08:00 AM - 09:00 AM | Sport          |
| Friday    | 08:00 AM - 09:00 AM | Arts           |
```

### Exemple 2 : Format 24 Heures

```
| Jour      | Créneau Horaire | Matière        |
|-----------|----------------|----------------|
| Monday    | 08:00 - 09:00  | Mathématiques  |
| Monday    | 09:00 - 10:00  | Français       |
| Monday    | 14:00 - 15:00  | Anglais        |
```

## 🚨 Messages d'Erreur Courants

### "Jour invalide"
**Cause :** Le jour n'est pas au bon format
**Solution :** Utilisez uniquement : Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday

### "Format de créneau horaire invalide"
**Cause :** Le format du créneau ne correspond pas aux formats acceptés
**Solution :** Utilisez `HH:MM AM/PM - HH:MM AM/PM` ou `HH:MM - HH:MM`

### "La matière n'existe pas pour cette classe"
**Cause :** La matière n'est pas enregistrée pour la classe
**Solution :** Vérifiez le nom exact dans l'onglet "Instructions" du template

### "Tous les champs sont requis"
**Cause :** Une ou plusieurs colonnes sont vides
**Solution :** Remplissez toutes les colonnes pour chaque ligne

## 🔐 Permissions

Cette fonctionnalité est réservée aux utilisateurs avec le rôle **Super Admin** uniquement.

## 📝 Notes Techniques

### Fichiers Créés
- `app/Imports/TimetableImport.php` : Classe d'import avec validation
- `app/Http/Controllers/SupportTeam/TimeTableController.php` : Méthodes ajoutées
  - `download_template()` : Génère le template Excel
  - `import_timetable()` : Traite l'import
  - `export_timetable()` : Exporte l'emploi du temps

### Routes Ajoutées
```php
Route::get('download-template/{ttr}', 'download_template')->name('tt.download_template');
Route::post('import/{ttr}', 'import_timetable')->name('tt.import');
Route::get('export/{ttr}', 'export_timetable')->name('tt.export');
```

### Dépendances
- `phpoffice/phpspreadsheet` : Bibliothèque pour manipuler les fichiers Excel

## 🎨 Interface Utilisateur

L'interface est divisée en 3 sections :

1. **Télécharger le Template** (Carte bleue)
   - Bouton de téléchargement
   - Liste des avantages du template

2. **Importer l'Emploi du Temps** (Carte verte)
   - Formulaire d'upload
   - Alertes d'information et d'avertissement

3. **Exporter l'Emploi du Temps** (Carte bleue claire)
   - Bouton d'export
   - Description de la fonctionnalité

4. **Guide d'utilisation** (Carte grise)
   - Instructions pas à pas
   - Exemples de formats

## 🔄 Processus d'Import

1. **Upload du fichier** → Sauvegarde temporaire
2. **Lecture du fichier** → Extraction des données
3. **Validation** → Vérification de toutes les règles
4. **Transaction** → Import en base de données (tout ou rien)
5. **Nettoyage** → Suppression du fichier temporaire
6. **Retour** → Message de succès ou d'erreur

## 💡 Conseils

- Téléchargez toujours le template pour avoir les matières à jour
- Vérifiez votre fichier avant l'import
- Gardez une copie de sauvegarde de votre emploi du temps
- Utilisez l'export pour créer des templates personnalisés
- En cas d'erreur, corrigez le fichier et réessayez

## 🆘 Support

En cas de problème :
1. Vérifiez que le format du fichier est correct
2. Consultez les messages d'erreur détaillés
3. Téléchargez un nouveau template si nécessaire
4. Contactez l'administrateur système si le problème persiste
