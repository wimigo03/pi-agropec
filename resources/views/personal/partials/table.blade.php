<div class="form-group row">
    <div class="col-md-12">
        <table class="table display responsive table-striped">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>COD. ING.</b></td>
                    <td class="text-left p-1"><b>INGRESO</b></td>
                    <td class="text-left p-1"><b>COD. RET.</b></td>
                    <td class="text-left p-1"><b>CI/RUN</b></td>
                    <td class="text-left p-1"><b>NOMBRES</b></td>
                    <td class="text-center p-1"><b>CARGO</b></td>
                    <td class="text-center p-1"><b>CNTTO.</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['personal.show','personal.modificar','personal.retirar','personal.cargar.contrato'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($personal_laborales as $datos)
                    <tr class="font-roboto-12">
                        @php
                            $contratos = App\Models\PersonalContrato::where('personal_id',$datos->personal_id)->get();
                        @endphp
                        <td class="text-left p-1" style="vertical-align: middle;">{{ $datos->codigo_ingreso }}</td>
                        <td  class="text-left p-1" style="vertical-align: middle;">
                            @foreach ($contratos as $contrato)
                                {{ $contrato->tipo .': ' . \Carbon\Carbon::parse($contrato->fecha_ingreso)->format('d/m/Y') }}<br>
                            @endforeach
                        </td>
                        <td class="text-left p-1">
                            @foreach ($contratos as $contrato)
                                {{ $contrato->tipo .': ' . $contrato->codigo_retiro }}<br>
                            @endforeach
                        </td>
                        <td class="text-left p-1" style="vertical-align: middle;">{{ $datos->personal->ci_run }}</td>
                        <td class="text-left p-1" style="vertical-align: middle;">{{ $datos->personal->full_name }}</td>
                        <td class="text-center p-1" style="vertical-align: middle;">{{ $datos->cargo->nombre }}</td>
                        <td class="text-center p-1" style="vertical-align: middle;">
                            @if ($datos->file_contrato != null)
                                <a href="{{ asset($datos->file_contrato) }}" target="_blank">
                                    <span class="text-danger"><i class="fas fa-lg fa-file-pdf"></i></span>
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center p-1" style="vertical-align: middle;">
                            <span class="badge-with-padding @if($datos->status == "HABILITADO") badge badge-success @else badge badge-danger @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                        @canany(['personal.show','personal.modificar','personal.retirar','personal.cargar.contrato'])
                            <td class="text-center p-1" style="vertical-align: middle;">
                                <select id="{{ $datos->id }}" onchange="redireccionar(this.id);" class="form-control form-control-sm select2-container">
                                    <option value="Seleccionar">Seleccionar</option>
                                    @can('personal.show')
                                        <option value="Ver">Ir a detalle</option>
                                    @endcan
                                    @can('personal.modificar')
                                        <option value="Editar">Modificar</option>
                                    @endcan
                                    @can('personal.retirar')
                                        @if ($datos->contrato_fiscal)
                                            <option value="Retirar Fiscal">Retirar Fiscal</option>
                                        @endif
                                        @if ($datos->contrato_interno)
                                            <option value="Retirar Interno">Retirar Interno</option>
                                        @endif
                                        @if ($datos->contrato_servicio)
                                            <option value="Retirar Servicio">Retirar Servicio</option>
                                        @endif
                                    @endcan
                                    @can('personal.cargar.contrato')
                                        @if (!$datos->file_contrato)
                                            <option value="File Contrato">Subir Contrato</option>
                                        @endif
                                    @endcan
                                </select>
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end font-roboto-12">
            {!! $personal_laborales->links() !!}
        </div>
    </div>
</div>