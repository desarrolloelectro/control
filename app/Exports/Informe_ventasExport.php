<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Informe_ventasExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $informe_ventas;

    public function __construct($informe_ventas = null)
    {
        $this->informe_ventas = $informe_ventas;
    }

    public function collection()
    {

        foreach ($this->informe_ventas as $index => $consul) {


            $datos = [

                $consul->id,
                $consul->tipoid,
                $consul->identificacion,
                $consul->nombre,
                $consul->tipofac,
                $consul->tipodoc,
                $consul->numdoc,
                $consul->lugar,
                $consul->fecha,
                $consul->categoria,
                $consul->genero,
                $consul->cantidad,
                $consul->unidad,
                $consul->descripcion,
                $consul->vrunit,
                $consul->vrtotal,
                $consul->medio_pago != null ? $consul->medio_pago->nombre : '',
                $consul->numsoporte,
                $consul->fechaentrega,
                $consul->pvppublico,
                $consul->obs,
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
            'tipodoc', 
            'numdoc', 
            'lugar', 
            'fecha', 
            'categoria', 
            'genero', 
            'cantidad', 
            'unidad',
            'descripcion',
            'vrunit', 
            'vrtotal', 
            'mediopago', 
            'numsoporte', 
            'fechaentrega', 
            'pvppublico', 
            'obs', 
            'date_new',
            'user_new',
            'user_update',
            'created_at',
            'updated_at',
                   
        ];
    }

}
