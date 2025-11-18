# 🔗 Comment Ajouter les Liens Examens dans le Menu

## 📍 Guide Rapide pour Intégrer le Système d'Examens au Menu

---

## 1️⃣ **Menu Administrateur/Enseignant**

### **Fichier à Modifier**
```
resources/views/partials/menu.blade.php
```

### **Code à Ajouter**

Trouvez une section appropriée (par exemple après "Academics") et ajoutez :

```blade
{{-- EXAMENS --}}
@if(Qs::userIsTeamSA())
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['exams.dashboard', 'exams.index', 'exam_schedules.index', 'exam_analytics.index', 'exam_publication.show']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-graduation"></i>
        <span>Examens</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Examens">
        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ route('exams.dashboard') }}" class="nav-link {{ Route::is('exams.dashboard') ? 'active' : '' }}">
                <i class="icon-grid"></i>
                Tableau de Bord
            </a>
        </li>
        
        {{-- Gestion --}}
        <li class="nav-item">
            <a href="{{ route('exams.index') }}" class="nav-link {{ Route::is('exams.index') ? 'active' : '' }}">
                <i class="icon-file-text2"></i>
                Gérer les Examens
            </a>
        </li>
        
        {{-- Calendrier --}}
        <li class="nav-item">
            <a href="{{ route('exam_schedules.index') }}" class="nav-link {{ Route::is('exam_schedules.*') ? 'active' : '' }}">
                <i class="icon-calendar"></i>
                Calendrier & Horaires
            </a>
        </li>
        
        {{-- Analytics --}}
        <li class="nav-item">
            <a href="{{ route('exam_analytics.index') }}" class="nav-link {{ Route::is('exam_analytics.*') ? 'active' : '' }}">
                <i class="icon-stats-dots"></i>
                Analytics & Rapports
            </a>
        </li>
        
        {{-- Notes (Marks) --}}
        <li class="nav-item">
            <a href="{{ route('marks.index') }}" class="nav-link {{ Route::is('marks.*') ? 'active' : '' }}">
                <i class="icon-pencil5"></i>
                Saisir les Notes
            </a>
        </li>
    </ul>
</li>
@endif
```

### **Alternative Simplifiée (1 lien direct)**

Si vous préférez un seul lien vers le dashboard :

```blade
@if(Qs::userIsTeamSA())
<li class="nav-item">
    <a href="{{ route('exams.dashboard') }}" class="nav-link {{ Route::is('exams.*') || Route::is('exam_*') ? 'active' : '' }}">
        <i class="icon-graduation"></i>
        <span>Examens</span>
    </a>
</li>
@endif
```

---

## 2️⃣ **Menu Étudiant**

### **Fichier à Modifier**
```
resources/views/partials/menu.blade.php
```

### **Code à Ajouter**

Dans la section étudiants, ajoutez :

```blade
{{-- EXAMENS ÉTUDIANT --}}
@if(Qs::userIsStudent())
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student.exams.index', 'student.exam_schedule', 'student.progress.index']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-graduation"></i>
        <span>Mes Examens</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Mes Examens">
        {{-- Hub Examens --}}
        <li class="nav-item">
            <a href="{{ route('student.exams.index') }}" class="nav-link {{ Route::is('student.exams.index') ? 'active' : '' }}">
                <i class="icon-home"></i>
                Accueil Examens
            </a>
        </li>
        
        {{-- Calendrier --}}
        <li class="nav-item">
            <a href="{{ route('student.exam_schedule') }}" class="nav-link {{ Route::is('student.exam_schedule') ? 'active' : '' }}">
                <i class="icon-calendar"></i>
                Calendrier d'Examens
            </a>
        </li>
        
        {{-- Progression --}}
        <li class="nav-item">
            <a href="{{ route('student.progress.index') }}" class="nav-link {{ Route::is('student.progress.*') ? 'active' : '' }}">
                <i class="icon-graph"></i>
                Ma Progression
            </a>
        </li>
        
        {{-- Notes par Période --}}
        <li class="nav-item">
            <a href="{{ route('student.grades.index') }}" class="nav-link {{ Route::is('student.grades.index') ? 'active' : '' }}">
                <i class="icon-certificate"></i>
                Mes Notes
            </a>
        </li>
        
        {{-- Bulletin --}}
        <li class="nav-item">
            <a href="{{ route('student.grades.bulletin') }}" class="nav-link {{ Route::is('student.grades.bulletin') ? 'active' : '' }}">
                <i class="icon-file-text2"></i>
                Mon Bulletin
            </a>
        </li>
    </ul>
</li>
@endif
```

