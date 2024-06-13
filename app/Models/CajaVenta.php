<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\Sucursal;
use App\Models\Comprobante;
use App\Models\TipoCambio;
use App\Models\Moneda;
use App\Models\Paises;
use Carbon\Carbon;

class CajaVenta extends Model
{
    use HasFactory;

    protected $table = 'cajas_ventas';
    protected $fillable = [
        'empresa_id',
        'pi_cliente_id',
        'sucursal_id',
        'user_id',
        'cargo_id',
        'comprobante_id',
        'tipo_cambio_id',
        'moneda_id',
        'pais_id',
        'user_asignado_id',
        'codigo',
        'fecha_registro',
        'monto_apertura',
        'obervaciones',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'PENDIENTE',
        '2' => 'APROBADO',
        '3' => 'RECHAZADO'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "PENDIENTE";
            case '2':
                return "APROBADO";
            case '3':
                return "RECHAZADO";
        }
    }

    public function getcolorBadgeStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "badge-with-padding badge badge-secondary";
            case '2':
                return "badge-with-padding badge badge-success";
            case '3':
                return "badge-with-padding badge badge-danger";
        }
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

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function user_asignado(){
        return $this->belongsTo(User::class,'user_asignado_id','id');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class,'cargo_id','id');
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

    public function scopeByFecha($query, $fecha){
        if ($fecha) {
            $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
            return $query->where('fecha_registro', $fecha);
        }
    }

    public function scopeByCodigo($query, $codigo){
        if($codigo){
            return $query->where('codigo', $codigo);
        }
    }

    public function scopeByUser($query, $user_id){
        if($user_id){
            return $query->where('user_id', $user_id);
        }
    }

    public function scopeByUserAsignado($query, $user_asignado_id){
        if($user_asignado_id){
            return $query->where('user_asignado_id', $user_asignado_id);
        }
    }

    public function scopeByMonto($query, $monto){
        if($monto){
            $monto = floatval(str_replace(",", "", $monto));
            return $query->where('monto_apertura', 'like', $monto . '%');
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado', $estado);
        }
    }
}
