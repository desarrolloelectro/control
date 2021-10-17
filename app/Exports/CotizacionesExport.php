<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CotizacionesExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $cotizaciones;

    public function __construct($cotizaciones = null)
    {
        $this->cotizaciones = $cotizaciones;
    }

    public function collection()
    {

        foreach ($this->cotizaciones as $index => $consul) {


            $datos = [

                $consul->id,
                $consul->descripcion,
                $consul->agencia != null ? $consul->agencia->agennom : "",
                $consul->tipo_gasto != null ? $consul->tipo_gasto->tipo." :: ".$consul->tipo_gasto->nombre : "" ,
                $consul->estado != null ? $consul->estado->nombre : "",
                $consul->valor_cotizaciones($consul->id),
                $consul->valor_autorizado($consul->id),
                $consul->obs,
                $consul->user_new,
                $consul->created_at,
                $consul->user_autoriza,
                $consul->usuario_nombre($consul->user_autoriza),
                $consul->date_autoriza,
                $consul->tipo_gasto_gasto != null ? $consul->tipo_gasto_gasto->tipo." :: ".$consul->tipo_gasto_gasto->nombre : "" ,
                $consul->num_gasto,
                $consul->tipo_gasto_gasto !=null ? $consul->tipo_gasto_gasto->tipo."-".$consul->num_gasto : "",
                $consul->area != null ? $consul->area->nombre : '',
                $consul->factura,
                $consul->codigo,
                $consul->valor_egreso,
                $consul->descripcion_gasto,
                $consul->tipo_pago != null ? $consul->tipo_pago->nombre : '',
                $consul->banco != null ? $consul->banco->nombre.' :: '.$consul->banco->num_cuenta : "",
                $consul->obs_auditoria,
                $consul->obs_revisoria,
                $consul->valor_autorizado,
                $consul->gasto_estado != null ? $consul->gasto_estado->nombre : '',
                $consul->gasto_revisoria != null ? $consul->gasto_revisoria->nombre : '',
                $consul->usuario_nombre($consul->user_autoriza_gasto),
                $consul->date_autoriza_gasto,

              
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
            
            'ID', 
            'Detalle', 
            'Agencia', 
            'Tipo Gasto', 
            'Estado Autorización', 
            'Valor Cotizaciones', 
            'Valor Autorizado', 
            'Observaciones', 
            'Usuario', 
            'Fecha', 
            'Usuario Autoriza',
            'Nombre Autoriza',
            'Fecha Autorización',
            'Tipo Gasto Gasto',
            'Número Gasto', 
            'tiponum',
            'Área', 
            'Factura', 
            'Código', 
            'Valor Egreso', 
            'Descripción Gasto', 
            'Tipo Pago', 
            'Banco', 
            'Observaciones Gasto Auditoría', 
            'Observaciones Gasto Revisoría', 
            'Valor Autorizado Gasto', 
            'Estado Gasto', 
            'Estado Revisoría', 
            'Usuario Autoriza Gasto', 
            'Fecha Autorización Gasto', 
                   
        ];
    }

}
