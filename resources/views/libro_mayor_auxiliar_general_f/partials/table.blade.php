<form action="#" method="get" id="form">
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
    <input type="hidden" name="fecha_i" value="{{ request('fecha_i') }}">
    <input type="hidden" name="fecha_f" value="{{ request('fecha_f') }}">
    <input type="hidden" name="plan_cuenta_auxiliar_id" value="{{ request('plan_cuenta_auxiliar_id') }}">
    <input type="hidden" name="estado" value="{{ request('estado') }}">
</form>
<div class="form-group row font-roboto-12">
    <div class="col-md-1 px-0 pr-1">
        <b>Auxiliar:</b>
    </div>
    <div class="col-md-11 pr-1 pl-1">
        {{ $plan_cuenta_auxiliar->nombre }}
    </div>
</div>
<div class="form-group row font-roboto-12">
    <div class="col-md-1 px-0 pr-1">
        <b>Desde:</b>
    </div>
    <div class="col-md-3 pr-1 pl-1">
        {{ \Carbon\Carbon::parse($fecha_i)->format('d/m/Y') }}
    </div>
    <div class="col-md-1 pr-1 pl-1">
        <b>Saldo Inicial:</b>
    </div>
    <div class="col-md-3 pr-1 pl-1">
        {{ number_format($saldo,2,'.',',') }}
    </div>
    <div class="col-md-1 pr-1 pl-1">
        <b>Total Debe:</b>
    </div>
    <div class="col-md-3 pr-1 pl-1">
        {{ number_format($total_debe,2,'.',',') }}
    </div>
</div>
<div class="form-group row font-roboto-12">
    <div class="col-md-1 px-0 pr-1">
        <b>Hasta:</b>
    </div>
    <div class="col-md-3 pr-1 pl-1">
        {{ \Carbon\Carbon::parse($fecha_f)->format('d/m/Y') }}
    </div>
    <div class="col-md-1 pr-1 pl-1">
        <b>Saldo Final:</b>
    </div>
    <div class="col-md-3 pr-1 pl-1">
        {{ number_format($saldo_final,2,'.',',') }}
    </div>
    <div class="col-md-1 pr-1 pl-1">
        <b>Total Haber:</b>
    </div>
    <div class="col-md-3 pr-1 pl-1">
        {{ number_format($total_haber,2,'.',',') }}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6 px-0 pr-1">
        <span class="tts:right tts-slideIn tts-custom" aria-label="Exportar a Excel" style="cursor: pointer;">
            <button class="btn btn-success font-verdana" type="button" onclick="excel();">
                <i class="fa-solid fa-file-excel fa-fw"></i>
            </button>
        </span>
        <span class="tts:right tts-slideIn tts-custom" aria-label="Exportar a Pdf" style="cursor: pointer;">
            <button class="btn btn-danger font-verdana" type="button" onclick="pdf();">
                <i class="fa-solid fa-file-pdf fa-fw"></i>
            </button>
        </span>
    </div>
    <div class="col-md-6 px-0 pl-1 text-right">
        <button class="btn btn-primary font-verdana" type="button" onclick="limpiar();">
            <i class="fa-solid fa-angles-left fa-fw"></i> Ir a inicio
        </button>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-12">
        <table id="table-precios" class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-center p-1"><b>FECHA</b></td>
                    <td class="text-center p-1"><b>COMPROBANTE</b></td>
                    <td class="text-center p-1"><b>CUENTA CONTABLE</b></td>
                    <td class="text-center p-1"><b>PROYECTO</b></td>
                    <td class="text-center p-1"><b>CHEQUE</b></td>
                    <td class="text-center p-1"><b>GLOSA</b></td>
                    <td class="text-center p-1"><b>DEBE</b></td>
                    <td class="text-center p-1"><b>HABER</b></td>
                    <td class="text-center p-1"><b>SALDO</b></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($comprobantes as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-center p-1">
                            {{ $datos->fecha }}
                        </td>
                        <td class="text-center p-1">
                            {{ $datos->nro_comprobante }} <b>{{ $datos->estado_abreviado}}</b>
                        </td>
                        <td class="text-left p-1">
                            {{ $datos->codigo . ' ' . $datos->plan_cuenta }}
                        </td>
                        <td class="text-center p-1">
                            {{ $datos->proyecto }}
                        </td>
                        <td class="text-center p-1">
                            {{ $datos->nro_cheque }}
                        </td>
                        <td class="text-left p-1">
                            {{ $datos->glosa }}
                        </td>
                        <td class="text-right p-1">
                            {{ number_format($datos->debe,2,'.',',') }}
                        </td>
                        <td class="text-right p-1">
                            {{ number_format($datos->haber,2,'.',',') }}
                        </td>
                        @php
                            if($datos->debe > 0){
                                $saldo += $datos->debe;
                            }else{
                                $saldo -= $datos->haber;
                            }
                        @endphp
                        <td class="text-right p-1">
                            {{ number_format($saldo,2,'.',',') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
