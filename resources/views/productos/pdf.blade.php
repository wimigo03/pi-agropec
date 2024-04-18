<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DETALLE DEL PRODUCTO</title>
	<style>
		body {
			font-family: verdana,arial,helvetica;
			font-size: 10px;
		}
		.table-titulo {
			width: 100%;
		}
		.table-data {
			width: 100%;
			border-collapse: collapse;
			border-bottom: 1px solid #000000;
			border-top: 1px solid #000000;
		}
		tr,td {
			padding: 5px;
		}
	</style>
</head>
<body>
	<table class="table-titulo">
		<tr>
			<td align="center">
				<h2>DETALLE DEL PRODUCTO</h2>
			</td>
		</tr>
	</table>
	<table width="100%">
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td>
                            <b>EMPRESA.- </b>
                        </td>
                        <td>
                            {{ $producto->empresa->nombre_comercial }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>CATEGORIA MASTER.- </b>
                        </td>
                        <td>
                            {{ $producto->categoria_master }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>CATEGORIA.- </b>
                        </td>
                        <td>
                            {{$producto->categoria != null ? $producto->categoria->nombre : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>PLAN DE CUENTA.- </b>
                        </td>
                        <td>
                            {{ $producto->plan_cuenta != null ? $producto->plan_cuenta->nombre : '#' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>NOMBRE.- </b>
                        </td>
                        <td>
                            {{ $producto->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>NOMBRE EN LA FACTURA.- </b>
                        </td>
                        <td>
                            {{ $producto->nombre_factura }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>UNIDAD DE MEDIDA.- </b>
                        </td>
                        <td>
                            {{ $producto->unidad->nombre }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>CODIGO.- </b>
                        </td>
                        <td>
                            {{ $producto->codigo }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>ESTADO.- </b>
                        </td>
                        <td>
                            {{ $producto->status }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>DETALLE.- </b>
                        </td>
                        <td>
                            {{ $producto->detalle }}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td align="center">
                            @if ($producto->foto_1 != null)
                                <img src={{ public_path($producto->foto_1) }} alt="{{ $producto->foto_1 }}" style="width: 200px; height:auto;"/>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            @if ($producto->foto_2 != null)
                                <img src={{ public_path($producto->foto_2) }} alt="{{ $producto->foto_2 }}" style="width: 200px; height:auto;"/>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            @if ($producto->foto_3 != null)
                                <img src={{ public_path($producto->foto_3) }} alt="{{ $producto->foto_3 }}" style="width: 200px; height:auto;"/>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
	</table>
</body>
</html>