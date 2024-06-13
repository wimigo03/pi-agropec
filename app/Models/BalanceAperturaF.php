<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Comprobante;
use App\Models\ComprobanteF;
use App\Models\TipoCambio;
use Carbon\Carbon;

class BalanceAperturaF extends Model
{
    use HasFactory;

    protected $table = 'balance_apertura_f';
    protected $fillable = [
        'balance_apertura_id',
        'empresa_id',
        'pi_cliente_id',
        'user_id',
        'cargo_id',
        'comprobante_id',
        'tipo_cambio_id',
        'inicio_mes_fiscal_id',
        'comprobantef_id',
        'configuracion_id',
        'moneda_id',
        'pais_id',
        'user_autorizado_id',
        'gestion',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'PENDIENTE',
        '2' => 'APROBADO',
        '3' => 'ANULADO'
    ];


    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "PENDIENTE";
            case '2':
                return "APROBADO";
            case '3':
                return "ANULADO";
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

    public function cargo(){
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }

    public function comprobante(){
        return $this->belongsTo(Comprobante::class,'comprobante_id','id');
    }

    public function comprobantef(){
        return $this->belongsTo(ComprobanteF::class,'comprobantef_id','id');
    }

    public function tipo_cambio(){
        return $this->belongsTo(TipoCambio::class,'tipo_cambio_id','id');
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
}
