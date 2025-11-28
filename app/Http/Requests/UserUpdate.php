<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
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
            'phone' => 'sometimes|nullable|string|max:20',
            'phone2' => 'sometimes|nullable|string|max:20',
            'email' => 'sometimes|nullable|email|max:100',
            'username' => 'sometimes|nullable|alpha_dash|min:3|max:100|unique:users,username,' . auth()->id(),
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg,webp|max:5120',
            'address' => 'sometimes|nullable|string|max:200'
        ];
    }

    public function attributes()
    {
        return  [
            'nal_id' => 'Nationality',
            'state_id' => 'State',
            'lga_id' => 'LGA',
            'phone2' => 'Telephone',
        ];
    }
}
