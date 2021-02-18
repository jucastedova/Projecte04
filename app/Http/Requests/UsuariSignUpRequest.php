<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule; 
use Illuminate\Foundation\Http\FormRequest;

class UsuariSignUpRequest extends FormRequest
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
            'nombre' => 'required',
            'apellido' => 'required',
            'password' => 'required',
            'password2' => 'required|same:password',
            'email' => 'required|unique:tbl_usuari,Correu_usuari|email|max:255|email'
        ];
    }
}
