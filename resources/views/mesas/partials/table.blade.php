<div class="form-group row">
    <div class="col-md-12 px-1">
        <table class="table display hover-orange responsive table-striped">
            <thead>
                <tr class="font-roboto-11">
                    <td class="text-left p-1"><b>ID</b></td>
                    <td class="text-left p-1"><b>SUCURSAL</b></td>
                    <td class="text-left p-1"><b>ZONA</b></td>
                    <td class="text-left p-1"><b>NUMERO</b></td>
                    <td class="text-left p-1"><b>CANT. SILLAS</b></td>
                    <td class="text-left p-1"><b>DETALLE</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['mesas.editar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($mesas as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-left p-1">{{ $datos->sucursal->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->zona->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->numero }}</td>
                        <td class="text-left p-1">{{ $datos->sillas }}</td>
                        <td class="text-left p-1">{{ $datos->detalle }}</td>
                        <td class="text-center p-1">
                            <span class="{{ $datos->colorStatus }}">
                                {{ $datos->status }}
                            </span>
                        </td>
                        <td class="text-center p-1">
                            @can('mesas.editar')
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                    <a href="{{ route('mesas.editar',$datos->id) }}" class="badge-with-padding badge badge-secondary text-white">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                </span>
                            @endcan
                            @can('mesas.habilitar')
                                @if($datos->status == "HABILITADO")
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <a href="{{ route('mesas.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger">
                                            <i class="fas fa-lg fa-arrow-alt-circle-down"></i>
                                        </a>
                                    </span>
                                @else
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <a href="{{ route('mesas.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
                                            <i class="fas fa-lg fa-arrow-alt-circle-up"></i>
                                        </a>
                                    </span>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-11">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$mesas->count()}}</strong> registros de
                    <strong>{{$mesas->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $mesas->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
