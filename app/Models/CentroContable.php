<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Zona;
use App\Models\Mesa;

class CentroContable extends Model
{
    use HasFactory;

    protected $table = 'centros_contables';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'nombre',
        'abreviatura',
        'estado'
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

    public function scopeByEstado($query, $estado){
        if($estado)
            return $query->where('estado', $estado);
    }
}
