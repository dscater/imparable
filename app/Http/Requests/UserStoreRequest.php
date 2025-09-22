<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            "persona_id" => "required|unique:users,persona_id",
            "acceso" => "required",
            "tipo" => "required",
            "foto" => "nullable|image|mimes:png,jpg,jpeg,webp|max:4096",
            "acceso" => "required",
        ];
    }

    /**
     * Mensages validacion
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            "persona_id.required" => "Este campo es obligatorio",
            "persona_id.unique" => "Esta persona ya tiene un usuario asignado",
            "tipo.required" => "Este campo es obligatorio",
            "foto.image" => "Debes cargar una imagen",
            "foto.mimes" => "Solo puedes enviar formatos png,jpg,jpeg,webp",
            "foto.max" => "No puedes cargar una imagen con mas de 4096KB",
            "acceso.required" => "Este campo es obligatorio",
        ];
    }
}
