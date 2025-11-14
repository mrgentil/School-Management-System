# 📅 GUIDE COMPLET - SYSTÈME D'EMPLOI DU TEMPS

**Date:** 14 Novembre 2025  
**Pour:** Super Admin  
**URL:** http://localhost:8000/timetables

---

## 🎯 COMPRENDRE LE SYSTÈME

### Architecture en 3 niveaux

```
1. TimeTable Record (TTR) - Enregistrement principal
   ↓
2. Time Slots (TS) - Créneaux horaires
   ↓
3. TimeTable (TT) - Cours assignés
```

### 📊 Structure de la base de données

#### 1. **TimeTableRecord** (Enregistrement principal)
```
- id
- name (ex: "Emploi du temps Classe 1A - Trimestre 1")
- my_class_id (Classe concernée)
- exam_id (Optionnel - pour emploi du temps d'examen)
- year (Session scolaire: 2025-2026)
```

#### 2. **TimeSlot** (Créneaux horaires)
```
- id
- ttr_id (Lien vers TimeTableRecord)
- time_from (ex: "08:00 AM")
- time_to (ex: "09:00 AM")
- full (ex: "08:00 AM - 09:00 AM")
- timestamp_from
- timestamp_to
```

#### 3. **TimeTable** (Cours assignés)
```
- id
- ttr_id (Lien vers TimeTableRecord)
- ts_id (Lien vers TimeSlot)
- subject_id (Matière enseignée)
- day (Jour de la semaine: Monday, Tuesday, etc.)
- exam_date (Pour les examens)
- timestamp_from
- timestamp_to
```

---

## 🚀 GUIDE ÉTAPE PAR ÉTAPE

### ÉTAPE 1: Créer un Emploi du Temps

1. **Aller sur** http://localhost:8000/timetables
2. **Cliquer sur l'onglet** "Create Timetable"
3. **Remplir le formulaire:**
   - **Name:** "Emploi du temps Classe 1A - 2025"
   - **Class:** Sélectionner la classe (ex: Classe 1A)
   - **Type:** 
     - Laisser vide pour un emploi du temps normal
     - Sélectionner un examen pour un emploi du temps d'examen
4. **Cliquer sur** "Submit form"

✅ **Résultat:** Un enregistrement d'emploi du temps est créé

---

### ÉTAPE 2: Configurer les Créneaux Horaires

1. **Aller dans** "Show TimeTables" → Sélectionner votre classe
2. **Cliquer sur** "Manage" pour l'emploi du temps créé
3. **Vous verrez la page de gestion avec:**
   - Section "Add Time Slot" (Ajouter un créneau)
   - Liste des créneaux existants

#### Ajouter un créneau horaire:

**Exemple 1: Cours du matin (8h-9h)**
```
Hour From: 08
Min From: 00
Meridian From: AM

Hour To: 09
Min To: 00
Meridian To: AM
```

**Exemple 2: Cours de l'après-midi (2h-3h)**
```
Hour From: 02
Min From: 00
Meridian From: PM

Hour To: 03
Min To: 00
Meridian To: PM
```

4. **Cliquer sur** "Submit form"
5. **Répéter** pour tous les créneaux de la journée

**Exemple de créneaux typiques:**
- 08:00 AM - 09:00 AM (Cours 1)
- 09:00 AM - 10:00 AM (Cours 2)
- 10:00 AM - 10:15 AM (Récréation)
- 10:15 AM - 11:15 AM (Cours 3)
- 11:15 AM - 12:15 PM (Cours 4)
- 12:15 PM - 01:00 PM (Déjeuner)
- 01:00 PM - 02:00 PM (Cours 5)
- 02:00 PM - 03:00 PM (Cours 6)

---

### ÉTAPE 3: Assigner les Matières

1. **Dans la page "Manage"**, vous verrez maintenant vos créneaux
2. **Pour chaque jour de la semaine** (Monday, Tuesday, etc.):
   - Cliquer sur le bouton "+" à côté du créneau
   - Sélectionner la matière (ex: Mathématiques, Français, etc.)
   - Cliquer sur "Submit"

**Exemple d'assignation pour Lundi:**
```
08:00 AM - 09:00 AM → Mathématiques
09:00 AM - 10:00 AM → Français
10:15 AM - 11:15 AM → Sciences
11:15 AM - 12:15 PM → Histoire
01:00 PM - 02:00 PM → Anglais
02:00 PM - 03:00 PM → Sport
```

3. **Répéter pour tous les jours** de la semaine

---

### ÉTAPE 4: Visualiser l'Emploi du Temps

1. **Retourner à** "Show TimeTables" → Votre classe
2. **Cliquer sur** "View" pour voir l'emploi du temps complet
3. **Vous verrez un tableau** avec:
   - Colonnes: Jours de la semaine
   - Lignes: Créneaux horaires
   - Cellules: Matières assignées

---

