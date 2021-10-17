<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DsiMeta;

class DsiMetaStoreRequest extends FormRequest
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
        $dsi_id = $this->dsi_id;
        $types = array_keys(DsiMeta::$types);

        return [
            'dsi_id' => 'required',
            'field_name' => ['required',
               #Ejemplo de validaciones avanzadas
               function ($attribute, $value, $fail) use ($dsi_id){
                   if (is_string($value) && !empty($value)) {
                       #Busqueda en nuestro modelo DsiMeta
                       $meta = DsiMeta::where('dsi_id', '=', $dsi_id)->where($attribute, '=', $value)->first();
                   }
                   if (!empty($meta)) {
                       #Mensaje personalizado
                       $fail(__("El registro $value ya existe en este grupo"));
                   }
               }],
            'type' => ['required',
                #Ejemplo de validaciones avanzadas
                function ($attribute, $value, $fail) use ($types){
                    if (is_string($value) && !empty($value)) {
                        #Busqueda en el arreglo types
                        $rule = in_array($value, $types);
                    }
                    if (!$rule) {
                        #Mensaje personalizado
                        $fail(__("El registro $value no es un tipo válido."));
                    }
                }],
        ];
    }
    public function messages()
{
    return [
        'dsi_id.required' => 'El codigo dsi es un campo requerido',
        'type.required' => 'El tipo es un campo requerido',
        'field_name.required' => 'El nombre del Campo es un campo requerido',
    ];
}
}
