<div class="modal fade" id="ModalUnidadMedida" tabindex="-1" role="dialog" aria-labelledby="ModalUnidadMedidaTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalUnidadMedidaTitle">Unidad de Medida</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="form-unidad-medida">
                    @csrf
                    <input type="hidden" name="form_prod" value="#">
                    @include('unidades.partials.form-create')
                </form>
            </div>
            <div class="modal-footer">
                <span class="btn btn-primary font-roboto-12" onclick="storeUnidadMedida();">
                    <i class="fas fa-paper-plane fa-fw"></i>&nbsp;Procesar
                </span>
                <span class="btn btn-secondary font-roboto-12" data-dismiss="modal">
                    <i class="fa-solid fa-xmark fa-fw"></i>&nbsp;Cancelar
                </span>
            </div>
        </div>
    </div>
</div>
