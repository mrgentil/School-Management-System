# 🎓 Système d'Examens: SESSION et HORS SESSION

## 📚 Vue d'Ensemble

Ce système implémente la gestion complète des examens selon le modèle RDC avec deux types d'examens distincts :

### **1. EXAMENS HORS SESSION** (Examens Réguliers)
- ✅ Étudiants restent dans leurs salles habituelles
- ✅ Chaque classe a son propre calendrier
- ✅ Pas de réorganisation des élèves
- ✅ Gestion simple par classe

### **2. EXAMENS SESSION** (Examens Officiels)
- ✅ Réorganisation par performance académique
- ✅ Mélange des classes et options
- ✅ Placement automatique dans les salles A, B, C
- ✅ But: éviter la triche, meilleure surveillance

---

## 🗄️ Structure de la Base de Données

### **1. Table `exams` (Modifiée)**

```sql
ALTER TABLE exams ADD COLUMN exam_type ENUM('hors_session', 'session') DEFAULT 'hors_session';
```

**Colonnes:**
- `exam_type`: Type d'examen
  - `hors_session`: Examen régulier
  - `session`: Examen officiel avec réorganisation

### **2. Table `exam_rooms` (Nouvelle)**

Stocke les salles d'examen disponibles pour les Sessions.

```sql
CREATE TABLE exam_rooms (
    id INT PRIMARY KEY,
    name VARCHAR(255),           -- Salle A1, Salle B1, etc.
    code VARCHAR(50) UNIQUE,     -- A1, B1, C1, etc.
    building VARCHAR(255),       -- Bâtiment Principal, Annexe, etc.
    capacity INT,                -- Capacité maximale
    level ENUM('excellence', 'moyen', 'faible'),
    is_active BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Niveaux des salles:**
- **excellence**: Pour les 30% meilleurs étudiants
- **moyen**: Pour les 40% moyens
- **faible**: Pour les 30% restants

### **3. Table `exam_schedules` (Modifiée)**

```sql
ALTER TABLE exam_schedules ADD COLUMN exam_room_id INT NULL;
```

**Fonctionnement:**
- **HORS SESSION**: `exam_room_id` est NULL (salle habituelle)
- **SESSION**: `exam_room_id` pointe vers une salle spécifique

### **4. Table `exam_student_placements` (Nouvelle)**

Stocke le placement des étudiants pour les examens SESSION.

```sql
CREATE TABLE exam_student_placements (
    id INT PRIMARY KEY,
    exam_schedule_id INT,        -- Horaire d'examen
    student_id INT,              -- Étudiant
    exam_room_id INT,            -- Salle assignée
    seat_number INT,             -- Numéro de place
    ranking_score DECIMAL(8,2),  -- Score utilisé pour le classement
    performance_level ENUM('excellence', 'moyen', 'faible'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE(exam_schedule_id, student_id)
);
```

---

## 🎯 Workflow des Examens

### **A. EXAMENS HORS SESSION**

#### **1. Création de l'Examen**
```php
Exam::create([
    'name' => 'Examen Période 1',
    'semester' => 1,
    'year' => '2024-2025',
    'exam_type' => 'hors_session',  // ← Important
]);
```

#### **2. Création du Calendrier (Par Classe)**

**Exemple: JSS2A**
```php
ExamSchedule::create([
    'exam_id' => 1,
    'my_class_id' => 5, // JSS2A
    'section_id' => 1,
    'subject_id' => 10, // Math
    'exam_date' => '2024-11-25',
    'start_time' => '08:00',
    'end_time' => '10:00',
    'exam_room_id' => null,  // ← NULL pour HORS SESSION
]);
```

**Exemple: JSS3B (même heure, autre matière)**
```php
ExamSchedule::create([
    'exam_id' => 1,
    'my_class_id' => 7, // JSS3B
    'section_id' => 2,
    'subject_id' => 15, // Anglais
    'exam_date' => '2024-11-25',
    'start_time' => '08:00',  // ← Même heure que JSS2A
    'end_time' => '10:00',
    'exam_room_id' => null,  // ← Pas de salle (restent dans leur classe)
]);
```

#### **3. Les Étudiants Voient:**
- "Math - 25/11 8h-10h - Votre salle habituelle"
- Pas besoin de chercher de salle

---

### **B. EXAMENS SESSION**

#### **1. Création de l'Examen**
```php
Exam::create([
    'name' => 'Examen de Fin Semestre 1',
    'semester' => 1,
    'year' => '2024-2025',
    'exam_type' => 'session',  // ← Type SESSION
]);
```

#### **2. Création du Calendrier (Par Matière)**

**Math pour TOUS les JSS2 (toutes sections confondues)**
```php
ExamSchedule::create([
    'exam_id' => 2,
    'my_class_id' => 5, // JSS2 (niveau général)
    'section_id' => null, // ← NULL = Toutes les sections
    'subject_id' => 10, // Math
    'exam_date' => '2024-12-10',
    'start_time' => '08:00',
    'end_time' => '10:00',
    'exam_room_id' => null,  // Sera assigné automatiquement
]);
```

#### **3. Placement Automatique**

```php
use App\Services\ExamPlacementService;

$placementService = new ExamPlacementService();
$result = $placementService->placeStudentsForSession($exam_schedule_id);
```

**Ce qui se passe:**

1. **Récupération des étudiants:**
   - Tous les JSS2A (Sciences)
   - Tous les JSS2B (Commerciale)
   - Tous les JSS2C (Littéraire)
   - **Total:** ~120 étudiants

2. **Calcul des performances:**
   - Moyenne générale de chaque étudiant
   - Basée sur les périodes précédentes

3. **Classement:**
   - Tri décroissant par moyenne
   - Les meilleurs en premier

4. **Répartition dans les salles:**

**Salle A1 (Excellence) - Capacité: 40**
- Top 10 de JSS2A Sciences
- Top 10 de JSS2B Commerciale
- Top 10 de JSS2C Littéraire
- +10 autres meilleurs
- **Total:** 40 étudiants (70-100%)

**Salle B1 (Moyen) - Capacité: 45**
- Étudiants moyens de toutes les classes
- **Total:** 45 étudiants (50-69%)

**Salle B2 (Moyen) - Capacité: 45**
- Suite des étudiants moyens

**Salle C1 (Faible) - Capacité: 40**
- Étudiants faibles de toutes les classes
- **Total:** 40 étudiants (0-49%)

#### **4. Les Étudiants Voient:**
- "Math - 10/12 8h-10h - **Salle A1 - Place N°15**"
- Doit se rendre à la salle indiquée

---

## 📊 Algorithme de Placement

### **Étapes:**

```
1. Récupérer tous les étudiants concernés
   ↓
2. Calculer la moyenne de chaque étudiant
   ↓
3. Trier par performance (DESC)
   ↓
4. Diviser en 3 groupes:
   - 30% Excellence (70-100%)
   - 40% Moyen (50-69%)
   - 30% Faible (0-49%)
   ↓
5. Assigner aux salles correspondantes:
   - Excellence → Salles A
   - Moyen → Salles B
   - Faible → Salles C
   ↓
6. Attribuer numéros de places
   ↓
7. Sauvegarder les placements
```

### **Calcul de la Performance:**

```php
function calculateStudentScore($student_id, $year, $semester) {
    // Option 1: Moyenne des exam_records
    $records = ExamRecord::where('student_id', $student_id)
        ->where('year', $year)
        ->get();
    
    if ($records->count() > 0) {
        return $records->avg('ave');
    }
    
    // Option 2: Moyenne des périodes
    $marks = Mark::where('student_id', $student_id)
        ->where('year', $year)
        ->get();
    
    if ($semester == 1) {
        return ($marks->avg('p1_avg') + $marks->avg('p2_avg')) / 2;
    } else {
        return ($marks->avg('p3_avg') + $marks->avg('p4_avg')) / 2;
    }
}
```

---

## 🖥️ Interfaces Utilisateur

### **1. Création d'Examen (Admin)**

```blade
<form method="POST" action="{{ route('exams.store') }}">
    @csrf
    
    <div class="form-group">
        <label>Nom de l'Examen</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Semestre</label>
        <select name="semester" class="form-control" required>
            <option value="1">Semestre 1</option>
            <option value="2">Semestre 2</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Type d'Examen</label>
        <select name="exam_type" class="form-control" required>
            <option value="hors_session">Hors Session (Salle habituelle)</option>
            <option value="session">Session (Réorganisation)</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Créer l'Examen</button>
</form>
```

### **2. Création du Calendrier**

#### **Pour HORS SESSION:**
```blade
<h4>Calendrier JSS2A - Examen Hors Session</h4>

<table>
    <tr>
        <th>Date</th>
        <th>Heure</th>
        <th>Matière</th>
        <th>Salle</th>
    </tr>
    <tr>
        <td>Lundi 25/11</td>
        <td>08:00-10:00</td>
        <td>Mathématiques</td>
        <td>Salle habituelle (101)</td>
    </tr>
    <tr>
        <td>Mardi 26/11</td>
        <td>08:00-10:00</td>
        <td>Français</td>
        <td>Salle habituelle (101)</td>
    </tr>
</table>
```

#### **Pour SESSION:**
```blade
<h4>Calendrier Examen SESSION - Math JSS2</h4>

<div class="alert alert-info">
    Tous les JSS2 (toutes sections) passeront Math le même jour.
    Les étudiants seront placés automatiquement par performance.
</div>

<button onclick="placeStudents()" class="btn btn-primary">
    Générer les Placements Automatiques
</button>

<!-- Après placement -->
<h5>Résumé des Placements</h5>
<ul>
    <li>Salle A1 (Excellence): 40 étudiants</li>
    <li>Salle B1 (Moyen): 45 étudiants</li>
    <li>Salle C1 (Faible): 35 étudiants</li>
    <li><strong>Total: 120 étudiants placés</strong></li>
</ul>
```

### **3. Vue Étudiant**

#### **HORS SESSION:**
```blade
<div class="exam-card">
    <h5>Mathématiques</h5>
    <p><i class="icon-calendar"></i> Lundi 25 Novembre 2024</p>
    <p><i class="icon-clock"></i> 08:00 - 10:00</p>
    <p><i class="icon-location"></i> Votre salle habituelle</p>
</div>
```

#### **SESSION:**
```blade
<div class="exam-card bg-warning">
    <h5>Mathématiques - EXAMEN SESSION</h5>
    <p><i class="icon-calendar"></i> Lundi 10 Décembre 2024</p>
    <p><i class="icon-clock"></i> 08:00 - 10:00</p>
    <div class="alert alert-danger">
        <strong>ATTENTION:</strong> Examen avec réorganisation
    </div>
    <p><i class="icon-location"></i> <strong>Salle A1 - Bâtiment Principal</strong></p>
    <p><i class="icon-hash"></i> Place N° <strong>15</strong></p>
</div>
```

### **4. Liste de Salle (Pour Surveillant)**

```blade
<h3>Salle A1 - Excellence</h3>
<p>Examen: Mathématiques - JSS2</p>
<p>Date: 10/12/2024 - 08:00-10:00</p>
<p>Capacité: 40 étudiants</p>

<table>
    <thead>
        <tr>
            <th>Place</th>
            <th>Nom</th>
            <th>Classe Origine</th>
            <th>Moyenne</th>
            <th>Signature</th>
        </tr>
    </thead>
    <tbody>
        @foreach($placements as $placement)
        <tr>
            <td>{{ $placement->seat_number }}</td>
            <td>{{ $placement->student->name }}</td>
            <td>{{ $placement->student->student_record->my_class->name }}</td>
            <td>{{ number_format($placement->ranking_score, 2) }}%</td>
            <td>__________</td>
        </tr>
        @endforeach
    </tbody>
</table>

<button onclick="window.print()" class="btn btn-primary">
    Imprimer la Liste
</button>
```

---

## 🔧 Installation

### **1. Exécuter la Migration**

```bash
php artisan migrate
```

### **2. Créer les Salles d'Examen**

```bash
php artisan db:seed --class=ExamRoomsSeeder
```

### **3. Configuration (Optionnelle)**

Dans `config/exam.php` (à créer):

```php
return [
    'placement' => [
        'excellence_percentage' => 30, // 30% top students
        'moyen_percentage' => 40,      // 40% middle students
        'faible_percentage' => 30,     // 30% bottom students
    ],
    
    'rooms' => [
        'default_capacity' => 40,
        'auto_create' => true,
    ],
];
```

---

## 📝 Exemples d'Utilisation

### **1. Créer un Examen HORS SESSION**

```php
$exam = Exam::create([
    'name' => 'Examen Période 1',
    'semester' => 1,
    'year' => '2024-2025',
    'exam_type' => 'hors_session',
]);

// Créer le calendrier pour JSS2A
ExamSchedule::create([
    'exam_id' => $exam->id,
    'my_class_id' => 5,
    'section_id' => 1,
    'subject_id' => 10,
    'exam_date' => '2024-11-25',
    'start_time' => '08:00',
    'end_time' => '10:00',
    // Pas besoin de exam_room_id
]);
```

### **2. Créer un Examen SESSION avec Placement**

```php
// Créer l'examen
$exam = Exam::create([
    'name' => 'Examen Fin Semestre 1',
    'semester' => 1,
    'year' => '2024-2025',
    'exam_type' => 'session',
]);

// Créer l'horaire (pour tous les JSS2)
$schedule = ExamSchedule::create([
    'exam_id' => $exam->id,
    'my_class_id' => 5, // JSS2
    'section_id' => null, // Toutes sections
    'subject_id' => 10, // Math
    'exam_date' => '2024-12-10',
    'start_time' => '08:00',
    'end_time' => '10:00',
]);

// Placer automatiquement les étudiants
$placementService = new ExamPlacementService();
$result = $placementService->placeStudentsForSession($schedule->id);

// Résultat
echo "Étudiants placés: " . $result['total_students'];
echo "Salles utilisées: " . $result['rooms_used'];
```

### **3. Obtenir le Placement d'un Étudiant**

```php
$placementService = new ExamPlacementService();
$placement = $placementService->getStudentPlacement($schedule_id, $student_id);

if ($placement) {
    echo "Salle: " . $placement->room->name;
    echo "Place: " . $placement->seat_number;
    echo "Niveau: " . $placement->performance_level;
}
```

### **4. Obtenir la Liste par Salle**

```php
$placementsByRoom = $placementService->getPlacementsByRoom($schedule_id);

foreach ($placementsByRoom as $room_id => $placements) {
    $room = ExamRoom::find($room_id);
    echo "Salle: " . $room->name;
    echo "Étudiants: " . $placements->count();
    
    foreach ($placements as $placement) {
        echo "- Place " . $placement->seat_number . ": " . $placement->student->name;
    }
}
```

---

## 🎨 Différences Visuelles

### **Badge TYPE D'EXAMEN:**

```blade
@if($exam->exam_type == 'hors_session')
    <span class="badge badge-primary">
        <i class="icon-home mr-1"></i>Hors Session
    </span>
@else
    <span class="badge badge-danger">
        <i class="icon-shuffle mr-1"></i>Session Officielle
    </span>
@endif
```

### **Alerte pour les Étudiants:**

```blade
@if($schedule->exam->isSession())
    <div class="alert alert-warning">
        <h5><i class="icon-warning"></i> Examen avec Réorganisation</h5>
        <p>Vous ne serez pas dans votre salle habituelle.</p>
        <p>Consultez votre placement ci-dessous.</p>
    </div>
@endif
```

---

## ✅ Avantages du Système

### **HORS SESSION:**
- ✅ Simple à gérer
- ✅ Pas de stress pour les étudiants
- ✅ Familiarité avec l'environnement

### **SESSION:**
- ✅ Réduit la triche
- ✅ Meilleure surveillance
- ✅ Équitable (mélange de niveaux)
- ✅ Placement automatique (gain de temps)

---

## 🔮 Prochaines Étapes

1. ✅ Créer les contrôleurs
2. ✅ Créer les vues
3. ✅ Ajouter les routes
4. ✅ Tester le placement automatique
5. ✅ Créer les impressions PDF

---

**Le système est maintenant prêt à être implémenté ! 🚀**

*Document créé le 18 Novembre 2025*
