<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class LibroMayorCuentaGeneralExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($libro_mayor_cuenta_general,$comprobantes,$fecha_i,$fecha_f,$saldo,$saldo_final,$empresa,$plan_cuenta,$total_debe,$total_haber){
        $this->libro_mayor_cuenta_general = $libro_mayor_cuenta_general;
        $this->comprobantes = $comprobantes;
        $this->fecha_i = $fecha_i;
        $this->fecha_f = $fecha_f;
        $this->saldo = $saldo;
        $this->saldo_final = $saldo_final;
        $this->empresa = $empresa;
        $this->plan_cuenta = $plan_cuenta;
        $this->total_debe = $total_debe;
        $this->total_haber = $total_haber;
    }

    public function view(): view{
        $libro_mayor_cuenta_general = $this->libro_mayor_cuenta_general;
        $comprobantes = $this->comprobantes;
        $fecha_i = $this->fecha_i;
        $fecha_f = $this->fecha_f;
        $saldo = $this->saldo;
        $saldo_final = $this->saldo_final;
        $empresa = $this->empresa;
        $plan_cuenta = $this->plan_cuenta;
        $total_debe = $this->total_debe;
        $total_haber = $this->total_haber;
        return view('libro_mayor_cuenta_general.excel',compact('libro_mayor_cuenta_general','comprobantes','fecha_i','fecha_f','saldo','saldo_final','empresa','plan_cuenta','total_debe','total_haber'));
    }
}
