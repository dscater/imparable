<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionUpdateRequest extends FormRequest
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
            "nombre_sistema" => "required|min:2",
            "razon_social" => "required|min:2",
            "alias" => "required",
            "logo" => "nullable",
        ];
    }

    public function messages()
    {
        return [
            "nombre_sistema.required" => "Este campo es obligatorio",
            "nombre_sistema.min" => "Debes ingresar al menos :min caracteres",
            "razon_social.required" => "Este campo es obligatorio",
            "razon_social.min" => "Debes ingresar al menos :min caracteres",
            "alias.required" => "Este campo es obligatorio",
        ];
    }
}
