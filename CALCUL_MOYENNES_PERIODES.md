# Système de Calcul Automatique des Moyennes par Période

## 🎯 Vue d'ensemble

Le système calcule **automatiquement** les moyennes de chaque période basées sur les notes des devoirs et interrogations.

## 📊 Logique de Calcul

### Exemple : Mathématiques - Période 1

```
Devoirs de la Période 1:
├── Devoir 1 : 15/20 (75%)
├── Devoir 2 : 36/40 (90%)
├── Interrogation 1 : 8/10 (80%)
└── MOYENNE P1 = (75% + 90% + 80%) / 3 = 81.67% = 16.33/20
```

### Formule

```
Moyenne Période = Moyenne de tous les devoirs notés de cette période
(toutes les notes sont ramenées sur 20 pour uniformisation)
```

## 🤖 Calcul Automatique

### Quand se déclenche le calcul ?

Le calcul se fait **AUTOMATIQUEMENT** quand :

1. ✅ Un enseignant **note un devoir**
2. ✅ La note est enregistrée dans la base

### Processus

```
Enseignant note Devoir 2 → Score: 18/20
         ↓
Système enregistre la note
         ↓
Système calcule automatiquement:
- Moyenne de TOUS les devoirs de cette période
- Met à jour marks.p2_avg (si c'est période 2)
         ↓
✅ Moyenne à jour instantanément
```

## 📁 Structure Base de Données

### Table `marks`

| Colonne | Type | Description |
|---------|------|-------------|
| `p1_avg` | decimal(5,2) | Moyenne Période 1 |
| `p2_avg` | decimal(5,2) | Moyenne Période 2 |
| `p3_avg` | decimal(5,2) | Moyenne Période 3 |
| `p4_avg` | decimal(5,2) | Moyenne Période 4 |
| `s1_exam` | integer | Note Examen Semestre 1 |
| `s2_exam` | integer | Note Examen Semestre 2 |

### Exemple de Données

```sql
-- Étudiant Jean, Mathématiques, 6ème A
INSERT INTO marks (student_id, subject_id, my_class_id, section_id, year, p1_avg, p2_avg, p3_avg, p4_avg)
VALUES (15, 3, 2, 1, '2024-2025', 16.33, 14.50, NULL, NULL);
```

## 🔧 Utilisation

### 1. Calcul Automatique (Recommandé)

**Rien à faire !** Le système calcule automatiquement quand vous notez.

### 2. Recalculer Manuellement (Si nécessaire)

Si vous devez recalculer toutes les moyennes :

```bash
# Recalculer pour TOUS les étudiants
php artisan periods:calculate

# Recalculer pour UN étudiant spécifique
php artisan periods:calculate --student_id=15
```

## 📊 Exemple Complet

### Situation : Jean - 6ème A - Mathématiques - Période 1

#### Devoirs de la Période 1

| Date | Devoir | Note | Sur | Pourcentage |
|------|--------|------|-----|-------------|
| 10/09 | Devoir 1 | 15 | 20 | 75% |
| 15/09 | Interrogation 1 | 8 | 10 | 80% |
| 20/09 | Devoir 2 | 36 | 40 | 90% |

#### Calcul

```
Moyenne P1 = (75% + 80% + 90%) / 3 = 81.67%
           = 16.33/20
```

#### Dans la Base

```sql
UPDATE marks 
SET p1_avg = 16.33 
WHERE student_id = 15 
  AND subject_id = 3 
  AND year = '2024-2025';
```

## 🎓 Calcul des Moyennes Semestrielles

### Formule

```
Moyenne Semestre 1 = (Période 1 + Période 2) / 2
Moyenne Semestre 2 = (Période 3 + Période 4) / 2
```

### Exemple

```
Jean - Mathématiques:
├── Période 1: 16.33/20
├── Période 2: 14.50/20
└── Moyenne S1 = (16.33 + 14.50) / 2 = 15.42/20
```

### Code

```php
use App\Helpers\PeriodCalculator;

// Calculer la moyenne du semestre 1
$semesterAvg = PeriodCalculator::calculateSemesterAverage(
    $student_id,   // 15
    $subject_id,   // 3 (Maths)
    1,             // Semestre 1
    $class_id,     // 2 (6ème A)
    $year          // '2024-2025'
);

echo $semesterAvg; // 15.42
```

## 📱 Visualisation

### Pour les Étudiants

Les étudiants peuvent voir leurs moyennes de périodes dans :
- 📊 Tableau de bord
- 📝 Bulletin de notes
- 📈 Section "Mes Notes"

### Pour les Enseignants

Les enseignants voient les moyennes dans :
- 📊 Tableau de bord classe
- 📝 Saisie des notes
- 📈 Statistiques

## 🔄 Workflow Complet

```
PÉRIODE 1 (Sept-Nov)
│
├─ Semaine 1: Devoir 1 créé
├─ Semaine 2: Étudiants soumettent
├─ Semaine 3: Enseignant note → CALCUL AUTO ✅
│             Moyenne P1 mise à jour
│
├─ Semaine 4: Devoir 2 créé
├─ Semaine 5: Étudiants soumettent
├─ Semaine 6: Enseignant note → CALCUL AUTO ✅
│             Moyenne P1 mise à jour (moyenne de Devoir 1 + 2)
│
└─ Fin période: Moyenne P1 finale disponible
```

## 🛠️ API/Helpers Disponibles

### PeriodCalculator

```php
use App\Helpers\PeriodCalculator;

// 1. Calculer moyenne d'une période
$avg = PeriodCalculator::calculatePeriodAverage(
    $student_id, 
    $subject_id, 
    $period,     // 1, 2, 3, ou 4
    $class_id, 
    $year
);

// 2. Mettre à jour dans marks
PeriodCalculator::updatePeriodAverageInMarks(
    $student_id,
    $subject_id,
    $period,
    $class_id,
    $section_id,
    $year
);

// 3. Calculer moyenne semestre
$semAvg = PeriodCalculator::calculateSemesterAverage(
    $student_id,
    $subject_id,
    $semester,   // 1 ou 2
    $class_id,
    $year
);

// 4. Recalculer tout pour un étudiant
PeriodCalculator::updateAllPeriodAveragesForStudent(
    $student_id,
    $class_id,
    $section_id,
    $year
);
```

## ⚠️ Points Importants

### 1. Notes Manquantes

- Si un étudiant n'a **aucune note** dans une période → `NULL`
- Si un étudiant a **quelques notes** → moyenne calculée sur les notes existantes

### 2. Notes sur Différentes Bases

Toutes les notes sont **normalisées sur 20** :
- 15/20 = 75% = 15/20
- 8/10 = 80% = 16/20
- 36/40 = 90% = 18/20

### 3. Statut du Devoir

Seuls les devoirs **notés** (`status = 'graded'`) sont inclus dans le calcul.

## 🎯 Avantages

1. ✅ **Automatique** : Pas de calcul manuel
2. ✅ **Temps réel** : Mise à jour instantanée
3. ✅ **Précis** : Pas d'erreur de calcul
4. ✅ **Uniforme** : Toutes les notes sur 20
5. ✅ **Auditable** : Tracé dans la base de données

---

**Dernière mise à jour** : 15 Novembre 2025  
**Version** : 1.0
