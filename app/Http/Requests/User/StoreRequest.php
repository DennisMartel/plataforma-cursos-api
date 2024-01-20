<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      "name" => "required|string|max:255",
      "email" => "required|string|email|max:255|unique:users",
      "password" => "required|string|min:8"
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'El campo nombre es requerido',
      'name.string' => 'El campo nombre debe de contener caracteres validos',
      'name.max' => 'El campo nombre tiene una cantidad de 255 caracteres',
      'email.required' => 'El campo correo electronico es requerido',
      'email.string' => 'El campo correo electronico debe de contener caracteres validos',
      'email.email' => "Debes de ingresar una dirección de correo válida",
      'email.max' => 'El campo correo electronico tiene una cantidad de 255 caracteres',
      'email.unique' => 'El correo electronico ya existe en nuestros registros',
      'password.required' => 'El campo contraseña es requerido',
      'password.string' => 'El campo contraseña debe de contener caracteres validos',
      'password.min' => 'El campo contraseña debe de tener 8 caracteres como minimo',
    ];
  }
}
