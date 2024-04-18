<div class="form-group row font-roboto-20 abs-center">
    <div class="col-md-6 text-center">
        <strong>{{ $user->name }}</strong>
    </div>
</div>
<div class="form-group row font-roboto-14 abs-center">
    <div class="col-md-6 text-center">
        <strong>LISTA DE ROLES</strong>
    </div>
</div>
<form action="#" method="post" id="form">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    @foreach ($roles as $datos)
        <div class="form-group row font-roboto-20 abs-center">
            <div class="col-md-6 font-roboto-12">
                <label for="roles_id" class="d-inline">
                    <input type="checkbox" name="roles[]" value="{{ $datos->id }}"  {{ $user->hasAnyRole($datos->id) ? 'checked' : '' }} class="mr-1">
                    {{ $datos->name }}
                </label>
            </div>
        </div>
    @endforeach
</form>
