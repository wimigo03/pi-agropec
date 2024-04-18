<div class="form-group row">
    <div class="col-md-12">
        <table class="table display responsive table-striped">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>ID</b></td>
                    <td class="text-left p-1"><b>NOMBRE</b></td>
                    <td class="text-left p-1"><b>CIUDAD</b></td>
                    <td class="text-left p-1"><b>DIRECCION</b></td>
                    <td class="text-left p-1"><b>CELULAR</b></td>
                    <td class="text-center p-1"><b>ZONAS</b></td>
                    <td class="text-center p-1"><b>MESAS</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @can(['sucursal.modificar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach ($sucursales as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->ciudad }}</td>
                        <td class="text-left p-1">{{ $datos->direccion }}</td>
                        <td class="text-left p-1">{{ $datos->celular }}</td>
                        <td class="text-center p-1">
                            <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a Zonas" style="cursor: pointer;">
                                <a href="{{ route('zonas.index',$datos->id) }}">
                                    <span class="badge-with-padding badge badge-info">
                                        {{ $datos->zonas }}
                                    </span>
                                </a>
                            </span>
                        </td>
                        <td class="text-center p-1">
                            <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a Mesas" style="cursor: pointer;">
                                <a href="{{-- route('mesas.index.sucursal',$datos->id) --}}">
                                    <span class="badge-with-padding badge badge-info">
                                        {{ $datos->mesas }}
                                    </span>
                                </a>
                            </span>
                        </td>
                        <td class="text-center p-1">
                            <span class="badge-with-padding @if($datos->status == "HABILITADO") badge badge-success @else badge badge-danger @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                        <td class="text-center p-1">
                            @can('sucursal.modificar')
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                    <a href="{{ route('sucursal.editar',$datos->id) }}" class="badge-with-padding badge badge-secondary text-white">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                </span>
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a Zonas" style="cursor: pointer;">
                                    <a href="{{ route('zonas.index',$datos->id) }}" class="badge-with-padding badge badge-primary">
                                        <i class="fas fa-laptop-house fa-fw"></i>
                                    </a>
                                </span>
                            @endcan
                            @can('mesas.setting')
                                <span class="tts:right tts-slideIn tts-custom" aria-label="Configurar Mesas" style="cursor: pointer;">
                                    <a href="{{ route('mesas.setting',$datos->id) }}" class="badge-with-padding badge badge-warning">
                                        <i class="fa-solid fa-gear fa-fw"></i>
                                    </a>
                                </span>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-12">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$sucursales->count()}}</strong> registros de
                    <strong>{{$sucursales->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $sucursales->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
