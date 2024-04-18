<br>
<div class="form-group row">
    <div class="col-md-12 table-responsive">
        <table id="#" class="table display table-bordered responsive" style="width:100%;">
            <thead>
                <tr class="font-verdana">
                    <td class="text-left p-1"><b>FAMILIAR</b></td>
                    <td class="text-left p-1"><b>TIPO</b></td>
                    <td class="text-left p-1"><b>EDAD</b></td>
                    <td class="text-left p-1"><b>TELEFONO</b></td>
                    <td class="text-left p-1"><b>OCUPACION</b></td>
                    <td class="text-left p-1"><b>NIVEL ESTUDIO</b></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($familiares as $datos)
                    <tr class="font-verdana">
                        <td class="text-left p-1" style="vertical-align: middle">{{ $datos->nombre }}</td>
                        <td class="text-left p-1" style="vertical-align: middle">{{ $datos->tipo }}</td>
                        <td class="text-left p-1" style="vertical-align: middle">{{ $datos->edad }}</td>
                        <td class="text-left p-1" style="vertical-align: middle">{{ $datos->telefono }}</td>
                        <td class="text-left p-1" style="vertical-align: middle">{{ $datos->ocupacion }}</td>
                        <td class="text-left p-1" style="vertical-align: middle">{{ $datos->nivel_estudio }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>