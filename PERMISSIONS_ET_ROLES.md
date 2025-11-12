# 📋 Permissions et Rôles dans E-School

## 👥 Types d'Utilisateurs

L'application définit les types d'utilisateurs suivants :

1. **super_admin** - Super Administrateur
2. **admin** - Administrateur
3. **teacher** - Enseignant
4. **accountant** - Comptable
5. **librarian** - Bibliothécaire
6. **student** - Étudiant
7. **parent** - Parent

## 🔐 Groupes de Permissions

### TeamSA (Super Admin + Admin)
**Membres** : `super_admin`, `admin`

**Permissions** :
- ✅ Gestion complète des utilisateurs
- ✅ Gestion des classes et sections
- ✅ Gestion des matières
- ✅ Gestion des examens
- ✅ Gestion des notes (avec restrictions)
- ✅ Gestion des emplois du temps
- ✅ Gestion des promotions d'étudiants
- ✅ **Ajout de livres à la bibliothèque**
- ✅ **Ajout de matériels pédagogiques**
- ✅ Paramètres système (super_admin uniquement)

### TeamSAT (Super Admin + Admin + Teacher)
**Membres** : `super_admin`, `admin`, `teacher`

**Permissions** :
- ✅ **Validation des demandes de livres**
- ✅ Gestion des devoirs/assignments
- ✅ Consultation des notes
- ✅ Gestion des présences
- ✅ Consultation des étudiants de leurs classes

### TeamAccount (Comptable)
**Membres** : `accountant`

**Permissions** :
- ✅ **Gestion complète des paiements**
- ✅ **Ajout de paiements pour les étudiants**
- ✅ Génération de reçus
- ✅ Consultation des soldes
- ✅ Rapports financiers

### Staff (Personnel)
**Membres** : `super_admin`, `admin`, `teacher`, `accountant`, `librarian`

**Permissions** :
- ✅ Accès au système
- ✅ Consultation des informations générales

## 📚 Permissions Détaillées par Fonctionnalité

### 1. 📖 Demandes de Livres (Book Requests)

#### Qui peut VALIDER les demandes ?
**Middleware** : `teamSAT`

✅ **Super Admin** (`super_admin`)
✅ **Admin** (`admin`)
✅ **Enseignant** (`teacher`)

**Fichier** : `app/Http/Controllers/SupportTeam/BookRequestController.php`
```php
public function __construct()
{
    $this->middleware('teamSAT');
}
```

**Actions disponibles** :
- `approve()` - Approuver une demande
- `reject()` - Refuser une demande
- `index()` - Voir toutes les demandes
- `show()` - Voir les détails d'une demande

#### Qui peut CRÉER une demande ?
✅ **Étudiant** (`student`)

**Fichier** : `app/Http/Controllers/Student/BookRequestController.php`

---

### 2. 📚 Gestion des Livres

#### Qui peut AJOUTER des livres ?
**Middleware** : `teamSA` (sauf suppression)

✅ **Super Admin** (`super_admin`)
✅ **Admin** (`admin`)

**Fichier** : `app/Http/Controllers/BookController.php`
```php
public function __construct(Book $book)
{
    $this->middleware('teamSA', ['except' => ['destroy',] ]);
    $this->middleware('super_admin', ['only' => ['destroy',] ]);
}
```

**Actions** :
- ✅ `store()` - Ajouter un livre (TeamSA)
- ✅ `update()` - Modifier un livre (TeamSA)
- ✅ `destroy()` - Supprimer un livre (Super Admin uniquement)
- ✅ `index()` - Voir tous les livres (TeamSA)

---

### 3. 📄 Matériels Pédagogiques (Study Materials)

#### Qui peut AJOUTER des matériels ?
**Middleware** : `teamSA` (sauf consultation et téléchargement)

✅ **Super Admin** (`super_admin`)
✅ **Admin** (`admin`)

**Fichier** : `app/Http/Controllers/StudyMaterialController.php`
```php
public function __construct(StudyMaterial $studyMaterial)
{
    $this->middleware('teamSA', ['except' => ['index', 'show', 'download']]);
    $this->middleware('super_admin', ['only' => ['destroy']]);
}
```

