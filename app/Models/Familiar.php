<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Cliente;

class Familiar extends Model
{
    use HasFactory;

    protected $table = 'familiares';
    protected $fillable = [
        'personal_id',
        'user_id',
        'cargo_id',
        'empresa_id',
        'cliente_id',
        'plan_cuenta_id',
        'nombre',
        'tipo',
        'observacion',
        'ocupacion',
        'nivel_estudio',
        'telefono',
        'edad',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    const TIPO_FAMILIARES = [
        'HIJO(A)' => 'HIJO(A)',
        'CONCUBINO(A)' => 'CONCUBINO(A)',
        'ESPOSO(A)' => 'ESPOSO(A)',
        'OTRO' => 'OTRO'
    ];

    const OCUPACIONES = [
        'ESTUDIA' => 'ESTUDIA',
        'TRABAJA' => 'TRABAJA',
        'NINGUNO' => 'NINGUNO'
    ];

    const NIVELES_ESTUDIO = [
        'PRIMARIA' => 'PRIMARIA',
        'SECUNDARIA' => 'SECUNDARIA',
        'UNIVERSITARIA' => 'UNIVERSITARIA',
        'SUPERIOR' => 'SUPERIOR'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1': 
                return "HABILITADO";
            case '2': 
                return "NO HABILITADO";
        }
    }

    public function personal(){
        return $this->belongsTo(Personal::class,'personal_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }
}
