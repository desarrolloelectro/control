<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Dia_ivasExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $dia_ivas;

    public function __construct($dia_ivas = null)
    {
        $this->dia_ivas = $dia_ivas;
    }

    public function collection()
    {

        foreach ($this->dia_ivas as $index => $consul) {


            $datos = [

                $consul->id,
                $consul->tipo_identificacion != null ? $consul->tipo_identificacion->nombre : '',
                $consul->identificacion,
                $consul->nombre,
                $consul->tipo_factura != null ? $consul->tipo_factura->codigo : '',
                $consul->tipo_factura != null ? $consul->tipo_factura->nombre : '',
                $consul->tipo_documento != null ? $consul->tipo_documento->codigo : '',
                $consul->tipo_documento != null ? $consul->tipo_documento->nombre : '',
                $consul->numdoc,
                $consul->tipo_documento != null ? $consul->tipo_documento->coddpto." :: ".$consul->tipo_documento->depto :'NO DEFINE',
                $consul->tipo_documento != null ? $consul->tipo_documento->codciu." :: ".$consul->tipo_documento->ciudad :'NO DEFINE',
                $consul->fecha,
                $consul->categoria_nombre != null ? $consul->categoria_nombre->codigo : '',
                $consul->categoria_nombre != null ? $consul->categoria_nombre->nombre : '',
                $consul->genero_nombre != null ? $consul->genero_nombre->codigo : '',
                $consul->genero_nombre != null ? $consul->genero_nombre->nombre : '',
                $consul->cantidad,
                $consul->unidad_nombre != null ? $consul->unidad_nombre->codigo : '',
                $consul->descripcion,
                $consul->vrunit,
                $consul->vrtotal,
                $consul->medio_pago != null ? $consul->medio_pago->id : '',
                $consul->medio_pago != null ? $consul->medio_pago->nombre : '',
                $consul->numsoporte,
                $consul->fechaentrega,
                $consul->pvppublico,
                $consul->obs,
                $consul->iva_estado != null ? $consul->iva_estado->nombre : '',
                $consul->banco_estado != null ? $consul->banco_estado->nombre : '',
                $consul->caja2_estado != null ? $consul->caja2_estado->nombre : '',
                $consul->date_new,
                $consul->user_new,
                $consul->user_update,
                $consul->created_at,
                $consul->updated_at,

              
            ];


            if($index == 0){
                $coleccion = collect([$datos]);
            }else{
                $coleccion->push($datos);
            }
        }
        return $coleccion;
    }

    public function headings(): array
    {
        return [
            'id', 
            'tipoid', 
            'identificacion', 
            'nombre', 
            'tipofac', 
            'nombrefac', 
            'tipodoc', 
            'nombredoc', 
            'numdoc', 
            'Departamento', 
            'Ciudad', 
            'fecha', 
            'categoría', 
            'Nombre Categoría', 
            'Género', 
            'Nombre Género', 
            'Cantidad', 
            'Unidad',
            'Descripción',
            'vrunit', 
            'vrtotal', 
            'mediopago', 
            'Nombre mediopago', 
            'numsoporte', 
            'fechaentrega', 
            'pvppublico', 
            'obs', 
            'Estado Caja', 
            'Estado Bancos', 
            'Estado Caja2', 
            'date_new',
            'user_new',
            'user_update',
            'created_at',
            'updated_at',
                   
        ];
    }

}
