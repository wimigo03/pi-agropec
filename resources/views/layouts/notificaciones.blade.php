@if (session('success_message'))
    <script type="text/javascript">
        var successMessage = '{{ Session::get('success_message') }}';
        if (successMessage) {
            Lobibox.notify('success', {
                icon: false,
                delay: 3500,
                position: 'top right',
                title: '<i class="far fa-lg fa-check-circle"></i> Éxito',
                msg: successMessage
            });
        }
    </script>
@endif

@if (session('error_message'))
    <script type="text/javascript">
        var errorMessage = '{{ Session::get('error_message') }}';
        if (errorMessage) {
            Lobibox.notify('error', {
                icon: false,
                delay: 3500,
                position: 'top right',
                title: '<i class="far fa-lg fa-times-circle"></i> Error',
                msg: errorMessage
            });
        }
    </script>
@endif

@if (session('info_message'))
    <script type="text/javascript">
        var infoMessage = '{{ Session::get('info_message') }}';
        if (infoMessage) {
            Lobibox.notify('info', {
                icon: false,
                delay: 3500,
                position: 'top right',
                title: '<i class="fas fa-lg fa-info-circle"></i> Informacion',
                msg: infoMessage
            });
        }
    </script>
@endif

@if (session('warning_message'))
    <script type="text/javascript">
        var warningMessage = '{{ Session::get('warning_message') }}';
        if (warningMessage) {
            Lobibox.notify('warning', {
                icon: false,
                delay: 3500,
                position: 'top right',
                title: '<i class="fas fa-lg fa-exclamation-circle"></i> Advertencia',
                msg: warningMessage
            });
        }
    </script>
@endif

<script>
    var errors = @json($errors->all());
    if (errors.length > 0) {
        Lobibox.notify('error', {
            icon: false,
            delay: 3500,
            position: 'top right',
            title: '<i class="fas fa-lg fa-exclamation-circle"></i> Error de validacion',
            msg: errors.join('<br>')
        });
    }
</script>