**Actions** :
- ✅ `store()` - Ajouter un matériel (TeamSA)
- ✅ `update()` - Modifier un matériel (TeamSA)
- ✅ `destroy()` - Supprimer un matériel (Super Admin uniquement)
- ✅ `index()` - Voir tous les matériels (Tous)
- ✅ `download()` - Télécharger un matériel (Tous)

---

### 4. 💰 Paiements

#### Qui peut AJOUTER des paiements ?
**Middleware** : `teamAccount`

✅ **Comptable** (`accountant`)

**Fichier** : `app/Http/Controllers/SupportTeam/PaymentController.php`
```php
public function __construct(...)
{
    $this->middleware('teamAccount');
}
```

**Actions** :
- ✅ `store()` - Enregistrer un paiement
- ✅ `update()` - Modifier un paiement
- ✅ `destroy()` - Supprimer un paiement
- ✅ `index()` - Voir tous les paiements
- ✅ `invoice()` - Générer une facture

---

### 5. 📝 Devoirs (Assignments)

#### Qui peut CRÉER des devoirs ?
**Middleware** : `teamSAT` (généralement)

✅ **Super Admin** (`super_admin`)
✅ **Admin** (`admin`)
✅ **Enseignant** (`teacher`)

**Note** : Les enseignants peuvent créer des devoirs pour leurs classes.

---

### 6. ✅ Présences (Attendance)

#### Qui peut MARQUER les présences ?
**Middleware** : `teamSAT`

✅ **Super Admin** (`super_admin`)
✅ **Admin** (`admin`)
✅ **Enseignant** (`teacher`)

**Note** : Les enseignants marquent généralement les présences pour leurs classes.

---

### 7. 📊 Notes/Marksheet

#### Qui peut SAISIR les notes ?
**Middleware** : `teamSAT` (avec restrictions)

✅ **Super Admin** (`super_admin`)
✅ **Admin** (`admin`)
✅ **Enseignant** (`teacher`)

**Fichier** : `app/Http/Controllers/SupportTeam/MarkController.php`

**Restrictions** :
- Les enseignants ne peuvent saisir que les notes de leurs matières
- Les notes peuvent être verrouillées par le système
- Seul le Super Admin peut déverrouiller les examens

---

## 📊 Tableau Récapitulatif

| Fonctionnalité | Super Admin | Admin | Teacher | Accountant | Librarian | Student | Parent |
|----------------|-------------|-------|---------|------------|-----------|---------|--------|
| **Valider demandes de livres** | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Ajouter des livres** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Ajouter matériels pédagogiques** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Ajouter paiements** | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |
| **Créer devoirs** | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Marquer présences** | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Saisir notes** | ✅ | ✅ | ✅* | ❌ | ❌ | ❌ | ❌ |
| **Supprimer utilisateurs** | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Paramètres système** | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Promotions étudiants** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

*\* Les enseignants ne peuvent saisir que les notes de leurs matières*

---

## 🔑 Middlewares Personnalisés

### 1. `teamSA`
**Fichier** : `app/Http/Middleware/Custom/TeamSA.php`

```php
public function handle($request, Closure $next)
{
    return (Auth::check() && Qs::userIsTeamSA()) 
        ? $next($request) 
        : redirect()->route('login');
}
```

**Membres** : Super Admin, Admin

---

### 2. `teamSAT`
**Fichier** : `app/Http/Middleware/Custom/TeamSAT.php`

**Membres** : Super Admin, Admin, Teacher

---

### 3. `teamAccount`
**Fichier** : `app/Http/Middleware/Custom/TeamAccount.php`

**Membres** : Accountant

---

### 4. `super_admin`
**Fichier** : `app/Http/Middleware/Custom/SuperAdmin.php`

**Membres** : Super Admin uniquement

---

### 5. `admin`
**Fichier** : `app/Http/Middleware/Custom/Admin.php`

**Membres** : Admin uniquement

---

### 6. `student`
**Fichier** : `app/Http/Middleware/Custom/Student.php`

**Membres** : Student uniquement

---

### 7. `my_parent`
**Fichier** : `app/Http/Middleware/Custom/MyParent.php`

**Membres** : Parent uniquement

---

## 🎯 Cas d'Usage Pratiques

