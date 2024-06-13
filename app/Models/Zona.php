<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\Sucursal;
use App\Models\Pedido;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';
    protected $fillable = [
        'sucursal_id',
        'empresa_id',
        'pi_cliente_id',
        'codigo',
        'nombre',
        'detalle',
        'filas',
        'columnas',
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

    public function getMesasDisponiblesAttribute(){
        $disponibles = Mesa::select('id')->where('zona_id',$this->id)->get()->count();
        return $disponibles;
    }

    public function getMesasHabilitadasAttribute(){
        $habilitadas = Mesa::select('id')->where('zona_id',$this->id)->where('estado','1')->get()->count();
        return $habilitadas;
    }

    public function getMesasNoHabilitadasAttribute(){
        $no_habilitadas = Mesa::select('id')->where('zona_id',$this->id)->where('estado','2')->get()->count();
        return $no_habilitadas;
    }

    public function getMesasConfiguradasAttribute(){
        $configuradas = Mesa::select('id')->where('zona_id',$this->id)->where('estado','3')->get()->count();
        return $configuradas;
    }

    public function getTotalMesasDisponiblesAttribute(){
        $total = $this->mesas_disponibles - $this->mesas_habilitadas - $this->mesas_no_habilitadas - $this->mesas_configuradas;
        return $total;
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function sucursal(){
        return $this->belongsTo(Sucursal::class,'sucursal_id','id');
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('empresa_id', $empresa_id);
        }
    }

    public function scopeBySucursal($query, $sucursal_id){
        if($sucursal_id){
            return $query->where('sucursal_id', $sucursal_id);
        }
    }

    public function scopeByCodigo($query, $codigo){
        if($codigo){
            return $query->where('codigo', $codigo);
        }
    }

    public function scopeByNombre($query, $nombre){
        if($nombre){
            return $query->where('nombre', 'like','%'.$nombre.'%');
        }
    }

    public function scopeByCantidadMesas($query, $mesas){
        if($mesas){
            return $query->where('mesas_disponibles', $mesas);
        }
    }

    public function scopeByDetalle($query, $detalle){
        if($detalle){
            return $query->where('detalle', 'like','%'.$detalle.'%');
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado', $estado);
        }
    }
}
