<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use Auth;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'nombre_comercial',
        'alias',
        'url_logo',
        'direccion',
        'telefono',
        'url_cover',
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

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function scopeByCliente($query){
        if(Auth::user()->id != 1){
            return $query->where('cliente_id', Auth::user()->cliente_id);
        }else{
            return null;
        }
    }
}
