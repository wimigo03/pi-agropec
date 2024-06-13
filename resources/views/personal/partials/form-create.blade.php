<form action="#" method="post" id="form" enctype="multipart/form-data">
    @csrf
    <div class="form-group row font-roboto-12">
        <div class="col-md-12 px-1">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1" data-toggle="tab" href="#content1" role="tab" aria-controls="content1" aria-selected="true">
                        Datos Personales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2" data-toggle="tab" href="#content2" role="tab" aria-controls="content2" aria-selected="false">
                        Datos Laborales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#content3" role="tab" aria-controls="content3" aria-selected="false">
                        Horario Laboral
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab4" data-toggle="tab" href="#content4" role="tab" aria-controls="content4" aria-selected="false">
                        Datos Familiares
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="content1" role="tabpanel" aria-labelledby="tab1">
                    @include('personal.partials.datos_personales')
                </div>
                <div class="tab-pane fade" id="content2" role="tabpanel" aria-labelledby="tab2">
                    @include('personal.partials.datos_laborales')
                </div>
                <div class="tab-pane fade" id="content3" role="tabpanel" aria-labelledby="tab3">
                    @include('personal.partials.horario_laboral')
                </div>
                <div class="tab-pane fade" id="content4" role="tabpanel" aria-labelledby="tab4">
                    @include('personal.partials.datos_familiares')
                </div>
            </div>
        </div>
    </div>
</form>
<div class="form-group row">
    <div class="col-md-12 text-right">
        <span class="btn btn-outline-primary font-roboto-12" onclick="procesar();">
            <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Procesar
        </span>
        <span class="btn btn-outline-danger font-roboto-12" onclick="cancelar();">
            <i class="fas fa-times fa-fw"></i>&nbsp;Cancelar
        </span>
        <i class="fa fa-spinner custom-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
    </div>
</div>
