<div class="form-group row">
    <div class="col-md-12">
        <table class="table display responsive table-striped">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>ID</b></td>
                    <td class="text-left p-1"><b>CODIGO</b></td>
                    <td class="text-left p-1"><b>NOMBRE</b></td>
                    <td class="text-right p-1"><b>MESAS</b></td>
                    <td class="text-left p-1"><b>DETALLE</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['zonas.editar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($zonas as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-left p-1">{{ $datos->codigo }}</td>
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-right p-1">{{ $datos->mesas_disponibles }}</td>
                        <td class="text-left p-1">{{ $datos->detalle }}</td>
                        <td class="text-center p-1">
                            <span class="badge-with-padding @if($datos->status == "HABILITADO") badge badge-success @else badge badge-danger @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                        <td class="text-center p-1">
                            @can('zonas.editar')
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                    <a href="{{ route('zonas.editar',$datos->id) }}" class="badge-with-padding badge badge-warning text-white">
                                        <i class="fas fa-edit fa-fw"></i>
                                    </a>
                                </span>
                            @endcan
                            @can('zonas.habilitar')
                                @if($datos->status == "HABILITADO")
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <a href="{{ route('zonas.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger">
                                            <i class="fas fa-lg fa-arrow-alt-circle-down"></i>
                                        </a>
                                    </span>
                                @else
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <a href="{{ route('zonas.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
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
        <div class="d-flex justify-content-end font-roboto-12">
            {!! $zonas->links() !!}
        </div>
    </div>
</div>