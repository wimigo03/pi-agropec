<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class LibroSumasYSaldosFExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($fecha_i,$fecha_f,$comprobantes,$empresa,$plan_cuentas,$plan_cuentas_codigo,$plan_cuentas_ids){
        $this->fecha_i = $fecha_i;
        $this->fecha_f = $fecha_f;
        $this->comprobantes = $comprobantes;
        $this->empresa = $empresa;
        $this->plan_cuentas = $plan_cuentas;
        $this->plan_cuentas_codigo = $plan_cuentas_codigo;
        $this->plan_cuentas_ids = $plan_cuentas_ids;
    }

    public function view(): view{
        $fecha_i = $this->fecha_i;
        $fecha_f = $this->fecha_f;
        $comprobantes = $this->comprobantes;
        $empresa = $this->empresa;
        $plan_cuentas = $this->plan_cuentas;
        $plan_cuentas_codigo = $this->plan_cuentas_codigo;
        $plan_cuentas_ids = $this->plan_cuentas_ids;
        return view('libro_sumas_y_saldos_f.excel',compact('fecha_i','fecha_f','comprobantes','empresa','plan_cuentas','plan_cuentas_codigo','plan_cuentas_ids'));
    }
}
