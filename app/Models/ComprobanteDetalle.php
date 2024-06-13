<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comprobante;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\TipoCambio;
use App\Models\Centro;
use App\Models\SubCentro;
use App\Models\User;
use App\Models\Cargo;
use App\Models\PlanCuenta;
use App\Models\PlanCuentaAuxiliar;
use App\Models\Moneda;
use App\Models\Pais;
use Carbon\Carbon;

class ComprobanteDetalle extends Model
{
    use HasFactory;

    protected $table = 'comprobante_detalles';
    protected $fillable = [
        'comprobante_id',
        'empresa_id',
        'pi_cliente_id',
        'tipo_cambio_id',
        'user_id',
        'cargo_id',
        'moneda_id',
        'pais_id',
        'user_autorizado_id',
        'plan_cuenta_id',
        'centro_id',
        'sub_centro_id',
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
        return $this->belongsTo(Comprobante::class,'comprobante_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function plan_cuenta(){
        return $this->belongsTo(PlanCuenta::class,'plan_cuenta_id','id');
    }

    public function plan_cuenta_auxiliar(){
        return $this->belongsTo(PlanCuentaAuxiliar::class,'plan_cuenta_auxiliar_id','id');
    }

    public function centro(){
        return $this->belongsTo(Centro::class,'centro_id','id');
    }

    public function subcentro(){
        return $this->belongsTo(SubCentro::class,'sub_centro_id','id');
    }
}
