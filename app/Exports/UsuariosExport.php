<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsuariosExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $usuarios;

    public function __construct($usuarios = null)
    {
        $this->usuarios = $usuarios;
    }

    public function collection()
    {

        foreach ($this->usuarios as $index => $consul) {


            $datos = [

                $consul->cedula,
                $consul->nombre,
                $consul->agencia_detalle($consul->agencia),
                $consul->useractivo == '1' ? 'ACTIVO' : 'INACTIVO',
                $consul->coduser,

                $consul->nivel_detalle($consul->nivel_audi,'auditoria'),
                $consul->nivel_detalle($consul->nivel_cartera,'cartera'),
                $consul->nivel_detalle($consul->nivel_clientes,'clientes'),
                $consul->nivel_detalle($consul->nivel_control,'control'),
                $consul->nivel_detalle($consul->nivel_cotiza,'cotiza'),
                $consul->nivel_detalle($consul->nivel_form,'formularios'),
                $consul->nivel_detalle($consul->nivel_help,'helpdesk'),
                $consul->nivel_detalle($consul->nivel_import,'importaciones'),
                $consul->nivel_detalle($consul->nivel_inter,'interelec'),
                $consul->nivel_detalle($consul->nivel_jurid,'juridico'),
                $consul->nivel_detalle($consul->nivel,'perfilaciones'),
                $consul->nivel_detalle($consul->nivel_repues,'repuestos'),
                $consul->user_fec_new,
                $consul->user_created_at,
                $consul->user_new,
                $consul->user_updated_at,
                $consul->user_update,
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
            
            'Identificación', 
            'Nombre', 
            'Agencia', 
            'Estado', 
            'Usuario', 

            'Rol Auditoria ', 
            'Rol Cartera ', 
            'Rol Clientes ', 
            'Rol Control ', 
            'Rol Cotiza ', 
            'Rol Formularios ', 
            'Rol Helpdesk ', 
            'Rol Importaciones ', 
            'Rol Interelec ', 
            'Rol Jurídico ', 
            'Rol Perfilaciones', 
            'Rol Repuestos ', 

            'Fecha Creación ', 
            'Detalle Fecha Creación ', 
            'Usuario Creación ', 
            'Detalle Fecha Actualización ', 
            'Usuario Actualización ', 
             
                   
        ];
    }

}
