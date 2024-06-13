<div class="form-group row abs-center">
    <div class="col-md-8">
        <table id="table-precios" class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>EMPRESA</b></td>
                    <td class="text-left p-1"><b>TIPO</b></td>
                    <td class="text-left p-1"><b>DETALLE</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['configuracion.show'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($configuraciones as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->empresa->nombre_comercial }}</td>
                        <td class="text-left p-1">{{ $datos->tipos }}</td>
                        <td class="text-left p-1">{{ $datos->detalle }}</td>
                        <td class="text-center p-1" width="150px">
                            <span class="badge-with-padding
                                @if($datos->status == "PENDIENTE")
                                    badge badge-secondary
                                @else
                                    badge badge-success
                                @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                        @canany(['configuracion.show'])
                            <td class="text-center p-1">
                                @can('configuracion.show')
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a configuracion" style="cursor: pointer;">
                                        <a href="{{ route('configuracion.show',$datos->id) }}" class="badge-with-padding badge badge-info text-white">
                                            <i class="fa-solid fa-bars-staggered fa-fw"></i>
                                        </a>
                                    </span>
                                @endcan
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-12">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$configuraciones->count()}}</strong> registros de
                    <strong>{{$configuraciones->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $configuraciones->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
