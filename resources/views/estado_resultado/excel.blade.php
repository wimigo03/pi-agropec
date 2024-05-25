<table>
    <thead>
        <tr>
            <td><b>CODIGO</b></td>
            <td><b>CUENTA</b></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
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
                <tr>
                    <td>
                        <strong>
                            {{ $ing->codigo }}
                        </strong>
                    </td>
                    <td>
                        <strong>
                            {{ $ing->nombre  }}
                        </strong>
                    </td>
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td></td>
                    @endfor
                    <td>
                        {{ $totales[$ing->id] }}
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
                <tr>
                    <td>
                        <strong>
                            {{ $costo->codigo }}
                        </strong>
                    </td>
                    <td>
                        <strong>
                            {{ $costo->nombre  }}
                        </strong>
                    </td>
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td></td>
                    @endfor
                    <td>
                        {{ $totales[$costo->id] }}
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
                <tr>
                    <td>
                        <strong>
                            {{ $gasto->codigo }}
                        </strong>
                    </td>
                    <td>
                        <strong>
                            {{ $gasto->nombre  }}
                        </strong>
                    </td>
                    @for ($i = 0; $i < $nroColumna; $i++)
                        <td></td>
                    @endfor
                    <td>
                        {{ $totales[$gasto->id] }}
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
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><b>TOTAL</b></td>
        <td><b>{{ $total }}</b></td>
    </tr>
</table>
