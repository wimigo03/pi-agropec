<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comprobante;
use App\Models\ComprobanteF;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\TipoCambio;
use App\Models\Sucursal;
use App\Models\CentroContable;
use App\Models\User;
use App\Models\Cargo;
use App\Models\PlanCuenta;
use App\Models\Moneda;
use App\Models\Pais;
use Carbon\Carbon;

class ComprobanteFDetalle extends Model
{
    use HasFactory;

    protected $table = 'comprobantef_detalles';
    protected $fillable = [
        'comprobantef_id',
        'comprobante_id',
        'empresa_id',
        'cliente_id',
        'tipo_cambio_id',
        'user_id',
        'cargo_id',
        'moneda_id',
        'pais_id',
        'centro_contable_id',
        'user_autorizado_id',
        'plan_cuenta_id',
        'plan_cuenta_auxiliar_id',
        'glosa',
        'debe',
        'haber',
        'tipo_transaccion',
        'nro_cheque',
        'orden_cheque',
        'fecha_cheque',
        'estado',
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

    public function comprobante(){
        return $this->belongsTo(ComprobanteF::class,'comprobantef_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function plan_cuenta(){
        return $this->belongsTo(PlanCuenta::class,'plan_cuenta_id','id');
    }

    public function plan_cuenta_auxiliar(){
        return $this->belongsTo(PlanCuentaAuxiliar::class,'plan_cuenta_auxiliar_id','id');
    }

    public function centro(){
        return $this->belongsTo(CentroContable::class,'centro_contable_id','id');
    }
}
