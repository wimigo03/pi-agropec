<div class="form-group row">
    <div class="col-md-12 abs-center">
        <table class="table display responsive table-striped" style="width:50%;">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>ID</b></td>
                    <td class="text-left p-1"><b>TIPO</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['tipo.precios.habilitar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($tipos_precio as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->id }}</td>
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-center p-1">
                            <span class="badge-with-padding @if($datos->status == "HABILITADO") badge badge-success @else badge badge-danger @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                        <td class="text-center p-1">
                            @if ($datos->id != 1)
                                @can('tipo.precios.habilitar')
                                    @if($datos->status == "HABILITADO")
                                        <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                            <a href="{{ route('tipo.precios.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger">
                                                <i class="fas fa-lg fa-arrow-alt-circle-down"></i>
                                            </a>
                                        </span>
                                    @else
                                        <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                            <a href="{{ route('tipo.precios.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
                                                <i class="fas fa-lg fa-arrow-alt-circle-up"></i>
                                            </a>
                                        </span>
                                    @endif
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end font-roboto-12">
            {!! $tipos_precio->links() !!}
        </div>
    </div>
</div>