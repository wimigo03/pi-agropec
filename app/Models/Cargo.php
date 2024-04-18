<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Cargo;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'plan_cuenta_id',
        'nombre',
        'codigo',
        'nivel',
        'parent_id',
        'email',
        'descripcion',
        'alias',
        'tipo',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    const TIPOS = [
        '1' => 'POR SERVICIO',
        '2' => 'PLANILLA DE SUELDO'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1': 
                return "HABILITADO";
            case '2': 
                return "NO HABILITADO";
        }
    }

    public function getTipoContratoAttribute(){
        switch ($this->estado) {
            case '1': 
                return "POR SERVICIO";
            case '2': 
                return "PLANILLA DE SUELDO";
        }
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function parent(){
        return $this->belongsTo(Cargo::class,'parent_id','id');
    }
}
