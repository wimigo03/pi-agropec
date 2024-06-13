<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AsientoAutomatico;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\Modulo;
use App\Models\PlanCuenta;
use App\Models\Moneda;
use App\Models\Pais;
use Carbon\Carbon;

class AsientoAutomaticoDetalle extends Model
{
    use HasFactory;

    protected $table = 'asientos_automaticos_detalles';
    protected $fillable = [
        'asiento_automatico_id',
        'empresa_id',
        'pi_cliente_id',
        'modulo_id',
        'plan_cuenta_id',
        'moneda_id',
        'pais_id',
        'tipo',
        'glosa',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'ELIMINADO'
    ];

    const TIPOS = [
        '1' => 'DEBE',
        '2' => 'HABER'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "ELIMINADO";
        }
    }

    public function getTiposAttribute(){
        switch ($this->tipo) {
            case '1':
                return "DEBE";
            case '2':
                return "HABER";
        }
    }

    public function getcolorBadgeStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "badge-with-padding badge badge-success";
            case '2':
                return "badge-with-padding badge badge-danger";
        }
    }

    public function asiento_automatico(){
        return $this->belongsTo(AsientoAutomatico::class,'asiento_automatico_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function modulo(){
        return $this->belongsTo(Modulo::class,'modulo_id','id');
    }

    public function plan_cuenta(){
        return $this->belongsTo(PlanCuenta::class,'plan_cuenta_id','id');
    }

    public function datos_moneda(){
        return $this->belongsTo(Moneda::class,'moneda_id','id');
    }

    public function pais(){
        return $this->belongsTo(Pais::class,'pais_id','id');
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('empresa_id', $empresa_id);
        }
    }

    public function scopeByModulo($query, $modulo_id){
        if($modulo_id){
            return $query->where('modulo_id', $modulo_id);
        }
    }

    public function scopeByPlanCuenta($query, $plan_cuenta_id){
        if($plan_cuenta_id){
            return $query->where('plan_cuenta_id', $plan_cuenta_id);
        }
    }

    public function scopeByConcepto($query, $concepto){
        if($concepto){
            return $query->where('concepto', 'like', '%' . $concepto . '%');
        }
    }

    public function scopeByTipo($query, $tipo){
        if($tipo){
            return $query->where('tipo', $tipo);
        }
    }

    public function scopeByGlosa($query, $glosa){
        if($glosa){
            return $query->where('glosa', 'like', '%' . $glosa . '%');
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado', $estado);
        }
    }

    public function scopeByCopia($query, $copia){
        if($copia){
            return $query->where('copia', $copia);
        }
    }
}
