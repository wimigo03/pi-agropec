@if (isset($comprobantes))
    <form action="#" method="get" id="form">
        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}" id="empresa_id">
        <input type="hidden" name="fecha_i" value="{{ request('fecha_i') }}">
        <input type="hidden" name="fecha_f" value="{{ request('fecha_f') }}">
        <input type="hidden" name="sub_centro_id" value="{{ request('sub_centro_id') }}">
        <input type="hidden" name="plan_cuenta_id" value="{{ request('plan_cuenta_id') }}">
        <input type="hidden" name="estado" value="{{ request('estado') }}">
    </form>
    <div class="form-group row font-roboto-12">
        <div class="col-md-1 px-1 pr-1">
            <b>Centro:</b>
        </div>
        <div class="col-md-5 pr-1 pl-1">
            {{ $sub_centro->centro->nombre }}
        </div>
        <div class="col-md-1 pr-1 pl-1">
            <b>SubCentro:</b>
        </div>
        <div class="col-md-5 px-1 pl-1">
            {{ $sub_centro->nombre }}
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-2 pr-1 pl-1">
            <b>Cuenta Contable:</b>
        </div>
        <div class="col-md-10 px-1 pl-1">
            {{ $plan_cuenta->codigo . ' ' . $plan_cuenta->nombre }}
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-1 px-1 pr-1">
            <b>Desde:</b>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            {{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <b>Total Debe:</b>
        </div>
        <div class="col-md-3 px-1 pl-1">
            {{ number_format($total_debe,2,'.',',') }}
        </div>
    </div>
    <div class="form-group row font-roboto-12">
        <div class="col-md-1 px-1 pr-1">
            <b>Hasta:</b>
        </div>
        <div class="col-md-3 pr-1 pl-1">
            {{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </div>
        <div class="col-md-2 pr-1 pl-1">
            <b>Total Haber:</b>
        </div>
        <div class="col-md-3 px-1 pl-1">
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
                        <td class="text-center p-1"><b>AUXILIAR</b></td>
                        <td class="text-center p-1"><b>GLOSA</b></td>
                        <td class="text-center p-1"><b>DEBE</b></td>
                        <td class="text-center p-1"><b>HABER</b></td>
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
                            <td class="text-justify p-1">
                                {{ $datos->auxiliar }}
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