### Scénario 1 : Gestion de la Bibliothèque

1. **Étudiant** demande un livre
   - Route : `/student/book-requests/create`
   - Middleware : `student`

2. **Enseignant/Admin** valide la demande
   - Route : `/book-requests/{id}/approve`
   - Middleware : `teamSAT`
   - Utilisateurs : Super Admin, Admin, Teacher

3. **Admin** ajoute de nouveaux livres
   - Route : `/books/store`
   - Middleware : `teamSA`
   - Utilisateurs : Super Admin, Admin

---

### Scénario 2 : Gestion Académique

1. **Enseignant** crée un devoir
   - Route : `/assignments/store`
   - Middleware : `teamSAT`

2. **Enseignant** marque les présences
   - Route : `/attendance/store`
   - Middleware : `teamSAT`

3. **Enseignant** saisit les notes
   - Route : `/marks/store`
   - Middleware : `teamSAT`
   - Restriction : Uniquement pour ses matières

4. **Admin** ajoute du matériel pédagogique
   - Route : `/study-materials/store`
   - Middleware : `teamSA`

---

### Scénario 3 : Gestion Financière

1. **Comptable** enregistre un paiement
   - Route : `/payments/store`
   - Middleware : `teamAccount`
   - Utilisateur : Accountant uniquement

2. **Comptable** génère une facture
   - Route : `/payments/invoice/{id}`
   - Middleware : `teamAccount`

---

## 💡 Recommandations

### Pour une Meilleure Gestion des Permissions

1. **Créer un rôle Bibliothécaire actif**
   - Actuellement, le rôle `librarian` existe mais n'est pas utilisé
   - Recommandation : Donner au bibliothécaire les permissions pour :
     - Valider les demandes de livres
     - Gérer l'inventaire des livres
     - Suivre les emprunts et retours

2. **Affiner les permissions des enseignants**
   - Limiter la saisie des notes aux matières qu'ils enseignent
   - Limiter les présences aux classes qu'ils ont

3. **Ajouter un journal d'audit**
   - Tracer qui fait quoi dans le système
   - Particulièrement important pour :
     - Modifications de notes
     - Validations de paiements
     - Suppressions de données

4. **Créer des rôles personnalisés**
   - Permettre la création de rôles avec permissions spécifiques
   - Exemple : "Responsable Pédagogique", "Surveillant", etc.

---

## 📝 Résumé des Réponses

### ❓ Qui peut valider les demandes de livres ?
✅ **Super Admin, Admin, Enseignant** (middleware `teamSAT`)

### ❓ Qui peut ajouter des matériels pédagogiques ?
✅ **Super Admin, Admin** (middleware `teamSA`)

### ❓ Qui peut ajouter des livres ?
✅ **Super Admin, Admin** (middleware `teamSA`)

### ❓ Qui peut ajouter des paiements ?
✅ **Comptable uniquement** (middleware `teamAccount`)

### ❓ Qui peut créer des devoirs ?
✅ **Super Admin, Admin, Enseignant** (middleware `teamSAT`)

### ❓ Qui peut marquer les présences ?
✅ **Super Admin, Admin, Enseignant** (middleware `teamSAT`)

### ❓ Qui peut saisir les notes (marksheet) ?
✅ **Super Admin, Admin, Enseignant** (middleware `teamSAT`)
⚠️ *Restriction : Les enseignants ne peuvent saisir que les notes de leurs matières*

---

## 🔍 Vérification des Permissions

Pour vérifier les permissions d'un utilisateur, utilisez les méthodes du helper `Qs` :

```php
// Vérifier si l'utilisateur est TeamSA
Qs::userIsTeamSA()  // Super Admin ou Admin

// Vérifier si l'utilisateur est TeamSAT
Qs::userIsTeamSAT()  // Super Admin, Admin ou Teacher

// Vérifier si l'utilisateur est TeamAccount
Qs::userIsTeamAccount()  // Accountant

// Vérifier le type exact
Qs::userIsSuperAdmin()
Qs::userIsAdmin()
Qs::userIsTeacher()
Qs::userIsStudent()
Qs::userIsParent()
```

---

**Documentation créée le** : 12 novembre 2025
**Version de l'application** : Laravel 12.37.0
