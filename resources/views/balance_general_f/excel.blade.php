<table>
    <tr>
        <td colspan="8" align="center">
            <b>BALANCE GENERAL</b>
        </td>
    </tr>
    <tr>
        <td colspan="8" align="center">
            <b>AL {{ $fecha_f }}</b>
        </td>
    </tr>
</table>
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
