<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrecio extends Model
{
    use HasFactory;

    protected $table = 'tipo_precios';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'nombre',
        'observaciones',
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

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('tipo_precios.empresa_id', $empresa_id);
        }
    }
}