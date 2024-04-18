<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class EstadoResultadoExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($empresa,$ingresos,$costos,$gastos,$nroMaxColumna,$total,$totales){
        $this->empresa = $empresa;
        $this->ingresos = $ingresos;
        $this->costos = $costos;
        $this->gastos = $gastos;
        $this->nroMaxColumna = $nroMaxColumna;
        $this->total = $total;
        $this->totales = $totales;
    }
    
    public function view(): view{
        $empresa = $this->empresa;
        $ingresos = $this->ingresos;
        $costos = $this->costos;
        $gastos = $this->gastos;
        $nroMaxColumna = $this->nroMaxColumna;
        $total = $this->total;
        $totales = $this->totales;
        return view('estado_resultado.excel',compact('empresa','ingresos','costos','gastos','nroMaxColumna','total','totales'));
    }
}