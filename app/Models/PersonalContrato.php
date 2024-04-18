<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Afp;

class PersonalContrato extends Model
{
    use HasFactory;

    protected $table = 'personal_contratos';
    protected $fillable = [
        'personal_id',
        'personal_laboral_id',
        'user_id',
        'cargo_id',
        'empresa_id',
        'cliente_id',
        'plan_cuenta_id',
        'afp_id',
        'horario_id',
        'codigo_retiro',
        'tipo',
        'tipo_retiro',
        'motivo_retiro',
        'contrato_file',
        'fecha_ingreso',
        'fecha_retiro',
        'sueldo',
        'tipo_bono',
        'bono',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    const TIPOS = [
        'F' => 'FISCAL',
        'I' => 'INTERNO',
        'S' => 'SERVICIO',
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
        return $this->belongsTo(Personal::class,'user_id','id');
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

    public function afp(){
        return $this->belongsTo(Afp::class,'afp_id','id');
    }

    public function getFullNameAttribute() {
        return str_replace("  "," ",strtoupper("{$this->apellido_paterno} {$this->apellido_materno} {$this->primer_nombre} {$this->segundo_nombre}"));
    }
}