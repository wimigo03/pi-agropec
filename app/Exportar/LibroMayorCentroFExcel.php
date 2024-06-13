<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class LibroMayorCentroFExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($empresa,$fecha_i,$fecha_f,$comprobantes,$sub_centro,$total_debe,$total_haber){
        $this->empresa = $empresa;
        $this->fecha_i = $fecha_i;
        $this->fecha_f = $fecha_f;
        $this->comprobantes = $comprobantes;
        $this->sub_centro = $sub_centro;
        $this->total_debe = $total_debe;
        $this->total_haber = $total_haber;
    }

    public function view(): view{
        $empresa = $this->empresa;
        $fecha_i = $this->fecha_i;
        $fecha_f = $this->fecha_f;
        $comprobantes = $this->comprobantes;
        $sub_centro = $this->sub_centro;
        $total_debe = $this->total_debe;
        $total_haber = $this->total_haber;
        return view('libro_mayor_centro_f.excel',compact('empresa','fecha_i','fecha_f','comprobantes','sub_centro','total_debe','total_haber'));
    }
}
