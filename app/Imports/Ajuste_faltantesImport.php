<?php

namespace App\Imports;

use App\Ajuste_faltante;
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


class Ajuste_faltantesImport implements ToCollection,WithHeadingRow
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

                if ($fila['crsaldocant'] < 0) {
                    
                    $validar_ajuste_faltante = Ajuste_faltante::where('ajuste_id',$this->ajuste_id)->where('impproduct',$fila['impproduct'])->first();

                    if($validar_ajuste_faltante == null){
                        
                        $ajuste_faltante = new Ajuste_faltante;
                        $ajuste_faltante->ajuste_id = $this->ajuste_id;
                        $ajuste_faltante->impproduct = $fila['impproduct'];
                        $ajuste_faltante->pronombre = $fila['pronombre'];
                        $ajuste_faltante->promarca = $fila['promarca'];
                        $ajuste_faltante->prorefe = $fila['prorefe'];
                        $ajuste_faltante->crestado = $fila['crestado'];
                        $ajuste_faltante->crserial = $fila['crserial'];
                        $ajuste_faltante->crlotepro = $fila['crlotepro'];
                        $ajuste_faltante->crsaldocant = $fila['crsaldocant'];
                        $ajuste_faltante->ulttipodoc = $fila['ulttipodoc'];
                        $ajuste_faltante->ultnumedoc = $fila['ultnumedoc'];
                        //$ajuste_faltante->fecha = $fila['fecha'];
                        
                        $ajuste_faltante->tipo_faltante = 1;
                        $ajuste_faltante->date_new = $dateonly;
                        $ajuste_faltante->created_at = $datehour;
                        $ajuste_faltante->updated_at = $datehour;
                        $ajuste_faltante->user_new = Auth::id();
                        $ajuste_faltante->user_update = Auth::id();
                        $ajuste_faltante->save();

                    }else{

                        $ajuste_faltante = $validar_ajuste_faltante;
                        $ajuste_faltante->impproduct = $fila['impproduct'];
                        $ajuste_faltante->pronombre = $fila['pronombre'];
                        $ajuste_faltante->promarca = $fila['promarca'];
                        $ajuste_faltante->prorefe = $fila['prorefe'];
                        $ajuste_faltante->crestado = $fila['crestado'];
                        $ajuste_faltante->crserial = $fila['crserial'];
                        $ajuste_faltante->crlotepro = $fila['crlotepro'];
                        $ajuste_faltante->crsaldocant = $fila['crsaldocant'];
                        $ajuste_faltante->ulttipodoc = $fila['ulttipodoc'];
                        $ajuste_faltante->ultnumedoc = $fila['ultnumedoc'];
                        //$ajuste_faltante->fecha = $fila['fecha'];
                        
                        $ajuste_faltante->created_at = $datehour;
                        $ajuste_faltante->user_update = Auth::id();
                        $ajuste_faltante->save();

                    }
                }
            }
        }
    }
}