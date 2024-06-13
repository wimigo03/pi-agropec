<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class LibroMayorCuenta199FExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($empresa,$fecha_i,$fecha_f,$plan_cuenta1,$plan_cuenta2,$comprobantes,$saldos_cuentas,$total_debe,$total_haber){
        $this->empresa = $empresa;
        $this->fecha_i = $fecha_i;
        $this->fecha_f = $fecha_f;
        $this->plan_cuenta1 = $plan_cuenta1;
        $this->plan_cuenta2 = $plan_cuenta2;
        $this->comprobantes = $comprobantes;
        $this->saldos_cuentas = $saldos_cuentas;
        $this->total_debe = $total_debe;
        $this->total_haber = $total_haber;
    }

    public function view(): view{
        $empresa = $this->empresa;
        $fecha_i = $this->fecha_i;
        $fecha_f = $this->fecha_f;
        $plan_cuenta1 = $this->plan_cuenta1;
        $plan_cuenta2 = $this->plan_cuenta2;
        $comprobantes = $this->comprobantes;
        $saldos_cuentas = $this->saldos_cuentas;
        $total_debe = $this->total_debe;
        $total_haber = $this->total_haber;
        return view('libro_mayor_cuenta_199_f.excel',compact('empresa','fecha_i','fecha_f','plan_cuenta1','plan_cuenta2','comprobantes','saldos_cuentas','total_debe','total_haber'));
    }
}
