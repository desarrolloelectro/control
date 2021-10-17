<?php

namespace App\Exports;

use App\Dsi;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DsiExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $dsi;
    protected $data;

    public function __construct($data = null, $id = null)
    {
        $this->data = $data;
        $this->dsi = Dsi::findOrFail($id);
    }

    public function collection()
    {
        $fields_report = json_decode($this->dsi->fields_report);
        
        foreach ($this->data as $index => $consul) {
            $datos = [];
            foreach($fields_report as $field){
                $field_ft = $field."_ft";
                $field_frt = $field."_frt";
                if(isset($consul->$field_frt)): // Campo con Formato y Estilos
                    $datos[] = $consul->$field_frt;
                elseif(isset($consul->$field_ft)): //Campo con Formato
                    $datos[] = $consul->$field_ft;
                else: // Campo normal
                    $datos[] = $consul->$field;
                endif;
            }
            
            if($index == 0){
                $coleccion = collect([$datos]);
            }else{
                $coleccion->push($datos);
            }
            unset($datos);
        }
        return $coleccion;
    }
    
    public function headings(): array
    {
        $fields = Dsi::$fields_data;
        $fields_report = json_decode($this->dsi->fields_report);
        $return = [] ;
        foreach($fields_report as $field){
            $return[] = $fields[$field];
        }
        return $return;
    }
}