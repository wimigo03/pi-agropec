<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\PlanCuenta;

class PlanCuenta extends Model
{
    use HasFactory;

    protected $table = 'plan_cuentas';
    protected $fillable = [
        'empresa_id',
        'pi_cliente_id',
        'moneda_id',
        'pais_id',
        'nombre',
        'codigo',
        'nivel',
        'parent_id',
        'auxiliar',
        'banco',
        'detalle',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    const CUENTAS = [
        '1' => 'ACTIVO',
        '2' => 'PASIVO',
        '3' => 'PATRIMONIO',
        '4' => 'INGRESOS',
        '5' => 'COSTOS',
        '6' => 'GASTOS'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
        }
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function parent(){
        return $this->belongsTo(PlanCuenta::class,'parent_id','id');
    }

    public static function orderByCodigo($planCuentas)
    {
        $sortedCollection = $planCuentas->sort(function ($a, $b) {
            return version_compare($a['codigo'], $b['codigo']);
        });
        return $sortedCollection;
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
