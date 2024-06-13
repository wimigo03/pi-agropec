<table>
    <tr>
        <td colspan="5" align="center">
            <b>Empresa:</b>&nbsp;{{ $empresa->nombre_comercial }}
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <b>Desde:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <b>Hasta:</b>&nbsp;{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
        </td>
    </tr>
</table>
<table>
    <tr>
        <td align="center"><b>CODIGO</b></td>
        <td align="center"><b>CUENTA CONTABLE</b></td>
        <td align="center"><b>DEBE</b></td>
        <td align="center"><b>HABER</b></td>
        <td align="center"><b>DIFERENCIA</b></td>
    </tr>
    @php
        $total_debe = 0;
        $total_haber = 0;
        $total_diferencia = 0;
    @endphp
    @foreach ($comprobantes as $datos)
        @php
            $total_debe += floatVal($datos->total_debe_mayor);
            $total_haber += floatVal($datos->total_haber_mayor);
            $diferencia  = floatVal($datos->total_debe_mayor) - floatVal($datos->total_haber_mayor);
            $total_diferencia += floatVal($diferencia);
        @endphp
        <tr>
            @if (in_array($datos->plan_cuenta_id,$plan_cuentas_ids))
                <td>{{ $plan_cuentas_codigo[$datos->plan_cuenta_id] }}</td>
                <td>{{ $plan_cuentas[$datos->plan_cuenta_id] }}</td>
            @else
                <td>{{ "S/N : "}}</td>
                <td>{{ "S/N : " . $datos->plan_cuenta_id }}</td>
            @endif
            <td align="right">
                {{ $datos->total_debe_mayor }}
            </td>
            <td align="right">
                {{ $datos->total_haber_mayor }}
            </td>
            <td align="right">
                {{ $diferencia }}
            </td>
        </tr>
    @endforeach
        <tr class="font-roboto-11">
            <td align="center" colspan="2">
                <b>TOTAL</b>
            </td>
            <td align="right">
                <b>{{ $total_debe }}</b>
            </td>
            <td align="right">
                <b>{{ $total_haber }}</b>
            </td>
            <td align="right">
                <b>{{ $total_diferencia }}</b>
            </td>
        </tr>
</table>
