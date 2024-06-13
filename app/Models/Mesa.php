<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\Sucursal;
use App\Models\Zona;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesas';
    protected $fillable = [
        'zona_id',
        'sucursal_id',
        'empresa_id',
        'pi_cliente_id',
        'numero',
        'sillas',
        'detalle',
        'fila',
        'columna',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO',
        '3' => 'CONFIGURADO',
        /*'3' => 'LIBRE',
        '4' => 'RESERVADA',
        '5' => 'OCUPADA',
        '6' => 'SUCIA',*/
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
            case '3':
                return "CONFIGURADO";
            /*case '3':
                return "LIBRE";
            case '4':
                return "RESERVADA";
            case '5':
                return "OCUPADA";
            case '6':
                return "SUCIA";*/
        }
    }

    public function getcolorStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "badge-with-padding badge badge-success";
            case '2':
                return "badge-with-padding badge badge-danger";
            case '3':
                return "badge-with-padding badge badge-warning";
            /*case '3':
                return "LIBRE";
            case '4':
                return "RESERVADA";
            case '5':
                return "OCUPADA";
            case '6':
                return "SUCIA";*/
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

    public function zona(){
        return $this->belongsTo(Zona::class,'zona_id','id');
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('mesas.empresa_id', $empresa_id);
        }
    }

    public function scopeBySucursal($query, $sucursal_id){
        if($sucursal_id){
            return $query->where('mesas.sucursal_id', $sucursal_id);
        }
    }

    public function scopeByZona($query, $zona_id){
        if($zona_id){
            return $query->where('mesas.zona_id', $zona_id);
        }
    }

    public function scopeByNumero($query, $numero){
        if($numero){
            return $query->where('mesas.numero', $numero);
        }
    }

    public function scopeBySillas($query, $sillas){
        if($sillas){
            return $query->where('mesas.sillas', $sillas);
        }
    }

    public function scopeByDetalle($query, $detalle){
        if($detalle){
            return $query->where('mesas.detalle', 'like', '%' . $detalle . '%');
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('mesas.estado', $estado);
        }
    }
}
