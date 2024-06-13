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
                        _*LIBRO SUMAS Y SALDOS*_
                    </b>
                </td>
                <td width="25%" class="font-verdana-9 align-center align-superior">
                    &nbsp;
                </td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9" style="width: 50%" align="center">
            <tr>
                <td class="align-center">
                    <b>Desde:</b>&nbsp;
                    {{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}
                </td>
                <td class="align-center">
                    <b>Hasta:</b>&nbsp;
                    {{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}
                </td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <thead class="linea-inferior">
                <tr>
                    <th>CODIGO</th>
                    <th>CUENTA CONTABLE</th>
                    <th>DEBE</th>
                    <th>HABER</th>
                    <th>DIFERENCIA</th>
                </tr>
            </thead>
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
                <tr class="sub-linea-inferior">
                    @if (in_array($datos->plan_cuenta_id,$plan_cuentas_ids))
                        <td>{{ $plan_cuentas_codigo[$datos->plan_cuenta_id] }}</td>
                        <td>{{ $plan_cuentas[$datos->plan_cuenta_id] }}</td>
                    @else
                        <td>{{ "S/N : "}}</td>
                        <td>{{ "S/N : " . $datos->plan_cuenta_id }}</td>
                    @endif
                    <td class="align-right">{{ number_format($datos->total_debe_mayor,2,'.',',') }}</td>
                    <td class="align-right">{{ number_format($datos->total_haber_mayor,2,'.',',') }}</td>
                    <td class="align-right">{{ number_format($diferencia,2,'.',',') }}</td>
                </tr>
            @endforeach
                <tr class="sub-linea-inferior">
                    <td class="align-center" colspan="2">
                        <b>TOTAL</b>
                    </td>
                    <td class="align-right">
                        <b>{{ number_format($total_debe,2,'.',',') }}</b>
                    </td>
                    <td class="align-right">
                        <b>{{ number_format($total_haber,2,'.',',') }}</b>
                    </td>
                    <td class="align-right">
                        <b>{{ number_format($total_diferencia,2,'.',',') }}</b>
                    </td>
                </tr>
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
