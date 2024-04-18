<div class="form-group row abs-center">
    <div class="col-md-9 px-0 pr-1">
        <table id="table-precios" class="table display responsive table-striped hover-orange">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-center p-1"><b>ID</b></td>
                    <td class="text-center p-1"><b>CREACION</b></td>
                    <td class="text-center p-1"><b>DIA</b></td>
                    <td class="text-center p-1"><b>MES</b></td>
                    <td class="text-center p-1"><b>USUARIO</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($anteriores as $datos)
                    <tr class="font-roboto-11">
                        <td class="text-center p-1">{{ $datos->id }}</td>
                        <td class="text-center p-1">{{ \Carbon\Carbon::parse($datos->fecha)->format('d/m/Y') }}</td>
                        <td class="text-center p-1">{{ $datos->dia }}</td>
                        <td class="text-center p-1">{{ $datos->mes_gestion }}</td>
                        <td class="text-center p-1">{{ $datos->user->username }}</td>
                        <td class="text-center p-1" width="150px">
                            <span class="badge-with-padding 
                                @if($datos->status == "HABILITADO") 
                                    badge badge-success 
                                @else 
                                    badge badge-secondary 
                                @endif">
                                {{ $datos->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>