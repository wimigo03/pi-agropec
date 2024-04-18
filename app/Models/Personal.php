<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\PersonalContrato;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';
    protected $fillable = [
        'user_id',
        'cargo_id',
        'empresa_id',
        'cliente_id',
        'plan_cuenta_id',
        'primer_nombre',
        'segundo_nombre',
        'apellido_paterno',
        'apellido_materno',
        'ci_run',
        'extension',
        'nro_cuenta',
        'banco',
        'nacionalidad',
        'sexo',
        'licencia_conducir',
        'licencia_categoria',
        'lugar_nacimiento',
        'fecha_nac',
        'foto',
        'direccion_domicilio',
        'celular',
        'telefono_fijo',
        'estado_civil',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO',
        '3' => 'RETIRADO'
    ];

    const NACIONALIDADES = [
        '1' => 'BOLIVIANA',
        '2' => 'EXTRANJERA'
    ];

    const EXTENSIONES = [
        'SC' => 'SANTA CRUZ',
        'BN' => 'BENI',
        'LP' => 'LA PAZ',
        'CB' => 'COCHABAMBA', 
        'CH' => 'CHUQUISACA', 
        'OR' => 'ORURO', 
        'PT' => 'POTOSI', 
        'PA' => 'PANDO', 
        'TJ' => 'TARIJA'
    ];

    const LICENCIA_CATEGORIAS = [
        'T' => 'T',
        'C' => 'C',
        'B' => 'B',
        'A' => 'A',
        'P' => 'P',
        'M' => 'M'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1': 
                return "HABILITADO";
            case '2': 
                return "NO HABILITADO";
            case '3': 
                return "RETIRADO";
        }
    }

    public function getNationalidadAttribute(){
        switch ($this->estado) {
            case '1': 
                return "BOLIVIANA";
            case '2': 
                return "EXTRANJERA";
        }
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

    public function getFullNameAttribute() {
        return str_replace("  "," ",strtoupper("{$this->apellido_paterno} {$this->apellido_materno} {$this->primer_nombre} {$this->segundo_nombre}"));
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('empresa_id', $empresa_id);
        }
    }
}