<!DOCTYPE html>
@extends('layouts.dashboard')
<style>

</style>
@section('content')
    <div class="form-group row">
        <div class="col-md-2 px-0 pr-1 font-roboto-13">
            <input type="hidden" value="{{ $comprobante->id }}" id="comprobante_id">
            <b>Nro.- </b>{{ $comprobante->nro_comprobante }}
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-13">
            <b>Tipo de Cambio.- </b>{{ $comprobante->tipo_cambio }}
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-13">
            <b>Ufv.- </b>{{ $comprobante->ufv }}
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-13">
            <b>Usuario.- </b>{{ $comprobante->user->username }}
        </div>
        <div class="col-md-3 px-0 pl-1 font-roboto-13">
            <b>Estado.- </b>
            @if ($comprobante->estado == '1')
                <span class="text-secondary"><b><u>{{ $comprobante->status }}</u></b></span>
            @endif
            @if ($comprobante->estado == '2')
                <span class="text-success"><b><u>{{ $comprobante->status }}</u></b></span>
            @endif
            @if ($comprobante->estado == '3')
                <span class="text-danger"><b><u>{{ $comprobante->status }}</u></b></span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-2 px-0 pr-1 font-roboto-13">
            <b>Moneda.- </b>{{ $comprobante->moneda }}
        </div>
        <div class="col-md-3 pr-1 pl-1 font-roboto-13">
            <b>Empresa.- </b>{{ $comprobante->empresa->nombre_comercial }}
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-13">
            <b>Fecha.- </b>{{ $comprobante->fecha }}
        </div>
        <div class="col-md-2 pr-1 pl-1 font-roboto-13">
            <b>Tipo.- </b>{{ App\Models\Comprobante::TIPOS[$comprobante->tipo] }}
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3 px-0 pr-1 font-roboto-13">
            @if ($comprobante->tipo != '3')
                @if ($comprobante->tipo == '1')
                    <b>Hemos recibido de </b>
                @else
                    <b>Hemos entregado a </b>
                @endif
                {{ $comprobante->entregado_recibido }}
            @endif
        </div>
        <div class="col-md-9 px-0 pl-1 font-roboto-13">
            <b>Concepto.- </b>{{ $comprobante->concepto }}
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6 px-0 pr-1 font-verdana-bg">
            @if ($comprobantei != null)
                @can('comprobante.show')
                    <span class="tts:right tts-slideIn tts-custom" aria-label="Ir" style="cursor: pointer;">
                        <a href="{{ route('comprobante.show',$comprobantei->id) }}">
                            <span class="badge-with-padding
                                @if($comprobantei->estado == "1")
                                    badge badge-secondary
                                @else
                                    @if($comprobantei->estado == "2")
                                        badge badge-success
                                    @else
                                        badge badge-danger
                                    @endif
                                @endif">
                                {{ $comprobantei->nro_comprobante }}
                            </span>
                        </a>
                    </span>
                @endcan
            @endif
        </div>
        <div class="col-md-6 px-0 pl-1 font-verdana-bg text-right">
            @can('comprobantef.pdf')
                <span class="tts:left tts-slideIn tts-custom" aria-label="Exportar a Pdf" style="cursor: pointer;">
                    <button class="btn btn-sm btn-danger font-verdana-bg " type="button" onclick="pdf();">
                        <i class="fa-solid fa-file-pdf fa-fw"></i>
                    </button>
                </span>
            @endcan
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 font-roboto-13 text-center">
            <b>_ DETALLE DEL COMPROBANTE _</b>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12 table-responsive">
            <table id="tabla_comprobante_detalle" class="table display table-bordered responsive hover-orange" style="width:100%;">
                <thead>
                    <tr class="font-roboto-12 bg-warning text-white">
                        <td class="text-left p-1"><b>N°</b></td>
                        <td class="text-left p-1"><b>CUENTA</b></td>
                        <td class="text-left p-1"><b>AUXILIAR</b></td>
                        <td class="text-left p-1"><b>CENTRO</b></td>
                        <td class="text-left p-1"><b>GLOSA</b></td>
                        <td class="text-right p-1"><b>DEBE</b></td>
                        <td class="text-right p-1"><b>HABER</b></td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1
                    @endphp
                     @foreach ($comprobante_detalles as $datos)
                        <tr class="font-roboto-11">
                            <td class="text-left p-1">{{ $cont++ }}</td>
                            <td class="text-left p-1">{{ $datos->plan_cuenta->nombre }}</td>
                            <td class="text-left p-1">{{ $datos->plan_cuenta_auxiliar != null ? $datos->plan_cuenta_auxiliar->nombre : '-' }}</td>
                            <td class="text-left p-1">{{ $datos->centro->nombre }}</td>
                            <td class="text-left p-1">{{ $datos->glosa }}</td>
                            <td class="text-right p-1">{{ number_format($datos->debe,2,'.',',') }}</td>
                            <td class="text-right p-1">{{ number_format($datos->haber,2,'.',',') }}</td>
                        </tr>
                     @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-roboto-11">
                        <td class="text-center p-1" colspan="5"><b>TOTAL</b></td>
                        <td class="text-right p-1"><b>{{ number_format($total_debe,2,'.',',') }}</b></td>
                        <td class="text-right p-1"><b>{{ number_format($total_haber,2,'.',',') }}</b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12 text-right">
            @if ($comprobante->estado == '1')
                @can('comprobantef.aprobar')
                    <button class="btn btn-outline-success font-verdana" type="button" onclick="procesar();">
                        <i class="fas fa-paper-plane"></i>&nbsp;Aprobar
                    </button>
                @endcan
                @can('comprobantef.aprobar')
                    <button class="btn btn-outline-warning font-verdana" type="button" onclick="anular();">
                        <i class="fas fa-paper-plane"></i>&nbsp;Anular
                    </button>
                @endcan
            @endif
            <button class="btn btn-outline-danger font-verdana" type="button" onclick="cancelar();">
                &nbsp;<i class="fas fa-times"></i>&nbsp;Cancelar
            </button>
            <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    @include('layouts.notificaciones')
    <script>
        $(document).ready(function() {

        });

        function alert(mensaje){
            $("#modal-alert .modal-body").html(mensaje);
            $('#modal-alert').modal({keyboard: false});
        }

        function procesar() {
            $('#modal_confirmacion').modal({
                keyboard: false
            })
        }

        function confirmar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#comprobante_id").val();
            var url = "{{ route('comprobantef.aprobar',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function anular(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#comprobante_id").val();
            var url = "{{ route('comprobantef.anular',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }

        function pdf(){
            var id = $("#comprobante_id").val();
            var url = "{{ route('comprobantef.pdf', ':id') }}";
            url = url.replace(':id', id);
            window.open(url, '_blank');
        }

        function cancelar(){
            $(".btn").hide();
            $(".spinner-btn").show();
            var id = $("#empresa_id").val();
            var url = "{{ route('comprobantef.index',':id') }}";
            url = url.replace(':id',id);
            window.location.href = url;
        }
    </script>
@stop
