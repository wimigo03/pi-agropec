{{--<tr class="font-roboto-12 table-success">
    <td class="text-center p-1" colspan="8">
        @can('sub.centros.create')
            <span class="tts:left tts-slideIn tts-custom float-right" aria-label="Agregar Sub Centro" style="cursor: pointer;">
                <a href="{{ route('sub.centros.create',$centro->id) }}" class="badge-with-padding badge badge-success">
                    <i class="fas fa-plus fa-fw"></i>
                </a>
            </span>
        @endcan
    </td>
</tr>--}}
<div class="form-group row abs-center">
    <div class="col-md-12 px-1">
        <table class="table display responsive table-bordered table-striped hover-orange">
            <thead>
                <tr class="font-roboto-11">
                    <th class="text-center p-1"><b>EMPRESA</b></th>
                    <th class="text-center p-1"><b>CENTRO</b></th>
                    <th class="text-center p-1"><b>SUBCENTRO</b></th>
                    <th class="text-center p-1"><b>CODIGO</b></th>
                    <th class="text-center p-1"><b>TIPO</b></th>
                    <th class="text-center p-1"><b>CREADO</b></th>
                    <th class="text-center p-1"><b>ESTADO</b></th>
                    <th class="text-center p-1"><b><i class="fas fa-bars"></i></b></th>
                </tr>
            </thead>
            <tbody>
                @foreach($sub_centros as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->empresa->nombre_comercial }}</td>
                        <td class="text-left p-1">{{ $datos->centro->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->abreviatura }}</td>
                        <td class="text-center p-1">{{ $datos->tipos }}</td>
                        <td class="text-center p-1">{{ \Carbon\Carbon::parse($datos->create)->format('d-m-y') }}</td>
                        <td class="text-center p-1">
                            <span class="{{ $datos->colorStatus }}">
                                {{ $datos->status }}
                            </span>
                        </td>
                        <td class="text-center p-1">
                            @if ($datos->estado == '1')
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                    <a href="{{ route('sub.centros.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger">
                                        <i class="fas fa-arrow-alt-circle-down fa-fw"></i>
                                    </a>
                                </span>
                            @else
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                    <a href="{{ route('sub.centros.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
                                        <i class="fas fa-arrow-alt-circle-up fa-fw"></i>
                                    </a>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row font-roboto-11">
            <div class="col-md-6">
                <p class="text- muted">Mostrando
                    <strong>{{$sub_centros->count()}}</strong> registros de
                    <strong>{{$sub_centros->total()}}</strong> totales
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $sub_centros->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
