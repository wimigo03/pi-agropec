<div class="counter d-flex justify-content-center">
    <select name="menu_dashboard" id="menu_dashboard" class="form-control form-control-sm bg-secondary" onchange="goToDashboardType(this);">
            <option value="COMERCIAL" @if(Session::get('menu') == 'COMERCIAL') selected @endif>COMERCIAL</option>
        @canany(['plan.cuentas.index','tipo.cambio.index','comprobante.index'])
            <option value="CONTABLE"  @if(Session::get('menu') == 'CONTABLE') selected @endif>CONTABLE</option>
        @endcanany
    </select>
</div>