<div class="form-group row">
    <div class="col-md-12">
        <table class="table display {{--table-bordered--}} responsive table-striped">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>NOMBRE</b></td>
                    <td class="text-left p-1"><b>CODIGO</b></td>
                    <td class="text-center p-1"><b>TIPO</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    {{--<td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>--}}
                </tr>
            </thead>
            <tbody>
                @foreach ($unidades as $datos)
                    <tr class="font-roboto-12">
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->codigo }}</td>
                        <td class="text-center p-1">{{ $datos->tipo_categoria }}</td>
                        <td class="text-center p-1">
                            <span class="badge-with-padding @if($datos->status == "HABILITADO") badge badge-success @else badge badge-danger @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                        {{--<td class="text-center p-1">
                            <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                <a href="{{ route('distritos.editar',$datos->id) }}" class="btn btn-xs btn-warning">
                                    <i class="fa-solid fa-lg fa-pen-to-square"></i>
                                </a>
                            </span>
                            @if (App\Models\Canasta\Distrito::ESTADOS[$datos->estado] == 'HABILITADO')
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Dehabilitar" style="cursor: pointer;">
                                    <a href="{{ route('distritos.deshabilitar',$datos->id) }}" class="btn btn-xs btn-danger">
                                        <i class="fa-regular fa-lg fa-circle-down"></i>
                                    </a>
                                </span>
                            @else
                                <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                    <a href="{{ route('distritos.habilitar',$datos->id) }}" class="btn btn-xs btn-success">
                                        <i class="fa-regular fa-lg fa-circle-up"></i>
                                    </a>
                                </span>
                            @endif
                        </td>--}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end font-roboto-12">
            {!! $unidades->links() !!}
        </div>
    </div>
</div>