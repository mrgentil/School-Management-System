<?php

namespace App\Http\Requests\SchoolEvent;

use Illuminate\Foundation\Http\FormRequest;

class SchoolEventUpdate extends FormRequest
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
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:academic,sports,cultural,meeting,exam,holiday,other',
            'target_audience' => 'required|in:all,students,teachers,parents,staff',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|in:daily,weekly,monthly,yearly'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Titre',
            'description' => 'Description',
            'event_date' => 'Date de l\'événement',
            'start_time' => 'Heure de début',
            'end_time' => 'Heure de fin',
            'location' => 'Lieu',
            'event_type' => 'Type d\'événement',
            'target_audience' => 'Public cible',
            'color' => 'Couleur',
            'is_recurring' => 'Récurrent',
            'recurrence_pattern' => 'Modèle de récurrence'
        ];
    }
}
