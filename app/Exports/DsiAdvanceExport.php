<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DsiAdvanceExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $dsi_data_advances;

    public function __construct($dsi_data_advances = null)
    {
        $this->dia_ivas = $dsi_data_advances;
    }

    public function collection()
    {

        foreach ($this->dsi_data_advances as $index => $consul) {


            $datos = [

                $consul->id,
                $consul->tipo_identificacion,
                $consul->identificacion,
                $consul->nombre,
                $consul->tipo_factura,
                $consul->tipo_documento,
                $consul->numdoc,
                $consul->tipo_documento ,
                $consul->fecha,
                $consul->categoria_nombre,
                $consul->genero_nombre,
                $consul->cantidad,
                $consul->unidad_nombre,
                $consul->descripcion,
                $consul->vrunit,
                $consul->vrtotal,
                $consul->medio_pago,
                $consul->numsoporte,
                $consul->fechaentrega,
                $consul->pvppublico,
                $consul->obs,
                $consul->iva_estado ,
                $consul->banco_estado,
                $consul->caja2_estado,
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
