<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class LibroMayorCuentaYAuxiliarExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($libro_mayor_cuenta_y_auxiliar,$comprobantes,$fecha_i,$fecha_f,$saldo_cuenta,$saldo_final_cuenta,$empresa,$plan_cuenta,$total_debe_cuenta,$total_haber_cuenta,$saldo_auxiliar,$saldo_final_auxiliar,$plan_cuenta_auxiliar,$total_debe_auxiliar,$total_haber_auxiliar){
        $this->libro_mayor_cuenta_y_auxiliar = $libro_mayor_cuenta_y_auxiliar;
        $this->comprobantes = $comprobantes;
        $this->fecha_i = $fecha_i;
        $this->fecha_f = $fecha_f;
        $this->saldo_cuenta = $saldo_cuenta;
        $this->saldo_final_cuenta = $saldo_final_cuenta;
        $this->empresa = $empresa;
        $this->plan_cuenta = $plan_cuenta;
        $this->total_debe_cuenta = $total_debe_cuenta;
        $this->total_haber_cuenta = $total_haber_cuenta;
        $this->saldo_auxiliar = $saldo_auxiliar;
        $this->saldo_final_auxiliar = $saldo_final_auxiliar;
        $this->plan_cuenta_auxiliar = $plan_cuenta_auxiliar;
        $this->total_debe_auxiliar = $total_debe_auxiliar;
        $this->total_haber_auxiliar = $total_haber_auxiliar;
    }

    public function view(): view{
        $libro_mayor_cuenta_y_auxiliar = $this->libro_mayor_cuenta_y_auxiliar;
        $comprobantes = $this->comprobantes;
        $fecha_i = $this->fecha_i;
        $fecha_f = $this->fecha_f;
        $saldo_cuenta = $this->saldo_cuenta;
        $saldo_final_cuenta = $this->saldo_final_cuenta;
        $empresa = $this->empresa;
        $plan_cuenta = $this->plan_cuenta;
        $total_debe_cuenta = $this->total_debe_cuenta;
        $total_haber_cuenta = $this->total_haber_cuenta;
        $saldo_auxiliar= $this->saldo_auxiliar;
        $saldo_final_auxiliar = $this->saldo_final_auxiliar;
        $plan_cuenta_auxiliar = $this->plan_cuenta_auxiliar;
        $total_debe_auxiliar = $this->total_debe_auxiliar;
        $total_haber_auxiliar = $this->total_haber_auxiliar;
        return view('libro_mayor_cuenta_general_y_auxiliar.excel',compact(
            'libro_mayor_cuenta_y_auxiliar',
            'comprobantes',
            'fecha_i',
            'fecha_f',
            'saldo_cuenta',
            'saldo_final_cuenta',
            'empresa',
            'plan_cuenta',
            'total_debe_cuenta',
            'total_haber_cuenta',
            'saldo_auxiliar',
            'saldo_final_auxiliar',
            'plan_cuenta_auxiliar',
            'total_debe_auxiliar',
            'total_haber_auxiliar'
        ));
    }
}
