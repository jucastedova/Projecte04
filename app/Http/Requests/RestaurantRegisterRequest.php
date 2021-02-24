<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class RestaurantRegisterRequest extends FormRequest
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
            'nom_restaurant' => 'required',
            'Ciutat_restaurant' => 'required',
            'CP_restaurant' => 'required',
            'adreca_restaurant' => 'required', 
            'preu_mitja' => 'required',
            'correu_gerent' => 'required|email',
            'descripcio_restaurant' => 'required',
            'imatge' => 'required',
            'tiposCocinas' => 'required'
        ];
    }
}