## 💡 ASTUCES ET BONNES PRATIQUES

### ✅ Recommandations

1. **Nommer clairement les emplois du temps**
   - ❌ Mauvais: "EDT 1"
   - ✅ Bon: "Emploi du temps Classe 1A - Trimestre 1 - 2025"

2. **Créer des créneaux cohérents**
   - Utiliser les mêmes créneaux pour toutes les classes
   - Prévoir des pauses entre les cours
   - Respecter les heures de déjeuner

3. **Utiliser la fonction "Use Time Slot"**
   - Si vous avez déjà créé des créneaux pour une classe
   - Vous pouvez les réutiliser pour d'autres classes
   - Cela évite de recréer les mêmes créneaux

4. **Vérifier avant de publier**
   - Utiliser "View" pour vérifier l'emploi du temps
   - S'assurer qu'aucun créneau n'est vide
   - Vérifier qu'il n'y a pas de conflits

---

## 🎨 FONCTIONNALITÉS DISPONIBLES

### Pour le Super Admin:
- ✅ Créer des emplois du temps
- ✅ Gérer les créneaux horaires
- ✅ Assigner les matières
- ✅ Modifier les emplois du temps
- ✅ Supprimer les emplois du temps
- ✅ Imprimer les emplois du temps
- ✅ Visualiser tous les emplois du temps

### Pour les Étudiants:
- ✅ Voir leur emploi du temps de classe
- ✅ Vue calendrier
- ✅ Vue liste
- ✅ Notifications des cours à venir

### Pour les Enseignants:
- ✅ Voir les emplois du temps des classes qu'ils enseignent
- ✅ Voir leurs propres horaires

---

## 🔧 PROBLÈMES COURANTS ET SOLUTIONS

### ❌ Problème 1: "Les créneaux ne s'affichent pas"
**Solution:** Assurez-vous d'avoir créé au moins un créneau horaire dans la page "Manage"

### ❌ Problème 2: "Je ne peux pas assigner de matière"
**Solution:** 
1. Vérifiez que la classe a des matières assignées
2. Allez dans "Matières" et assignez des matières à la classe

### ❌ Problème 3: "L'emploi du temps est vide"
**Solution:** Vous devez assigner des matières à chaque créneau pour chaque jour

### ❌ Problème 4: "Les heures ne correspondent pas"
**Solution:** Vérifiez que vous utilisez le bon format AM/PM

---

## 📱 ACCÈS ÉTUDIANT

Les étudiants peuvent voir leur emploi du temps via:
1. **Menu Étudiant** → "Emploi du Temps"
2. **Deux vues disponibles:**
   - Vue Liste: Affichage classique par jour
   - Vue Calendrier: Affichage calendrier interactif

---

## 🎯 WORKFLOW COMPLET (Exemple)

### Scénario: Créer l'emploi du temps pour Classe 1A

```
1. Créer l'enregistrement
   ↓
   Name: "EDT Classe 1A - Trimestre 1"
   Class: Classe 1A
   Type: Class Timetable
   
2. Ajouter les créneaux horaires
   ↓
   08:00 AM - 09:00 AM
   09:00 AM - 10:00 AM
   10:00 AM - 10:15 AM (Récréation)
   10:15 AM - 11:15 AM
   11:15 AM - 12:15 PM
   12:15 PM - 01:00 PM (Déjeuner)
   01:00 PM - 02:00 PM
   02:00 PM - 03:00 PM
   
3. Assigner les matières pour Lundi
   ↓
   08:00 - 09:00: Mathématiques
   09:00 - 10:00: Français
   10:15 - 11:15: Sciences
   11:15 - 12:15: Histoire
   01:00 - 02:00: Anglais
   02:00 - 03:00: Sport
   
4. Répéter pour Mardi, Mercredi, Jeudi, Vendredi
   
5. Vérifier avec "View"
   
6. Publier ✅
```

---

## 🚀 AMÉLIORATIONS SUGGÉRÉES

### Court terme:
1. ✅ Traduction complète en français
2. ✅ Interface plus intuitive
3. ✅ Drag & drop pour assigner les matières
4. ✅ Vue calendrier améliorée

### Moyen terme:
1. ✅ Détection automatique des conflits
2. ✅ Suggestions d'emploi du temps
3. ✅ Templates d'emploi du temps
4. ✅ Export PDF amélioré

### Long terme:
1. ✅ Génération automatique d'emploi du temps
2. ✅ Optimisation des horaires
3. ✅ Intégration avec les salles de classe
4. ✅ Notifications push pour les étudiants

---

## 📞 BESOIN D'AIDE ?

Si vous avez des questions ou rencontrez des problèmes:
1. Consultez ce guide
2. Vérifiez que toutes les données de base sont configurées (Classes, Matières, Enseignants)
3. Testez avec une classe avant de déployer pour toutes les classes

**Bon courage avec la configuration des emplois du temps !** 📅✨
