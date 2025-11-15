# Système Académique RDC - 4 Périodes + 2 Semestres

## 📚 Vue d'ensemble

Ce document explique le système académique implémenté pour la République Démocratique du Congo (RDC), basé sur **4 périodes** et **2 semestres**.

## 🎯 Structure du Système

### Organisation Annuelle

```
ANNÉE SCOLAIRE
│
├── SEMESTRE 1
│   ├── Période 1 (Septembre - Novembre)
│   │   ├── Interrogations
│   │   ├── Devoirs
│   │   └── Cotations continues
│   │
│   ├── Période 2 (Décembre - Février)
│   │   ├── Interrogations
│   │   ├── Devoirs
│   │   └── Cotations continues
│   │
│   └── EXAMEN SEMESTRE 1
│
└── SEMESTRE 2
    ├── Période 3 (Mars - Mai)
    │   ├── Interrogations
    │   ├── Devoirs
    │   └── Cotations continues
    │
    ├── Période 4 (Juin - Juillet)
    │   ├── Interrogations
    │   ├── Devoirs
    │   └── Cotations continues
    │
    └── EXAMEN SEMESTRE 2
```

## 🔧 Modifications Techniques

### 1. Base de Données

#### Table `exams`
- **Avant** : Champ `term` (valeurs: 1, 2, 3)
- **Maintenant** : Champ `semester` (valeurs: 1, 2)
- **Signification** : 
  - `semester = 1` : Examen du premier semestre (après périodes 1 & 2)
  - `semester = 2` : Examen du deuxième semestre (après périodes 3 & 4)

#### Table `assignments` (Devoirs)
- **Nouveau champ** : `period` (valeurs: 1, 2, 3, 4)
- **Signification** :
  - `period = 1` : Devoir de la 1ère période (Semestre 1)
  - `period = 2` : Devoir de la 2ème période (Semestre 1)
  - `period = 3` : Devoir de la 3ème période (Semestre 2)
  - `period = 4` : Devoir de la 4ème période (Semestre 2)

#### Table `marks` (Notes)
Nouvelles colonnes ajoutées :
- `p1_avg` : Moyenne de la période 1
- `p2_avg` : Moyenne de la période 2
- `p3_avg` : Moyenne de la période 3
- `p4_avg` : Moyenne de la période 4
- `s1_exam` : Note de l'examen du semestre 1
- `s2_exam` : Note de l'examen du semestre 2

### 2. Paramètres Système (Settings)

Trois nouveaux paramètres ajoutés dans la table `settings` :

| Type | Description | Valeur |
|------|-------------|---------|
| `academic_system` | Système académique utilisé | `rdc` |
| `period_count` | Nombre de périodes | `4` |
| `semester_count` | Nombre de semestres | `2` |

### 3. Modèles Laravel

#### Exam Model
```php
protected $fillable = ['name', 'semester', 'year'];
```

#### Assignment Model
```php
protected $fillable = [
    'title', 'description', 'my_class_id', 'section_id', 
    'subject_id', 'period', 'due_date', 'max_score', 
    'teacher_id', 'file_path', 'status'
];
```

### 4. Helpers (Mk.php)

#### Nouvelles méthodes

**Pour les périodes :**
```php
Mk::getSubTotalPeriod($student_id, $subject_id, $period, $class_id, $year)
// $period = 1, 2, 3 ou 4
```

**Pour les semestres :**
```php
Mk::getSemesterAverage($student_id, $semester, $year)
Mk::getSemesterTotal($student_id, $semester, $year)
Mk::getExamBySemester($semester, $year)
// $semester = 1 ou 2
```

#### Méthodes legacy (compatibilité)
Les anciennes méthodes avec `term` sont conservées et redirigent vers les nouvelles méthodes pour maintenir la compatibilité.

## 📋 Utilisation

### Créer un Examen

1. Aller dans **Académique > Examens > Ajouter un Examen**
2. Remplir le formulaire :
   - **Nom** : Ex. "Examen Semestriel 1 - 2024"
   - **Semestre** : Choisir 1 ou 2
     - Semestre 1 (Périodes 1 & 2)
     - Semestre 2 (Périodes 3 & 4)

### Créer un Devoir

1. Aller dans **Académique > Devoirs > Créer un Devoir**
2. Remplir le formulaire :
   - Tous les champs habituels...
   - **Période** : Choisir 1, 2, 3 ou 4
     - Période 1 (Semestre 1)
     - Période 2 (Semestre 1)
     - Période 3 (Semestre 2)
     - Période 4 (Semestre 2)

## 🔄 Migration

Pour appliquer les modifications, exécuter :

```bash
php artisan migrate
```

Cette commande va :
1. Renommer `exams.term` en `exams.semester`
2. Ajouter `assignments.period`
3. Ajouter les colonnes de périodes et semestres dans `marks`
4. Insérer les nouveaux paramètres dans `settings`

## ⚠️ Important

### Données Existantes

- Les **examens existants** avec `term=1,2,3` seront automatiquement convertis
- Les **devoirs existants** auront `period=1` par défaut (ajustez manuellement si nécessaire)

### Calcul des Notes

Le calcul des bulletins doit tenir compte de :
- **Moyenne de période** : Moyenne des interrogations + devoirs de la période
- **Note semestrielle** : 
  ```
  Note finale semestre = (Moyenne Période 1 + Moyenne Période 2 + Examen Semestre) / 3
  ```
  ou selon la formule de pondération de votre école

### Exemples de Pondération Courante en RDC

**Option 1 : Pondération égale**
```
Semestre 1 = (Période 1 + Période 2 + Examen S1) / 3
```

**Option 2 : Examen pondéré**
```
Semestre 1 = ((Période 1 + Période 2) / 2 × 40%) + (Examen S1 × 60%)
```

## 🎓 Workflow Recommandé

1. **Début de période** : Créer les devoirs pour cette période
2. **Pendant la période** : Les étudiants soumettent leurs devoirs, passer les interrogations
3. **Fin de période** : Calculer la moyenne de période
4. **Fin de semestre** : Organiser l'examen semestriel
5. **Après examen** : Calculer la note finale du semestre
6. **Fin d'année** : Note annuelle = (Semestre 1 + Semestre 2) / 2

## 📞 Support

Pour toute question ou problème concernant ce système, contactez l'administrateur système.

---

**Dernière mise à jour** : 15 Novembre 2025
**Version** : 1.0
**Auteur** : Équipe Développement eSchool
