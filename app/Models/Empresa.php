<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PiCliente;
use Auth;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'pi_cliente_id',
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
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function scopeByPiCliente($query, $pi_cliente_id){
        if($pi_cliente_id){
            return $query->where('pi_cliente_id', $pi_cliente_id);
        }
    }

    public function scopeByCodigo($query, $codigo){
        if($codigo){
            return $query->where('id', $codigo);
        }
    }

    public function scopeByNombreComercial($query, $nombre_comercial){
        if($nombre_comercial){
            return $query->where('nombre_comercial', 'like', '%' . $nombre_comercial . '%');
        }
    }

    public function scopeByTelefono($query, $telefono){
        if($telefono){
            return $query->where('telefono', $telefono);
        }
    }

}
