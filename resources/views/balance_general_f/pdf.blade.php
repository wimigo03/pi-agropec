<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>REPORTE::PI-RESTO</title>
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
                        BALANCE GENERAL
                        <br>
                        AL {{ \Carbon\Carbon::parse($fecha_f)->format('d/m/Y') }}
                    </b>
                </td>
                <td width="25%" class="font-verdana-9 align-center align-superior">
                    &nbsp;
                </td>
            </tr>
        </table>
        <table class="font-verdana-9">
            <thead class="linea-inferior">
                <tr>
                    <th>CODIGO</th>
                    <th>CUENTA</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
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
                    <tr class="sub-linea-inferior">
                        <td>{{ $ing->codigo }}</td>
                        <td>{{ $ing->nombre }}</td>
                        @for ($i = 0; $i < $nroColumna; $i++)
                            <td></td>
                        @endfor
                        <td class="align-right">
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
                    <tr class="sub-linea-inferior">
                        <td>{{ $costo->codigo }}</td>
                        <td>{{ $costo->nombre }}</td>
                        @for ($i = 0; $i < $nroColumna; $i++)
                            <td></td>
                        @endfor
                        <td class="align-right">
                            {{ number_format($totales[$costo->id],2,'.',',') }}
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
                    <tr class="sub-linea-inferior">
                        <td>{{ $gasto->codigo }}</td>
                        <td>{{ $gasto->nombre }}</td>
                        @for ($i = 0; $i < $nroColumna; $i++)
                            <td></td>
                        @endfor
                        <td class="align-right">
                            {{ number_format($totales[$gasto->id],2,'.',',') }}
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
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><b>TOTAL</b></td>
                <td class="align-right">{{ number_format($total,2,'.',',') }}</td>
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
