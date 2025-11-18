# 🛣️ Routes à Ajouter - Système SESSION/HORS SESSION

## 📝 Routes à Ajouter dans `routes/web.php`

Ajoutez ces routes dans la section **teamSA** (après les routes existantes des examens) :

```php
// Dans le groupe Route::group(['middleware' => 'teamSA'], function () {

    // ========================================
    // SALLES D'EXAMEN
    // ========================================
    Route::prefix('exam-rooms')->name('exam_rooms.')->group(function () {
        Route::get('/', [App\Http\Controllers\SupportTeam\ExamRoomController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SupportTeam\ExamRoomController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SupportTeam\ExamRoomController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\SupportTeam\ExamRoomController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\SupportTeam\ExamRoomController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\SupportTeam\ExamRoomController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // PLACEMENTS D'ÉTUDIANTS (SESSION)
    // ========================================
    Route::prefix('exam-placements')->name('exam_placements.')->group(function () {
        // Générer les placements automatiques
        Route::post('/{schedule_id}/generate', [App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'generate'])->name('generate');
        
        // Voir tous les placements d'un examen
        Route::get('/{schedule_id}', [App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'show'])->name('show');
        
        // Voir les placements d'une salle spécifique (pour impression)
        Route::get('/{schedule_id}/room/{room_id}', [App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'byRoom'])->name('by_room');
        
        // Supprimer tous les placements
        Route::delete('/{schedule_id}', [App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'destroy'])->name('destroy');
    });
    
}); // Fin du groupe teamSA
```

---

## 🔧 Modifications à Faire dans le Contrôleur Exam

Dans `app/Http/Controllers/SupportTeam/ExamController.php`, modifiez la méthode `store` :

```php
public function store(ExamCreate $req)
{
    $data = $req->only(['name', 'semester', 'exam_type']); // ← Ajouter exam_type
    $data['year'] = Qs::getSetting('current_session');
    
    // Valeur par défaut si non fourni
    if (!isset($data['exam_type'])) {
        $data['exam_type'] = 'hors_session';
    }
    
    $this->exam->create($data);

    return back()->with('flash_success', __('msg.store_ok'));
}
```

---

## 📋 Request Validation à Modifier

Dans `app/Http/Requests/ExamCreate.php`, ajoutez la validation pour `exam_type` :

```php
public function rules()
{
    return [
        'name' => 'required|string|max:100',
        'semester' => 'required|integer|in:1,2',
        'exam_type' => 'required|in:hors_session,session', // ← Ajouter cette ligne
    ];
}
```

Dans `app/Http/Requests/ExamUpdate.php`, même chose :

```php
public function rules()
{
    return [
        'name' => 'required|string|max:100',
        'semester' => 'required|integer|in:1,2',
        'exam_type' => 'required|in:hors_session,session', // ← Ajouter cette ligne
    ];
}
```

---

## 🎯 Routes Complètes Résumées

| Route | Méthode | Contrôleur | Description |
|-------|---------|------------|-------------|
| `/exam-rooms` | GET | ExamRoomController@index | Liste des salles |
| `/exam-rooms/create` | GET | ExamRoomController@create | Formulaire création salle |
| `/exam-rooms` | POST | ExamRoomController@store | Enregistrer salle |
| `/exam-rooms/{id}/edit` | GET | ExamRoomController@edit | Formulaire édition |
| `/exam-rooms/{id}` | PUT | ExamRoomController@update | Mettre à jour salle |
| `/exam-rooms/{id}` | DELETE | ExamRoomController@destroy | Supprimer salle |
| `/exam-placements/{schedule_id}/generate` | POST | ExamPlacementController@generate | Générer placements |
| `/exam-placements/{schedule_id}` | GET | ExamPlacementController@show | Voir placements |
| `/exam-placements/{schedule_id}/room/{room_id}` | GET | ExamPlacementController@byRoom | Liste par salle |
| `/exam-placements/{schedule_id}` | DELETE | ExamPlacementController@destroy | Supprimer placements |

---

## ✅ Checklist d'Installation

- [ ] Ajouter les routes dans `routes/web.php`
- [ ] Modifier `ExamController@store` pour accepter `exam_type`
- [ ] Modifier `ExamCreate.php` pour valider `exam_type`
- [ ] Modifier `ExamUpdate.php` pour valider `exam_type`
- [ ] Exécuter `php artisan migrate`
- [ ] Exécuter `php artisan db:seed --class=ExamRoomsSeeder`
- [ ] Vider le cache: `php artisan optimize:clear`

---

*Document créé le 18 Novembre 2025*
