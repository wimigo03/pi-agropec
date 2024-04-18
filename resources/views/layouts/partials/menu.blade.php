<div class="nav-menu font-roboto-15">
    <ul>
        @canany(['clientes.index','users.index','roles.index','permissions.index'])
            <li>
                <a href="" data-toggle="collapse" data-target="#dashboard_setting" class="active collapsed" aria-expanded="false">
                    <i class="fa-solid fa-gear fa-fw mr-1"></i>&nbsp;Administracion
                    <span class="fa fa-arrow-circle-left float-right"></span>
                </a>
                <ul class="sub-menu collapse" id="dashboard_setting">
                    @can('clientes.index')
                        <li>
                            <a href="{{ route('clientes.index') }}">
                                &nbsp;&nbsp;<i class="fas fa-address-card fa-fw mr-2"></i>&nbsp;Clientes
                            </a>
                        </li>
                    @endcan
                    @can('users.index')
                        <li>
                            <a href="{{ route('users.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fas fa-users fa-fw mr-2"></i>&nbsp;Usuarios
                            </a>
                        </li>
                    @endcan
                    @can('roles.index')
                        <li>
                            <a href="{{ route('roles.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fas fa-user-shield fa-fw mr-2"></i>&nbsp;Roles
                            </a>
                        </li>
                    @endcan
                    @can('permissions.index')
                        <li>
                            <a href="{{ route('permissions.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fas fa-user-cog fa-fw mr-2"></i>&nbsp;Permisos
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @can('configuracion.index')
            <li>
                <a href="{{ route('configuracion.indexAfter') }}">
                    <i class="fa-solid fa-gear fa-fw mr-1"></i>&nbsp;Configuracion
                </a>
            </li>
        @endcan
        @canany(['estado.resultado.index','balance.apertura.index','plan.cuentas.index','plan.cuentas.auxiliar.index','tipo.cambio.index','comprobante.index'])
            <li>
                <a href="" data-toggle="collapse" data-target="#dashboard_contabilidad" class="active collapsed" aria-expanded="false">
                    <i class="fa-solid fa-layer-group fa-fw mr-1"></i>&nbsp;Contabilidad
                    <span class="fa fa-arrow-circle-left float-right"></span>
                </a>
                <ul class="sub-menu collapse" id="dashboard_contabilidad">
                    @can('comprobante.index')
                        <li>
                            <a href="{{ route('comprobante.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fa-solid fa-file-invoice-dollar fa-fw mr-2"></i>&nbsp;Comprobantes
                            </a>
                        </li>
                    @endcan
                    @can('balance.apertura.index')
                        <li>
                            <a href="{{ route('balance.apertura.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fa-solid fa-layer-group fa-fw mr-2"></i>&nbsp;Balance Apertura
                            </a>
                        </li>
                    @endcan
                    @canany(['estado.resultado.index'])
                        <li>
                            <a href="" data-toggle="collapse" data-target="#dashboard_reportes_contabilidad" class="active collapsed" aria-expanded="false">
                                &nbsp;&nbsp;<i class="fa-solid fa-list fa-fw mr-1"></i>&nbsp;Reportes
                                <span class="fa fa-arrow-circle-left float-right"></span>
                            </a>
                            <ul class="sub-menu collapse" id="dashboard_reportes_contabilidad">
                                @can('estado.resultado.index')
                                    <li>
                                        <a href="{{ route('estado.resultado.indexAfter') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-layer-group fa-fw mr-2"></i>&nbsp;Estado de Resultado
                                        </a>
                                    </li>
                                @endcan
                                @can('balance.general.index')
                                    <li>
                                        <a href="{{ route('balance.general.indexAfter') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-layer-group fa-fw mr-2"></i>&nbsp;Balance General
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    @canany(['libro.mayor.cuenta.general.index','libro.mayor.auxiliar.general.index'])
                        <li>
                            <a href="" data-toggle="collapse" data-target="#dashboard_libros_contabilidad" class="active collapsed" aria-expanded="false">
                                &nbsp;&nbsp;<i class="fa-solid fa-list fa-fw mr-1"></i>&nbsp;Libros
                                <span class="fa fa-arrow-circle-left float-right"></span>
                            </a>
                            <ul class="sub-menu collapse" id="dashboard_libros_contabilidad">
                                @can('libro.mayor.cuenta.general.index')
                                    <li>
                                        <a href="{{ route('libro.mayor.cuenta.general.indexAfter') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-layer-group fa-fw mr-2"></i>&nbsp;Mayor Por Cuenta-General
                                        </a>
                                    </li>
                                @endcan
                                @can('libro.mayor.auxiliar.general.index')
                                    <li>
                                        <a href="{{ route('libro.mayor.auxiliar.general.indexAfter') }}">
                                            &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-layer-group fa-fw mr-2"></i>&nbsp;Mayor Por Auxiliar-General
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    @can('tipo.cambio.index')
                        <li>
                            <a href="{{ route('tipo.cambio.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fa-solid fa-circle-dollar-to-slot fa-fw mr-2"></i>&nbsp;Tipo de Cambio
                            </a>
                        </li>
                    @endcan
                    @can('plan.cuentas.index')
                        <li>
                            <a href="{{ route('plan_cuentas.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fa-regular fa-chart-bar fa-fw mr-2"></i>&nbsp;Plan de Cuentas
                            </a>
                        </li>
                    @endcan
                    @can('plan.cuentas.auxiliar.index')
                        <li>
                            <a href="{{ route('plan_cuentas.auxiliar.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fas fa-user-friends fa-fw mr-2"></i>&nbsp;Auxiliares
                            </a>
                        </li>
                    @endcan
                    @can('caja.venta.index')
                        <li>
                            <a href="{{ route('caja.venta.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fa-solid fa-layer-group fa-fw mr-2"></i>&nbsp;Cajas Ventas
                            </a>
                        </li>
                    @endcan
                    @can('asiento.automatico.index')
                        <li>
                            <a href="{{ route('asiento.automatico.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fa-solid fa-layer-group fa-fw mr-2"></i>&nbsp;Asientos Automaticos
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @can('mesas.index')
            <li>
                <a href="{{ route('mesas.indexAfter') }}">
                    <i class="fas fa-utensils fa-fw mr-1"></i>&nbsp;Mesas
                </a>
            </li>
        @endcan
        {{--@can('zonas.index')
            <li>
                <a href="{{ route('zonas.indexAfter') }}">
                    <i class="fa-solid fa-house-laptop fa-fw mr-1"></i>&nbsp;Zonas
                </a>
            </li>
        @endcan--}}
        @can('sucursal.index')
            <li>
                <a href="{{ route('sucursal.indexAfter') }}">
                    <i class="fa-solid fa-house-damage fa-fw mr-1"></i>&nbsp;Sucursales
                </a>
            </li>
        @endcan
        @can('productos.index')
            <li>
                <a href="{{ route('productos.indexAfter') }}">
                    <i class="fas fa-wine-glass-alt fa-fw mr-1"></i>&nbsp;Productos
                </a>
            </li>
        @endcan
        @canany(['cargos.index','personal.index'])
            <li>
                <a href="" data-toggle="collapse" data-target="#dashboard_rrhh" class="active collapsed" aria-expanded="false">
                    <i class="fa-solid fa-gift fa-fw mr-1"></i>&nbsp;Recursos Humanos
                    <span class="fa fa-arrow-circle-left float-right"></span>
                </a>
                <ul class="sub-menu collapse" id="dashboard_rrhh">
                    @can('cargos.index')
                        <li>
                            <a href="{{ route('cargos.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fa-solid fa-diagram-project fa-fw mr-2"></i>&nbsp;Cargos
                            </a>
                        </li>
                    @endcan
                    @can('personal.index')
                        <li>
                            <a href="{{ route('personal.indexAfter') }}">
                                &nbsp;&nbsp;<i class="fas fa-user-friends fa-fw mr-2"></i>&nbsp;Personal
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
    </ul>
</div>
