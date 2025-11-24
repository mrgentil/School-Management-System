<?php

namespace App\Http\Requests\Mark;

use Illuminate\Foundation\Http\FormRequest;

class MarkSelector extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'evaluation_type' => 'required|in:devoir,interrogation,examen',
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
        ];
        
        // Validation conditionnelle selon le type d'évaluation
        if ($this->evaluation_type === 'examen') {
            $rules['exam_id'] = 'required|exists:exams,id';
        } elseif ($this->evaluation_type === 'devoir') {
            $rules['period'] = 'required|integer|in:1,2,3,4';
            $rules['assignment_id'] = 'required|exists:assignments,id';
        } elseif ($this->evaluation_type === 'interrogation') {
            $rules['period'] = 'required|integer|min:1|max:4';
            $rules['interrogation_max_score'] = 'required|numeric|min:1';
        }
        
        return $rules;
    }

    public function attributes()
    {
        return  [
            'evaluation_type' => 'Type d\'évaluation',
            'period' => 'Période',
            'assignment_id' => 'Devoir',
            'exam_id' => 'Examen',
            'my_class_id' => 'Classe',
            'section_id' => 'Section',
            'subject_id' => 'Matière',
            'interrogation_max_score' => 'Cote de l\'interrogation',
        ];
    }
}
