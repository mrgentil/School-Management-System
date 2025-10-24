<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudyMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'my_class_id' => 'nullable|exists:my_classes,id',
            'is_public' => 'boolean',
        ];

        // Si c'est une création (POST), le fichier est requis
        if ($this->isMethod('post')) {
            $rules['file'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mov,mp3,wav,txt|max:51200'; // 50MB max
        } else {
            // Si c'est une mise à jour (PUT), le fichier est optionnel
            $rules['file'] = 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,avi,mov,mp3,wav,txt|max:51200';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => 'Titre',
            'description' => 'Description',
            'file' => 'Fichier',
            'subject_id' => 'Matière',
            'my_class_id' => 'Classe',
            'is_public' => 'Visible publiquement',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Veuillez sélectionner un fichier à télécharger.',
            'file.mimes' => 'Le fichier doit être de type : PDF, Word, Excel, PowerPoint, Image, Vidéo, Audio ou Texte.',
            'file.max' => 'Le fichier ne doit pas dépasser 50 MB.',
        ];
    }
}
