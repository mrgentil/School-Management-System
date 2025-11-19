<?php

namespace App\Http\Requests\MyClass;

use Illuminate\Foundation\Http\FormRequest;

class ClassCreate extends FormRequest
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
        return [
            'name' => 'nullable|string|min:3',
            'class_type_id' => 'required|exists:class_types,id',
            'academic_level' => 'required|string',
            'division' => 'required|string|in:A,B,C,D',
            'academic_option' => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return  [
            'class_type_id' => 'Class Type',
        ];
    }

}
