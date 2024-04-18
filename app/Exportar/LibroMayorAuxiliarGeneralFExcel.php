<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class LibroMayorAuxiliarGeneralFExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($libro_mayor_auxiliar_general,$comprobantes,$empresa,$plan_cuenta_auxiliar,$saldo,$saldo_final,$total_debe,$total_haber,$fecha_i,$fecha_f){
        $this->libro_mayor_auxiliar_general = $libro_mayor_auxiliar_general;
        $this->comprobantes = $comprobantes;
        $this->empresa = $empresa;
        $this->plan_cuenta_auxiliar = $plan_cuenta_auxiliar;
        $this->saldo = $saldo;
        $this->saldo_final = $saldo_final;
        $this->total_debe = $total_debe;
        $this->total_haber = $total_haber;
        $this->fecha_i = $fecha_i;
        $this->fecha_f = $fecha_f;
    }

    public function view(): view{
        $libro_mayor_auxiliar_general = $this->libro_mayor_auxiliar_general;
        $comprobantes = $this->comprobantes;
        $empresa = $this->empresa;
        $plan_cuenta_auxiliar = $this->plan_cuenta_auxiliar;
        $saldo = $this->saldo;
        $saldo_final = $this->saldo_final;
        $total_debe = $this->total_debe;
        $total_haber = $this->total_haber;
        $fecha_i = $this->fecha_i;
        $fecha_f = $this->fecha_f;
        return view('libro_mayor_auxiliar_general_f.excel',compact('libro_mayor_auxiliar_general','comprobantes','empresa','plan_cuenta_auxiliar','saldo','saldo_final','total_debe','total_haber','fecha_i','fecha_f'));
    }
}
