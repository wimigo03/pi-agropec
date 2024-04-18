<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Empresa;
use Carbon\Carbon;

class TipoCambio extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'fecha',
        'ufv',
        'dolar_oficial',
        'dolar_compra',
        'dolar_venta',
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

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id)  
            return $query->where('empresa_id', $empresa_id);
    }

    public function scopeByEntreFechas($query, $from, $to){
        if ($from && $to) {
            $from = date('Y-m-d', strtotime(str_replace('/', '-', $from)));
            $to = date('Y-m-d', strtotime(str_replace('/', '-', $to)));
            $from = Carbon::parse($from)->toDateString();
            $to = Carbon::parse($to)->toDateString();
            return $query->where(
                'fecha','>=',Carbon::parse($from)->toDateString()
            )
            ->where('fecha', '<', Carbon::parse($to)->addDays(1)->toDateString());
        }
    }
}
