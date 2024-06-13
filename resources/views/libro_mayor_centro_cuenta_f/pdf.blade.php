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
                        LIBRO MAYOR POR CENTRO Y CUENTA
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
                <td><b>Centro:</b>&nbsp;</td>
                <td colspan="2">{{ $sub_centro->centro->nombre }}</td>
                <td><b>Subcentro:</b>&nbsp;</td>
                <td colspan="3">{{ $sub_centro->nombre }}</td>
            </tr>
            <tr>
                <td><b>Cuenta contable:</b>&nbsp;</td>
                <td colspan="6">{{ $plan_cuenta->codigo . ' ' . $plan_cuenta->nombre }}</td>
            </tr>
            <tr>
                <td><b>Desde:</b>&nbsp;</td>
                <td colspan="2">{{ \Carbon\Carbon::parse($fecha_i)->format('d-m-Y') }}</td>
                <td><b>Total Debe:</b>&nbsp;</td>
                <td colspan="3">BS. {{ number_format($total_debe,2,'.',',') }}</td>
            </tr>
            <tr>
                <td><b>Hasta:</b>&nbsp;</td>
                <td colspan="2">{{ \Carbon\Carbon::parse($fecha_f)->format('d-m-Y') }}</td>
                <td><b>Total Haber:</b>&nbsp;</td>
                <td colspan="3">BS. {{ number_format($total_haber,2,'.',',') }}</td>
            </tr>
        </table>
        <br>
        <table class="font-verdana-9">
            <thead class="linea-inferior">
                <tr>
                    <th width="10%">FECHA</th>
                    <th>COMPROBANTE</th>
                    <th>AUXILIAR</th>
                    <th>GLOSA</th>
                    <th>DEBE</th>
                    <th>HABER</th>
                </tr>
            </thead>
            @foreach ($comprobantes as $datos)
                <tr class="sub-linea-inferior">
                    <td class="align-center">{{ $datos->fecha }}</td>
                    <td class="align-center">{{ $datos->nro_comprobante }}&nbsp;<b>{{ $datos->estado_abreviado}}</b></td>
                    <td>{{ $datos->auxiliar }}</td>
                    <td>{{ $datos->glosa }}</td>
                    <td class="align-right">{{ number_format($datos->debe,2,'.',',') }}</td>
                    <td class="align-right">{{ number_format($datos->haber,2,'.',',') }}</td>
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