### **Alternative Simplifiée (1 lien direct)**

```blade
@if(Qs::userIsStudent())
<li class="nav-item">
    <a href="{{ route('student.exams.index') }}" class="nav-link {{ Route::is('student.exams.*') || Route::is('student.exam_schedule') || Route::is('student.progress.*') ? 'active' : '' }}">
        <i class="icon-graduation"></i>
        <span>Mes Examens</span>
    </a>
</li>
@endif
```

---

## 3️⃣ **Ajouter au Dashboard**

### **Dashboard Administrateur**

Fichier: `resources/views/pages/dashboard.blade.php` (ou équivalent)

Ajoutez une carte dans le dashboard :

```blade
{{-- Carte Examens --}}
@if(Qs::userIsTeamSA())
<div class="col-md-3">
    <div class="card bg-primary text-white">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $total_exams ?? 0 }}</h3>
                    <span>Examens</span>
                </div>
                <div>
                    <i class="icon-graduation icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            <a href="{{ route('exams.dashboard') }}" class="text-white">
                Gérer les Examens <i class="icon-arrow-right8 ml-2"></i>
            </a>
        </div>
    </div>
</div>
@endif
```

### **Dashboard Étudiant**

Ajoutez dans le dashboard étudiant :

```blade
{{-- Carte Mes Examens --}}
<div class="col-md-4">
    <div class="card bg-success text-white">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $upcoming_exams ?? 0 }}</h3>
                    <span>Examens à Venir</span>
                </div>
                <div>
                    <i class="icon-calendar icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            <a href="{{ route('student.exams.index') }}" class="text-white">
                Voir mes Examens <i class="icon-arrow-right8 ml-2"></i>
            </a>
        </div>
    </div>
</div>
```

---

## 4️⃣ **Breadcrumbs (Fil d'Ariane)**

### **Ajouter dans les Vues**

Pour les vues admin, ajoutez en haut de page :

```blade
{{-- Breadcrumb Examens --}}
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}" class="breadcrumb-item">
                    <i class="icon-home2 mr-2"></i> Accueil
                </a>
                <a href="{{ route('exams.dashboard') }}" class="breadcrumb-item">Examens</a>
                <span class="breadcrumb-item active">{{ $title ?? 'Dashboard' }}</span>
            </div>
        </div>
    </div>
</div>
```

---

## 5️⃣ **Vérification**

### **Checklist Menu Admin**

Après modification, vérifiez :

- [ ] Menu "Examens" visible pour les admins
- [ ] Sous-menu se déploie correctement
- [ ] Liens fonctionnels :
  - [ ] Tableau de Bord
  - [ ] Gérer les Examens
  - [ ] Calendrier & Horaires
  - [ ] Analytics & Rapports
  - [ ] Saisir les Notes
- [ ] Highlight actif sur la page courante

### **Checklist Menu Étudiant**

- [ ] Menu "Mes Examens" visible pour les étudiants
- [ ] Sous-menu se déploie correctement
- [ ] Liens fonctionnels :
  - [ ] Accueil Examens
  - [ ] Calendrier d'Examens
  - [ ] Ma Progression
  - [ ] Mes Notes
  - [ ] Mon Bulletin
- [ ] Highlight actif sur la page courante

---

## 6️⃣ **Icônes Utilisées**

Voici la liste des icônes utilisées (FontAwesome/Icomoon) :

