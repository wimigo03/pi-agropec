<div class="modal fade font-verdana-bg" id="modal_confirmacion" tabindex="-1" role="dialog" aria-labelledby="modal_confirmacion_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_confirmacion_label">
          <i class="fa fa-lg fa-exclamation-triangle text-warning" aria-hidden="true"></i>&nbsp;AVISO
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        Estas por <span class="text-success"><b>[Confirmar]</b></span> este proceso.
        <b>¿Estas seguro que desea continuar?</b>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary font-verdana" type="button" onclick="confirmar();">
          <i class="fas fa-paper-plane" aria-hidden="true"></i>&nbsp;Confirmar
        </button>
        <button class="btn btn-secondary font-verdana text-white" type="button" data-dismiss="modal">
            <i class="fas fa-times"></i>&nbsp;Cancelar
        </button>
        <i class="fa fa-spinner fa-spin fa-lg fa-fw spinner-btn" style="display: none;"></i>
      </div>
    </div>
  </div>
</div>
