<div class="form-group row">
    <div class="col-md-12">
        <table class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-center p-1"><b>NOMBRE</b></td>
                    <td class="text-center p-1"><b>ORIGEN</b></td>
                    <td class="text-center p-1"><b>INDENTIFICADOR</b></td>
                    <td class="text-center p-1"><b>TIPO</b></td>
                    <td class="text-center p-1"><b>EST.</b></td>
                    @canany(['plan.cuentas.auxiliar.habilitar','plan.cuentas.auxiliar.editar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($plan_cuentas_auxiliares as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-left p-1">{{ $datos->nombre }}</td>
                        <td class="text-left p-1">{{ $datos->class_name }}</td>
                        <td class="text-left p-1">{{ $datos->class_name_id }}</td>
                        <td class="text-center p-1">{{ $datos->tipus }}</td>
                        <td class="text-center p-1">
                            <span class="badge-with-padding @if($datos->status == "HABILITADO") badge badge-success @else badge badge-danger @endif">
                                &nbsp;{{ $datos->status }}&nbsp;
                            </span>
                        </td>
                        @canany(['plan.cuentas.auxiliar.habilitar','plan.cuentas.auxiliar.editar'])
                            <td class="text-center p-1">
                                @if($datos->status == "HABILITADO")
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Deshabilitar" style="cursor: pointer;">
                                        <a href="{{ route('plan_cuentas.auxiliar.deshabilitar',$datos->id) }}" class="badge-with-padding badge badge-danger">
                                            <i class="fas fa-lg fa-arrow-alt-circle-down"></i>
                                        </a>
                                    </span>
                                @else
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Habilitar" style="cursor: pointer;">
                                        <a href="{{ route('plan_cuentas.auxiliar.habilitar',$datos->id) }}" class="badge-with-padding badge badge-success">
                                            <i class="fas fa-lg fa-arrow-alt-circle-up"></i>
                                        </a>
                                    </span>
                                @endif
                                @can('plan.cuentas.auxiliar.editar')
                                    <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                        <a href="{{ route('plan_cuentas.auxiliar.editar',$datos->id) }}" class="badge-with-padding badge badge-warning">
                                            <i class="fas fa-lg fa-edit text-white"></i>
                                        </a>
                                    </span>
                                @endcan
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end font-roboto-12">
            {!! $plan_cuentas_auxiliares->links() !!}
        </div>
    </div>
</div>