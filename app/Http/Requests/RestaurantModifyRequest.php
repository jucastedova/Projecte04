<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantModifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'Nom_restaurant' => 'required',
            'Ciutat_restaurant' => 'required',
            'CP_restaurant' => 'required|max:5',
            'Adreca_restaurant' => 'required',
            'Preu_mitja_restaurant' => 'required',
            'Correu_gerent_restaurant' => 'required|email',
            'Descripcio_restaurant' => 'required',
            'Tipos_Cocinas' => 'required'
        ];
    }
}
