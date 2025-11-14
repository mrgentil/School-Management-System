# 🧹 NETTOYAGE DES COURS ORPHELINS

**Problème:** Vous avez des cours avec des créneaux supprimés qui causent des erreurs

---

## 📊 SITUATION ACTUELLE

D'après le dernier `dd()`, vous avez **6 cours** :

### ✅ Cours Valides (2)
```
ID 9:  Monday    + Créneau 6 (7:30 AM - 8:30 AM)   + English Language  ✅
ID 10: Tuesday   + Créneau 5 (10:00 AM - 10:50 AM) + Mathematics       ✅
```

### ❌ Cours Orphelins (4) - À SUPPRIMER
```
ID 5: Monday    + Créneau 3 (supprimé) + English Language  ❌
ID 8: Monday    + Créneau 4 (supprimé) + Mathematics       ❌
ID 6: Tuesday   + Créneau 4 (supprimé) + Mathematics       ❌
ID 7: Wednesday + Créneau 4 (supprimé) + English Language  ❌
```

---

## 🔧 SOLUTION : Supprimer les Cours Orphelins

### Option 1: Via SQL (RAPIDE) ✅

```sql
-- Supprimer les cours avec créneaux orphelins (3 et 4)
DELETE FROM time_tables 
WHERE ttr_id = 3 
AND ts_id IN (3, 4);

-- Vérifier les cours restants
SELECT id, day, ts_id, subject_id 
FROM time_tables 
WHERE ttr_id = 3;

-- Résultat attendu: Seulement 2 cours (ID 9 et 10)
```

### Option 2: Via l'Interface

1. Allez sur http://localhost:8000/timetables/records/manage/3
2. Onglet "✏️ Modifier les Matières"
3. Vous verrez les cours avec "(Créneau supprimé)"
4. Pour chaque cours orphelin:
   - Cliquez sur l'icône 🗑️ (poubelle)
   - Confirmez la suppression

---

## 📋 APRÈS LE NETTOYAGE

Vous aurez un emploi du temps propre avec seulement 2 cours :

```
Monday    07:30-08:30  English Language
Tuesday   10:00-10:50  Mathematics
```

---

## 🚀 CRÉER UN EMPLOI DU TEMPS COMPLET

### Étape 1: Créer Plus de Créneaux

Créez au moins 5-6 créneaux pour une journée complète :

```
Créneau 1: 08:00 AM - 09:00 AM
Créneau 2: 09:00 AM - 10:00 AM
Créneau 3: 10:00 AM - 11:00 AM
Créneau 4: 11:00 AM - 12:00 PM
Créneau 5: 01:00 PM - 02:00 PM
Créneau 6: 02:00 PM - 03:00 PM
```

### Étape 2: Ajouter des Cours

Pour chaque jour (Monday à Friday), ajoutez 5-6 cours :

**Monday:**
```
Monday + Mathématiques + 08:00 AM - 09:00 AM
Monday + Français + 09:00 AM - 10:00 AM
Monday + Sciences + 10:00 AM - 11:00 AM
Monday + Histoire + 11:00 AM - 12:00 PM
Monday + Anglais + 01:00 PM - 02:00 PM
Monday + Sport + 02:00 PM - 03:00 PM
```

**Tuesday, Wednesday, Thursday, Friday:** Répétez avec différentes matières

---

## ⚠️ IMPORTANT

**NE PAS MODIFIER** les cours orphelins, **SUPPRIMEZ-LES** !

Quand vous essayez de modifier un cours orphelin, vous risquez de créer un doublon avec un cours existant, d'où l'erreur :

```
Duplicate entry '3-6-Monday' for key 'time_tables_ttr_id_ts_id_day_unique'
```

Cette contrainte empêche d'avoir 2 cours au même moment (même jour + même créneau).

---

## 🎯 CHECKLIST

- [ ] Exécuter la requête SQL de suppression
- [ ] Vérifier qu'il reste seulement 2 cours
- [ ] Créer 5-6 nouveaux créneaux horaires (avec AM/PM corrects)
- [ ] Ajouter des cours pour tous les jours de la semaine
- [ ] Tester la vue liste (tous les jours visibles)
- [ ] Tester la vue calendrier (calendrier complet)

---

## 📞 COMMANDE SQL COMPLÈTE

```sql
-- 1. Supprimer les cours orphelins
DELETE FROM time_tables WHERE ttr_id = 3 AND ts_id IN (3, 4);

-- 2. Vérifier
SELECT 
    tt.id,
    tt.day,
    ts.full as time_slot,
    s.name as subject
FROM time_tables tt
LEFT JOIN time_slots ts ON tt.ts_id = ts.id
LEFT JOIN subjects s ON tt.subject_id = s.id
WHERE tt.ttr_id = 3;

-- Résultat attendu:
-- ID 9:  Monday   | 7:30 AM - 8:30 AM   | English Language
-- ID 10: Tuesday  | 10:00 AM - 10:50 AM | Mathematics
```

**Exécutez cette requête dans phpMyAdmin et vous serez prêt !** 🚀
