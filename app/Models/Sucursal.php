<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Zona;
use App\Models\Mesa;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'nombre',
        'ciudad',
        'direccion',
        'celular',
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

    public function getZonasAttribute(){
        $zonas = Zona::where('sucursal_id',$this->id)->where('estado','1')->get()->count();
        return $zonas;
    }

    public function getMesasAttribute(){
        $mesas = Mesa::where('sucursal_id',$this->id)->where('estado','!=','2')->get()->count();
        return $mesas;
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

    public function scopeBySucursalId($query, $sucursal_id){
        if($sucursal_id)  
            return $query->where('id', $sucursal_id);
    }

    public function scopeBySucursal($query, $sucursal){
        if($sucursal)  
            return $query->where('nombre', 'like', '%'.$sucursal.'%');
    }

    public function scopeByDireccion($query, $direccion){
        if($direccion)  
            return $query->where('direccion', 'like', '%'.$direccion.'%');
    }

    public function scopeByTelefono($query, $telefono){
        if($telefono)  
            return $query->where('celular', 'like', '%'.$telefono.'%');
    }

    public function scopeByEstado($query, $estado){
        if($estado)  
            return $query->where('estado', $estado);
    }
}