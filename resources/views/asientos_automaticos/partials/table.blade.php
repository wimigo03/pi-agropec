<div class="form-group row abs-center">
    <div class="col-md-8">
        <table class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-12 bg-secondary text-white">
                    <td class="text-center p-1"><b>MODULO</b></td>
                    <td class="text-center p-1"><b>NOMBRE DEL ASIENTO AUTOMATICO</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['asiento.automatico.show','asiento.automatico.editar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($asientos_automaticos as $datos)
                    <tr class="font-roboto-12">
                        <td class="text-center p-1">{{ $datos->modulo->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-center p-1">
                            <span class="{{ $datos->color_badge_status}}">
                                {{ $datos->status }}
                            </span>
                        </td>
                        @canany(['asiento.automatico.editar','asiento.automatico.habilitar'])
                            <td class="text-center p-1">
                                <div class="d-flex justify-content-center">
                                    @can('asiento.automatico.show')
                                        <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a detalle" style="cursor: pointer;">
                                            <a href="{{ route('asiento.automatico.show',$datos->id) }}" class="badge-with-padding badge badge-primary">
                                                <i class="fas fa-list fa-fw"></i>
                                            </a>
                                        </span>
                                    @endcan
                                    &nbsp;
                                    @if ($datos->estado == '1')
                                        @can('asiento.automatico.editar')
                                            <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                                <a href="{{ route('asiento.automatico.editar',$datos->id) }}" class="badge-with-padding badge badge-warning">
                                                    <i class="fas fa-edit fa-fw"></i>
                                                </a>
                                            </span>
                                        @endcan
                                    @else
                                        <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar No Permitido" style="cursor: pointer;">
                                            <a href="#" class="badge-with-padding badge badge-secondary">
                                                <i class="fas fa-edit fa-fw"></i>
                                            </a>
                                        </span>
                                    @endif
                                    &nbsp;
                                    @can('asiento.automatico.habilitar')
                                        @if($datos->status == "HABILITADO")
                                            <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                                <a href="{{ route('asiento.automatico.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger">
                                                    <i class="fas fa-arrow-alt-circle-down fa-fw"></i>
                                                </a>
                                            </span>
                                        @else
                                            <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                                <a href="{{ route('asiento.automatico.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
                                                    <i class="fas fa-arrow-alt-circle-up fa-fw"></i>
                                                </a>
                                            </span>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-12">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$asientos_automaticos->count()}}</strong> registros de
                    <strong>{{$asientos_automaticos->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $asientos_automaticos->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
