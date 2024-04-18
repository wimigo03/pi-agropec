<div class="form-group row">
    <div class="col-md-12">
        <table id="table-precios" class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-center p-1"><b>USUARIO</b></td>
                    <td class="text-center p-1"><b>CARGO</b></td>
                    <td class="text-center p-1"><b>COMPROBANTE</b></td>
                    <td class="text-center p-1"><b>TIPO CAMBIO</b></td>
                    <td class="text-center p-1"><b>GESTION</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['balance.apertura.f.editar','comprobantef.editar'])
                        <td class="text-center p-1" style="vertical-align: bottom;"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($balances as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-center p-1">{{ $datos->user->username }}</td>
                        <td class="text-center p-1">{{ $datos->cargo != null ? $datos->cargo->nombre : '#' }}</td>
                        <td class="text-center p-1">{{ $datos->comprobantef->nro_comprobante }}</td>
                        <td class="text-center p-1">{{ $datos->tipo_cambio != null ? number_format($datos->tipo_cambio->dolar_oficial,2,'.',',') : '-' }}</td>
                        <td class="text-center p-1">{{ $datos->gestion }}</td>
                        <td class="text-center p-1" width="150px">
                            <span class="badge-with-padding
                                @if($datos->status == "PENDIENTE")
                                    badge badge-secondary
                                @else
                                    @if($datos->status == "ANULADO")
                                        badge badge-danger
                                    @else
                                        badge badge-success text-white
                                    @endif
                                @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                        @canany(['balance.apertura.f.editar','comprobantef.editar'])
                            <td class="text-center p-1">
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a detalle" style="cursor: pointer;">
                                    <a href="{{ route('comprobantef.editar',$datos->comprobantef_id) }}" class="badge-with-padding badge badge-warning text-white">
                                        <i class="fa-solid fa-bars-staggered fa-fw"></i>
                                    </a>
                                </span>
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
