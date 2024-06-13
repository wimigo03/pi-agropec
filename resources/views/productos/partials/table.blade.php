<div class="form-group row">
    <div class="col-md-12 px-1">
        <table class="table display {{--table-bordered--}} responsive table-striped">
            <thead>
                <tr class="font-roboto-11">
                    <td class="text-center p-1"><b>NOMBRE</b></td>
                    <td class="text-center p-1"><b>EN FACTURA</b></td>
                    <td class="text-center p-1"><b>CODIGO</b></td>
                    <td class="text-center p-1"><b>UN.</b></td>
                    <td class="text-center p-1"><b>CATEGORIA</b></td>
                    <td class="text-center p-1"><b>SUB CATEGORIA</b></td>
                    <td class="text-center p-1"><b>TIPO</b></td>
                    <td class="text-center p-1"><b>EST.</b></td>
                    @canany(['productos.modificar','productos.show'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->nombre_factura }}</td>
                        <td class="text-center p-1">{{ $datos->codigo }}</td>
                        <td class="text-center p-1">{{ $datos->unidad->codigo }}</td>
                        <td class="text-center p-1">{{ $datos->categoria_master }}</td>
                        <td class="text-center p-1">{{ $datos->categoria != null ? $datos->categoria->nombre : '-' }}</td>
                        <td class="text-center p-1">{{ $datos->categoria_m->tipo_producto }}</td>
                        <td class="text-center p-1">
                            <span class="tts:left tts-slideIn tts-custom" aria-label="@if($datos->status == "H") HABILITADO @else DESHABILITADO @endif" style="cursor: pointer;">
                                <span class="badge-with-padding @if($datos->status == "H") badge badge-success @else badge badge-danger @endif">
                                    &nbsp;{{ $datos->status }}&nbsp;
                                </span>
                            </span>
                        </td>
                        @canany(['productos.modificar','productos.show'])
                            <td class="text-center p-1">
                                @if($datos->status == "H")
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <a href="{{ route('productos.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger">
                                            <i class="fas fa-lg fa-arrow-alt-circle-down"></i>
                                        </a>
                                    </span>
                                @else
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <a href="{{ route('productos.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
                                            <i class="fas fa-lg fa-arrow-alt-circle-up"></i>
                                        </a>
                                    </span>
                                @endif
                                @can('productos.modificar')
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                        <a href="{{ route('productos.editar',$datos->id) }}" class="badge-with-padding badge badge-warning">
                                            <i class="fas fa-lg fa-edit text-white"></i>
                                        </a>
                                    </span>
                                @endcan
                                @can('productos.show')
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a detalle" style="cursor: pointer;">
                                        <a href="{{ route('productos.show',$datos->id) }}" class="badge-with-padding badge badge-info">
                                            <i class="fa fa-lg fa-list"></i>
                                        </a>
                                    </span>
                                @endcan
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-11">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$productos->count()}}</strong> registros de
                    <strong>{{$productos->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $productos->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
