<?php

namespace App\Exportar;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class ComprobantesExcel implements FromView,ShouldAutoSize{
    use Exportable;

    public function __construct($comprobantes){
        $this->comprobantes = $comprobantes;
    }

    public function view(): view{
        $comprobantes = $this->comprobantes;
        return view('comprobantes.excel',compact('comprobantes'));
    }
}
