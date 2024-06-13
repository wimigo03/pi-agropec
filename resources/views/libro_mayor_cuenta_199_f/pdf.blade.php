<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>REPORTE::PI</title>
    <style>
        <?php echo file_get_contents(public_path('css/styles/font-verdana-pdf.css')); ?>
    </style>
    <body>
        <table>
            <tr>
                <td width="25%" class="font-verdana-6 align-center">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($empresa->url_cover))) }}" class="logo-callejx" alt="#"/>
                    <br>
                    {{ $empresa->nombre_comercial }}
                    <br>
                    {{ $empresa->direccion }} - NIT {{ $empresa->nit }}
                </td>
                <td class="font-verdana-15 align-center align-middle align-inferior">
                    <b>
                        LIBRO MAYOR POR CUENTA 1 A 99
                    </b>
                </td>
                <td width="25%" class="font-verdana-9 align-center align-superior">
                    &nbsp;
                </td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <tr>
                <td><b>Desde:</b>&nbsp;</td>
                <td>{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}</td>
                <td><b>Desde cuenta:</b>&nbsp;</td>
                <td>{{ $plan_cuenta1->codigo . ' ' . $plan_cuenta1->nombre }}</td>
                <td><b>Total Debe:</b>&nbsp;</td>
                <td>BS. {{ number_format($total_debe,2,'.',',') }}</td>
            </tr>
            <tr>
                <td><b>Hasta:</b>&nbsp;</td>
                <td>{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}</td>
                <td><b>Hasta cuenta:</b>&nbsp;</td>
                <td>{{ $plan_cuenta2->codigo . ' ' . $plan_cuenta2->nombre }}</td>
                <td><b>Total Haber:</b>&nbsp;</td>
                <td>BS. {{ number_format($total_haber,2,'.',',') }}</td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <thead class="linea-inferior">
                <tr>
                    <th width="5%">FECHA</th>
                    <th>COMPROBANTE</th>
                    <th>CUENTA CONTABLE</th>
                    <th>AUXILIAR</th>
                    <th>CENTRO</th>
                    <th>SUBCENTRO</th>
                    {{--<th>CHEQUE</th>--}}
                    <th>GLOSA</th>
                    <th>DEBE</th>
                    <th>HABER</th>
                    <th>SALDO</th>
                </tr>
            </thead>
            @php
                $cuenta_actual = -1;
            @endphp
            @foreach ($comprobantes as $datos)
                @php
                    if($datos->plan_cuenta_id != $cuenta_actual){
                        $saldo_actual = isset($saldos_cuentas[$datos->plan_cuenta_id]) ? $saldos_cuentas[$datos->plan_cuenta_id] : 0;
                        $cuenta_actual = $datos->plan_cuenta_id;
                    }
                    $saldo_actual += $datos->debe - $datos->haber;
                @endphp
                <tr class="sub-linea-inferior">
                    <td class="align-center">{{ $datos->fecha }}</td>
                    <td class="align-center">{{ $datos->nro_comprobante }}&nbsp;<b>{{ $datos->estado_abreviado}}</b></td>
                    <td class="align-center">{{ $datos->codigo_contable . ' ' . $datos->cuenta_contable }}</td>
                    <td class="align-center">{{ $datos->auxiliar }}</td>
                    <td class="align-center">{{ $datos->ab_centro }}</td>
                    <td class="align-center">{{ $datos->ab_subcentro }}</td>
                    {{--<td class="align-center">{{ $datos->nro_cheque }}</td>--}}
                    <td>{{ $datos->glosa }}</td>
                    <td class="align-right">{{ number_format($datos->debe,2,'.',',') }}</td>
                    <td class="align-right">{{ number_format($datos->haber,2,'.',',') }}</td>
                    <td class="align-right">{{ number_format($saldo_actual,2,'.',',') }}</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>
<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("verdana");
            $pdf->text(40, 765, "{{ date('d/m/Y H:i') }} - {{ Auth()->user()->username }}", $font, 6);
            $pdf->text(530, 765, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 6);
        ');
    }
</script>
