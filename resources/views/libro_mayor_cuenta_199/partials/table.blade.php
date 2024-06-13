@if (isset($comprobantes))
    <form action="#" method="get" id="form">
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
        <input type="hidden" name="fecha_i" value="{{ request('fecha_i') }}">
        <input type="hidden" name="fecha_f" value="{{ request('fecha_f') }}">
        <input type="hidden" name="plan_cuenta_id1" value="{{ request('plan_cuenta_id1') }}">
        <input type="hidden" name="plan_cuenta_id2" value="{{ request('plan_cuenta_id2') }}">
        <input type="hidden" name="estado" value="{{ request('estado') }}">
    </form>
    <div class="form-group row font-roboto-12">
        <div class="col-md-1 px-1 pr-1">
            <b>Desde:</b>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            {{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <b>Desde Cuenta:</b>
        </div>
        <div class="col-md-4 pr-1 pl-1">
            {{ $plan_cuenta1->codigo . ' ' . $plan_cuenta1->nombre }}
        </div>
        <div class="col-md-1 pr-1 pl-1">
            <b>Total Debe:</b>
        </div>
        <div class="col-md-2 px-1 pl-1">
            {{ number_format($total_debe,2,'.',',') }}
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-1 px-1 pr-1">
            <b>Hasta:</b>
        </div>
        <div class="col-md-2 pr-1 pl-1">
            {{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <b>Hasta Cuenta:</b>
        </div>
        <div class="col-md-4 pr-1 pl-1">
            {{ $plan_cuenta2->codigo . ' ' . $plan_cuenta2->nombre }}
        </div>
        <div class="col-md-1 pr-1 pl-1">
            <b>Total Haber:</b>
        </div>
        <div class="col-md-2 px-1 pl-1">
            {{ number_format($total_haber,2,'.',',') }}
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
                        <td class="text-center p-1"><b>FECHA</b></td>
                        <td class="text-center p-1"><b>COMPROBANTE</b></td>
                        <td class="text-center p-1"><b>CUENTA CONTABLE</b></td>
                        <td class="text-center p-1"><b>AUXILIAR</b></td>
                        <td class="text-center p-1"><b>CENTRO</b></td>
                        <td class="text-center p-1"><b>SUBCENTRO</b></td>
                        {{--<td class="text-center p-1"><b>CHEQUE</b></td>--}}
                        <td class="text-center p-1"><b>GLOSA</b></td>
                        <td class="text-center p-1"><b>DEBE</b></td>
                        <td class="text-center p-1"><b>HABER</b></td>
                        <td class="text-center p-1"><b>SALDO</b></td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cuenta_actual = -1;
                    @endphp
                    @foreach ($comprobantes as $datos)
                        @php
                            if($datos->plan_cuenta_id != $cuenta_actual){
                                $saldo_actual = isset($saldos_cuentas[$datos->plan_cuenta_id]) ? $saldos_cuentas[$datos->plan_cuenta_id] : 0;
                                $cuenta_actual = $datos->plan_cuenta_id;
                            }
                            $saldo_actual += $datos->debe - $datos->haber;
                        @endphp
                        <tr class="font-roboto-11">
                            <td class="text-center p-1">
                                {{ $datos->fecha }}
                            </td>
                            <td class="text-center p-1">
                                {{ $datos->nro_comprobante }} <b>{{ $datos->estado_abreviado}}</b>
                            </td>
                            <td class="text-center p-1">
                                {{ $datos->codigo_contable . ' ' . $datos->cuenta_contable }}
                            </td>
                            <td class="text-center p-1">
                                {{ $datos->auxiliar }}
                            </td>
                            <td class="text-center p-1">
                                {{ $datos->ab_centro }}
                            </td>
                            <td class="text-center p-1">
                                {{ $datos->ab_subcentro }}
                            </td>
                            {{--<td class="text-center p-1">
                                {{ $datos->nro_cheque }}
                            </td>--}}
                            <td class="text-left p-1">
                                {{ $datos->glosa }}
                            </td>
                            <td class="text-right p-1">
                                {{ number_format($datos->debe,2,'.',',') }}
                            </td>
                            <td class="text-right p-1">
                                {{ number_format($datos->haber,2,'.',',') }}
                            </td>
                            <td class="text-right p-1">
                                {{ number_format($saldo_actual,2,'.',',') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
