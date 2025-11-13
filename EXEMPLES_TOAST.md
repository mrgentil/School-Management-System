# 📚 Exemples Pratiques d'Utilisation des Toasts

## 🎯 Exemples par Scénario

### 1. CRUD Utilisateurs

#### Création
```php
use App\Helpers\Toast;

public function store(UserRequest $request)
{
    $user = User::create($request->validated());
    return Toast::redirectSuccess('users.index', 'Utilisateur créé avec succès!');
}
```

#### Modification
```php
public function update(UserRequest $request, $id)
{
    $user = User::findOrFail($id);
    $user->update($request->validated());
    return Toast::success('Utilisateur mis à jour!');
}
```

#### Suppression
```php
public function destroy($id)
{
    User::findOrFail($id)->delete();
    return Toast::success('Utilisateur supprimé avec succès!');
}
```

#### Erreur de Validation
```php
public function store(UserRequest $request)
{
    if (User::where('email', $request->email)->exists()) {
        return Toast::error('Cet email existe déjà!');
    }
    
    // Suite du code...
}
```

### 2. Authentification

#### Connexion Réussie
```php
public function login(Request $request)
{
    if (Auth::attempt($request->only('email', 'password'))) {
        return Toast::redirectSuccess('dashboard', 'Bienvenue ' . Auth::user()->name . '!');
    }
    
    return Toast::error('Identifiants incorrects!');
}
```

#### Déconnexion
```php
public function logout()
{
    Auth::logout();
    return Toast::redirectSuccess('login', 'Vous êtes déconnecté. À bientôt!');
}
```

#### Réinitialisation de Mot de Passe
```php
public function resetPassword(Request $request)
{
    // Logique de réinitialisation...
    
    return Toast::success('Mot de passe réinitialisé! Vérifiez votre email.');
}
```

### 3. Gestion des Étudiants

#### Inscription
```php
public function enrollStudent(Request $request)
{
    $student = Student::create($request->validated());
    return Toast::popSuccess('Étudiant inscrit avec succès!', 'Inscription Réussie!');
}
```

#### Promotion
```php
public function promote($studentId)
{
    $student = Student::findOrFail($studentId);
    $student->promote();
    
    return Toast::success($student->name . ' a été promu(e) avec succès!');
}
```

#### Notes
```php
public function saveGrades(Request $request)
{
    // Sauvegarde des notes...
    
    return Toast::info('Notes enregistrées. En attente de validation.');
}
```

### 4. Paiements

#### Paiement Réussi
```php
public function processPayment(Request $request)
{
    $payment = Payment::create($request->validated());
    
    return Toast::popSuccess(
        'Paiement de ' . $payment->amount . ' $ enregistré!',
        'Paiement Confirmé'
    );
}
```

#### Paiement Partiel
```php
public function partialPayment(Request $request)
{
    // Logique...
    
    return Toast::warning('Paiement partiel enregistré. Reste à payer: ' . $balance . ' $');
}
```

#### Paiement Refusé
```php
public function processPayment(Request $request)
{
    if ($request->amount < $minimumAmount) {
        return Toast::error('Le montant minimum est de ' . $minimumAmount . ' $');
    }
    
    // Suite...
}
```

### 5. Gestion des Examens

#### Création d'Examen
```php
public function store(Request $request)
{
    $exam = Exam::create($request->validated());
    return Toast::success('Examen créé pour la session ' . $exam->session);
}
```

#### Verrouillage d'Examen
```php
public function lock($examId)
{
    $exam = Exam::findOrFail($examId);
    $exam->update(['locked' => true]);
    
    return Toast::warning('Examen verrouillé. Aucune modification possible.');
}
```

#### Publication des Résultats
```php
public function publishResults($examId)
{
    $exam = Exam::findOrFail($examId);
    $exam->publish();
    
    return Toast::popSuccess('Résultats publiés!', 'Publication Réussie');
}
```

### 6. Gestion de la Bibliothèque

#### Emprunt de Livre
```php
public function borrowBook(Request $request)
{
    $book = Book::findOrFail($request->book_id);
    
    if (!$book->isAvailable()) {
        return Toast::error('Ce livre n\'est pas disponible actuellement.');
    }
    
    $book->borrow($request->student_id);
    return Toast::success('Livre emprunté avec succès! À retourner avant le ' . $book->due_date);
}
```

#### Retour de Livre
```php
public function returnBook($bookId)
{
    $book = Book::findOrFail($bookId);
    
    if ($book->isOverdue()) {
        return Toast::warning('Livre en retard! Pénalité: ' . $book->penalty . ' $');
    }
    
    $book->return();
    return Toast::success('Livre retourné avec succès!');
}
```

### 7. Emploi du Temps

#### Création
```php
public function store(Request $request)
{
    $timetable = TimeTable::create($request->validated());
    return Toast::success('Emploi du temps créé pour ' . $timetable->class->name);
}
```

