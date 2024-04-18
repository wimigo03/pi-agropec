<div class="form-group row">
    <div class="col-md-12">
        <table id="dataTable" class="table display responsive table-striped">
            <thead>
                <tr class="font-roboto-12">
                    <td class="text-left p-1"><b>ID</b></td>
                    <td class="text-left p-1"><b>SUCURSAL</b></td>
                    <td class="text-left p-1"><b>ZONA</b></td>
                    <td class="text-left p-1"><b>NUMERO</b></td>
                    <td class="text-left p-1"><b>CANT. SILLAS</b></td>
                    <td class="text-left p-1"><b>DETALLE</b></td>
                    <td class="text-center p-1"><b>ESTADO</b></td>
                    @canany(['mesas.editar'])
                        <td class="text-center p-1"><b><i class="fas fa-bars"></i></b></td>
                    @endcanany
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>