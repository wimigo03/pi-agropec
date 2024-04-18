<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Cliente;

class PlanCuentaAuxiliar extends Model
{
    use HasFactory;

    protected $table = 'plan_cuentas_auxiliares';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'user_id',
        'nombre',
        'class_name',
        'class_name_id',
        'tipo',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    const TIPOS = [
        '1' => 'AUTOMATICO',
        '2' => 'MANUAL'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
        }
    }

    public function getTipusAttribute(){
        switch ($this->tipo) {
            case '1':
                return "AUTOMATICO";
            case '2':
                return "MANUAL";
        }
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id)
            return $query->where('empresa_id', $empresa_id);
    }

    public function scopeByNombre($query, $nombre){
        if($nombre)
            return $query->where('nombre', 'like', '%'.$nombre.'%');
    }

    public function scopeByTipo($query, $tipo){
        if($tipo)
            return $query->where('tipo', $tipo);
    }

    public function scopeByEstado($query, $estado){
        if($estado)
            return $query->where('estado', $estado);
    }
}
