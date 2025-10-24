<?php

namespace App\Http\Requests\StudyMaterial;

use Illuminate\Foundation\Http\FormRequest;

class StudyMaterialCreate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:51200', // 50MB max
            'subject_id' => 'nullable|exists:subjects,id',
            'my_class_id' => 'nullable|exists:my_classes,id',
            'is_public' => 'boolean'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Titre',
            'description' => 'Description',
            'file' => 'Fichier',
            'subject_id' => 'Matière',
            'my_class_id' => 'Classe',
            'is_public' => 'Public'
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'Le fichier ne doit pas dépasser 50 MB.',
            'file.required' => 'Veuillez sélectionner un fichier à télécharger.'
        ];
    }
}
