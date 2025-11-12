<?php

namespace App\Http\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;

class NoticeCreate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:announcement,event,urgent,general',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'target_audience' => 'required|in:all,students,teachers,parents,staff',
            'is_active' => 'boolean'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Titre',
            'content' => 'Contenu',
            'type' => 'Type',
            'start_date' => 'Date de début',
            'end_date' => 'Date de fin',
            'target_audience' => 'Public cible',
            'is_active' => 'Actif'
        ];
    }
}
