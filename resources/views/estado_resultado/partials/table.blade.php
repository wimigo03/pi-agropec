@if ($show == '1')
    <div class="form-group row">
        <div class="col-md-12 px-1">
            <table id="table-precios" class="table display responsive table-striped hover-orange">
                <thead>
                    <tr class="font-roboto-11">
                        <td class="text-left p-1"><b>CODIGO</b></td>
                        <td class="text-left p-1"><b>CUENTA</b></td>
                        <td class="text-center p-1">&nbsp;</td>
                        <td class="text-center p-1">&nbsp;</td>
                        <td class="text-center p-1">&nbsp;</td>
                        <td class="text-center p-1">&nbsp;</td>
                        <td class="text-center p-1">&nbsp;</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingresos as $ing)
                        @php
                            $nroPuntos = 1;
                            for ($i=0; $i < strlen($ing->codigo); $i++) {
                                if($ing->codigo[$i] == '.'){
                                    $nroPuntos++;
                                }
                            }
                            $nroColumna = $nroMaxColumna - $nroPuntos;
                        @endphp
                        @if ($totales[$ing->id] != 0)
                            <tr class="font-roboto-11">
                                <td class="text-justify p-1">
                                    <strong>
                                        {{ $ing->codigo }}
                                    </strong>
                                </td>
                                <td class="text-justify p-1">
                                    <strong>
                                        {{ $ing->nombre  }}
                                    </strong>
                                </td>
                                @for ($i = 0; $i < $nroColumna; $i++)
                                    <td></td>
                                @endfor
                                <td class="text-right p-1">
                                    {{ number_format($totales[$ing->id],2,'.',',') }}
                                </td>
                                @php
                                    $nroColumna = $nroMaxColumna - $nroColumna -1;
                                @endphp
                                @for ($i = 0; $i < $nroColumna; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($costos as $costo)
                        @php
                            $nroPuntos = 1;
                            for ($i=0; $i < strlen($costo->codigo); $i++) {
                                if($costo->codigo[$i] == '.'){
                                    $nroPuntos++;
                                }
                            }
                            $nroColumna = $nroMaxColumna - $nroPuntos;
                        @endphp
                        @if ($totales[$costo->id] != 0)
                            <tr class="font-roboto-11">
                                <td class="text-justify p-1">
                                    <strong>
                                        {{ $costo->codigo }}
                                    </strong>
                                </td>
                                <td class="text-justify p-1">
                                    <strong>
                                        {{ $costo->nombre  }}
                                    </strong>
                                </td>
                                @for ($i = 0; $i < $nroColumna; $i++)
                                    <td></td>
                                @endfor
                                <td class="text-right p-1">
                                    {{number_format($totales[$costo->id],2,'.',',') }}
                                </td>
                                @php
                                    $nroColumna = $nroMaxColumna - $nroColumna - 1;
                                @endphp
                                @for ($i = 0; $i < $nroColumna; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($gastos as $gasto)
                        @php
                            $nroPuntos = 1;
                            for ($i=0; $i < strlen($gasto->codigo); $i++) {
                                if($gasto->codigo[$i] == '.'){
                                    $nroPuntos++;
                                }
                            }
                            $nroColumna = $nroMaxColumna - $nroPuntos;
                        @endphp
                        @if ($totales[$gasto->id] != 0)
                            <tr class="font-roboto-11">
                                <td class="text-justify p-1">
                                    <strong>
                                        {{ $gasto->codigo }}
                                    </strong>
                                </td>
                                <td class="text-justify p-1">
                                    <strong>
                                        {{ $gasto->nombre  }}
                                    </strong>
                                </td>
                                @for ($i = 0; $i < $nroColumna; $i++)
                                    <td></td>
                                @endfor
                                <td class="text-right p-1">
                                    {{number_format($totales[$gasto->id],2,'.',',') }}
                                </td>
                                @php
                                    $nroColumna = $nroMaxColumna - $nroColumna - 1;
                                @endphp
                                @for ($i = 0; $i < $nroColumna; $i++)
                                    <td></td>
                                @endfor
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="font-roboto-11">
                    <td class="text-right p-1" colspan="6" ><strong>TOTAL</strong></td>
                    <td class="text-right p-1"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                </tfoot>
            </table>
        </div>
    </div>
@endif
