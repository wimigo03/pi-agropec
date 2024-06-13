<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PiCliente;
use Carbon\Carbon;

class TipoCambio extends Model
{
    use HasFactory;

    protected $fillable = [
        'pi_cliente_id',
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
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function scopeByPiCliente($query, $pi_cliente_id){
        if($pi_cliente_id){
            return $query->where('pi_cliente_id', $pi_cliente_id);
        }
    }

    public function scopeByEntreFechas($query, $from, $to){
        if ($from && $to) {
            $from = date('Y-m-d', strtotime($from));
            $to = date('Y-m-d', strtotime($to));
            $from = Carbon::parse($from)->toDateString();
            $to = Carbon::parse($to)->toDateString();
            return $query->where(
                'fecha','>=',Carbon::parse($from)->toDateString()
            )
            ->where('fecha', '<', Carbon::parse($to)->addDays(1)->toDateString());
        }
    }
}
