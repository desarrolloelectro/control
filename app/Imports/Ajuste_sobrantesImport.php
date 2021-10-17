<?php

namespace App\Imports;

use App\Ajuste_sobrante;
use App\Linea;
use App\Marca;
use App\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;


class Ajuste_sobrantesImport implements ToCollection,WithHeadingRow
{


    protected $mensaje;
    protected $ajuste_id;
    protected $contador_errores;
    protected $mensaje_errores;

    public function __construct($mensaje = null,$ajuste_id = null)
    {
        $this->mensaje = $mensaje;
        $this->ajuste_id = $ajuste_id;
        $this->contador_errores = 0;
        $this->mensaje_errores = 0;
    }
    

    public function collection(Collection $filas)
    {

        $bandera = true;


        foreach ($filas as $fila) {

            /*$marca = null;
            $linea = null;
            $color = null;*/

            if($fila->filter()->isNotEmpty()){

                $customMessages = [
                    'required' => 'El campo :attribute es obligatorio'
                ];
        
                Validator::make($fila->toArray(), [
                    'impproduct' => 'required',
                ], $customMessages)->validate();


                date_default_timezone_set('America/Bogota');
                $fechahora=time();
                $dateonly=date("Y-m-d", $fechahora);
                $datehour= date("Y-m-d H:i:s", $fechahora);  
                
                if ($fila['crsaldocant'] >= 0) {

                    $validar_ajuste_sobrante = Ajuste_sobrante::where('ajuste_id',$this->ajuste_id)->where('impproduct',$fila['impproduct'])->first();

                    if($validar_ajuste_sobrante == null){
                        
                        $ajuste_sobrante = new Ajuste_sobrante;
                        $ajuste_sobrante->ajuste_id = $this->ajuste_id;
                        $ajuste_sobrante->impproduct = $fila['impproduct'];
                        $ajuste_sobrante->pronombre = $fila['pronombre'];
                        $ajuste_sobrante->promarca = $fila['promarca'];
                        $ajuste_sobrante->prorefe = $fila['prorefe'];
                        $ajuste_sobrante->crestado = $fila['crestado'];
                        $ajuste_sobrante->crserial = $fila['crserial'];
                        $ajuste_sobrante->crlotepro = $fila['crlotepro'];
                        $ajuste_sobrante->crsaldocant = $fila['crsaldocant'];
                        $ajuste_sobrante->ulttipodoc = $fila['ulttipodoc'];
                        $ajuste_sobrante->ultnumedoc = $fila['ultnumedoc'];
                        //$ajuste_sobrante->fecha = $fila['fecha'];
                        
                        $ajuste_sobrante->tipo_sobrante = 1;
                        $ajuste_sobrante->date_new = $dateonly;
                        $ajuste_sobrante->created_at = $datehour;
                        $ajuste_sobrante->updated_at = $datehour;
                        $ajuste_sobrante->user_new = Auth::id();
                        $ajuste_sobrante->user_update = Auth::id();
                        $ajuste_sobrante->save();

                    }else{

                        $ajuste_sobrante = $validar_ajuste_sobrante;
                        $ajuste_sobrante->impproduct = $fila['impproduct'];
                        $ajuste_sobrante->pronombre = $fila['pronombre'];
                        $ajuste_sobrante->promarca = $fila['promarca'];
                        $ajuste_sobrante->prorefe = $fila['prorefe'];
                        $ajuste_sobrante->crestado = $fila['crestado'];
                        $ajuste_sobrante->crserial = $fila['crserial'];
                        $ajuste_sobrante->crlotepro = $fila['crlotepro'];
                        $ajuste_sobrante->crsaldocant = $fila['crsaldocant'];
                        $ajuste_sobrante->ulttipodoc = $fila['ulttipodoc'];
                        $ajuste_sobrante->ultnumedoc = $fila['ultnumedoc'];
                        //$ajuste_sobrante->fecha = $fila['fecha'];
                        
                        $ajuste_sobrante->created_at = $datehour;
                        $ajuste_sobrante->user_update = Auth::id();
                        $ajuste_sobrante->save();

                    }
                }
            }
        }
    }

}