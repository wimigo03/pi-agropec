@if (isset($comprobantes))
    <form action="#" method="get" id="form">
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
        <input type="hidden" name="fecha_i" value="{{ request('fecha_i') }}">
        <input type="hidden" name="fecha_f" value="{{ request('fecha_f') }}">
        <input type="hidden" name="estado" value="{{ request('estado') }}">
    </form>
    <div class="form-group row font-roboto-12 abs-center">
        <div class="col-md-1 px-1 pr-1">
            <b>Desde:</b>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            {{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </div>
        <div class="col-md-1 pr-1 pl-1">
            <b>Hasta:</b>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            {{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12 px-1">
            <span class="btn btn-primary font-roboto-12" onclick="limpiar();">
                <i class="fa-solid fa-angles-left fa-fw"></i> Ir atras
            </span>
            <span class="tts:left tts-slideIn tts-custom float-right" aria-label="Exportar" style="cursor: pointer;">
                <span class="btn btn-success font-roboto-12" onclick="excel();">
                    <i class="fa-solid fa-file-excel fa-fw"></i> Excel
                </span>
            </span>
            <span class="tts:left tts-slideIn tts-custom mr-1 float-right" aria-label="Exportar" style="cursor: pointer;">
                <span class="btn btn-danger font-roboto-12" onclick="pdf();">
                    <i class="fa-solid fa-file-pdf fa-fw"></i> Pdf
                </span>
            </span>
            <i class="fa fa-spinner fa-spin fa-lg spinner-btn-send float-right" style="display: none;"></i>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12 px-1">
            <table id="table-precios" class="table display table-bordered responsive table-striped hover-orange">
                <thead>
                    <tr class="font-roboto-11">
                        <td class="text-center p-1"><b>CODIGO</b></td>
                        <td class="text-center p-1"><b>CUENTA CONTABLE</b></td>
                        <td class="text-center p-1"><b>DEBE</b></td>
                        <td class="text-center p-1"><b>HABER</b></td>
                        <td class="text-center p-1"><b>DIFERENCIA</b></td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_debe = 0;
                        $total_haber = 0;
                        $total_diferencia = 0;
                    @endphp
                    @foreach ($comprobantes as $datos)
                        @php
                            $total_debe += floatVal($datos->total_debe_mayor);
                            $total_haber += floatVal($datos->total_haber_mayor);
                            $diferencia  = floatVal($datos->total_debe_mayor) - floatVal($datos->total_haber_mayor);
                            $total_diferencia += floatVal($diferencia);
                        @endphp
                        <tr class="font-roboto-11">
                            @if (in_array($datos->plan_cuenta_id,$plan_cuentas_ids))
                                <td class="text-justify p-1">{{ $plan_cuentas_codigo[$datos->plan_cuenta_id] }}</td>
                                <td class="text-justify p-1">{{ $plan_cuentas[$datos->plan_cuenta_id] }}</td>
                            @else
                                <td class="text-justify p-1">{{ "S/N : "}}</td>
                                <td class="text-justify p-1">{{ "S/N : " . $datos->plan_cuenta_id }}</td>
                            @endif
                            <td class="text-right p-1">
                                {{ number_format($datos->total_debe_mayor,2,'.',',') }}
                            </td>
                            <td class="text-right p-1">
                                {{ number_format($datos->total_haber_mayor,2,'.',',') }}
                            </td>
                            <td class="text-right p-1">
                                {{ number_format($diferencia,2,'.',',') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-roboto-11">
                        <td class="text-center p-1" colspan="2">
                            <b>TOTAL</b>
                        </td>
                        <td class="text-right p-1">
                            <b>{{ number_format($total_debe,2,'.',',') }}</b>
                        </td>
                        <td class="text-right p-1">
                            <b>{{ number_format($total_haber,2,'.',',') }}</b>
                        </td>
                        <td class="text-right p-1">
                            <b>{{ number_format($total_diferencia,2,'.',',') }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif
