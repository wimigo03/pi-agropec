<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Cliente;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'nombre',
        'tipo',
        'detalle',
        'estado'
    ];

    const TIPOS = [
        '1' => 'CONTABLE'
    ];

    const ESTADOS = [
        '1' => 'PENDIENTE',
        '2' => 'CONFIGURADO'
    ];

    public function getTiposAttribute(){
        switch ($this->tipo) {
            case '1':
                return "CONTABLE";
        }
    }

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "PENDIENTE";
            case '2':
                return "CONFIGURADO";
        }
    }

    /*public function getStatusInicioGestionFiscalAttribute(){
        $inicio_mes_fiscal = InicioMesFiscal::where('configuracion_id',$this->id)
                                            ->where('empresa_id',$this->empresa_id)
                                            ->orderBy('id','desc')
                                            ->first();
        if($inicio_mes_fiscal != null){
            switch ($inicio_mes_fiscal->estado) {
                case '1':
                    return "CONFIGURADO";
                    break;
                case '2':
                    return "PENDIENTE";
                    break;
            }
        }else{
            return "PENDIENTE";
        }
    }*/

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('empresa_id', $empresa_id);
        }
    }

    public function scopeByNombre($query, $nombre){
        if($nombre){
            return $query->where('nombre', 'like', '%'.$nombre.'%');
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado', $estado);
        }
    }
}
