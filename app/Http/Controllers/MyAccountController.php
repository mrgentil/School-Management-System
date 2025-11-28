<?php

namespace App\Http\Controllers;


use App\Helpers\Qs;
use App\Http\Requests\UserChangePass;
use App\Http\Requests\UserUpdate;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyAccountController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function edit_profile()
    {
        $d['my'] = Auth::user();
        return view('pages.support_team.my_account', $d);
    }

    public function update_profile(UserUpdate $req)
    {
        $user = Auth::user();

        // Récupérer tous les champs modifiables
        $d = $req->only(['email', 'phone', 'phone2', 'address']);
        
        // Ajouter username seulement si pas déjà défini
        if (!$user->username && $req->username) {
            $d['username'] = $req->username;
        }

        $user_type = $user->user_type;
        $code = $user->code ?: $user->id; // Utiliser l'ID si pas de code

        // Traitement de la photo
        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            
            if ($photo->isValid()) {
                $ext = strtolower($photo->getClientOriginalExtension());
                $fileName = 'photo_' . time() . '.' . $ext;
                
                // Créer le dossier si nécessaire
                $uploadPath = 'uploads/' . $user_type . '/' . $code;
                $fullPath = storage_path('app/public/' . $uploadPath);
                
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }
                
                // Déplacer le fichier directement
                $photo->move($fullPath, $fileName);
                
                // Sauvegarder le chemin dans la base de données
                $d['photo'] = 'storage/' . $uploadPath . '/' . $fileName;
            }
        }

        // Mettre à jour l'utilisateur
        $this->user->update($user->id, $d);
        
        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function change_pass(UserChangePass $req)
    {
        $user_id = Auth::user()->id;
        $my_pass = Auth::user()->password;
        $old_pass = $req->current_password;
        $new_pass = $req->password;

        if(password_verify($old_pass, $my_pass)){
            $data['password'] = Hash::make($new_pass);
            $this->user->update($user_id, $data);
            return back()->with('flash_success', __('msg.p_reset'));
        }

        return back()->with('flash_danger', __('msg.p_reset_fail'));
    }

}
