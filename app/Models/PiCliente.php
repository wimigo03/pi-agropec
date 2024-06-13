<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paises;

class PiCliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'pais',
        'fecha_i',
        'razon_social',
        'nombre',
        'telefono',
        'nit',
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

    public function _pais(){
        return $this->belongsTo(Paises::class,'pais','id');
    }

    public function scopeByCodigo($query, $codigo){
        if($codigo){
            return $query->where('id', $codigo);
        }
    }

    public function scopeByPais($query, $pais){
        if($pais){
            return $query->where('pais', $pais);
        }
    }

    public function scopeByFecha($query, $fecha){
        if($fecha){
            $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
            return $query->where('fecha_i', $fecha);
        }
    }

    public function scopeByRazonSocial($query, $razon_social){
        if($razon_social){
            return $query->where('razon_social','like','%'.$razon_social.'%');
        }
    }

    public function scopeByNombre($query, $nombre){
        if($nombre){
            return $query->where('nombre','like','%'.$nombre.'%');
        }
    }

    public function scopeByNit($query, $nit){
        if($nit){
            return $query->where('nit',$nit);
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado',$estado);
        }
    }
}
