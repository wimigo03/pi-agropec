<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Categoria;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'plan_cuenta_id',
        'moneda_id',
        'pais_id',
        'nombre',
        'detalle',
        'codigo',
        'numeracion',
        'nivel',
        'parent_id',
        'tipo',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '0' => 'NO HABILITADO'
    ];

    const TIPOS = [
        '1' => 'MENU',
        '2' => 'INSUMOS'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1': 
                return "HABILITADO";
            case '0': 
                return "NO HABILITADO";
        }
    }

    public function getTipoProductoAttribute(){
        switch ($this->tipo) {
            case '1': 
                return "MENU";
            case '2': 
                return "INSUMOS";
        }
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function parent(){
        return $this->belongsTo(Categoria::class,'parent_id','id');
    }
}