#### Conflit Détecté
```php
public function store(Request $request)
{
    if ($this->hasConflict($request)) {
        return Toast::error('Conflit d\'horaire détecté! Vérifiez les créneaux.');
    }
    
    // Suite...
}
```

### 8. Gestion des Paramètres

#### Mise à Jour des Paramètres
```php
public function update(Request $request)
{
    Setting::updateSettings($request->all());
    
    return Toast::popSuccess(
        'Paramètres système mis à jour!',
        'Configuration Sauvegardée'
    );
}
```

#### Changement de Session
```php
public function changeSession(Request $request)
{
    Setting::set('current_session', $request->session);
    
    return Toast::warning('Session changée en ' . $request->session . '. Vérifiez vos données!');
}
```

### 9. Upload de Fichiers

#### Upload Réussi
```php
public function upload(Request $request)
{
    $file = $request->file('document');
    $path = $file->store('documents');
    
    return Toast::success('Fichier "' . $file->getClientOriginalName() . '" uploadé!');
}
```

#### Erreur de Taille
```php
public function upload(Request $request)
{
    if ($request->file('document')->getSize() > 5000000) {
        return Toast::error('Fichier trop volumineux! Maximum 5MB.');
    }
    
    // Suite...
}
```

### 10. Notifications par Email

#### Email Envoyé
```php
public function sendNotification(Request $request)
{
    Mail::to($request->email)->send(new Notification());
    
    return Toast::info('Email envoyé à ' . $request->email);
}
```

#### Erreur d'Envoi
```php
public function sendNotification(Request $request)
{
    try {
        Mail::to($request->email)->send(new Notification());
        return Toast::success('Email envoyé!');
    } catch (\Exception $e) {
        return Toast::error('Erreur d\'envoi: ' . $e->getMessage());
    }
}
```

## 🎨 Exemples avec Personnalisation

### Toast avec Durée Personnalisée

```javascript
// Dans votre fichier JS
toastr.options.timeOut = 10000; // 10 secondes
toastr.success('Ce message reste 10 secondes', 'Important!');

// Remettre à la normale
toastr.options.timeOut = 5000;
```

### Toast Permanent

```javascript
toastr.options.timeOut = 0;
toastr.options.extendedTimeOut = 0;
toastr.warning('Cliquez pour fermer', 'Action Requise');
```

### Toast avec Callback

```javascript
toastr.options.onclick = function() {
    window.location.href = '/dashboard';
};
toastr.info('Cliquez pour aller au tableau de bord', 'Navigation');
```

## 🔄 Conversion des Anciens Codes

### Avant
```php
return back()->with('flash_success', __('msg.update_ok'));
```

### Après (Option 1 - Aucun changement nécessaire)
```php
return back()->with('flash_success', __('msg.update_ok'));
```

### Après (Option 2 - Avec le nouveau helper)
```php
return Toast::success(__('msg.update_ok'));
```

### Avant
```php
return redirect()->route('users.index')->with('flash_success', 'Utilisateur créé!');
```

### Après
```php
return Toast::redirectSuccess('users.index', 'Utilisateur créé!');
```

## 💡 Bonnes Pratiques

### 1. Messages Clairs et Concis
```php
// ❌ Mauvais
return Toast::success('Ok');

// ✅ Bon
return Toast::success('Utilisateur créé avec succès!');
```

### 2. Utiliser le Bon Type
```php
// ❌ Mauvais - Utiliser success pour une erreur
return Toast::success('Erreur de connexion');

// ✅ Bon
return Toast::error('Erreur de connexion');
```

### 3. Messages Informatifs
```php
// ❌ Mauvais
return Toast::error('Erreur');

// ✅ Bon
return Toast::error('Email déjà utilisé. Veuillez en choisir un autre.');
```

### 4. Pop-ups pour Actions Importantes
```php
// Pour une simple mise à jour
return Toast::success('Profil mis à jour');

// Pour une action critique
return Toast::popSuccess('Données sauvegardées!', 'Sauvegarde Réussie');
```

## 🎯 Cas d'Usage Avancés

### Validation Multiple
```php
public function store(Request $request)
{
    $errors = [];
    
    if (User::where('email', $request->email)->exists()) {
        $errors[] = 'Email déjà utilisé';
    }
    
    if (strlen($request->password) < 8) {
        $errors[] = 'Mot de passe trop court';
    }
    
    if (!empty($errors)) {
        return Toast::error(implode('. ', $errors));
    }
    
    // Suite...
}
```

### Traitement par Lot
```php
public function bulkDelete(Request $request)
{
    $count = User::whereIn('id', $request->ids)->delete();
    
    return Toast::success($count . ' utilisateur(s) supprimé(s)');
}
```

### Avec Traduction
```php
return Toast::success(__('messages.user_created'));
return Toast::error(__('messages.user_not_found'));
```

---

**Ces exemples couvrent la plupart des cas d'usage dans votre application !** 🎉