| Fonctionnalité | Icône | Code |
|----------------|-------|------|
| Examens (général) | 🎓 | `icon-graduation` |
| Dashboard | 📊 | `icon-grid` |
| Liste | 📋 | `icon-file-text2` |
| Calendrier | 📅 | `icon-calendar` |
| Analytics | 📈 | `icon-stats-dots` |
| Notes | ✏️ | `icon-pencil5` |
| Progression | 📊 | `icon-graph` |
| Bulletin | 📄 | `icon-certificate` |
| Accueil | 🏠 | `icon-home` |

### **Pour Changer les Icônes**

Remplacez simplement la classe `icon-*` par une autre icône de votre choix.

---

## 7️⃣ **Personnalisation**

### **Changer les Couleurs**

Vous pouvez personnaliser les couleurs des cartes dans le dashboard :

```blade
{{-- Couleurs disponibles --}}
bg-primary    {{-- Bleu --}}
bg-success    {{-- Vert --}}
bg-info       {{-- Cyan --}}
bg-warning    {{-- Jaune --}}
bg-danger     {{-- Rouge --}}
bg-secondary  {{-- Gris --}}
```

### **Changer les Textes**

Tous les textes peuvent être modifiés directement dans le code HTML.

---

## 8️⃣ **Exemple Complet**

### **Structure Recommandée du Menu Admin**

```
📁 DASHBOARD
📁 ACADEMICS
   ├── Classes
   ├── Sections
   ├── Subjects
   └── ...
📁 EXAMENS ⭐ NOUVEAU
   ├── Tableau de Bord
   ├── Gérer les Examens
   ├── Calendrier & Horaires
   ├── Analytics & Rapports
   └── Saisir les Notes
📁 STUDENTS
📁 USERS
...
```

### **Structure Recommandée du Menu Étudiant**

```
📁 DASHBOARD
📁 MES EXAMENS ⭐ NOUVEAU
   ├── Accueil Examens
   ├── Calendrier d'Examens
   ├── Ma Progression
   ├── Mes Notes
   └── Mon Bulletin
📁 DEVOIRS
📁 EMPLOI DU TEMPS
📁 BIBLIOTHÈQUE
...
```

---

## 9️⃣ **Commandes Utiles**

Après modification du menu :

```bash
# Vider le cache des vues
php artisan view:clear

# Vider le cache complet
php artisan cache:clear

# Recharger la configuration
php artisan config:clear
```

---

## 🔟 **Dépannage**

### **Problème : Menu ne s'affiche pas**
- Vérifiez la condition `@if(Qs::userIsTeamSA())` ou `@if(Qs::userIsStudent())`
- Assurez-vous d'être connecté avec le bon rôle

### **Problème : Liens ne fonctionnent pas**
- Vérifiez que les routes existent : `php artisan route:list | findstr exam`
- Vérifiez les noms des routes

### **Problème : Highlight actif ne fonctionne pas**
- Vérifiez la condition `Route::is('...')`
- Assurez-vous que le nom de route correspond

### **Problème : Sous-menu ne se déploie pas**
- Vérifiez la classe `nav-item-submenu`
- Vérifiez que le JavaScript est chargé

---

## ✅ **Validation Finale**

Après ajout au menu, testez :

1. **Connexion Admin**
   - Voir le menu "Examens"
   - Cliquer sur chaque lien
   - Vérifier que les pages se chargent

2. **Connexion Étudiant**
   - Voir le menu "Mes Examens"
   - Cliquer sur chaque lien
   - Vérifier que les pages se chargent

3. **Navigation**
   - Vérifier le highlight actif
   - Vérifier les breadcrumbs
   - Vérifier les retours en arrière

---

## 🎉 **Résultat Attendu**

Après intégration, vous devriez avoir :

✅ Menu "Examens" pour les admins avec 5 sous-liens  
✅ Menu "Mes Examens" pour les étudiants avec 5 sous-liens  
✅ Cartes dans les dashboards  
✅ Highlight actif fonctionnel  
✅ Breadcrumbs cohérents  

**Le système d'examens est maintenant pleinement intégré ! 🚀**

---

*Document créé le 18 Novembre 2025*
*Guide d'intégration Menu Examens v1.0*
