@if (!isset($comprobantes))
    <div class="form-group row font-roboto-12">
        <div class="col-md-12 px-1">
            <table class="table table-striped table-bordered hover-orange" style="width:100%;" id="dataTable">
                <thead>
                    <tr class="font-roboto-11">
                        <th>EMP.</th>
                        <th>TIPO</th>
                        <th>FECHA</th>
                        <th>COMPROBANTE</th>
                        <th>CREADO</th>
                        <th>CONCEPTO</th>
                        <th>MONTO</th>
                        <th>ESTADO</th>
                        <th>CREADO POR</th>
                        @can('comprobantef.index')
                            <th>COPIA</th>
                        @endcan
                        @canany(['comprobante.editar'])
                            <th>
                                <i class="fas fa-bars fa-fw"></i>
                            </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        @can('comprobantef.index')
                            <th></th>
                        @endcan
                        @canany(['comprobante.editar'])
                            <th></th>
                        @endcanany
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endif
@if (isset($comprobantes))
    <div class="form-group row">
        <div class="col-md-12 px-1">
            <table class="table table-striped table-bordered hover-orange" style="width:100%;">
                <thead>
                    <tr class="font-roboto-11">
                        <td class="text-center p-1"><b>EMP.</b></td>
                        <td class="text-center p-1"><b>TIPO</b></td>
                        <td class="text-center p-1"><b>FECHA</b></td>
                        <td class="text-center p-1"><b>COMPROBANTE</b></td>
                        <td class="text-center p-1"><b>CREADO</b></td>
                        <td class="text-justify p-1"><b>CONCEPTO</b></td>
                        <td class="text-center p-1"><b>MONTO</b></td>
                        <td class="text-center p-1"><b>ESTADO</b></td>
                        <td class="text-center p-1"><b>CREADO POR</b></td>
                        @can('comprobantef.index')
                            <td class="text-center p-1"><b>COPIA</b></td>
                        @endcan
                        @canany(['comprobante.editar'])
                            <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comprobantes as $datos)
                        <tr class="font-roboto-11">
                            <td class="text-center p-1">{{ $datos->empresa->alias }}</td>
                            <td class="text-center p-1">{{ $datos->tipos }}</td>
                            <td class="text-center p-1">{{ \Carbon\Carbon::parse($datos->fecha)->format('d-m-y') }}</td>
                            <td class="text-center p-1">{{ $datos->nro_comprobante }}</td>
                            <td class="text-center p-1">{{ \Carbon\Carbon::parse($datos->creado)->format('d-m-y') }}</td>
                            <td class="text-justify p-1">{{ $datos->concepto }}</td>
                            <td class="text-justify p-1">{{ number_format($datos->monto,2,'.',',') }}</td>
                            <td class="text-center p-1">
                                <span class="badge-with-padding
                                    @if($datos->status == "PENDIENTE")
                                        badge badge-secondary
                                    @else
                                        @if($datos->status == "APROBADO")
                                            badge badge-success
                                        @else
                                            badge badge-danger
                                        @endif
                                    @endif">
                                    {{ $datos->status }}
                                </span>
                            </td>
                            <td class="text-center p-1">{{ strtoupper($datos->user->username) }}</td>
                            @can('comprobantef.index')
                                <td class="text-center p-1">
                                    @if ($datos->copia == '1')
                                        <i class="fa-solid fa-check fa-fw"></i>
                                    @else
                                        <i class="fa-solid fa-xmark fa-fw"></i>
                                    @endif
                                </td>
                            @endcan
                            @canany(['comprobante.editar','comprobante.pdf','comprobante.show'])
                                <td class="text-center p-1">
                                    <div class="d-flex justify-content-center">
                                        @can('comprobante.pdf')
                                            <span class="tts:left tts-slideIn tts-custom" aria-label="Pdf" style="cursor: pointer;">
                                                <a href="{{ route('comprobante.pdf',$datos->id) }}" class="badge-with-padding badge badge-danger" target="_blank">
                                                    <i class="fa-solid fa-file-pdf fa-fw"></i>
                                                </a>
                                            </span>
                                            &nbsp;
                                        @endcan
                                        @can('comprobante.show')
                                            <span class="tts:left tts-slideIn tts-custom" aria-label="Ir a detalle" style="cursor: pointer;">
                                                <a href="{{ route('comprobante.show',$datos->id) }}" class="badge-with-padding badge badge-primary">
                                                    <i class="fa-solid fa-list-check fa-fw"></i>
                                                </a>
                                            </span>
                                            &nbsp;
                                        @endcan
                                        @if ($datos->estado == '1')
                                            @can('comprobante.editar')
                                                <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar" style="cursor: pointer;">
                                                    <a href="{{ route('comprobante.editar',$datos->id) }}" class="badge-with-padding badge badge-warning">
                                                        <i class="fas fa-edit fa-fw"></i>
                                                    </a>
                                                </span>
                                            @endcan
                                            &nbsp;
                                        @else
                                            <span class="tts:left tts-slideIn tts-custom" aria-label="Modificar No Permitido" style="cursor: pointer;">
                                                <a href="#" class="badge-with-padding badge badge-secondary">
                                                    <i class="fas fa-edit fa-fw"></i>
                                                </a>
                                            </span>
                                            &nbsp;
                                        @endif
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row font-roboto-11">
                <div class="col-md-6">
                    <p class="text- muted">Mostrando
                        <strong>{{$comprobantes->count()}}</strong> registros de
                        <strong>{{$comprobantes->total()}}</strong> totales
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        {{ $comprobantes->appends(Request::all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
