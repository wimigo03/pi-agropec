<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Moneda;
use App\Models\Pais;
use Carbon\Carbon;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobantes';
    protected $fillable = [
        'empresa_id',
        'pi_cliente_id',
        'tipo_cambio_id',
        'user_id',
        'cargo_id',
        'moneda_id',
        'pais_id',
        'user_autorizado_id',
        'nro',
        'nro_comprobante',
        'tipo_cambio',
        'ufv',
        'tipo',
        'entregado_recibido',
        'fecha',
        'concepto',
        'monto',
        'moneda',
        'copia',
        'estado',
        'creado'
    ];

    const ESTADOS = [
        '1' => 'PENDIENTE',
        '2' => 'APROBADO',
        '3' => 'ANULADO',
        '4' => 'ELIMINADO',
    ];

    const ESTADOS_SEARCH = [
        '1' => 'PENDIENTES',
        '2' => 'APROBADOS'
    ];

    const TIPOS = [
        '1' => 'INGRESO',
        '2' => 'EGRESO',
        '3' => 'TRASPASO'
    ];

    const TIPOS_ALIAS = [
        '1' => 'CI1',
        '2' => 'CE1',
        '3' => 'CT1'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "PENDIENTE";
            case '2':
                return "APROBADO";
            case '3':
                return "ANULADO";
            case '4':
                return "ELIMINADO";
        }
    }

    public function getTiposAttribute(){
        switch ($this->tipo) {
            case '1':
                return "INGRESO";
            case '2':
                return "EGRESO";
            case '3':
                return "TRASPASO";
        }
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function datos_moneda(){
        return $this->belongsTo(Moneda::class,'moneda_id','id');
    }

    public function scopeByPiCliente($query, $pi_cliente_id){
        if($pi_cliente_id){
            return $query->where('pi_cliente_id', $pi_cliente_id);
        }
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id)
            return $query->where('empresa_id', $empresa_id);
    }

    public function scopeByEntreFechas($query, $from, $to){
        if ($from && $to) {
            $from = date('Y-m-d 00:00:00', strtotime($from));
            $to = date('Y-m-d 23:59:59', strtotime($to));
            return $query->where(
                'fecha','>=',Carbon::parse($from)->toDateString()
            )
            ->where('fecha', '<=', Carbon::parse($to)->toDateString());
        }
    }

    public function scopeByNroComprobante($query, $nro_comprobante){
        if($nro_comprobante)
            return $query->where('nro_comprobante', $nro_comprobante);
    }

    public function scopeByConcepto($query, $concepto){
        if($concepto)
            return $query->where('concepto', 'like', '%' . $concepto . '%');
    }

    public function scopeByTipo($query, $tipo){
        if($tipo)
            return $query->where('tipo', $tipo);
    }

    public function scopeByEstado($query, $estado){
        if($estado)
            return $query->where('estado', $estado);
    }

    public function scopeByMonto($query, $monto){
        if($monto)
            return $query->where('monto', 'like', '%' . $monto . '%');
    }

    public function scopeByCreadoPor($query, $user_id){
        if($user_id)
            return $query->where('user_id', $user_id);
    }

    public function scopeByCopia($query, $copia){
        if($copia)
            return $query->where('copia', $copia);
    }
}
