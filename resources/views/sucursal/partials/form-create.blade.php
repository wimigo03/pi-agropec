<form action="#" method="post" id="form">
    @csrf
    <div class="form-group row font-roboto-12">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1" data-toggle="tab" href="#content1" role="tab" aria-controls="content1" aria-selected="true">
                        Datos Generales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2" data-toggle="tab" href="#content2" role="tab" aria-controls="content2" aria-selected="false">
                        Datos Facturacion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#content3" role="tab" aria-controls="content3" aria-selected="false">
                        Datos Contables
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="content1" role="tabpanel" aria-labelledby="tab1">
                    @include('sucursal.partials.datos_generales')
                </div>
                <div class="tab-pane fade" id="content2" role="tabpanel" aria-labelledby="tab2">
                    @include('sucursal.partials.datos_facturacion')
                </div>
                <div class="tab-pane fade" id="content3" role="tabpanel" aria-labelledby="tab3">
                    @include('sucursal.partials.datos_contables')
                </div>
            </div>
        </div>
    </div>
</form>