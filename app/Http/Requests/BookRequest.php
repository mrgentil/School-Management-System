<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'author' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'book_type' => 'nullable|string|max:255',
            'url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'total_copies' => 'nullable|integer|min:0',
            'issued_copies' => 'nullable|integer|min:0',
            'my_class_id' => 'nullable|exists:my_classes,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nom du livre',
            'author' => 'Auteur',
            'description' => 'Description',
            'book_type' => 'Type de livre',
            'url' => 'URL',
            'location' => 'Emplacement',
            'total_copies' => 'Nombre total de copies',
            'issued_copies' => 'Copies empruntées',
            'my_class_id' => 'Classe',
        ];
    }
}
