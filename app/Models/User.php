<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Empresa;
use App\Models\Cargo;
use App\Models\PiCliente;
use DB;
use Auth;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cargo_id',
        'empresa_id',
        'pi_cliente_id',
        'name',
        'username',
        'email',
        'password',
        'estado'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
        }
    }

    public function getCargoHeaderAttribute(){
        if($this->id == 1){
            return 'SUPER ADMINISTRADOR';
        }
        $cargo = DB::table('users as a')->join('cargos as b','a.cargo_id','b.id')->where('a.id',Auth::user()->id)->first()->nombre;
        return $cargo;
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function scopeByPiCliente($query, $pi_cliente_id){
        if($pi_cliente_id){
            return $query->where('pi_cliente_id', $pi_cliente_id);
        }
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('empresa_id', $empresa_id);
        }
    }

    public function scopeByCargo($query, $cargo){
        if ($cargo) {
                return $query
                    ->whereIn('cargo_id', function ($subquery) use($cargo) {
                        $subquery->select('id')
                            ->from('cargos')
                            ->where('nombre',$cargo);
                    });
        }
    }

    public function scopeByRole($query, $role){
        if ($role) {
                return $query
                    ->whereIn('id', function ($subquery) use($role) {
                        $subquery->select('model_id')
                            ->from('model_has_roles')
                            ->whereIn('role_id', function ($subquery) use($role) {
                                $subquery->select('id')
                                    ->from('roles')
                                    ->where('name',$role);
                            });
                    });
        }
    }

    public function scopeByNombre($query, $nombre){
        if($nombre){
            return $query->where('name', 'like', '%'.$nombre.'%');
        }
    }

    public function scopeByUsername($query, $username){
        if($username){
            return $query->where('username', 'like', '%'.$username.'%');
        }
    }

    public function scopeByEmail($query, $email){
        if($email){
            return $query->where('email', 'like', '%'.$email.'%');
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado', $estado);
        }
    }
}
